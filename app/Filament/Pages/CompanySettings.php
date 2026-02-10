<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use App\Services\ImageOptimizationService;
use BackedEnum;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use UnitEnum;

class CompanySettings extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationLabel = 'Company Settings';
    protected static string|UnitEnum|null $navigationGroup = 'Settings';
    protected static ?int $navigationSort = 1;
    protected string $view = 'filament.pages.company-settings';

    public static function canAccess(): bool
    {
        return auth()->user()?->can('manage settings') ?? false;
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Settings');
    }

    public ?array $data = [];

    public function mount(): void
    {
        $this->loadSettings();
    }

    protected function loadSettings(): void
    {
        $settings = Setting::where('group', 'general')
            ->orWhere('group', 'contact')
            ->pluck('value', 'key')
            ->toArray();

        $this->form->fill($settings);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Tabs::make('Settings')
                    ->tabs([
                        Tabs\Tab::make('Company Info')
                            ->icon('heroicon-o-building-office')
                            ->schema([
                                TextInput::make('company_name')
                                    ->label('Company Name')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('company_tagline')
                                    ->label('Tagline')
                                    ->maxLength(255),
                                Textarea::make('company_description')
                                    ->label('Description')
                                    ->rows(4),
                                FileUpload::make('logo')
                                    ->label('Logo')
                                    ->image()
                                    ->disk('public')
                                    ->directory('settings')
                                    ->visibility('public')
                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml'])
                                    ->helperText('Upload gambar, akan otomatis dikonversi ke WebP (<20KB) saat disimpan')
                                    ->imagePreviewHeight('100'),
                                FileUpload::make('logo_white')
                                    ->label('Logo (White/Dark Mode)')
                                    ->image()
                                    ->disk('public')
                                    ->directory('settings')
                                    ->visibility('public')
                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml'])
                                    ->helperText('Upload gambar, akan otomatis dikonversi ke WebP (<20KB) saat disimpan')
                                    ->imagePreviewHeight('100'),
                                FileUpload::make('favicon')
                                    ->label('Favicon')
                                    ->disk('public')
                                    ->directory('settings')
                                    ->visibility('public')
                                    ->acceptedFileTypes(['image/x-icon', 'image/png', 'image/jpeg', 'image/gif', 'image/webp', 'image/svg+xml', 'image/vnd.microsoft.icon'])
                                    ->helperText('Upload gambar, akan otomatis dikonversi ke ICO saat disimpan')
                                    ->imagePreviewHeight('50'),
                            ]),
                        Tabs\Tab::make('Contact Info')
                            ->icon('heroicon-o-phone')
                            ->schema([
                                TextInput::make('address')
                                    ->label('Address')
                                    ->maxLength(500),
                                TextInput::make('city')
                                    ->label('City')
                                    ->maxLength(100),
                                TextInput::make('postal_code')
                                    ->label('Postal Code')
                                    ->maxLength(10),
                                TextInput::make('phone')
                                    ->label('Phone')
                                    ->tel()
                                    ->maxLength(20),
                                TextInput::make('phone_2')
                                    ->label('Phone 2 (Optional)')
                                    ->tel()
                                    ->maxLength(20),
                                TextInput::make('whatsapp')
                                    ->label('WhatsApp Number')
                                    ->helperText('Format: 6281234567890 (without +)')
                                    ->maxLength(20),
                                TextInput::make('email')
                                    ->label('Email')
                                    ->email()
                                    ->maxLength(255),
                                TextInput::make('email_sales')
                                    ->label('Sales Email')
                                    ->email()
                                    ->maxLength(255),
                            ]),
                        Tabs\Tab::make('Business Hours')
                            ->icon('heroicon-o-clock')
                            ->schema([
                                TextInput::make('business_hours_weekday')
                                    ->label('Weekday Hours')
                                    ->placeholder('Senin - Jumat: 08:00 - 17:00')
                                    ->maxLength(100),
                                TextInput::make('business_hours_weekend')
                                    ->label('Weekend Hours')
                                    ->placeholder('Sabtu: 08:00 - 12:00')
                                    ->maxLength(100),
                                TextInput::make('business_hours_sunday')
                                    ->label('Sunday Hours')
                                    ->placeholder('Minggu: Tutup')
                                    ->maxLength(100),
                            ]),
                        Tabs\Tab::make('Maps')
                            ->icon('heroicon-o-map-pin')
                            ->schema([
                                Textarea::make('google_maps_embed')
                                    ->label('Google Maps Embed Code')
                                    ->helperText('Paste the iframe embed code from Google Maps')
                                    ->rows(4),
                                TextInput::make('google_maps_link')
                                    ->label('Google Maps Link')
                                    ->url()
                                    ->maxLength(500),
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
                ->label('Save Settings')
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $service = app(ImageOptimizationService::class);

        // Process logo conversion to WebP if uploaded (PNG files)
        foreach (['logo', 'logo_white'] as $logoField) {
            if (!empty($data[$logoField])) {
                $logoPath = is_array($data[$logoField]) ? reset($data[$logoField]) : $data[$logoField];
                
                if ($logoPath) {
                    $extension = strtolower(pathinfo($logoPath, PATHINFO_EXTENSION));
                    
                    // If not WebP, convert it
                    if (!in_array($extension, ['webp', 'svg'])) {
                        $fullPath = Storage::disk('public')->path($logoPath);
                        
                        if (file_exists($fullPath)) {
                            $newPath = $service->convertToWebp($logoPath, 20, 400);
                            
                            if ($newPath !== $logoPath) {
                                Storage::disk('public')->delete($logoPath);
                                $data[$logoField] = $newPath;
                            }
                        }
                    }
                }
            }
        }

        // Process favicon conversion to ICO if uploaded
        if (!empty($data['favicon'])) {
            $faviconPath = is_array($data['favicon']) ? reset($data['favicon']) : $data['favicon'];
            
            if ($faviconPath) {
                $extension = strtolower(pathinfo($faviconPath, PATHINFO_EXTENSION));
                
                // If not ICO, convert it
                if ($extension !== 'ico') {
                    $fullPath = Storage::disk('public')->path($faviconPath);
                    
                    if (file_exists($fullPath)) {
                        $newPath = $service->convertToIco($faviconPath, 'settings');
                        
                        if ($newPath !== $faviconPath) {
                            Storage::disk('public')->delete($faviconPath);
                            $data['favicon'] = $newPath;
                        }
                    }
                }
            }
        }

        foreach ($data as $key => $value) {
            // Determine the group based on key
            if (in_array($key, ['company_name', 'company_tagline', 'company_description', 'logo', 'logo_white', 'favicon'])) {
                $group = 'general';
            } else {
                $group = 'contact';
            }

            Setting::updateOrCreate(
                ['key' => $key, 'group' => $group],
                ['value' => $value, 'type' => 'text']
            );
        }

        // Clear all related caches
        Cache::forget('settings.general');
        Cache::forget('settings.contact');
        Cache::forget('company_info');
        Cache::forget('navbar_categories');
        Cache::forget('footer_categories');

        // Refresh form data to show converted files
        $this->loadSettings();

        Notification::make()
            ->title('Settings saved successfully')
            ->success()
            ->send();
    }
}
