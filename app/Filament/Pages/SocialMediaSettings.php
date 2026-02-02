<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use BackedEnum;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Cache;
use UnitEnum;

class SocialMediaSettings extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-share';
    protected static ?string $navigationLabel = 'Social Media';
    protected static string|UnitEnum|null $navigationGroup = null;
    protected static ?int $navigationSort = 2;
    protected string $view = 'filament.pages.social-media-settings';

    public static function getNavigationGroup(): ?string
    {
        return __('Settings');
    }

    public ?array $data = [];

    public function mount(): void
    {
        $settings = Setting::where('group', 'social')
            ->pluck('value', 'key')
            ->toArray();

        $this->form->fill($settings);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Social Media Links')
                    ->description('Configure your social media profiles')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('whatsapp')
                                    ->label('WhatsApp Number')
                                    ->helperText('Format: 6281234567890')
                                    ->prefixIcon('heroicon-o-phone')
                                    ->maxLength(20),
                                Toggle::make('whatsapp_active')
                                    ->label('WhatsApp Active')
                                    ->default(true),
                                TextInput::make('instagram')
                                    ->label('Instagram URL')
                                    ->url()
                                    ->prefixIcon('heroicon-o-camera')
                                    ->placeholder('https://instagram.com/yourusername')
                                    ->maxLength(255),
                                Toggle::make('instagram_active')
                                    ->label('Instagram Active')
                                    ->default(true),
                                TextInput::make('facebook')
                                    ->label('Facebook URL')
                                    ->url()
                                    ->prefixIcon('heroicon-o-globe-alt')
                                    ->placeholder('https://facebook.com/yourpage')
                                    ->maxLength(255),
                                Toggle::make('facebook_active')
                                    ->label('Facebook Active')
                                    ->default(true),
                                TextInput::make('youtube')
                                    ->label('YouTube URL')
                                    ->url()
                                    ->prefixIcon('heroicon-o-play')
                                    ->placeholder('https://youtube.com/@yourchannel')
                                    ->maxLength(255),
                                Toggle::make('youtube_active')
                                    ->label('YouTube Active')
                                    ->default(true),
                                TextInput::make('tiktok')
                                    ->label('TikTok URL')
                                    ->url()
                                    ->prefixIcon('heroicon-o-musical-note')
                                    ->placeholder('https://tiktok.com/@yourusername')
                                    ->maxLength(255),
                                Toggle::make('tiktok_active')
                                    ->label('TikTok Active')
                                    ->default(true),
                                TextInput::make('linkedin')
                                    ->label('LinkedIn URL')
                                    ->url()
                                    ->prefixIcon('heroicon-o-briefcase')
                                    ->placeholder('https://linkedin.com/company/yourcompany')
                                    ->maxLength(255),
                                Toggle::make('linkedin_active')
                                    ->label('LinkedIn Active')
                                    ->default(true),
                                TextInput::make('twitter')
                                    ->label('Twitter/X URL')
                                    ->url()
                                    ->prefixIcon('heroicon-o-chat-bubble-left')
                                    ->placeholder('https://x.com/yourusername')
                                    ->maxLength(255),
                                Toggle::make('twitter_active')
                                    ->label('Twitter Active')
                                    ->default(true),
                            ]),
                    ]),
                Section::make('Social Media Display Settings')
                    ->schema([
                        Toggle::make('show_social_topbar')
                            ->label('Show in Top Bar')
                            ->default(true),
                        Toggle::make('show_social_footer')
                            ->label('Show in Footer')
                            ->default(true),
                        Toggle::make('show_floating_whatsapp')
                            ->label('Show Floating WhatsApp Button')
                            ->default(true),
                    ]),
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
            Setting::updateOrCreate(
                ['key' => $key, 'group' => 'social'],
                ['value' => is_bool($value) ? ($value ? '1' : '0') : $value, 'type' => is_bool($value) ? 'boolean' : 'text']
            );
        }

        Cache::forget('settings.social');

        Notification::make()
            ->title('Social media settings saved successfully')
            ->success()
            ->send();
    }
}