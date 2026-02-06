<?php

namespace App\Filament\Resources\Testimonials\Schemas;

use App\Services\ImageOptimizationService;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class TestimonialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Author Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('client_id')
                                    ->relationship('client', 'name')
                                    ->label('Client')
                                    ->searchable()
                                    ->preload()
                                    ->default(null),
                                TextInput::make('author_name')
                                    ->label('Author Name')
                                    ->required(),
                                TextInput::make('author_company')
                                    ->label('Author Company')
                                    ->default(null),
                                FileUpload::make('author_photo')
                                    ->label('Author Photo')
                                    ->image()
                                    ->directory('testimonials')
                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                    ->maxSize(10240)
                                    ->saveUploadedFileUsing(function ($file) {
                                        $service = app(ImageOptimizationService::class);
                                        return $service->processUpload($file, 'testimonials', 50);
                                    })
                                    ->helperText('Auto-convert to WebP max 200KB')
                                    ->visibility('public')
                                    ->imageResizeMode('cover')
                                    ->imageCropAspectRatio('1:1')
                                    ->imageResizeTargetWidth('200')
                                    ->imageResizeTargetHeight('200')
                                    ->default(null),
                            ]),
                    ]),

                Tabs::make('Translations')
                    ->tabs([
                        Tab::make('Indonesia')
                            ->icon('heroicon-o-flag')
                            ->schema([
                                TextInput::make('author_position.id')
                                    ->label('Posisi / Jabatan')
                                    ->helperText('Contoh: Direktur Utama, Manajer Proyek')
                                    ->default(null),
                                Textarea::make('content.id')
                                    ->label('Isi Testimoni')
                                    ->required()
                                    ->rows(4),
                                TextInput::make('project_name.id')
                                    ->label('Nama Proyek')
                                    ->helperText('Opsional: Nama proyek terkait')
                                    ->default(null),
                            ]),
                        Tab::make('English')
                            ->icon('heroicon-o-globe-alt')
                            ->schema([
                                TextInput::make('author_position.en')
                                    ->label('Position / Title')
                                    ->helperText('Example: CEO, Project Manager')
                                    ->default(null),
                                Textarea::make('content.en')
                                    ->label('Testimonial Content')
                                    ->rows(4),
                                TextInput::make('project_name.en')
                                    ->label('Project Name')
                                    ->helperText('Optional: Related project name')
                                    ->default(null),
                            ]),
                    ])
                    ->columnSpanFull(),

                Section::make('Settings')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('rating')
                                    ->label('Rating (1-5)')
                                    ->numeric()
                                    ->minValue(1)
                                    ->maxValue(5)
                                    ->default(5),
                                TextInput::make('order')
                                    ->label('Display Order')
                                    ->required()
                                    ->numeric()
                                    ->default(0),
                                Toggle::make('is_featured')
                                    ->label('Featured')
                                    ->helperText('Show on homepage')
                                    ->required(),
                                Toggle::make('is_active')
                                    ->label('Active')
                                    ->helperText('Publish this testimonial')
                                    ->required()
                                    ->default(true),
                            ]),
                    ]),
            ]);
    }
}
