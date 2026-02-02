<?php

namespace App\Filament\Resources\HomepageSections\Pages;

use App\Filament\Resources\HomepageSections\HomepageSectionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHomepageSections extends ListRecords
{
    protected static string $resource = HomepageSectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('preview')
                ->label('Lihat Homepage')
                ->icon('heroicon-o-eye')
                ->url(route('home'))
                ->openUrlInNewTab(),
        ];
    }
}
