<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use ZipArchive;

class BackupRestore extends Command
{
    protected $signature = 'backup:restore {backup : Backup filename (without .zip)} {--force : Skip confirmation}';
    protected $description = 'Restore backup from database and files';

    public function handle()
    {
        $backupName = $this->argument('backup');
        $zipPath = storage_path("app/backups/{$backupName}.zip");

        if (!file_exists($zipPath)) {
            $this->error("Backup file not found: {$zipPath}");
            $this->info("Available backups:");
            $this->call('backup:list');
            return Command::FAILURE;
        }

        if (!$this->option('force')) {
            if (!$this->confirm('This will overwrite current data. Are you sure?')) {
                $this->info('Restore cancelled.');
                return Command::SUCCESS;
            }
        }

        $this->warn('Starting restore process...');

        try {
            // Extract ZIP
            $extractPath = storage_path("app/backups/restore_temp");
            $this->info('Extracting backup...');
            $this->extractZip($zipPath, $extractPath);
            $this->info('✓ Backup extracted');

            // Restore database
            if (file_exists("{$extractPath}/database.sql")) {
                $this->info('Restoring database...');
                $this->restoreDatabase("{$extractPath}/database.sql");
                $this->info('✓ Database restored');
            }

            // Restore files
            if (is_dir("{$extractPath}/files")) {
                $this->info('Restoring files...');
                $this->restoreFiles("{$extractPath}/files");
                $this->info('✓ Files restored');
            }

            // Cleanup
            $this->deleteDirectory($extractPath);

            // Clear caches
            $this->info('Clearing caches...');
            Artisan::call('optimize:clear');
            
            $this->info('✓ Restore completed successfully!');
            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error("Restore failed: " . $e->getMessage());
            
            // Cleanup on failure
            if (isset($extractPath) && is_dir($extractPath)) {
                $this->deleteDirectory($extractPath);
            }
            
            return Command::FAILURE;
        }
    }

    protected function extractZip($zipPath, $extractPath)
    {
        // Remove old extract directory
        if (is_dir($extractPath)) {
            $this->deleteDirectory($extractPath);
        }

        mkdir($extractPath, 0755, true);

        $zip = new ZipArchive();
        if ($zip->open($zipPath) !== true) {
            throw new \Exception("Failed to open ZIP file");
        }

        $zip->extractTo($extractPath);
        $zip->close();
    }

    protected function restoreDatabase($sqlFile)
    {
        $databaseName = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $host = config('database.connections.mysql.host');
        $port = config('database.connections.mysql.port', 3306);

        // Try mysql command first
        $mysqlPath = $this->findMysql();
        
        if ($mysqlPath) {
            $passwordArg = $password ? "-p\"{$password}\"" : '';
            $command = "\"{$mysqlPath}\" -h {$host} -P {$port} -u {$username} {$passwordArg} {$databaseName} < \"{$sqlFile}\" 2>&1";
            
            exec($command, $output, $returnVar);
            
            if ($returnVar !== 0) {
                $this->info('mysql command failed, using PHP import...');
                $this->phpDatabaseImport($sqlFile);
            }
        } else {
            $this->phpDatabaseImport($sqlFile);
        }
    }

    protected function findMysql()
    {
        $paths = [
            'C:\xampp\mysql\bin\mysql.exe',
            'C:\xampp2\mysql\bin\mysql.exe',
            'C:\Program Files\MySQL\MySQL Server 8.0\bin\mysql.exe',
            'C:\Program Files\MySQL\MySQL Server 5.7\bin\mysql.exe',
        ];

        foreach ($paths as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }

        exec('where mysql', $output, $returnVar);
        if ($returnVar === 0 && !empty($output[0])) {
            return trim($output[0]);
        }

        return null;
    }

    protected function phpDatabaseImport($sqlFile)
    {
        $sql = file_get_contents($sqlFile);
        
        // Split by semicolon but not inside quotes
        DB::unprepared($sql);
    }

    protected function restoreFiles($filesPath)
    {
        $mappings = [
            'storage_public' => 'storage/app/public',
            'public_storage' => 'public/storage',
            'public_build' => 'public/build',
            'public_images' => 'public/images',
            'public_fonts' => 'public/fonts',
            'public_css' => 'public/css',
            'public_js' => 'public/js',
        ];

        foreach ($mappings as $source => $dest) {
            $sourcePath = "{$filesPath}/{$source}";
            if (is_dir($sourcePath)) {
                $destPath = base_path($dest);
                
                // Backup existing directory
                if (is_dir($destPath)) {
                    $backupPath = $destPath . '_backup_' . time();
                    rename($destPath, $backupPath);
                    $this->info("  - Backed up existing {$dest} to " . basename($backupPath));
                }
                
                $this->copyDirectory($sourcePath, $destPath);
                $this->info("  - Restored {$dest}");
            }
        }

        // Restore .env (optional, ask first)
        if (file_exists("{$filesPath}/.env")) {
            if ($this->confirm('Restore .env file?', false)) {
                copy("{$filesPath}/.env", base_path('.env'));
                $this->info("  - Restored .env");
            }
        }
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
}
