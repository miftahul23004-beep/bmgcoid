<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use App\Services\ImageOptimizationService;
use BackedEnum;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use UnitEnum;

class StaticPageSettings extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-duplicate';
    protected static ?string $navigationLabel = 'Static Page Settings';
    protected static string|UnitEnum|null $navigationGroup = 'Settings';
    protected static ?int $navigationSort = 2;
    protected string $view = 'filament.pages.static-page-settings';

    public static function getNavigationGroup(): ?string
    {
        return __('Settings');
    }

    public static function getNavigationLabel(): string
    {
        return __('Static Page Settings');
    }

    public function getTitle(): string
    {
        return __('Static Page Settings');
    }

    public ?array $data = [];

    public function mount(): void
    {
        $this->loadSettings();
    }

    protected function loadSettings(): void
    {
        $settings = Setting::where('group', 'static_pages')
            ->pluck('value', 'key')
            ->toArray();

        $this->form->fill($settings);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Tabs::make('Static Pages')
                    ->tabs([
                        // ABOUT PAGE
                        Tabs\Tab::make('About')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                Section::make('Profil Perusahaan')
                                    ->description('Halaman /tentang')
                                    ->schema([
                                        FileUpload::make('about_story_image')
                                            ->label('Story/Sejarah Image')
                                            ->image()
                                            ->disk('public')
                                            ->directory('static-pages/about')
                                            ->visibility('public')
                                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                            ->helperText('Gambar cerita perusahaan. Max 50KB setelah konversi.')
                                            ->imagePreviewHeight('150'),
                                    ]),
                            ]),

                        // VISION PAGE
                        Tabs\Tab::make('Visi')
                            ->icon('heroicon-o-eye')
                            ->schema([
                                Section::make('Halaman Visi')
                                    ->description('Halaman /tentang/visi-misi')
                                    ->schema([
                                        FileUpload::make('vision_image')
                                            ->label('Vision Image')
                                            ->image()
                                            ->disk('public')
                                            ->directory('static-pages/about')
                                            ->visibility('public')
                                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                            ->helperText('Gambar section visi. Max 50KB setelah konversi.')
                                            ->imagePreviewHeight('150'),
                                    ]),
                            ]),

                        // TEAM PAGE
                        Tabs\Tab::make('Tim')
                            ->icon('heroicon-o-user-group')
                            ->schema([
                                Section::make('Pimpinan 1 - Direktur Utama')
                                    ->description('Halaman /tentang/tim - Direksi')
                                    ->schema([
                                        Grid::make(3)->schema([
                                            TextInput::make('team_direktur_utama_name')
                                                ->label('Nama')
                                                ->placeholder('H. Ahmad Sulaiman'),
                                            TextInput::make('team_direktur_utama_position')
                                                ->label('Jabatan')
                                                ->placeholder('Direktur Utama'),
                                            FileUpload::make('team_direktur_utama')
                                                ->label('Foto')
                                                ->image()
                                                ->disk('public')
                                                ->directory('static-pages/team')
                                                ->visibility('public')
                                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                                ->imagePreviewHeight('100'),
                                        ]),
                                    ]),
                                Section::make('Pimpinan 2 - Direktur Operasional')
                                    ->schema([
                                        Grid::make(3)->schema([
                                            TextInput::make('team_direktur_operasional_name')
                                                ->label('Nama')
                                                ->placeholder('H. Muhammad Haris'),
                                            TextInput::make('team_direktur_operasional_position')
                                                ->label('Jabatan')
                                                ->placeholder('Direktur Operasional'),
                                            FileUpload::make('team_direktur_operasional')
                                                ->label('Foto')
                                                ->image()
                                                ->disk('public')
                                                ->directory('static-pages/team')
                                                ->visibility('public')
                                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                                ->imagePreviewHeight('100'),
                                        ]),
                                    ]),
                                Section::make('Tim Manajemen 1')
                                    ->description('Foto dan data tim manajemen')
                                    ->schema([
                                        Grid::make(3)->schema([
                                            TextInput::make('team_management_1_name')
                                                ->label('Nama')
                                                ->placeholder('Nama Manajer'),
                                            TextInput::make('team_management_1_position')
                                                ->label('Jabatan')
                                                ->placeholder('Manager Operasional'),
                                            FileUpload::make('team_management_1')
                                                ->label('Foto')
                                                ->image()
                                                ->disk('public')
                                                ->directory('static-pages/team')
                                                ->visibility('public')
                                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                                ->imagePreviewHeight('80'),
                                        ]),
                                    ]),
                                Section::make('Tim Manajemen 2')
                                    ->schema([
                                        Grid::make(3)->schema([
                                            TextInput::make('team_management_2_name')
                                                ->label('Nama')
                                                ->placeholder('Nama Manajer'),
                                            TextInput::make('team_management_2_position')
                                                ->label('Jabatan')
                                                ->placeholder('Manager Keuangan'),
                                            FileUpload::make('team_management_2')
                                                ->label('Foto')
                                                ->image()
                                                ->disk('public')
                                                ->directory('static-pages/team')
                                                ->visibility('public')
                                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                                ->imagePreviewHeight('80'),
                                        ]),
                                    ]),
                                Section::make('Tim Manajemen 3')
                                    ->schema([
                                        Grid::make(3)->schema([
                                            TextInput::make('team_management_3_name')
                                                ->label('Nama')
                                                ->placeholder('Nama Manajer'),
                                            TextInput::make('team_management_3_position')
                                                ->label('Jabatan')
                                                ->placeholder('Manager Logistik'),
                                            FileUpload::make('team_management_3')
                                                ->label('Foto')
                                                ->image()
                                                ->disk('public')
                                                ->directory('static-pages/team')
                                                ->visibility('public')
                                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                                ->imagePreviewHeight('80'),
                                        ]),
                                    ]),
                                Section::make('Tim Manajemen 4')
                                    ->schema([
                                        Grid::make(3)->schema([
                                            TextInput::make('team_management_4_name')
                                                ->label('Nama')
                                                ->placeholder('Nama Manajer'),
                                            TextInput::make('team_management_4_position')
                                                ->label('Jabatan')
                                                ->placeholder('Manager Pemasaran'),
                                            FileUpload::make('team_management_4')
                                                ->label('Foto')
                                                ->image()
                                                ->disk('public')
                                                ->directory('static-pages/team')
                                                ->visibility('public')
                                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                                ->imagePreviewHeight('80'),
                                        ]),
                                    ]),
                                Section::make('Gambar Halaman Tim')
                                    ->schema([
                                        FileUpload::make('team_work_image')
                                            ->label('Team Work Image')
                                            ->image()
                                            ->disk('public')
                                            ->directory('static-pages/team')
                                            ->visibility('public')
                                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                            ->helperText('Gambar kerja tim')
                                            ->imagePreviewHeight('150'),
                                    ]),
                            ]),

                        // CERTIFICATES PAGE
                        Tabs\Tab::make('Sertifikat')
                            ->icon('heroicon-o-academic-cap')
                            ->schema([
                                Section::make('Halaman Sertifikat & Legalitas')
                                    ->description('Halaman /tentang/sertifikat')
                                    ->schema([
                                        FileUpload::make('certificates_quality_image')
                                            ->label('Quality Assurance Image')
                                            ->image()
                                            ->disk('public')
                                            ->directory('static-pages/about')
                                            ->visibility('public')
                                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                            ->helperText('Gambar jaminan kualitas')
                                            ->imagePreviewHeight('150'),
                                    ]),
                            ]),

                        // PRIVACY & TERMS
                        Tabs\Tab::make('Legal')
                            ->icon('heroicon-o-shield-check')
                            ->schema([
                                Section::make('Privacy Policy')
                                    ->description('Halaman /privacy-policy')
                                    ->schema([
                                        Textarea::make('privacy_policy_content')
                                            ->label('Privacy Policy Content (HTML)')
                                            ->rows(10)
                                            ->helperText('Konten kebijakan privasi dalam format HTML'),
                                    ]),
                                Section::make('Terms of Service')
                                    ->description('Halaman /terms-of-service')
                                    ->schema([
                                        Textarea::make('terms_content')
                                            ->label('Terms of Service Content (HTML)')
                                            ->rows(10)
                                            ->helperText('Konten syarat dan ketentuan dalam format HTML'),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull(),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label(__('Save Settings'))
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $service = app(ImageOptimizationService::class);

        // Image fields that need WebP conversion
        $imageFields = [
            // About
            'about_story_image',
            // Vision
            'vision_image',
            // Team
            'team_direktur_utama',
            'team_direktur_operasional',
            'team_management_1',
            'team_management_2',
            'team_management_3',
            'team_management_4',
            'team_work_image',
            // Certificates
            'certificates_hero_image',
            'certificates_quality_image',
        ];

        // Process all image fields - always optimize to WebP < 50KB
        foreach ($imageFields as $imageField) {
            if (!empty($data[$imageField])) {
                $imagePath = is_array($data[$imageField]) ? reset($data[$imageField]) : $data[$imageField];
                
                if ($imagePath) {
                    $fullPath = Storage::disk('public')->path($imagePath);
                    
                    if (file_exists($fullPath)) {
                        $currentSize = filesize($fullPath) / 1024; // KB
                        
                        // Convert if not WebP OR if size > 50KB
                        $extension = strtolower(pathinfo($imagePath, PATHINFO_EXTENSION));
                        if ($extension !== 'webp' || $currentSize > 50) {
                            $newPath = $service->convertToWebp($imagePath, 50, 1200);
                            
                            if ($newPath !== $imagePath) {
                                Storage::disk('public')->delete($imagePath);
                                $data[$imageField] = $newPath;
                            }
                        }
                    }
                }
            }
        }

        // Save all settings
        foreach ($data as $key => $value) {
            if ($value !== null) {
                Setting::updateOrCreate(
                    ['key' => $key, 'group' => 'static_pages'],
                    ['value' => $value, 'type' => 'text']
                );
            }
        }

        // Clear cache
        Cache::forget('settings.static_pages');
        Cache::forget('static_page_images');

        // Refresh form data
        $this->loadSettings();

        Notification::make()
            ->title(__('Settings saved successfully'))
            ->success()
            ->send();
    }
}
