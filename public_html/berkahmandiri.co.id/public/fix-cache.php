<?php
/**
 * Clear Laravel Cache via Web Access
 * Upload to: /public_html/berkahmandiri.co.id/fix-cache.php
 * Access via: https://berkahmandiri.co.id/fix-cache.php?key=bmg2024fix
 */

// Security key
$FIX_KEY = 'bmg2024fix';
if (!isset($_GET['key']) || $_GET['key'] !== $FIX_KEY) {
    die('Access denied. Add ?key=' . $FIX_KEY . ' to URL');
}

echo "<!DOCTYPE html><html><head><title>Cache Fixer</title>";
echo "<style>body{font-family:monospace;padding:20px;background:#f5f5f5;}";
echo "pre{background:#fff;padding:15px;border:1px solid #ddd;margin:10px 0;}";
echo ".success{color:green;} .error{color:red;}</style></head><body>";

echo "<h1>🔧 Laravel Cache Fixer</h1>";
echo "<p>Started: " . date('Y-m-d H:i:s') . "</p><hr>";

$laravel_base = dirname(__FILE__);
$results = [];

// 1. Delete bootstrap cache files
echo "<h2>1. Clearing Bootstrap Cache</h2><pre>";
$bootstrap_files = [
    '/bootstrap/cache/config.php',
    '/bootstrap/cache/routes-v7.php',
    '/bootstrap/cache/services.php',
    '/bootstrap/cache/packages.php',
    '/bootstrap/cache/events.php',
];

foreach ($bootstrap_files as $file) {
    $fullPath = $laravel_base . $file;
    if (file_exists($fullPath)) {
        if (unlink($fullPath)) {
            echo "<span class='success'>✓</span> Deleted: {$file}\n";
            $results[] = "Deleted {$file}";
        } else {
            echo "<span class='error'>✗</span> Failed to delete: {$file}\n";
        }
    } else {
        echo "  - Not found: {$file}\n";
    }
}
echo "</pre>";

// 2. Clear storage/framework/cache
echo "<h2>2. Clearing Framework Cache</h2><pre>";
$cache_dir = $laravel_base . '/storage/framework/cache/data';
if (is_dir($cache_dir)) {
    $deleted_count = 0;
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($cache_dir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    );
    
    foreach ($iterator as $file) {
        if ($file->isFile()) {
            if (unlink($file->getRealPath())) {
                $deleted_count++;
            }
        }
    }
    
    echo "<span class='success'>✓</span> Deleted {$deleted_count} cache files\n";
    $results[] = "Deleted {$deleted_count} framework cache files";
} else {
    echo "  - Cache directory not found\n";
}
echo "</pre>";

// 3. Clear storage/framework/views
echo "<h2>3. Clearing Compiled Views</h2><pre>";
$views_dir = $laravel_base . '/storage/framework/views';
if (is_dir($views_dir)) {
    $deleted_count = 0;
    $files = glob($views_dir . '/*');
    foreach ($files as $file) {
        if (is_file($file) && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
            if (unlink($file)) {
                $deleted_count++;
            }
        }
    }
    echo "<span class='success'>✓</span> Deleted {$deleted_count} compiled views\n";
    $results[] = "Deleted {$deleted_count} compiled views";
} else {
    echo "  - Views directory not found\n";
}
echo "</pre>";

// 4. Clear old sessions (older than 2 hours)
echo "<h2>4. Clearing Old Sessions</h2><pre>";
$sessions_dir = $laravel_base . '/storage/framework/sessions';
if (is_dir($sessions_dir)) {
    $deleted_count = 0;
    $files = glob($sessions_dir . '/*');
    $two_hours_ago = time() - (2 * 3600);
    
    foreach ($files as $file) {
        if (is_file($file) && filemtime($file) < $two_hours_ago) {
            if (unlink($file)) {
                $deleted_count++;
            }
        }
    }
    echo "<span class='success'>✓</span> Deleted {$deleted_count} old session files\n";
    $results[] = "Deleted {$deleted_count} old sessions";
} else {
    echo "  - Sessions directory not found\n";
}
echo "</pre>";

// 5. Fix permissions
echo "<h2>5. Fixing Permissions</h2><pre>";
$dirs_to_fix = [
    '/storage',
    '/storage/framework',
    '/storage/framework/cache',
    '/storage/framework/sessions',
    '/storage/framework/views',
    '/storage/logs',
    '/bootstrap/cache',
];

foreach ($dirs_to_fix as $dir) {
    $fullPath = $laravel_base . $dir;
    if (is_dir($fullPath)) {
        if (chmod($fullPath, 0775)) {
            echo "<span class='success'>✓</span> Fixed: {$dir}\n";
        } else {
            echo "<span class='error'>✗</span> Failed: {$dir}\n";
        }
    }
}
echo "</pre>";

// 6. Try to bootstrap Laravel and run artisan commands
echo "<h2>6. Running Artisan Commands</h2><pre>";
try {
    require $laravel_base . '/vendor/autoload.php';
    $app = require_once $laravel_base . '/bootstrap/app.php';
    
    // Clear application cache
    try {
        Artisan::call('cache:clear');
        echo "<span class='success'>✓</span> Artisan: cache:clear\n";
        $results[] = "Ran cache:clear";
    } catch (Exception $e) {
        echo "<span class='error'>✗</span> cache:clear failed: " . $e->getMessage() . "\n";
    }
    
    // Clear config cache
    try {
        Artisan::call('config:clear');
        echo "<span class='success'>✓</span> Artisan: config:clear\n";
        $results[] = "Ran config:clear";
    } catch (Exception $e) {
        echo "<span class='error'>✗</span> config:clear failed: " . $e->getMessage() . "\n";
    }
    
    // Clear route cache
    try {
        Artisan::call('route:clear');
        echo "<span class='success'>✓</span> Artisan: route:clear\n";
        $results[] = "Ran route:clear";
    } catch (Exception $e) {
        echo "<span class='error'>✗</span> route:clear failed: " . $e->getMessage() . "\n";
    }
    
    // Clear view cache
    try {
        Artisan::call('view:clear');
        echo "<span class='success'>✓</span> Artisan: view:clear\n";
        $results[] = "Ran view:clear";
    } catch (Exception $e) {
        echo "<span class='error'>✗</span> view:clear failed: " . $e->getMessage() . "\n";
    }
    
} catch (Exception $e) {
    echo "<span class='error'>✗</span> Could not bootstrap Laravel: " . $e->getMessage() . "\n";
}
echo "</pre>";

// Summary
echo "<hr><h2>✅ Summary</h2><pre>";
foreach ($results as $result) {
    echo "• {$result}\n";
}
echo "\nCompleted: " . date('Y-m-d H:i:s') . "</pre>";

echo "<hr><h2>🔄 Next Steps</h2><pre>";
echo "1. Wait 2-3 minutes for changes to take effect\n";
echo "2. Try accessing admin panel in incognito window:\n";
echo "   https://berkahmandiri.co.id/admin\n";
echo "3. If still 403, run diagnostic.php to check server config\n";
echo "4. Delete this file after use for security\n";
echo "</pre>";

echo "</body></html>";
