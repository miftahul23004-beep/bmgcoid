<?php
/**
 * Comprehensive Diagnostic Script for 403 Forbidden Issue
 * Upload to: /public_html/berkahmandiri.co.id/diagnostic.php
 * Access via: https://berkahmandiri.co.id/diagnostic.php
 */

// Security: Remove after diagnosis
$DIAGNOSTIC_KEY = 'bmg2024diagnosis';
if (!isset($_GET['key']) || $_GET['key'] !== $DIAGNOSTIC_KEY) {
    die('Access denied. Add ?key=' . $DIAGNOSTIC_KEY . ' to URL');
}

echo "<!DOCTYPE html><html><head><title>BMG Diagnostic</title>";
echo "<style>body{font-family:monospace;padding:20px;background:#f5f5f5;}";
echo "h2{color:#333;border-bottom:2px solid #007bff;padding-bottom:5px;}";
echo "pre{background:#fff;padding:15px;border:1px solid #ddd;overflow:auto;}";
echo ".success{color:green;} .error{color:red;} .warning{color:orange;}";
echo "</style></head><body>";

echo "<h1>🔍 BMG Diagnostic Report</h1>";
echo "<p><strong>Generated:</strong> " . date('Y-m-d H:i:s') . "</p>";

// 1. PHP Configuration
echo "<h2>1. PHP Configuration</h2><pre>";
echo "PHP Version: " . PHP_VERSION . "\n";
echo "Server API: " . PHP_SAPI . "\n";
echo "Max Execution Time: " . ini_get('max_execution_time') . "s\n";
echo "Memory Limit: " . ini_get('memory_limit') . "\n";
echo "Upload Max: " . ini_get('upload_max_filesize') . "\n";
echo "Post Max: " . ini_get('post_max_size') . "\n";
echo "Display Errors: " . (ini_get('display_errors') ? 'On' : 'Off') . "\n";
echo "</pre>";

// 2. Laravel Environment
echo "<h2>2. Laravel Environment</h2><pre>";
$laravel_base = dirname(__FILE__);
if (file_exists($laravel_base . '/bootstrap/cache/config.php')) {
    echo "<span class='success'>✓</span> Config cache exists\n";
} else {
    echo "<span class='warning'>⚠</span> Config cache not found\n";
}

if (file_exists($laravel_base . '/bootstrap/cache/routes-v7.php')) {
    echo "<span class='success'>✓</span> Route cache exists\n";
} else {
    echo "<span class='warning'>⚠</span> Route cache not found\n";
}

if (file_exists($laravel_base . '/.env')) {
    echo "<span class='success'>✓</span> .env file exists\n";
    // Read first few lines without exposing sensitive data
    $env_content = file_get_contents($laravel_base . '/.env');
    $lines = explode("\n", $env_content);
    echo "\nKey Environment Variables:\n";
    foreach ($lines as $line) {
        if (strpos($line, 'APP_') === 0 || strpos($line, 'SESSION_') === 0) {
            if (strpos($line, 'APP_KEY') === false) {
                echo "  " . $line . "\n";
            } else {
                echo "  APP_KEY=***hidden***\n";
            }
        }
    }
} else {
    echo "<span class='error'>✗</span> .env file NOT found!\n";
}
echo "</pre>";

// 3. File Permissions
echo "<h2>3. Critical Directory Permissions</h2><pre>";
$dirs = [
    'storage' => $laravel_base . '/storage',
    'storage/logs' => $laravel_base . '/storage/logs',
    'storage/framework' => $laravel_base . '/storage/framework',
    'storage/framework/cache' => $laravel_base . '/storage/framework/cache',
    'storage/framework/sessions' => $laravel_base . '/storage/framework/sessions',
    'storage/framework/views' => $laravel_base . '/storage/framework/views',
    'bootstrap/cache' => $laravel_base . '/bootstrap/cache',
];

foreach ($dirs as $name => $path) {
    if (file_exists($path)) {
        $perms = substr(sprintf('%o', fileperms($path)), -4);
        $writable = is_writable($path);
        $status = $writable ? "<span class='success'>✓</span>" : "<span class='error'>✗</span>";
        echo "{$status} {$name}: {$perms} " . ($writable ? "(writable)" : "(NOT writable)") . "\n";
    } else {
        echo "<span class='error'>✗</span> {$name}: NOT EXISTS\n";
    }
}
echo "</pre>";

// 4. .htaccess Check
echo "<h2>4. .htaccess Configuration</h2><pre>";
$htaccess_files = [
    'public/.htaccess' => $laravel_base . '/public/.htaccess',
    '.htaccess (root)' => $laravel_base . '/.htaccess',
];

foreach ($htaccess_files as $name => $path) {
    if (file_exists($path)) {
        echo "<span class='success'>✓</span> {$name} exists\n";
        $content = file_get_contents($path);
        
        // Check for ModSecurity
        if (stripos($content, 'SecRuleEngine') !== false) {
            echo "  → Contains ModSecurity rules\n";
            if (stripos($content, 'SecRuleEngine Off') !== false) {
                echo "     <span class='success'>✓</span> ModSecurity is OFF\n";
            } else {
                echo "     <span class='warning'>⚠</span> ModSecurity may be ON\n";
            }
        }
        
        // Check for RewriteEngine
        if (stripos($content, 'RewriteEngine') !== false) {
            echo "  → RewriteEngine found\n";
        }
        
    } else {
        echo "<span class='error'>✗</span> {$name} NOT found\n";
    }
}
echo "</pre>";

