<?php
/**
 * Clear Laravel Cache - Web Accessible Script
 * Access: https://berkahmandiri.co.id/clear-cache.php?key=YOUR_SECRET_KEY
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
echo "=== Laravel Cache Clear ===\n\n";

// Clear view cache
try {
    Artisan::call('view:clear');
    echo "✓ View cache cleared\n";
} catch (Exception $e) {
    echo "✗ View cache error: " . $e->getMessage() . "\n";
}

// Clear config cache
try {
    Artisan::call('config:clear');
    echo "✓ Config cache cleared\n";
} catch (Exception $e) {
    echo "✗ Config cache error: " . $e->getMessage() . "\n";
}

// Clear route cache
try {
    Artisan::call('route:clear');
    echo "✓ Route cache cleared\n";
} catch (Exception $e) {
    echo "✗ Route cache error: " . $e->getMessage() . "\n";
}

// Clear application cache
try {
    Artisan::call('cache:clear');
    echo "✓ Application cache cleared\n";
} catch (Exception $e) {
    echo "✗ Application cache error: " . $e->getMessage() . "\n";
}

// Clear compiled classes
try {
    Artisan::call('clear-compiled');
    echo "✓ Compiled classes cleared\n";
} catch (Exception $e) {
    echo "✗ Clear compiled error: " . $e->getMessage() . "\n";
}

echo "\n=== Done! ===\n";
echo "</pre>";
