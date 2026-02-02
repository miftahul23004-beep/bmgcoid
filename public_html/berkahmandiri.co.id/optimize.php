<?php
/**
 * Optimize Script for Production Server
 * Run this once after deployment to enable caching
 * Access via: https://berkahmandiri.co.id/optimize.php?key=bmg2026
 */

// Security key
$securityKey = 'bmg2026';

if (!isset($_GET['key']) || $_GET['key'] !== $securityKey) {
    http_response_code(403);
    die('Forbidden');
}

chdir(__DIR__);

echo "<pre>\n";
echo "=== Laravel Production Optimization ===\n\n";

// 1. Cache config
echo "1. Caching configuration...\n";
echo shell_exec('php artisan config:cache 2>&1') . "\n";

// 2. Cache routes  
echo "2. Caching routes...\n";
echo shell_exec('php artisan route:cache 2>&1') . "\n";

// 3. Cache views
echo "3. Caching views...\n";
echo shell_exec('php artisan view:cache 2>&1') . "\n";

// 4. Cache events
echo "4. Caching events...\n";
echo shell_exec('php artisan event:cache 2>&1') . "\n";

// 5. Optimize autoloader
echo "5. Optimizing composer autoloader...\n";
echo shell_exec('composer dump-autoload --optimize --no-dev 2>&1') . "\n";

// 6. Cache icons
echo "6. Caching blade icons...\n";
echo shell_exec('php artisan icons:cache 2>&1') . "\n";

// 7. Cache Filament components
echo "7. Caching Filament components...\n";
echo shell_exec('php artisan filament:cache-components 2>&1') . "\n";

echo "\n=== Optimization Complete! ===\n";
echo "</pre>";

// Check OPcache status
echo "<h3>OPcache Status:</h3><pre>";
if (function_exists('opcache_get_status')) {
    $status = opcache_get_status();
    if ($status) {
        echo "OPcache: ENABLED ✓\n";
        echo "Memory Used: " . round($status['memory_usage']['used_memory'] / 1024 / 1024, 2) . " MB\n";
        echo "Cached Scripts: " . $status['opcache_statistics']['num_cached_scripts'] . "\n";
    } else {
        echo "OPcache: DISABLED - Enable for better performance!\n";
    }
} else {
    echo "OPcache: NOT INSTALLED\n";
}
echo "</pre>";
