<?php
/**
 * Web Backup Script - Production Use
 * Access: https://berkahmandiri.co.id/backup.php?key=YOUR_SECRET_KEY&action=create|list|download|restore
 */

// Security key - CHANGE THIS!
$secretKey = 'bm2026backup';

if (!isset($_GET['key']) || $_GET['key'] !== $secretKey) {
    http_response_code(403);
    die('Access denied');
}

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$action = $_GET['action'] ?? 'list';

header('Content-Type: text/plain');

switch ($action) {
    case 'create':
        createBackup();
        break;
    
    case 'list':
        listBackups();
        break;
    
    case 'download':
        downloadBackup();
        break;
    
    case 'restore':
        restoreBackup();
        break;
    
    default:
        echo "Invalid action. Use: create, list, download, or restore\n";
}

function createBackup()
{
    $type = $_GET['type'] ?? 'full'; // full, database, files
    
    echo "=== Creating {$type} Backup ===\n\n";
    
    try {
        Artisan::call('backup:create', [
            '--type' => $type
        ]);
        
        echo Artisan::output();
        echo "\n=== Backup Complete! ===\n";
        
    } catch (Exception $e) {
        echo "ERROR: " . $e->getMessage() . "\n";
    }
}

function listBackups()
{
    echo "=== Available Backups ===\n\n";
    
    $backupPath = storage_path('app/backups');
    
    if (!is_dir($backupPath)) {
        echo "No backups directory found.\n";
        return;
    }
    
    $backups = glob("{$backupPath}/*.zip");
    
    if (empty($backups)) {
        echo "No backups found.\n";
        return;
    }
    
    foreach ($backups as $backup) {
        $filename = basename($backup);
        $size = formatBytes(filesize($backup));
        $date = date('Y-m-d H:i:s', filemtime($backup));
        
        echo "• {$filename}\n";
        echo "  Size: {$size}\n";
        echo "  Created: {$date}\n";
        echo "  Download: backup.php?key={$_GET['key']}&action=download&file=" . urlencode($filename) . "\n\n";
    }
    
    echo "\nTo create backup: backup.php?key={$_GET['key']}&action=create&type=full\n";
    echo "To restore backup: backup.php?key={$_GET['key']}&action=restore&file=backup_full_YYYY-MM-DD_HH-ii-ss.zip\n";
}

function downloadBackup()
{
    if (!isset($_GET['file'])) {
        echo "ERROR: File parameter required\n";
        return;
    }
    
    $filename = basename($_GET['file']); // Security: prevent path traversal
    $backupPath = storage_path("app/backups/{$filename}");
    
    if (!file_exists($backupPath)) {
        echo "ERROR: Backup file not found\n";
        return;
    }
    
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Content-Length: ' . filesize($backupPath));
    
    readfile($backupPath);
    exit;
}

function restoreBackup()
{
    if (!isset($_GET['file'])) {
        echo "ERROR: File parameter required\n";
        return;
    }
    
    $filename = basename($_GET['file']);
    $backupId = str_replace('.zip', '', $filename);
    
    echo "=== Restoring Backup: {$filename} ===\n\n";
    echo "WARNING: This will overwrite current data!\n";
    echo "Starting restore in 3 seconds...\n\n";
    
    sleep(3);
    
    try {
        Artisan::call('backup:restore', [
            'backup' => $backupId,
            '--force' => true
        ]);
        
        echo Artisan::output();
        echo "\n=== Restore Complete! ===\n";
        echo "Please clear caches and refresh your browser.\n";
        
    } catch (Exception $e) {
        echo "ERROR: " . $e->getMessage() . "\n";
    }
}

function formatBytes($bytes, $precision = 2)
{
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    
    for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
        $bytes /= 1024;
    }
    
    return round($bytes, $precision) . ' ' . $units[$i];
}
