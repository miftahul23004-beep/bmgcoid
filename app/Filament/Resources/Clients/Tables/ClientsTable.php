<?php

namespace App\Filament\Resources\Clients\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ClientsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('logo')
                    ->label('Logo')
                    ->disk('public')
                    ->height(40)
                    ->width(80),
                TextColumn::make('name')
                    ->label('Client Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('industry')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'construction' => 'primary',
                        'manufacturing' => 'success',
                        'infrastructure' => 'warning',
                        'automotive' => 'info',
                        default => 'gray',
                    }),
                TextColumn::make('website')
                    ->label('Website')
                    ->url(fn ($record) => $record->website)
                    ->openUrlInNewTab()
                    ->limit(30)
                    ->toggleable(),
                TextColumn::make('order')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean(),
                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('industry')
                    ->options([
                        'construction' => 'Construction',
                        'manufacturing' => 'Manufacturing',
                        'infrastructure' => 'Infrastructure',
                        'automotive' => 'Automotive',
                        'shipbuilding' => 'Shipbuilding',
                        'real_estate' => 'Real Estate',
                        'oil_gas' => 'Oil & Gas',
                        'mining' => 'Mining',
                        'retail' => 'Retail',
                        'hospitality' => 'Hospitality',
                        'logistics' => 'Logistics',
                        'agriculture' => 'Agriculture',
                        'other' => 'Other',
                    ]),
                TernaryFilter::make('is_featured')
                    ->label('Featured'),
                TernaryFilter::make('is_active')
                    ->label('Active'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('order', 'asc')
            ->reorderable('order');
    }
}
