<?php
/**
 * Truncate Database Cache Table
 * Access: https://berkahmandiri.co.id/truncate-cache.php?key=YOUR_SECRET_KEY
 */

// Security key - change this!
$secretKey = 'bm2026clearcache';

if (!isset($_GET['key']) || $_GET['key'] !== $secretKey) {
    http_response_code(403);
    die('Access denied');
}

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->bootstrap();

echo "<pre>\n";
echo "=== Truncate Cache Table ===\n\n";

try {
    // Truncate cache table
    DB::table('cache')->truncate();
    echo "✓ Cache table truncated\n";
    
    // Also clear Laravel caches
    Artisan::call('cache:clear');
    echo "✓ Laravel cache cleared\n";
    
    Artisan::call('view:clear');
    echo "✓ View cache cleared\n";
    
    echo "\n=== Done! Cache completely cleared ===\n";
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}

echo "</pre>";
