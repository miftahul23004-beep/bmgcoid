<?php

namespace App\Filament\Resources\BackupResource\Pages;

use App\Filament\Resources\BackupResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;

class ListBackups extends ListRecords
{
    protected static string $resource = BackupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('refresh')
                ->label('Refresh')
                ->icon('heroicon-o-arrow-path')
                ->color('gray')
                ->action(fn () => $this->redirect(static::getUrl())),
            
            Actions\Action::make('info')
                ->label('Info')
                ->icon('heroicon-o-information-circle')
                ->color('info')
                ->modalHeading('Backup Information')
                ->modalContent(view('filament.resources.backup.info-modal'))
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('Close'),
        ];
    }

    protected function getTableQuery(): ?\Illuminate\Database\Eloquent\Builder
    {
        return BackupResource::getBackupsQuery();
    }
}
