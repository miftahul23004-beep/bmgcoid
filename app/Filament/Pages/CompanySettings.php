<?php

namespace App\Filament\Pages;

use App\Models\Setting;
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
use UnitEnum;

class CompanySettings extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationLabel = 'Company Settings';
    protected static string|UnitEnum|null $navigationGroup = 'Settings';
    protected static ?int $navigationSort = 1;
    protected string $view = 'filament.pages.company-settings';

    public static function getNavigationGroup(): ?string
    {
        return __('Settings');
    }

    public ?array $data = [];

    public function mount(): void
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
                                    ->imagePreviewHeight('100'),
                                FileUpload::make('logo_white')
                                    ->label('Logo (White/Dark Mode)')
                                    ->image()
                                    ->disk('public')
                                    ->directory('settings')
                                    ->visibility('public')
                                    ->imagePreviewHeight('100'),
                                FileUpload::make('favicon')
                                    ->label('Favicon')
                                    ->image()
                                    ->disk('public')
                                    ->directory('settings')
                                    ->visibility('public')
                                    ->acceptedFileTypes(['image/x-icon', 'image/png', 'image/jpeg', 'image/gif', 'image/webp', 'image/svg+xml'])
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

        foreach ($data as $key => $value) {
            $group = in_array($key, ['company_name', 'company_tagline', 'company_description', 'logo', 'logo_white', 'favicon']) 
                ? 'general' 
                : 'contact';

            Setting::updateOrCreate(
                ['key' => $key, 'group' => $group],
                ['value' => $value, 'type' => 'text']
            );
        }

        // Clear cache
        Cache::forget('settings.general');
        Cache::forget('settings.contact');

        Notification::make()
            ->title('Settings saved successfully')
            ->success()
            ->send();
    }
}
