<?php

namespace App\Filament\Resources\Clients\Schemas;

use App\Services\ImageOptimizationService;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ClientForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Client Information')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('name')
                                ->label('Client Name')
                                ->required()
                                ->maxLength(255),
                            Select::make('industry')
                                ->label('Industry')
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
                                ])
                                ->default('other')
                                ->required(),
                        ]),
                        TextInput::make('website')
                            ->label('Website URL')
                            ->url()
                            ->placeholder('https://example.com')
                            ->default(null),
                        Textarea::make('description')
                            ->label('Description')
                            ->rows(3)
                            ->default(null)
                            ->columnSpanFull(),
                    ]),

                Section::make('Logo')
                    ->description('Upload client logo for display on website')
                    ->schema([
                        Grid::make(2)->schema([
                            FileUpload::make('logo')
                                ->label('Client Logo')
                                ->image()
                                ->directory('clients')
                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml'])
                                ->maxSize(10240)
                                ->saveUploadedFileUsing(function ($file) {
                                    $service = app(ImageOptimizationService::class);
                                    return $service->processUpload($file, 'clients', 200);
                                })
                                ->helperText('Recommended: PNG/SVG with transparent background. Auto-convert to WebP max 200KB.'),
                            ColorPicker::make('bg_color')
                                ->label('Background Color')
                                ->default('#ffffff')
                                ->helperText('Logo box background color'),
                        ]),
                    ]),

                Section::make('Display Settings')
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('order')
                                ->label('Display Order')
                                ->numeric()
                                ->default(0)
                                ->helperText('Lower number = show first'),
                            Toggle::make('is_featured')
                                ->label('Featured')
                                ->helperText('Show on homepage'),
                            Toggle::make('is_active')
                                ->label('Active')
                                ->default(true),
                        ]),
                    ]),
            ]);
    }
}
