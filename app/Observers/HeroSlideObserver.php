<?php

namespace App\Observers;

use App\Models\HeroSlide;
use App\Services\CloudflarePurgeService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class HeroSlideObserver
{
    /**
     * Mobile variant width for responsive hero images.
     */
    protected const MOBILE_WIDTH = 640;

    /**
     * WebP quality for mobile variants.
     */
    protected const MOBILE_QUALITY = 80;

    /**
     * Handle the HeroSlide "saved" event (after create or update)
     */
    public function saved(HeroSlide $heroSlide): void
    {
        // Auto-generate 640w mobile variant when image changes
        if ($heroSlide->wasChanged('image') && $heroSlide->image) {
            $this->generateMobileVariant($heroSlide);
        }

        $this->clearCacheAndPurge();
    }

    /**
     * Handle the HeroSlide "deleted" event.
     */
    public function deleted(HeroSlide $heroSlide): void
    {
        // Delete associated images
        if ($heroSlide->image) {
            Storage::disk('public')->delete($heroSlide->image);
            // Also delete the auto-generated 640w variant
            $this->deleteMobileVariant($heroSlide->image);
        }

        if ($heroSlide->mobile_image) {
            Storage::disk('public')->delete($heroSlide->mobile_image);
        }

        $this->clearCacheAndPurge();
    }

    /**
     * Generate a 640w mobile variant of the hero image.
     * Creates a file like hero-slides/{uuid}-640w.webp alongside the original.
     */
    protected function generateMobileVariant(HeroSlide $heroSlide): void
    {
        $disk = Storage::disk('public');
        $imagePath = $heroSlide->image;

        if (!$disk->exists($imagePath)) {
            return;
        }

        try {
            $fullPath = $disk->path($imagePath);
            $extension = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));

            // Load source image based on format
            $source = match ($extension) {
                'webp' => imagecreatefromwebp($fullPath),
                'jpg', 'jpeg' => imagecreatefromjpeg($fullPath),
                'png' => imagecreatefrompng($fullPath),
                default => null,
            };

            if (!$source) {
                Log::warning("HeroSlide: Cannot load image for mobile variant: {$imagePath}");
                return;
            }

            $origWidth = imagesx($source);
            $origHeight = imagesy($source);

            // Only generate if original is wider than target
            if ($origWidth <= self::MOBILE_WIDTH) {
                imagedestroy($source);
                return;
            }

            // Calculate proportional height
            $newWidth = self::MOBILE_WIDTH;
            $newHeight = (int) round($origHeight * ($newWidth / $origWidth));

            // Resize
            $resized = imagecreatetruecolor($newWidth, $newHeight);

            // Preserve transparency for PNG/WebP
            if (in_array($extension, ['png', 'webp'])) {
                imagealphablending($resized, false);
                imagesavealpha($resized, true);
            }

            imagecopyresampled($resized, $source, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);

            // Build output path: hero-slides/{uuid}-640w.webp
            $mobilePath = preg_replace('/\.(webp|jpe?g|png)$/i', '-' . self::MOBILE_WIDTH . 'w.webp', $imagePath);
            $mobileFullPath = $disk->path($mobilePath);

            // Ensure directory exists
            $dir = dirname($mobileFullPath);
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }

            // Save as WebP
            imagewebp($resized, $mobileFullPath, self::MOBILE_QUALITY);

            imagedestroy($source);
            imagedestroy($resized);

            Log::info("HeroSlide: Generated mobile variant {$mobilePath} ({$newWidth}x{$newHeight})");

            // Delete old mobile variant if image was changed
            $oldImage = $heroSlide->getOriginal('image');
            if ($oldImage && $oldImage !== $heroSlide->image) {
                $this->deleteMobileVariant($oldImage);
            }

        } catch (\Exception $e) {
            Log::error("HeroSlide: Failed to generate mobile variant: {$e->getMessage()}");
        }
    }

    /**
     * Delete the auto-generated 640w mobile variant for an image path.
     */
    protected function deleteMobileVariant(string $imagePath): void
    {
        $mobilePath = preg_replace('/\.(webp|jpe?g|png)$/i', '-' . self::MOBILE_WIDTH . 'w.webp', $imagePath);
        Storage::disk('public')->delete($mobilePath);
    }

    /**
     * Clear Laravel cache and purge Cloudflare
     */
    protected function clearCacheAndPurge(): void
    {
        // Clear hero slides cache
        Cache::forget('hero_slides.displayable');

        // Clear homepage data cache for all locales
        Cache::forget('homepage_data:id');
        Cache::forget('homepage_data:en');
        Cache::forget('page_cache:home:id');
        Cache::forget('page_cache:home:en');

        // Purge Cloudflare homepage
        try {
            app(CloudflarePurgeService::class)->purgeHomepage();
        } catch (\Exception $e) {
            // Silently fail - don't break the save
        }
    }
}
