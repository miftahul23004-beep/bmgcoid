<?php
/**
 * Clear Cache Script for Hosting
 * Access via: https://berkahmandiri.co.id/clear-cache.php?key=bmg2026
 */

// Security key - change this!
$securityKey = 'bmg2026';

if (!isset($_GET['key']) || $_GET['key'] !== $securityKey) {
    http_response_code(403);
    die('Forbidden');
}

// Change to Laravel root
chdir(__DIR__);

echo "<pre>\n";
echo "=== Laravel Cache Clear ===\n\n";

// Clear various caches
$commands = [
    'php artisan config:clear',
    'php artisan cache:clear', 
    'php artisan view:clear',
    'php artisan route:clear',
    'php artisan filament:clear-cached-components',
];

foreach ($commands as $cmd) {
    echo "Running: {$cmd}\n";
    $output = shell_exec($cmd . ' 2>&1');
    echo $output . "\n";
}

// Also clear bootstrap cache files
$cacheFiles = [
    'bootstrap/cache/config.php',
    'bootstrap/cache/routes-v7.php',
    'bootstrap/cache/services.php',
    'bootstrap/cache/packages.php',
];

echo "Clearing bootstrap cache files...\n";
foreach ($cacheFiles as $file) {
    if (file_exists($file)) {
        unlink($file);
        echo "Deleted: {$file}\n";
    }
}

// Clear compiled views
$viewsPath = 'storage/framework/views';
if (is_dir($viewsPath)) {
    $files = glob($viewsPath . '/*.php');
    $count = 0;
    foreach ($files as $file) {
        unlink($file);
        $count++;
    }
    echo "Cleared {$count} compiled view files\n";
}

echo "\n=== Cache Cleared Successfully! ===\n";
echo "</pre>";

// Self-delete option
if (isset($_GET['delete'])) {
    unlink(__FILE__);
    echo "<p>This script has been deleted for security.</p>";
}
