<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use UnitEnum;

class BackupManager extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-archive-box';
    protected static ?string $navigationLabel = 'Backups';
    protected static string|UnitEnum|null $navigationGroup = 'System';
    protected static ?int $navigationSort = 100;
    protected string $view = 'filament.pages.backup-manager';

    public string $selectedType = 'full';

    public static function canAccess(): bool
    {
        return auth()->user()?->hasRole('Super Admin') ?? false;
    }

    public static function getNavigationGroup(): ?string
    {
        return 'System';
    }

    /**
     * Get total size of all backups (Spatie + SQL)
     */
    public function getTotalSize(): string
    {
        $totalBytes = 0;

        // Spatie backups
        $backupName = config('backup.backup.name');
        $disk = Storage::disk('backups');
        $backupPath = $backupName;
        
        if ($disk->exists($backupPath)) {
            foreach ($disk->files($backupPath) as $file) {
                if (str_ends_with($file, '.zip')) {
                    $totalBytes += $disk->size($file);
                }
            }
        }

        // SQL backups
        $sqlDir = storage_path('app/backups');
        if (is_dir($sqlDir)) {
            foreach (array_merge(glob("{$sqlDir}/*.sql"), glob("{$sqlDir}/*.sql.gz")) as $f) {
                $totalBytes += filesize($f);
            }
        }
        
        return $this->formatBytes($totalBytes);
    }

    /**
     * Get all backups from Spatie backup destination
     */
    public function getBackups(): array
    {
        $backupName = config('backup.backup.name');
        $disk = Storage::disk('backups');
        
        // Spatie stores backups in {app-name}/ folder
        $backupPath = $backupName;
        
        if (!$disk->exists($backupPath)) {
            return [];
        }

        $files = $disk->files($backupPath);
        $backups = [];
        
        foreach ($files as $file) {
            if (!str_ends_with($file, '.zip')) {
                continue;
            }
            
            $filename = basename($file);
            
            // Determine backup type from content or default to full
            $type = 'full';
            
            $backups[] = [
                'id' => str_replace('.zip', '', $filename),
                'filename' => $filename,
                'path' => $file,
                'type' => $type,
                'type_color' => 'success',
                'size' => $this->formatBytes($disk->size($file)),
                'created_at' => date('Y-m-d H:i:s', $disk->lastModified($file)),
            ];
        }

        // Sort by date descending
        usort($backups, fn($a, $b) => strtotime($b['created_at']) - strtotime($a['created_at']));
        
        return $backups;
    }

    /**
     * Create backup using Spatie's backup:run command
     */
    public function createBackup(): void
    {
        try {
            Notification::make()
                ->title('Creating backup...')
                ->info()
                ->body('This may take a few minutes. Please wait.')
                ->send();

            // Determine which backup to run based on type
            $options = [];
            
            if ($this->selectedType === 'database') {
                $options['--only-db'] = true;
            } elseif ($this->selectedType === 'files') {
                $options['--only-files'] = true;
            }
            // 'full' runs without options (backs up both db and files)
            
            // Disable notifications during run
            $options['--disable-notifications'] = true;

            $exitCode = Artisan::call('backup:run', $options);
            $output = Artisan::output();

            if ($exitCode === 0) {
                Notification::make()
                    ->title('Backup created successfully!')
                    ->success()
                    ->body('Your backup has been created.')
                    ->send();
            } else {
                Notification::make()
                    ->title('Backup may have issues')
                    ->warning()
                    ->body($output)
                    ->send();
            }
                
            // Force page refresh
            $this->dispatch('$refresh');
        } catch (\Exception $e) {
            Notification::make()
                ->title('Backup failed')
                ->danger()
                ->body($e->getMessage())
                ->send();
        }
    }

    /**
     * Download a backup file
     */
    public function downloadBackup(string $filename): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $backupName = config('backup.backup.name');
        $disk = Storage::disk('backups');
        $path = $backupName . '/' . $filename;
        
        if (!$disk->exists($path)) {
            Notification::make()
                ->title('File not found')
                ->danger()
                ->send();
            return response()->noContent();
        }

        return $disk->download($path, $filename);
    }

    /**
     * Delete a backup file
     */
    public function deleteBackup(string $filename): void
    {
        $backupName = config('backup.backup.name');
        $disk = Storage::disk('backups');
        $path = $backupName . '/' . $filename;
        
        if ($disk->exists($path) && $disk->delete($path)) {
            Notification::make()
                ->title('Backup deleted')
                ->success()
                ->send();
        } else {
            Notification::make()
                ->title('Failed to delete backup')
                ->danger()
                ->send();
        }
        
        $this->dispatch('$refresh');
    }

    /**
     * Get SQL backups (phpMyAdmin-style)
     */
    public function getSqlBackups(): array
    {
        $sqlDir = storage_path('app/backups');
        if (!is_dir($sqlDir)) {
            return [];
        }

        $sqlFiles = array_merge(glob("{$sqlDir}/*.sql"), glob("{$sqlDir}/*.sql.gz"));
        $backups = [];

        foreach ($sqlFiles as $file) {
            $filename = basename($file);
            $isCompressed = str_ends_with($filename, '.gz');

            $backups[] = [
                'filename' => $filename,
                'path' => $file,
                'type' => 'sql',
                'type_label' => $isCompressed ? 'SQL (gzip)' : 'SQL',
                'type_color' => 'info',
                'size' => $this->formatBytes(filesize($file)),
                'created_at' => date('Y-m-d H:i:s', filemtime($file)),
            ];
        }

        usort($backups, fn($a, $b) => strtotime($b['created_at']) - strtotime($a['created_at']));

        return $backups;
    }

    /**
     * Create phpMyAdmin-style SQL backup
     */
    public function createSqlBackup(): void
    {
        try {
            Notification::make()
                ->title('Creating SQL backup...')
                ->info()
                ->body('Generating phpMyAdmin-compatible SQL dump.')
                ->send();

            $exitCode = Artisan::call('backup:database', ['--compress' => true, '--max-files' => 10]);
            $output = Artisan::output();

            if ($exitCode === 0) {
                Notification::make()
                    ->title('SQL Backup created successfully!')
                    ->success()
                    ->body('phpMyAdmin-compatible SQL dump has been saved.')
                    ->send();
            } else {
                Notification::make()
                    ->title('SQL Backup failed')
                    ->danger()
                    ->body($output)
                    ->send();
            }

            $this->dispatch('$refresh');
        } catch (\Exception $e) {
            Notification::make()
                ->title('SQL Backup failed')
                ->danger()
                ->body($e->getMessage())
                ->send();
        }
    }

    /**
     * Download an SQL backup file
     */
    public function downloadSqlBackup(string $filename): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $path = storage_path('app/backups/' . $filename);

        if (!file_exists($path)) {
            Notification::make()
                ->title('File not found')
                ->danger()
                ->send();
            return response()->noContent();
        }

        return response()->download($path, $filename);
    }

    /**
     * Delete an SQL backup file
     */
    public function deleteSqlBackup(string $filename): void
    {
        $path = storage_path('app/backups/' . $filename);

        if (file_exists($path) && unlink($path)) {
            Notification::make()
                ->title('SQL backup deleted')
                ->success()
                ->send();
        } else {
            Notification::make()
                ->title('Failed to delete SQL backup')
                ->danger()
                ->send();
        }

        $this->dispatch('$refresh');
    }

    /**
     * Clean old backups using Spatie's cleanup
     */
    public function cleanupBackups(): void
    {
        try {
            Artisan::call('backup:clean', ['--disable-notifications' => true]);
            
            Notification::make()
                ->title('Cleanup completed')
                ->success()
                ->body('Old backups have been removed.')
                ->send();
                
            $this->dispatch('$refresh');
        } catch (\Exception $e) {
            Notification::make()
                ->title('Cleanup failed')
                ->danger()
                ->body($e->getMessage())
                ->send();
        }
    }

    /**
     * Format bytes to human readable
     */
    protected function formatBytes($bytes, $precision = 2): string
    {
        if ($bytes == 0) return '0 B';
        
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
