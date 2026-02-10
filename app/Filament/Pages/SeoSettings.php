<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use App\Services\ImageOptimizationService;
use BackedEnum;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Cache;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use UnitEnum;

class SeoSettings extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-magnifying-glass';
    protected static ?string $navigationLabel = 'SEO Settings';
    protected static string|UnitEnum|null $navigationGroup = null;
    protected static ?int $navigationSort = 4;
    protected string $view = 'filament.pages.seo-settings';

    public static function canAccess(): bool
    {
        return auth()->user()?->can('manage seo') ?? false;
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Settings');
    }

    public ?array $data = [];

    public function mount(): void
    {
        $settings = Setting::where('group', 'seo')
            ->pluck('value', 'key')
            ->toArray();

        $this->form->fill($settings);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Tabs::make('SEO Settings')
                    ->tabs([
                        Tabs\Tab::make('Default Meta Tags')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                TextInput::make('default_meta_title')
                                    ->label('Default Meta Title')
                                    ->helperText('Used when page has no custom title. Max 60 characters.')
                                    ->maxLength(60)
                                    ->live()
                                    ->afterStateUpdated(fn ($state, Set $set) => $set('title_length', strlen($state ?? ''))),
                                Placeholder::make('title_length')
                                    ->label('Title Length')
                                    ->content(fn ($get) => strlen($get('default_meta_title') ?? '') . '/60 characters'),
                                Textarea::make('default_meta_description')
                                    ->label('Default Meta Description')
                                    ->helperText('Used when page has no custom description. Max 160 characters.')
                                    ->maxLength(160)
                                    ->rows(3),
                                TextInput::make('meta_keywords')
                                    ->label('Default Meta Keywords')
                                    ->helperText('Comma-separated keywords')
                                    ->maxLength(255),
                                FileUpload::make('og_image')
                                    ->label('Default OG Image')
                                    ->image()
                                    ->disk('public')
                                    ->directory('seo')
                                    ->visibility('public')
                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                    ->maxSize(10240) // 10MB max upload
                                    ->saveUploadedFileUsing(function (TemporaryUploadedFile $file) {
                                        $service = app(ImageOptimizationService::class);
                                        return $service->processUpload($file, 'seo', 30); // Max 30KB output
                                    })
                                    ->helperText('Recommended: 1200x630px. Max upload 10MB, auto-convert to WebP max 30KB.'),
                            ]),
                        Tabs\Tab::make('Analytics')
                            ->icon('heroicon-o-chart-bar')
                            ->schema([
                                TextInput::make('google_analytics_id')
                                    ->label('Google Analytics 4 ID')
                                    ->placeholder('G-XXXXXXXXXX')
                                    ->maxLength(20),
                                TextInput::make('google_tag_manager_id')
                                    ->label('Google Tag Manager ID')
                                    ->placeholder('GTM-XXXXXXX')
                                    ->maxLength(20),
                                TextInput::make('facebook_pixel_id')
                                    ->label('Facebook Pixel ID')
                                    ->placeholder('XXXXXXXXXXXXX')
                                    ->maxLength(20),
                                Textarea::make('custom_head_scripts')
                                    ->label('Custom Head Scripts')
                                    ->helperText('Additional scripts to add in <head>')
                                    ->rows(4),
                                Textarea::make('custom_body_scripts')
                                    ->label('Custom Body Scripts')
                                    ->helperText('Additional scripts to add before </body>')
                                    ->rows(4),
                            ]),
                        Tabs\Tab::make('Search Console')
                            ->icon('heroicon-o-globe-alt')
                            ->schema([
                                TextInput::make('google_site_verification')
                                    ->label('Google Site Verification')
                                    ->helperText('Meta tag content for Google Search Console')
                                    ->maxLength(100),
                                TextInput::make('bing_site_verification')
                                    ->label('Bing Site Verification')
                                    ->helperText('Meta tag content for Bing Webmaster Tools')
                                    ->maxLength(100),
                            ]),
                        Tabs\Tab::make('Schema & Structured Data')
                            ->icon('heroicon-o-code-bracket')
                            ->schema([
                                Toggle::make('enable_organization_schema')
                                    ->label('Enable Organization Schema')
                                    ->default(true),
                                Toggle::make('enable_local_business_schema')
                                    ->label('Enable Local Business Schema')
                                    ->default(true),
                                Toggle::make('enable_product_schema')
                                    ->label('Enable Product Schema')
                                    ->default(true),
                                Toggle::make('enable_breadcrumb_schema')
                                    ->label('Enable Breadcrumb Schema')
                                    ->default(true),
                                Toggle::make('enable_faq_schema')
                                    ->label('Enable FAQ Schema')
                                    ->default(true),
                            ]),
                        Tabs\Tab::make('Robots & Sitemap')
                            ->icon('heroicon-o-cog')
                            ->schema([
                                Toggle::make('enable_sitemap')
                                    ->label('Enable Sitemap Generation')
                                    ->default(true),
                                Toggle::make('enable_robots_txt')
                                    ->label('Enable Robots.txt')
                                    ->default(true),
                                Textarea::make('robots_txt_custom')
                                    ->label('Custom Robots.txt Rules')
                                    ->rows(6)
                                    ->placeholder("User-agent: *\nAllow: /"),
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
            if (is_bool($value)) {
                $value = $value ? '1' : '0';
                $type = 'boolean';
            } elseif (is_array($value)) {
                $value = json_encode($value);
                $type = 'json';
            } else {
                $type = 'text';
            }

            Setting::updateOrCreate(
                ['key' => $key, 'group' => 'seo'],
                ['value' => $value, 'type' => $type]
            );
        }

        Cache::forget('settings.seo');

        Notification::make()
            ->title('SEO settings saved successfully')
            ->success()
            ->send();
    }
}