<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class BackupList extends Command
{
    protected $signature = 'backup:list';
    protected $description = 'List all available backups';

    public function handle()
    {
        $backupPath = storage_path('app/backups');
        
        if (!is_dir($backupPath)) {
            $this->info('No backups directory found.');
            return Command::SUCCESS;
        }

        $backups = glob("{$backupPath}/*.zip");
        
        if (empty($backups)) {
            $this->info('No backups found.');
            return Command::SUCCESS;
        }

        $this->info('Available backups:');
        $this->info('');

        $data = [];
        foreach ($backups as $backup) {
            $filename = basename($backup);
            $size = $this->formatBytes(filesize($backup));
            $date = date('Y-m-d H:i:s', filemtime($backup));
            
            $data[] = [
                'filename' => str_replace('.zip', '', $filename),
                'size' => $size,
                'date' => $date,
            ];
        }

        $this->table(['Backup Name', 'Size', 'Created'], array_map(function($item) {
            return [$item['filename'], $item['size'], $item['date']];
        }, $data));

        $this->info('');
        $this->info('To restore a backup, use: php artisan backup:restore <backup-name>');

        return Command::SUCCESS;
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
