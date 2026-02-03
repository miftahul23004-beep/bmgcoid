<?php

namespace App\Console\Commands;

use App\Models\HeroSlide;
use App\Services\ImageOptimizationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class OptimizeHeroImages extends Command
{
    protected $signature = 'hero:optimize 
                            {--max-size=95 : Maximum file size in KB for desktop images}
                            {--mobile-max-size=80 : Maximum file size in KB for mobile images}
                            {--force : Force re-optimization even if already under size limit}';

    protected $description = 'Optimize all hero slide images to WebP format under specified size limit';

    public function handle(ImageOptimizationService $imageService): int
    {
        $maxSize = (int) $this->option('max-size');
        $mobileMaxSize = (int) $this->option('mobile-max-size');
        $force = $this->option('force');

        $this->info("🖼️  Hero Image Optimization");
        $this->info("   Desktop max size: {$maxSize}KB");
        $this->info("   Mobile max size: {$mobileMaxSize}KB");
        $this->newLine();

        $slides = HeroSlide::all();
        
        if ($slides->isEmpty()) {
            $this->warn('No hero slides found.');
            return self::SUCCESS;
        }

        $optimized = 0;
        $skipped = 0;

        foreach ($slides as $slide) {
            $this->line("Processing Slide #{$slide->id}: {$slide->title_id}");

            // Process desktop image
            if ($slide->image) {
                $result = $this->optimizeImage(
                    $imageService,
                    $slide,
                    'image',
                    $maxSize,
                    1920,
                    $force
                );
                
                if ($result === 'optimized') {
                    $optimized++;
                } elseif ($result === 'skipped') {
                    $skipped++;
                }
            }

            // Process mobile image
            if ($slide->mobile_image) {
                $result = $this->optimizeImage(
                    $imageService,
                    $slide,
                    'mobile_image',
                    $mobileMaxSize,
                    750,
                    $force
                );
                
                if ($result === 'optimized') {
                    $optimized++;
                } elseif ($result === 'skipped') {
                    $skipped++;
                }
            }
        }

        // Clear cache
        Cache::forget('hero_slides.displayable');

        $this->newLine();
        $this->info("✅ Optimization complete!");
        $this->info("   Optimized: {$optimized} images");
        $this->info("   Skipped: {$skipped} images (already optimized)");

        return self::SUCCESS;
    }

    protected function optimizeImage(
        ImageOptimizationService $imageService,
        HeroSlide $slide,
        string $field,
        int $maxSizeKb,
        int $maxWidth,
        bool $force
    ): string {
        $path = $slide->$field;
        $storagePath = Storage::disk('public')->path($path);

        if (!file_exists($storagePath)) {
            $this->warn("   ⚠️  {$field}: File not found - {$path}");
            return 'error';
        }

        $currentSize = round(filesize($storagePath) / 1024, 1);
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        // Check if already optimized
        if (!$force && $extension === 'webp' && $currentSize <= $maxSizeKb) {
            $this->line("   ⏭️  {$field}: Already optimized ({$currentSize}KB)");
            return 'skipped';
        }

        $this->line("   🔄 {$field}: Optimizing ({$currentSize}KB -> max {$maxSizeKb}KB)...");

        try {
            $newPath = $imageService->convertToWebp($path, $maxSizeKb, $maxWidth);
            
            // Update database if path changed
            if ($newPath !== $path) {
                $slide->$field = $newPath;
                $slide->saveQuietly(); // Don't trigger observer again
            }

            $newStoragePath = Storage::disk('public')->path($newPath);
            $newSize = round(filesize($newStoragePath) / 1024, 1);

            $this->info("   ✅ {$field}: Optimized to {$newSize}KB");
            
            return 'optimized';

        } catch (\Exception $e) {
            $this->error("   ❌ {$field}: Failed - " . $e->getMessage());
            return 'error';
        }
    }
}
