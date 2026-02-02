<?php

namespace App\Filament\Pages;

use App\Services\FtpSyncService;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use UnitEnum;

class FtpSync extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-cloud-arrow-up';
    protected static ?string $navigationLabel = 'FTP Sync';
    protected static ?string $title = 'FTP Sync - Upload ke Hosting';
    protected static string|UnitEnum|null $navigationGroup = null;
    protected static ?int $navigationSort = 100;
    protected string $view = 'filament.pages.ftp-sync';

    public static function getNavigationGroup(): ?string
    {
        return __('System');
    }

    public array $selectedFiles = [];
    public array $syncLogs = [];
    public bool $isSyncing = false;
    public ?string $connectionStatus = null;
    public array $changedFiles = [];

    public function mount(): void
    {
        $this->loadChangedFiles();
    }

    public function loadChangedFiles(): void
    {
        $service = new FtpSyncService();
        $this->changedFiles = $service->getChangedFiles();
        $this->selectedFiles = array_keys($this->changedFiles);
    }

    #[Computed]
    public function getChangedFilesProperty(): array
    {
        return $this->changedFiles;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('testConnection')
                ->label('Test Koneksi FTP')
                ->icon('heroicon-o-signal')
                ->color('info')
                ->action(function () {
                    $service = new FtpSyncService();
                    $result = $service->testConnection();
                    
                    if ($result['success']) {
                        $this->connectionStatus = 'connected';
                        Notification::make()
                            ->title('Koneksi Berhasil')
                            ->body($result['message'])
                            ->success()
                            ->send();
                    } else {
                        $this->connectionStatus = 'failed';
                        Notification::make()
                            ->title('Koneksi Gagal')
                            ->body($result['message'])
                            ->danger()
                            ->send();
                    }
                }),

            Action::make('refresh')
                ->label('Refresh Daftar')
                ->icon('heroicon-o-arrow-path')
                ->color('gray')
                ->action(function () {
                    $this->loadChangedFiles();
                    Notification::make()
                        ->title('Daftar file diperbarui')
                        ->success()
                        ->send();
                }),

            Action::make('syncSelected')
                ->label('Sinkronkan File Terpilih')
                ->icon('heroicon-o-cloud-arrow-up')
                ->color('primary')
                ->requiresConfirmation()
                ->modalHeading('Konfirmasi Sinkronisasi')
                ->modalDescription(fn () => 'Anda akan mengupload ' . count($this->selectedFiles) . ' file ke server. Lanjutkan?')
                ->modalSubmitActionLabel('Ya, Upload Sekarang')
                ->disabled(fn () => empty($this->selectedFiles))
                ->action(function () {
                    $this->syncFiles($this->selectedFiles);
                }),

            Action::make('syncAll')
                ->label('Sinkronkan Semua')
                ->icon('heroicon-o-cloud-arrow-up')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Konfirmasi Sinkronisasi')
                ->modalDescription(fn () => 'Anda akan mengupload semua ' . count($this->changedFiles) . ' file yang berubah ke server. Lanjutkan?')
                ->modalSubmitActionLabel('Ya, Upload Semua')
                ->disabled(fn () => empty($this->changedFiles))
                ->action(function () {
                    $this->syncFiles(array_keys($this->changedFiles));
                }),
        ];
    }

    public function syncFiles(array $files): void
    {
        $this->isSyncing = true;
        $this->syncLogs = [];

        try {
            $service = new FtpSyncService();
            $results = $service->syncFiles($files);
            $this->syncLogs = $service->getLogs();

            $successCount = count($results['success']);
            $failedCount = count($results['failed']);

            if ($successCount > 0) {
                // Update tracking for successful files
                $service->updateTracking($results['success']);
                
                // Reload changed files
                $this->loadChangedFiles();
            }

            if ($failedCount === 0) {
                Notification::make()
                    ->title('Sinkronisasi Berhasil')
                    ->body("{$successCount} file berhasil diupload ke server.")
                    ->success()
                    ->send();
            } else {
                Notification::make()
                    ->title('Sinkronisasi Selesai dengan Error')
                    ->body("Berhasil: {$successCount}, Gagal: {$failedCount}")
                    ->warning()
                    ->send();
            }
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error Sinkronisasi')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }

        $this->isSyncing = false;
    }

    public function toggleFile(string $file): void
    {
        if (in_array($file, $this->selectedFiles)) {
            $this->selectedFiles = array_values(array_diff($this->selectedFiles, [$file]));
        } else {
            $this->selectedFiles[] = $file;
        }
    }

    public function selectAll(): void
    {
        $this->selectedFiles = array_keys($this->changedFiles);
    }

    public function deselectAll(): void
    {
        $this->selectedFiles = [];
    }

    public function syncSingleFile(string $file): void
    {
        $this->syncFiles([$file]);
    }

    public static function getNavigationBadge(): ?string
    {
        $service = new FtpSyncService();
        $count = count($service->getChangedFiles());
        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}
