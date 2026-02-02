<?php

namespace App\Filament\Resources\Products\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MarketplaceLinksRelationManager extends RelationManager
{
    protected static string $relationship = 'marketplaceLinks';

    protected static ?string $title = 'Marketplace Links';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Grid::make(2)->schema([
                    Select::make('platform')
                        ->label(__('Platform'))
                        ->options([
                            'shopee' => 'Shopee',
                            'tokopedia' => 'Tokopedia',
                            'lazada' => 'Lazada',
                            'bukalapak' => 'Bukalapak',
                            'blibli' => 'Blibli',
                            'tiktok' => 'TikTok Shop',
                        ])
                        ->required()
                        ->searchable(),
                    TextInput::make('price')
                        ->label(__('Price on Platform'))
                        ->numeric()
                        ->prefix('Rp')
                        ->placeholder('0'),
                ]),

                TextInput::make('url')
                    ->label(__('Product URL'))
                    ->url()
                    ->required()
                    ->placeholder('https://shopee.co.id/product/...')
                    ->columnSpanFull(),

                Toggle::make('is_active')
                    ->label(__('Active'))
                    ->default(true),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('platform')
                    ->label(__('Platform'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'shopee' => 'warning',
                        'tokopedia' => 'success',
                        'lazada' => 'info',
                        'bukalapak' => 'danger',
                        'blibli' => 'primary',
                        'tiktok' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),
                TextColumn::make('url')
                    ->label(__('URL'))
                    ->url(fn ($record) => $record->url)
                    ->openUrlInNewTab()
                    ->limit(40),
                TextColumn::make('price')
                    ->label(__('Price'))
                    ->formatStateUsing(fn ($state) => $state ? 'Rp ' . number_format($state, 0, ',', '.') : '-')
                    ->sortable(),
                TextColumn::make('click_count')
                    ->label(__('Clicks'))
                    ->numeric()
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label(__('Active'))
                    ->boolean(),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }
}
