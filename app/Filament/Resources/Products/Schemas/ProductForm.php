<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Models\Category;
use App\Models\Product;
use App\Services\ImageOptimizationService;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                // Basic Info Section
                Section::make(__('Basic Information'))
                    ->description(__('Product identity and categorization'))
                    ->icon('heroicon-o-cube')
                    ->schema([
                        Grid::make(3)->schema([
                            Select::make('category_id')
                                ->label(__('Category'))
                                ->relationship('category', 'name')
                                ->searchable()
                                ->preload()
                                ->required()
                                ->createOptionForm([
                                    TextInput::make('name.id')
                                        ->label(__('Name (ID)'))
                                        ->required(),
                                    TextInput::make('slug')
                                        ->label(__('Slug'))
                                        ->required(),
                                ]),
                            TextInput::make('sku')
                                ->label('SKU')
                                ->required()
                                ->unique(Product::class, 'sku', ignoreRecord: true)
                                ->maxLength(100)
                                ->placeholder('PRD-001'),
                            TextInput::make('slug')
                                ->label(__('Slug'))
                                ->required()
                                ->unique(Product::class, 'slug', ignoreRecord: true)
                                ->maxLength(255)
                                ->helperText(__('Auto-generated from Indonesian name')),
                        ]),
                    ]),

                // Multi-language Content
                Section::make(__('Product Content'))
                    ->description(__('Multi-language product information'))
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        Tabs::make('content_tabs')->tabs([
                            Tabs\Tab::make('🇮🇩 Indonesia')->schema([
                                TextInput::make('name.id')
                                    ->label(__('Product Name (ID)'))
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (string $operation, $state, callable $set) {
                                        if ($operation === 'create') {
                                            $set('slug', Str::slug($state));
                                        }
                                    }),
                                Textarea::make('short_description.id')
                                    ->label(__('Short Description (ID)'))
                                    ->rows(3)
                                    ->maxLength(500)
                                    ->helperText(__('Brief product summary, max 500 characters')),
                                RichEditor::make('description.id')
                                    ->label(__('Full Description (ID)'))
                                    ->toolbarButtons([
                                        'bold', 'italic', 'underline', 'strike',
                                        'bulletList', 'orderedList',
                                        'link', 'blockquote',
                                        'h2', 'h3',
                                    ])
                                    ->columnSpanFull(),
                            ]),
                            Tabs\Tab::make('🇬🇧 English')->schema([
                                TextInput::make('name.en')
                                    ->label(__('Product Name (EN)'))
                                    ->maxLength(255),
                                Textarea::make('short_description.en')
                                    ->label(__('Short Description (EN)'))
                                    ->rows(3)
                                    ->maxLength(500)
                                    ->helperText(__('Brief product summary, max 500 characters')),
                                RichEditor::make('description.en')
                                    ->label(__('Full Description (EN)'))
                                    ->toolbarButtons([
                                        'bold', 'italic', 'underline', 'strike',
                                        'bulletList', 'orderedList',
                                        'link', 'blockquote',
                                        'h2', 'h3',
                                    ])
                                    ->columnSpanFull(),
                            ]),
                        ]),
                    ]),

                // Pricing Section
                Section::make(__('Pricing'))
                    ->description(__('Product pricing information'))
                    ->icon('heroicon-o-currency-dollar')
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('base_price')
                                ->label(__('Base Price'))
                                ->numeric()
                                ->prefix('Rp')
                                ->default(null)
                                ->placeholder('0'),
                            TextInput::make('price_unit')
                                ->label(__('Price Unit'))
                                ->default('kg')
                                ->placeholder('kg, pcs, meter'),
                            Toggle::make('price_on_request')
                                ->label(__('Price on Request'))
                                ->helperText(__('Hide price, show "Contact for Price"'))
                                ->default(false),
                        ]),
                    ]),

                // Media Section
                Section::make(__('Media'))
                    ->description(__('Product images and gallery'))
                    ->icon('heroicon-o-photo')
                    ->schema([
                        FileUpload::make('featured_image')
                            ->label(__('Featured Image'))
                            ->image()
                            ->imageEditor()
                            ->disk('public')
                            ->directory('products/featured')
                            ->visibility('public')
                            ->maxSize(10240)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->saveUploadedFileUsing(function (TemporaryUploadedFile $file, $get) {
                                $service = app(ImageOptimizationService::class);
                                $path = $service->processUpload($file, 'products/featured', 50);
                                
                                // Return full path (Filament stores this in DB)
                                return $path;
                            })
                            ->helperText(__('Image will be auto-converted to WebP (max 50KB)')),
                    ]),

                // Specifications Section
                Section::make(__('Specifications'))
                    ->description(__('Technical specifications as key-value pairs'))
                    ->icon('heroicon-o-clipboard-document-list')
                    ->collapsed()
                    ->schema([
                        KeyValue::make('specifications')
                            ->label(__('Product Specifications'))
                            ->keyLabel(__('Specification'))
                            ->valueLabel(__('Value'))
                            ->addActionLabel(__('Add Specification'))
                            ->reorderable()
                            ->columnSpanFull(),
                    ]),

                // SEO Section with Multi-lang
                Section::make(__('SEO Settings'))
                    ->description(__('Search engine optimization for each language'))
                    ->icon('heroicon-o-magnifying-glass')
                    ->collapsed()
                    ->schema([
                        Tabs::make('seo_tabs')->tabs([
                            Tabs\Tab::make('🇮🇩 SEO Indonesia')->schema([
                                TextInput::make('meta_title.id')
                                    ->label(__('Meta Title (ID)'))
                                    ->maxLength(60)
                                    ->helperText(__('Max 60 characters. Leave empty to use product name.')),
                                Textarea::make('meta_description.id')
                                    ->label(__('Meta Description (ID)'))
                                    ->maxLength(160)
                                    ->rows(2)
                                    ->helperText(__('Max 160 characters. Leave empty to use short description.')),
                                TextInput::make('meta_keywords.id')
                                    ->label(__('Meta Keywords (ID)'))
                                    ->maxLength(255)
                                    ->helperText(__('Comma separated keywords')),
                            ]),
                            Tabs\Tab::make('🇬🇧 SEO English')->schema([
                                TextInput::make('meta_title.en')
                                    ->label(__('Meta Title (EN)'))
                                    ->maxLength(60)
                                    ->helperText(__('Max 60 characters. Leave empty to use product name.')),
                                Textarea::make('meta_description.en')
                                    ->label(__('Meta Description (EN)'))
                                    ->maxLength(160)
                                    ->rows(2)
                                    ->helperText(__('Max 160 characters. Leave empty to use short description.')),
                                TextInput::make('meta_keywords.en')
                                    ->label(__('Meta Keywords (EN)'))
                                    ->maxLength(255)
                                    ->helperText(__('Comma separated keywords')),
                            ]),
                        ]),
                    ]),

                // Status & Settings Section
                Section::make(__('Status & Display'))
                    ->description(__('Product visibility and display settings'))
                    ->icon('heroicon-o-cog-6-tooth')
                    ->schema([
                        Grid::make(5)->schema([
                            Toggle::make('is_active')
                                ->label(__('Active'))
                                ->helperText(__('Show on website'))
                                ->default(true),
                            Toggle::make('is_featured')
                                ->label(__('Featured'))
                                ->helperText(__('Show in featured section'))
                                ->default(false),
                            Toggle::make('is_new')
                                ->label(__('New'))
                                ->helperText(__('Show "New" badge'))
                                ->default(true),
                            Toggle::make('is_bestseller')
                                ->label(__('Best Seller'))
                                ->helperText(__('Show "Best Seller" badge'))
                                ->default(false),
                            TextInput::make('order')
                                ->label(__('Display Order'))
                                ->numeric()
                                ->default(0)
                                ->minValue(0),
                        ]),
                    ]),

                // Statistics (Read-only, shown on edit)
                Section::make(__('Statistics'))
                    ->description(__('Product performance metrics'))
                    ->icon('heroicon-o-chart-bar')
                    ->collapsed()
                    ->visible(fn (string $operation): bool => $operation === 'edit')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('view_count')
                                ->label(__('View Count'))
                                ->numeric()
                                ->disabled()
                                ->default(0),
                            TextInput::make('inquiry_count')
                                ->label(__('Inquiry Count'))
                                ->numeric()
                                ->disabled()
                                ->default(0),
                        ]),
                    ]),
            ]);
    }
}
