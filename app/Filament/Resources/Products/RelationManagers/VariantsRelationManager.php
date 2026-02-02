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
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class VariantsRelationManager extends RelationManager
{
    protected static string $relationship = 'allVariants';

    protected static ?string $title = 'Variants';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make(__('Variant Information'))
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('sku')
                                ->label('SKU')
                                ->maxLength(100)
                                ->placeholder('SKU-VARIANT-001'),
                            TextInput::make('order')
                                ->label(__('Order'))
                                ->numeric()
                                ->default(0),
                        ]),

                        Tabs::make('variant_name')->tabs([
                            Tabs\Tab::make('🇮🇩 Indonesia')->schema([
                                TextInput::make('name.id')
                                    ->label(__('Variant Name (ID)'))
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Ukuran 10mm x 20mm'),
                            ]),
                            Tabs\Tab::make('🇬🇧 English')->schema([
                                TextInput::make('name.en')
                                    ->label(__('Variant Name (EN)'))
                                    ->maxLength(255)
                                    ->placeholder('Size 10mm x 20mm'),
                            ]),
                        ]),
                    ]),

                Section::make(__('Dimensions & Specifications'))
                    ->collapsed()
                    ->schema([
                        Grid::make(4)->schema([
                            TextInput::make('size')
                                ->label(__('Size'))
                                ->maxLength(100)
                                ->placeholder('10mm x 20mm'),
                            TextInput::make('thickness')
                                ->label(__('Thickness'))
                                ->maxLength(50)
                                ->placeholder('0.5mm'),
                            TextInput::make('length')
                                ->label(__('Length'))
                                ->maxLength(50)
                                ->placeholder('6m'),
                            TextInput::make('width')
                                ->label(__('Width'))
                                ->maxLength(50)
                                ->placeholder('1.2m'),
                        ]),
                        Grid::make(3)->schema([
                            TextInput::make('weight')
                                ->label(__('Weight'))
                                ->maxLength(50)
                                ->placeholder('5kg'),
                            TextInput::make('grade')
                                ->label(__('Grade'))
                                ->maxLength(50)
                                ->placeholder('Grade A'),
                            TextInput::make('finish')
                                ->label(__('Finish'))
                                ->maxLength(50)
                                ->placeholder('Galvanized'),
                        ]),
                    ]),

                Section::make(__('Pricing & Stock'))
                    ->schema([
                        Grid::make(4)->schema([
                            TextInput::make('price')
                                ->label(__('Price'))
                                ->numeric()
                                ->prefix('Rp')
                                ->placeholder('0'),
                            TextInput::make('price_unit')
                                ->label(__('Price Unit'))
                                ->default('kg')
                                ->placeholder('kg, pcs, meter'),
                            TextInput::make('min_order')
                                ->label(__('Min Order'))
                                ->numeric()
                                ->default(1)
                                ->placeholder('1'),
                            Select::make('stock_status')
                                ->label(__('Stock Status'))
                                ->options([
                                    'available' => __('Available'),
                                    'limited' => __('Limited Stock'),
                                    'out_of_stock' => __('Out of Stock'),
                                    'pre_order' => __('Pre Order'),
                                ])
                                ->default('available'),
                        ]),
                    ]),

                Toggle::make('is_active')
                    ->label(__('Active'))
                    ->default(true),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('Variant'))
                    ->formatStateUsing(fn ($record) => $record->getTranslation('name', 'id'))
                    ->description(fn ($record) => $record->sku)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('size')
                    ->label(__('Size'))
                    ->toggleable(),
                TextColumn::make('price')
                    ->label(__('Price'))
                    ->formatStateUsing(fn ($record) => $record->price ? 'Rp ' . number_format($record->price, 0, ',', '.') . '/' . ($record->price_unit ?? 'pcs') : '-')
                    ->sortable(),
                TextColumn::make('stock_status')
                    ->label(__('Stock'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'available' => 'success',
                        'limited' => 'warning',
                        'out_of_stock' => 'danger',
                        'pre_order' => 'info',
                        default => 'gray',
                    }),
                IconColumn::make('is_active')
                    ->label(__('Active'))
                    ->boolean(),
                TextColumn::make('order')
                    ->label(__('Order'))
                    ->sortable(),
            ])
            ->defaultSort('order', 'asc')
            ->reorderable('order')
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
