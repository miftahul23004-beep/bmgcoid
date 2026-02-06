<?php

namespace App\Filament\Resources\HeroSlides\Schemas;

use App\Services\ImageOptimizationService;
use Filament\Forms\Components\DateTimePicker;
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
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class HeroSlideForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Hero Slide')
                    ->tabs([
                        // Content Tab
                        Tab::make('🇮🇩 Indonesia')
                            ->schema([
                                TextInput::make('title_id')
                                    ->label('Judul')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Supplier Besi Terpercaya'),
                                    
                                Textarea::make('subtitle_id')
                                    ->label('Subtitle')
                                    ->rows(3)
                                    ->placeholder('Deskripsi singkat tentang slide ini...'),
                                    
                                TextInput::make('badge_text_id')
                                    ->label('Badge/Label')
                                    ->maxLength(100)
                                    ->placeholder('Terpercaya Sejak 2011'),
                            ]),

                        Tab::make('🇬🇧 English')
                            ->schema([
                                TextInput::make('title_en')
                                    ->label('Title')
                                    ->maxLength(255)
                                    ->placeholder('Your Trusted Steel Partner'),
                                    
                                Textarea::make('subtitle_en')
                                    ->label('Subtitle')
                                    ->rows(3)
                                    ->placeholder('Short description about this slide...'),
                                    
                                TextInput::make('badge_text_en')
                                    ->label('Badge/Label')
                                    ->maxLength(100)
                                    ->placeholder('Trusted Since 2011'),
                            ]),
                    ])
                    ->columnSpanFull(),

                Section::make('Media')
                    ->schema([
                        FileUpload::make('image')
                            ->label('Gambar Desktop')
                            ->image()
                            ->disk('public')
                            ->directory('hero-slides')
                            ->visibility('public')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->maxSize(5120) // 5MB max upload
                            ->saveUploadedFileUsing(function (TemporaryUploadedFile $file) {
                                $service = app(ImageOptimizationService::class);
                                return $service->processUpload($file, 'hero-slides', 50);
                            })
                            ->helperText('Upload gambar (JPG, PNG, WebP). Akan otomatis dikonversi ke WebP max 50KB. Ukuran ideal: 1920x1080 pixels (16:9).'),

                        FileUpload::make('mobile_image')
                            ->label('Gambar Mobile (Opsional)')
                            ->image()
                            ->disk('public')
                            ->directory('hero-slides')
                            ->visibility('public')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->maxSize(5120) // 5MB max upload
                            ->saveUploadedFileUsing(function (TemporaryUploadedFile $file) {
                                $service = app(ImageOptimizationService::class);
                                return $service->processUpload($file, 'hero-slides', 40);
                            })
                            ->helperText('Akan otomatis dikonversi ke WebP max 40KB. Ukuran ideal: 750x1000 pixels.'),

                        Grid::make(2)
                            ->schema([
                                Select::make('gradient_class')
                                    ->label('Gradient Overlay')
                                    ->options([
                                        'from-primary-900/95 via-primary-800/90 to-primary-700/85' => 'Biru (Primary)',
                                        'from-secondary-900/95 via-secondary-800/90 to-secondary-700/85' => 'Merah (Secondary)',
                                        'from-green-900/95 via-green-800/90 to-green-700/85' => 'Hijau',
                                        'from-gray-900/95 via-gray-800/90 to-gray-700/85' => 'Abu-abu',
                                        'from-black/80 via-black/60 to-black/40' => 'Hitam Transparan',
                                        'from-primary-900/80 to-secondary-900/80' => 'Biru ke Merah',
                                    ])
                                    ->default('from-primary-900/95 via-primary-800/90 to-primary-700/85'),

                                Select::make('text_color')
                                    ->label('Warna Teks')
                                    ->options([
                                        'white' => 'Putih',
                                        'black' => 'Hitam',
                                    ])
                                    ->default('white'),
                            ]),
                    ]),

                Section::make('Tombol CTA')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('primary_button_text_id')
                                    ->label('Tombol Utama (ID)')
                                    ->maxLength(50)
                                    ->placeholder('Lihat Produk'),
                                    
                                TextInput::make('primary_button_text_en')
                                    ->label('Tombol Utama (EN)')
                                    ->maxLength(50)
                                    ->placeholder('View Products'),
                            ]),

                        TextInput::make('primary_button_url')
                            ->label('URL Tombol Utama')
                            ->placeholder('/products atau https://...')
                            ->helperText('Gunakan path relatif (/products) atau URL lengkap'),

                        Grid::make(2)
                            ->schema([
                                TextInput::make('secondary_button_text_id')
                                    ->label('Tombol Sekunder (ID)')
                                    ->maxLength(50)
                                    ->placeholder('Minta Penawaran'),
                                    
                                TextInput::make('secondary_button_text_en')
                                    ->label('Tombol Sekunder (EN)')
                                    ->maxLength(50)
                                    ->placeholder('Get Quote'),
                            ]),

                        TextInput::make('secondary_button_url')
                            ->label('URL Tombol Sekunder')
                            ->placeholder('/quote atau https://...'),
                    ]),

                Section::make('Pengaturan')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Toggle::make('is_active')
                                    ->label('Aktif')
                                    ->default(true)
                                    ->helperText('Slide yang tidak aktif tidak akan ditampilkan'),

                                TextInput::make('order')
                                    ->label('Urutan')
                                    ->numeric()
                                    ->default(0)
                                    ->helperText('Angka lebih kecil tampil lebih dulu'),
                            ]),

                        Grid::make(2)
                            ->schema([
                                DateTimePicker::make('start_date')
                                    ->label('Mulai Tayang')
                                    ->placeholder('Pilih tanggal mulai'),

                                DateTimePicker::make('end_date')
                                    ->label('Selesai Tayang')
                                    ->placeholder('Pilih tanggal selesai'),
                            ]),
                    ]),
            ]);
    }
}
