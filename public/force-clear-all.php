<?php
/**
 * Force Clear All Caches + Delete Compiled Views
 * Access: https://berkahmandiri.co.id/force-clear-all.php?key=YOUR_SECRET_KEY
 */

// Security key
$secretKey = 'bm2026clearcache';

if (!isset($_GET['key']) || $_GET['key'] !== $secretKey) {
    http_response_code(403);
    die('Access denied');
}

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->bootstrap();

header('Content-Type: text/plain');

echo "=== Force Clear All Caches ===\n\n";

try {
    // 1. Truncate cache table
    DB::table('cache')->truncate();
    echo "✓ Cache table truncated\n";
    
    // 2. Clear Laravel caches
    Artisan::call('cache:clear');
    echo "✓ Laravel cache cleared\n";
    
    Artisan::call('view:clear');
    echo "✓ View cache cleared\n";
    
    Artisan::call('config:clear');
    echo "✓ Config cache cleared\n";
    
    Artisan::call('route:clear');
    echo "✓ Route cache cleared\n";
    
    // 3. Delete compiled view files manually
    $viewPath = storage_path('framework/views');
    if (is_dir($viewPath)) {
        $files = glob($viewPath . '/*');
        $deletedCount = 0;
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
                $deletedCount++;
            }
        }
        echo "✓ Deleted {$deletedCount} compiled view files\n";
    }
    
    // 4. Clear OPcache
    if (function_exists('opcache_reset')) {
        opcache_reset();
        echo "✓ OPcache cleared\n";
    }
    
    // 5. Clear Blade component cache
    if (is_dir(storage_path('framework/cache'))) {
        $cacheFiles = glob(storage_path('framework/cache') . '/*');
        $deletedCache = 0;
        foreach ($cacheFiles as $file) {
            if (is_file($file) && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                unlink($file);
                $deletedCache++;
            }
        }
        if ($deletedCache > 0) {
            echo "✓ Deleted {$deletedCache} framework cache files\n";
        }
    }
    
    echo "\n=== DONE! All caches completely cleared ===\n";
    echo "Please refresh your browser (Ctrl+F5)\n";
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
