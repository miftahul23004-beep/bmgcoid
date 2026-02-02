<?php
/**
 * Fix Routes Script
 * Clears route cache and re-registers routes
 * Access via: https://berkahmandiri.co.id/fix-routes.php?key=bmg2026
 */

$securityKey = 'bmg2026';

if (!isset($_GET['key']) || $_GET['key'] !== $securityKey) {
    http_response_code(403);
    die('Forbidden');
}

// Change to Laravel root (one level up from public)
chdir(__DIR__ . '/..');

echo "<pre>\n";
echo "=== Fix Laravel Routes ===\n";
echo "Working directory: " . getcwd() . "\n\n";

// 1. Clear all caches
echo "1. Clearing caches...\n";
echo shell_exec('php artisan config:clear 2>&1') . "\n";
echo shell_exec('php artisan route:clear 2>&1') . "\n";
echo shell_exec('php artisan cache:clear 2>&1') . "\n";
echo shell_exec('php artisan view:clear 2>&1') . "\n";

// 2. List routes to verify Livewire is registered
echo "2. Checking Livewire routes...\n";
$routes = shell_exec('php artisan route:list --name=livewire 2>&1');
echo $routes . "\n";

// 3. Re-cache (optional)
echo "3. Routes cleared. Try login again!\n";

echo "\n=== Done ===\n";
echo "</pre>";
