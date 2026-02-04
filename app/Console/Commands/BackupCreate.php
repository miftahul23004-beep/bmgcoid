<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class BackupCreate extends Command
{
    protected $signature = 'backup:create {--type=full : Type of backup (full, database, files)}';
    protected $description = 'Create a full backup of database and files';

    public function handle()
    {
        $type = $this->option('type');
        $timestamp = now()->format('Y-m-d_H-i-s');
        $backupName = "backup_{$type}_{$timestamp}";
        $backupPath = storage_path("app/backups/{$backupName}");
        
        // Create backup directory
        if (!is_dir(storage_path('app/backups'))) {
            mkdir(storage_path('app/backups'), 0755, true);
        }
        
        if (!is_dir($backupPath)) {
            mkdir($backupPath, 0755, true);
        }

        $this->info("Creating {$type} backup...");

        try {
            // Backup database
            if ($type === 'full' || $type === 'database') {
                $this->info('Backing up database...');
                $this->backupDatabase($backupPath);
                $this->info('✓ Database backup completed');
            }

            // Backup files
            if ($type === 'full' || $type === 'files') {
                $this->info('Backing up files...');
                $this->backupFiles($backupPath);
                $this->info('✓ Files backup completed');
            }

            // Create ZIP archive
            $this->info('Creating ZIP archive...');
            $zipPath = storage_path("app/backups/{$backupName}.zip");
            $this->createZip($backupPath, $zipPath);
            
            // Remove temp directory
            $this->deleteDirectory($backupPath);

            $size = $this->formatBytes(filesize($zipPath));
            $this->info("✓ Backup completed: {$backupName}.zip ({$size})");
            $this->info("Location: {$zipPath}");

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error("Backup failed: " . $e->getMessage());
            return Command::FAILURE;
        }
    }

    protected function backupDatabase($backupPath)
    {
        $databaseName = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $host = config('database.connections.mysql.host');
        $port = config('database.connections.mysql.port', 3306);

        $sqlFile = "{$backupPath}/database.sql";

        // Try mysqldump first
        $mysqldumpPath = $this->findMysqldump();
        
        if ($mysqldumpPath) {
            $passwordArg = $password ? "-p\"{$password}\"" : '';
            $command = "\"{$mysqldumpPath}\" -h {$host} -P {$port} -u {$username} {$passwordArg} {$databaseName} > \"{$sqlFile}\" 2>&1";
            
            exec($command, $output, $returnVar);
            
            if ($returnVar !== 0 || !file_exists($sqlFile) || filesize($sqlFile) === 0) {
                // Fallback to PHP export
                $this->info('mysqldump failed, using PHP export...');
                $this->phpDatabaseExport($sqlFile);
            }
        } else {
            // Use PHP export
            $this->phpDatabaseExport($sqlFile);
        }

        if (!file_exists($sqlFile) || filesize($sqlFile) === 0) {
            throw new \Exception('Database backup failed');
        }
    }

    protected function findMysqldump()
    {
        // Common paths for mysqldump on Windows
        $paths = [
            'C:\xampp\mysql\bin\mysqldump.exe',
            'C:\xampp2\mysql\bin\mysqldump.exe',
            'C:\Program Files\MySQL\MySQL Server 8.0\bin\mysqldump.exe',
            'C:\Program Files\MySQL\MySQL Server 5.7\bin\mysqldump.exe',
        ];

        foreach ($paths as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }

        // Try system PATH
        exec('where mysqldump', $output, $returnVar);
        if ($returnVar === 0 && !empty($output[0])) {
            return trim($output[0]);
        }

        return null;
    }

    protected function phpDatabaseExport($sqlFile)
    {
        $tables = DB::select('SHOW TABLES');
        $databaseName = config('database.connections.mysql.database');
        $tableKey = "Tables_in_{$databaseName}";
        
        $sql = "-- Database Backup\n";
        $sql .= "-- Generated: " . now()->toDateTimeString() . "\n\n";
        $sql .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

        foreach ($tables as $table) {
            $tableName = $table->$tableKey;
            
            // Get CREATE TABLE
            $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`");
            $sql .= "DROP TABLE IF EXISTS `{$tableName}`;\n";
            $sql .= $createTable[0]->{'Create Table'} . ";\n\n";
            
            // Get data
            $rows = DB::table($tableName)->get();
            if ($rows->count() > 0) {
                $sql .= "INSERT INTO `{$tableName}` VALUES\n";
                $values = [];
                foreach ($rows as $row) {
                    $rowData = array_map(function($value) {
                        if ($value === null) return 'NULL';
                        return "'" . addslashes($value) . "'";
                    }, (array)$row);
                    $values[] = '(' . implode(',', $rowData) . ')';
                }
                $sql .= implode(",\n", $values) . ";\n\n";
            }
        }

        $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";
        
        file_put_contents($sqlFile, $sql);
    }

    protected function backupFiles($backupPath)
    {
        $filesPath = "{$backupPath}/files";
        mkdir($filesPath, 0755, true);

        // Backup important directories
        $directories = [
            'storage/app/public' => 'storage_public',
            'public/storage' => 'public_storage',
            'public/build' => 'public_build',
            'public/images' => 'public_images',
            'public/fonts' => 'public_fonts',
            'public/css' => 'public_css',
            'public/js' => 'public_js',
        ];

        foreach ($directories as $source => $dest) {
            $sourcePath = base_path($source);
            if (is_dir($sourcePath)) {
                $destPath = "{$filesPath}/{$dest}";
                $this->copyDirectory($sourcePath, $destPath);
                $this->info("  - Copied {$source}");
            }
        }

        // Backup .env file
        if (file_exists(base_path('.env'))) {
            copy(base_path('.env'), "{$filesPath}/.env");
            $this->info("  - Copied .env");
        }
    }

    protected function createZip($sourcePath, $zipPath)
    {
        $zip = new ZipArchive();
        
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new \Exception("Failed to create ZIP file");
        }

        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($sourcePath),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($sourcePath) + 1);
                $zip->addFile($filePath, $relativePath);
            }
        }

        $zip->close();
    }

    protected function copyDirectory($source, $dest)
    {
        if (!is_dir($dest)) {
            mkdir($dest, 0755, true);
        }

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $item) {
            $destPath = $dest . DIRECTORY_SEPARATOR . $iterator->getSubPathName();
            if ($item->isDir()) {
                if (!is_dir($destPath)) {
                    mkdir($destPath, 0755, true);
                }
            } else {
                copy($item, $destPath);
            }
        }
    }

    protected function deleteDirectory($dir)
    {
        if (!is_dir($dir)) {
            return;
        }

        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($files as $file) {
            if ($file->isDir()) {
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }

        rmdir($dir);
    }

    protected function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
