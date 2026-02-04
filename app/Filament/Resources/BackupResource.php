<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BackupResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class BackupResource extends Resource
{
    protected static ?string $model = null;
    
    protected static ?string $navigationLabel = 'Backups';
    
    protected static ?string $modelLabel = 'Backup';
    
    protected static ?int $navigationSort = 100;

    public static function table(Table $table): Table
    {
        return $table
            ->query(fn () => static::getBackupsQuery())
            ->columns([
                Tables\Columns\TextColumn::make('filename')
                    ->label('Filename')
                    ->searchable()
                    ->icon('heroicon-o-document-arrow-down')
                    ->copyable()
                    ->copyMessage('Filename copied'),
                
                Tables\Columns\TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'full' => 'success',
                        'database' => 'info',
                        'files' => 'warning',
                        default => 'gray',
                    }),
                
                Tables\Columns\TextColumn::make('size')
                    ->label('Size')
                    ->formatStateUsing(fn ($state) => static::formatBytes($state)),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('Y-m-d H:i:s')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\Action::make('download')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->url(fn ($record) => route('backup.download', ['file' => $record->filename]))
                    ->openUrlInNewTab(),
                
                Tables\Actions\Action::make('restore')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Restore Backup')
                    ->modalDescription('This will overwrite current database and files. Are you sure?')
                    ->modalSubmitActionLabel('Yes, restore')
                    ->action(function ($record) {
                        try {
                            Artisan::call('backup:restore', [
                                'backup' => str_replace('.zip', '', $record->filename),
                                '--force' => true
                            ]);

                            Notification::make()
                                ->title('Backup restored successfully!')
                                ->success()
                                ->body('Please refresh the page.')
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Restore failed')
                                ->danger()
                                ->body($e->getMessage())
                                ->send();
                        }
                    }),
                
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $path = storage_path("app/backups/{$record->filename}");
                        if (file_exists($path)) {
                            unlink($path);
                            
                            Notification::make()
                                ->title('Backup deleted')
                                ->success()
                                ->send();
                        }
                    }),
            ])
            ->headerActions([
                Tables\Actions\Action::make('create_full')
                    ->label('Create Full Backup')
                    ->icon('heroicon-o-circle-stack')
                    ->color('success')
                    ->action(function () {
                        try {
                            Notification::make()
                                ->title('Creating full backup...')
                                ->info()
                                ->body('This may take a few minutes.')
                                ->send();

                            Artisan::call('backup:create', ['--type' => 'full']);

                            Notification::make()
                                ->title('Full backup created!')
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Backup failed')
                                ->danger()
                                ->body($e->getMessage())
                                ->send();
                        }
                    }),
                
                Tables\Actions\Action::make('create_database')
                    ->label('Database Only')
                    ->icon('heroicon-o-server-stack')
                    ->color('info')
                    ->action(function () {
                        try {
                            Artisan::call('backup:create', ['--type' => 'database']);
                            
                            Notification::make()
                                ->title('Database backup created!')
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Backup failed')
                                ->danger()
                                ->body($e->getMessage())
                                ->send();
                        }
                    }),
                
                Tables\Actions\Action::make('create_files')
                    ->label('Files Only')
                    ->icon('heroicon-o-folder')
                    ->color('warning')
                    ->action(function () {
                        try {
                            Artisan::call('backup:create', ['--type' => 'files']);
                            
                            Notification::make()
                                ->title('Files backup created!')
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Backup failed')
                                ->danger()
                                ->body($e->getMessage())
                                ->send();
                        }
                    }),
            ])
            ->emptyStateHeading('No backups found')
            ->emptyStateDescription('Create your first backup using the buttons above')
            ->emptyStateIcon('heroicon-o-archive-box');
    }

    public static function getBackupsQuery()
    {
        $backupPath = storage_path('app/backups');
        
        if (!is_dir($backupPath)) {
            return collect([]);
        }

        $backups = glob("{$backupPath}/*.zip");
        
        $data = collect($backups)->map(function ($backup) {
            $filename = basename($backup);
            
            // Extract type from filename
            $type = 'unknown';
            if (str_contains($filename, 'full')) {
                $type = 'full';
            } elseif (str_contains($filename, 'database')) {
                $type = 'database';
            } elseif (str_contains($filename, 'files')) {
                $type = 'files';
            }
            
            return (object)[
                'id' => str_replace('.zip', '', $filename),
                'filename' => $filename,
                'type' => $type,
                'size' => filesize($backup),
                'created_at' => date('Y-m-d H:i:s', filemtime($backup)),
                'path' => $backup,
            ];
        });

        // Convert to query builder-like collection
        return new class($data) {
            private $data;

            public function __construct($data)
            {
                $this->data = $data;
            }

            public function get()
            {
                return $this->data;
            }

            public function paginate($perPage = 15)
            {
                return new \Illuminate\Pagination\LengthAwarePaginator(
                    $this->data,
                    $this->data->count(),
                    $perPage
                );
            }
        };
    }

    protected static function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBackups::route('/'),
        ];
    }
}