// 5. Server Variables
echo "<h2>5. Server Variables</h2><pre>";
echo "SERVER_SOFTWARE: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'N/A') . "\n";
echo "DOCUMENT_ROOT: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'N/A') . "\n";
echo "HTTP_HOST: " . ($_SERVER['HTTP_HOST'] ?? 'N/A') . "\n";
echo "REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'N/A') . "\n";
echo "REMOTE_ADDR: " . ($_SERVER['REMOTE_ADDR'] ?? 'N/A') . "\n";

// Check for ModSecurity
if (isset($_SERVER['HTTP_X_MODSECURITY'])) {
    echo "<span class='warning'>⚠</span> ModSecurity Header Detected\n";
}

// Check for security modules
$modules = apache_get_modules();
if (in_array('mod_security2', $modules)) {
    echo "<span class='warning'>⚠</span> mod_security2 is loaded\n";
} else {
    echo "<span class='success'>✓</span> mod_security2 not detected\n";
}
echo "</pre>";

// 6. Laravel Logs (last 20 lines)
echo "<h2>6. Recent Laravel Logs</h2><pre>";
$log_file = $laravel_base . '/storage/logs/laravel.log';
if (file_exists($log_file) && is_readable($log_file)) {
    $log_content = file_get_contents($log_file);
    $log_lines = explode("\n", $log_content);
    $recent_logs = array_slice($log_lines, -30);
    echo implode("\n", $recent_logs);
} else {
    echo "<span class='warning'>⚠</span> Log file not found or not readable\n";
}
echo "</pre>";

// 7. Session Configuration
echo "<h2>7. Session Configuration</h2><pre>";
echo "Session Save Path: " . session_save_path() . "\n";
echo "Session Name: " . session_name() . "\n";
echo "Session Module: " . ini_get('session.save_handler') . "\n";

if (file_exists($laravel_base . '/storage/framework/sessions')) {
    $session_files = glob($laravel_base . '/storage/framework/sessions/*');
    echo "Session Files Count: " . count($session_files) . "\n";
    echo "Session Dir Writable: " . (is_writable($laravel_base . '/storage/framework/sessions') ? 'Yes' : 'No') . "\n";
}
echo "</pre>";

// 8. Test Laravel Bootstrap
echo "<h2>8. Laravel Bootstrap Test</h2><pre>";
try {
    require $laravel_base . '/vendor/autoload.php';
    echo "<span class='success'>✓</span> Composer autoload successful\n";
    
    $app = require_once $laravel_base . '/bootstrap/app.php';
    echo "<span class='success'>✓</span> Laravel app bootstrap successful\n";
    
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    echo "<span class='success'>✓</span> Kernel instance created\n";
    
} catch (Exception $e) {
    echo "<span class='error'>✗</span> Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
echo "</pre>";

// 9. Test Routes
echo "<h2>9. Test Route Access</h2><pre>";
echo "Testing internal route resolution...\n";
try {
    if (isset($app)) {
        $routes = $app->make('router')->getRoutes();
        echo "Total routes registered: " . count($routes) . "\n";
        
        // Check if admin route exists
        $admin_route_found = false;
        foreach ($routes as $route) {
            if (strpos($route->uri(), 'admin') !== false) {
                $admin_route_found = true;
                break;
            }
        }
        
        if ($admin_route_found) {
            echo "<span class='success'>✓</span> Admin routes are registered\n";
        } else {
            echo "<span class='error'>✗</span> Admin routes NOT found\n";
        }
    }
} catch (Exception $e) {
    echo "<span class='error'>✗</span> Error testing routes: " . $e->getMessage() . "\n";
}
echo "</pre>";

// 10. Recommendations
echo "<h2>10. Recommendations</h2><pre>";
$recommendations = [];

if (!is_writable($laravel_base . '/storage/framework/sessions')) {
    $recommendations[] = "❌ Fix permissions: chmod -R 775 storage bootstrap/cache";
}

if (!file_exists($laravel_base . '/bootstrap/cache/config.php')) {
    $recommendations[] = "⚠️ Clear and recache: php artisan config:cache";
}

if (empty($recommendations)) {
    echo "<span class='success'>✓</span> No critical issues detected from diagnostics\n";
    echo "\nIf 403 persists, the issue may be:\n";
    echo "1. Hosting-level WAF (Web Application Firewall)\n";
    echo "2. Imunify360 Proactive Defense blocking requests\n";
    echo "3. Server-level firewall rules\n";
    echo "4. Contact hosting support to check server logs\n";
} else {
    foreach ($recommendations as $rec) {
        echo $rec . "\n";
    }
}
echo "</pre>";

echo "<hr><p><strong>⚠️ IMPORTANT:</strong> Delete this file after diagnosis for security!</p>";
echo "</body></html>";
