<?php
/**
 * Clear OPcache
 * Access: https://berkahmandiri.co.id/clear-opcache.php?key=YOUR_SECRET_KEY
 */

// Security key
$secretKey = 'bm2026clearcache';

if (!isset($_GET['key']) || $_GET['key'] !== $secretKey) {
    http_response_code(403);
    die('Access denied');
}

header('Content-Type: text/plain');

echo "=== OPcache Clear ===\n\n";

if (function_exists('opcache_reset')) {
    if (opcache_reset()) {
        echo "✓ OPcache cleared successfully\n";
    } else {
        echo "✗ Failed to clear OPcache\n";
    }
    
    // Get OPcache status
    $status = opcache_get_status();
    if ($status) {
        echo "\nOPcache Stats:\n";
        echo "- Memory Used: " . round($status['memory_usage']['used_memory'] / 1024 / 1024, 2) . " MB\n";
        echo "- Cached Scripts: " . $status['opcache_statistics']['num_cached_scripts'] . "\n";
        echo "- Hits: " . $status['opcache_statistics']['hits'] . "\n";
        echo "- Misses: " . $status['opcache_statistics']['misses'] . "\n";
    }
} else {
    echo "✗ OPcache is not enabled\n";
}

echo "\n=== Done! ===\n";
