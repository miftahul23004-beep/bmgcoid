<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class OptimizeProduction extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'app:optimize-production 
                            {--clear : Clear all caches first}
                            {--no-views : Skip view caching}';

    /**
     * The console command description.
     */
    protected $description = 'Optimize application for production (GTmetrix Grade A)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🚀 Optimizing for Production...');
        $this->newLine();

        // Clear caches first if requested
        if ($this->option('clear')) {
            $this->warn('Clearing all caches...');
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');
            $this->info('✓ All caches cleared');
            $this->newLine();
        }

        // 1. Cache configuration
        $this->info('1. Caching configuration...');
        Artisan::call('config:cache');
        $this->info('   ✓ Configuration cached');

        // 2. Cache routes
        $this->info('2. Caching routes...');
        Artisan::call('route:cache');
        $this->info('   ✓ Routes cached');

        // 3. Cache views (optional)
        if (!$this->option('no-views')) {
            $this->info('3. Caching views...');
            Artisan::call('view:cache');
            $this->info('   ✓ Views cached');
        }

        // 4. Cache events
        $this->info('4. Caching events...');
        Artisan::call('event:cache');
        $this->info('   ✓ Events cached');

        // 5. Optimize autoloader
        $this->info('5. Optimizing autoloader...');
        exec('composer dump-autoload --optimize --no-dev 2>&1', $output, $returnCode);
        if ($returnCode === 0) {
            $this->info('   ✓ Autoloader optimized');
        } else {
            $this->warn('   ⚠ Autoloader optimization skipped (composer not found)');
        }

        // 6. Generate sitemap
        $this->info('6. Generating sitemap...');
        try {
            // Trigger sitemap generation by calling the controller
            $sitemapPath = storage_path('app/sitemap/sitemap.xml');
            if (File::exists($sitemapPath)) {
                File::delete($sitemapPath);
            }
            $this->info('   ✓ Sitemap cache cleared (will regenerate on first request)');
        } catch (\Exception $e) {
            $this->warn('   ⚠ Sitemap generation skipped: ' . $e->getMessage());
        }

        // 7. Storage link check
        $this->info('7. Checking storage link...');
        $publicStoragePath = public_path('storage');
        if (!File::exists($publicStoragePath)) {
            Artisan::call('storage:link');
            $this->info('   ✓ Storage link created');
        } else {
            $this->info('   ✓ Storage link exists');
        }

        $this->newLine();
        $this->info('═══════════════════════════════════════════');
        $this->info('✅ Production optimization complete!');
        $this->info('═══════════════════════════════════════════');
        $this->newLine();

        // Display recommendations
        $this->warn('📋 Additional Recommendations for GTmetrix Grade A:');
        $this->line('   1. Run "npm run build" to compile and minify assets');
        $this->line('   2. Enable OPcache in PHP for faster execution');
        $this->line('   3. Use Redis for caching instead of file/database');
        $this->line('   4. Enable mod_deflate and mod_expires in Apache');
        $this->line('   5. Use CDN for static assets if high traffic');
        $this->line('   6. Optimize images with WebP format');
        $this->newLine();

        return Command::SUCCESS;
    }
}
