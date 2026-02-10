<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use BackedEnum;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
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

class MarketplaceSettings extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationLabel = 'Marketplace';
    protected static string|UnitEnum|null $navigationGroup = null;
    protected static ?int $navigationSort = 3;
    protected string $view = 'filament.pages.marketplace-settings';

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
        $settings = Setting::where('group', 'marketplace')
            ->pluck('value', 'key')
            ->toArray();

        // Parse JSON fields
        foreach ($settings as $key => $value) {
            if (is_string($value)) {
                $decoded = json_decode($value, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    $settings[$key] = $decoded;
                }
            }
        }

        $this->form->fill($settings);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Marketplace Store Links')
                    ->description('Configure links to your marketplace stores')
                    ->schema([
                        Repeater::make('marketplaces')
                            ->schema([
                                Select::make('platform')
                                    ->label('Platform')
                                    ->options([
                                        'shopee' => 'Shopee',
                                        'tokopedia' => 'Tokopedia',
                                        'tiktok_shop' => 'TikTok Shop',
                                        'lazada' => 'Lazada',
                                        'blibli' => 'Blibli',
                                        'bukalapak' => 'Bukalapak',
                                    ])
                                    ->required(),
                                TextInput::make('store_url')
                                    ->label('Store URL')
                                    ->url()
                                    ->required()
                                    ->maxLength(500),
                                TextInput::make('store_name')
                                    ->label('Store Name')
                                    ->maxLength(255),
                                Toggle::make('is_active')
                                    ->label('Active')
                                    ->default(true),
                            ])
                            ->columns(4)
                            ->defaultItems(0)
                            ->addActionLabel('Add Marketplace')
                            ->reorderable()
                            ->collapsible(),
                    ]),
                Section::make('Individual Marketplace URLs')
                    ->description('Or set URLs individually')
                    ->collapsed()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('shopee_url')
                                    ->label('Shopee Store URL')
                                    ->url()
                                    ->placeholder('https://shopee.co.id/yourstore')
                                    ->maxLength(500),
                                TextInput::make('tokopedia_url')
                                    ->label('Tokopedia Store URL')
                                    ->url()
                                    ->placeholder('https://tokopedia.com/yourstore')
                                    ->maxLength(500),
                                TextInput::make('tiktok_shop_url')
                                    ->label('TikTok Shop URL')
                                    ->url()
                                    ->placeholder('https://tiktok.com/shop/yourstore')
                                    ->maxLength(500),
                                TextInput::make('lazada_url')
                                    ->label('Lazada Store URL')
                                    ->url()
                                    ->placeholder('https://lazada.co.id/shop/yourstore')
                                    ->maxLength(500),
                                TextInput::make('blibli_url')
                                    ->label('Blibli Store URL')
                                    ->url()
                                    ->placeholder('https://blibli.com/merchant/yourstore')
                                    ->maxLength(500),
                            ]),
                    ]),
                Section::make('Display Settings')
                    ->schema([
                        Toggle::make('show_marketplace_homepage')
                            ->label('Show Marketplace Section on Homepage')
                            ->default(true),
                        Toggle::make('show_marketplace_product')
                            ->label('Show Marketplace Links on Product Page')
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
            if ($key === 'marketplaces') {
                $value = json_encode($value);
                $type = 'json';
            } elseif (is_bool($value)) {
                $value = $value ? '1' : '0';
                $type = 'boolean';
            } else {
                $type = 'text';
            }

            Setting::updateOrCreate(
                ['key' => $key, 'group' => 'marketplace'],
                ['value' => $value, 'type' => $type]
            );
        }

        Cache::forget('settings.marketplace');

        Notification::make()
            ->title('Marketplace settings saved successfully')
            ->success()
            ->send();
    }
}