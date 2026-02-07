<?php

namespace App\Filament\Resources\Categories\Schemas;

use App\Models\Category;
use App\Services\ImageOptimizationService;
use Filament\Forms\Components\FileUpload;
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

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                // Multi-language Content
                Section::make(__('Category Content'))
                    ->description(__('Multi-language category information'))
                    ->icon('heroicon-o-folder')
                    ->schema([
                        Tabs::make('content_tabs')->tabs([
                            Tabs\Tab::make('🇮🇩 Indonesia')->schema([
                                TextInput::make('name.id')
                                    ->label(__('Name (ID)'))
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (string $operation, $state, callable $set) {
                                        if ($operation === 'create') {
                                            $set('slug', Str::slug($state));
                                        }
                                    }),
                                RichEditor::make('description.id')
                                    ->label(__('Description (ID)'))
                                    ->toolbarButtons([
                                        'bold', 'italic', 'underline', 'strike',
                                        'bulletList', 'orderedList',
                                        'link', 'blockquote',
                                    ]),
                            ]),
                            Tabs\Tab::make('🇬🇧 English')->schema([
                                TextInput::make('name.en')
                                    ->label(__('Name (EN)'))
                                    ->maxLength(255),
                                RichEditor::make('description.en')
                                    ->label(__('Description (EN)'))
                                    ->toolbarButtons([
                                        'bold', 'italic', 'underline', 'strike',
                                        'bulletList', 'orderedList',
                                        'link', 'blockquote',
                                    ]),
                            ]),
                        ]),
                    ]),

                // Settings & Media Section
                Section::make(__('Settings & Media'))
                    ->icon('heroicon-o-cog-6-tooth')
                    ->schema([
                        Grid::make(3)->schema([
                            Select::make('parent_id')
                                ->label(__('Parent Category'))
                                ->relationship('parent', 'name')
                                ->searchable()
                                ->preload()
                                ->placeholder(__('Select parent category')),
                            TextInput::make('slug')
                                ->label(__('Slug'))
                                ->required()
                                ->maxLength(255)
                                ->unique(Category::class, 'slug', ignoreRecord: true)
                                ->helperText(__('Auto-generated from Indonesian name')),
                            TextInput::make('order')
                                ->label(__('Display Order'))
                                ->numeric()
                                ->default(0)
                                ->minValue(0),
                        ]),
                        Grid::make(4)->schema([
                            Toggle::make('is_active')
                                ->label(__('Active'))
                                ->default(true),
                            Toggle::make('is_featured')
                                ->label(__('Featured'))
                                ->default(false),
                            FileUpload::make('icon')
                                ->label(__('Icon'))
                                ->image()
                                ->imageEditor()
                                ->disk('public')
                                ->directory('categories/icons')
                                ->visibility('public')
                                ->maxSize(10240)
                                ->acceptedFileTypes(['image/png', 'image/svg+xml', 'image/webp', 'image/jpeg'])
                                ->saveUploadedFileUsing(function (TemporaryUploadedFile $file) {
                                    $service = app(ImageOptimizationService::class);
                                    return $service->processUpload($file, 'categories/icons', 50);
                                }),
                            FileUpload::make('image')
                                ->label(__('Category Image'))
                                ->image()
                                ->imageEditor()
                                ->disk('public')
                                ->directory('categories/images')
                                ->visibility('public')
                                ->maxSize(10240)
                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                ->saveUploadedFileUsing(function (TemporaryUploadedFile $file) {
                                    $service = app(ImageOptimizationService::class);
                                    return $service->processUpload($file, 'categories/images', 10, 308, 240);
                                })
                                ->helperText(__('Auto WebP & resize 308×240px, max 10KB')),
                        ]),
                    ]),

                // SEO Section with Multi-lang
                Section::make(__('SEO Settings'))
                    ->description(__('Search engine optimization for each language'))
                    ->icon('heroicon-o-magnifying-glass')
                    ->collapsed()
                    ->schema([
                        Tabs::make('seo_tabs')->tabs([
                            Tabs\Tab::make('🇮🇩 SEO Indonesia')->schema([
                                Grid::make(2)->schema([
                                    TextInput::make('meta_title.id')
                                        ->label(__('Meta Title (ID)'))
                                        ->maxLength(60)
                                        ->helperText(__('Max 60 characters')),
                                    Textarea::make('meta_description.id')
                                        ->label(__('Meta Description (ID)'))
                                        ->maxLength(160)
                                        ->rows(2)
                                        ->helperText(__('Max 160 characters')),
                                ]),
                            ]),
                            Tabs\Tab::make('🇬🇧 SEO English')->schema([
                                Grid::make(2)->schema([
                                    TextInput::make('meta_title.en')
                                        ->label(__('Meta Title (EN)'))
                                        ->maxLength(60)
                                        ->helperText(__('Max 60 characters')),
                                    Textarea::make('meta_description.en')
                                        ->label(__('Meta Description (EN)'))
                                        ->maxLength(160)
                                        ->rows(2)
                                        ->helperText(__('Max 160 characters')),
                                ]),
                            ]),
                        ]),
                    ]),
            ]);
    }
}
