<?php

namespace App\Filament\Resources\HomepageSections;

use App\Filament\Resources\HomepageSections\Pages;
use App\Models\HomepageSection;
use BackedEnum;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\EditAction;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class HomepageSectionResource extends Resource
{
    protected static ?string $model = HomepageSection::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedViewColumns;

    protected static ?int $navigationSort = 5;

    public static function getNavigationGroup(): ?string
    {
        return __('Content');
    }

    public static function getNavigationLabel(): string
    {
        return __('Homepage Sections');
    }

    public static function getModelLabel(): string
    {
        return __('Section');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Homepage Sections');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Section')
                    ->schema([
                        TextInput::make('key')
                            ->label('Key (ID Section)')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->disabled(fn ($record) => $record !== null)
                            ->helperText('Tidak bisa diubah setelah dibuat')
                            ->maxLength(50),

                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nama (ID)')
                                    ->required()
                                    ->maxLength(100),

                                TextInput::make('name_en')
                                    ->label('Nama (EN)')
                                    ->maxLength(100),
                            ]),

                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(2)
                            ->maxLength(255),
                    ]),

                Section::make('Tampilan')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Toggle::make('is_active')
                                    ->label('Tampilkan')
                                    ->default(true)
                                    ->helperText('Aktifkan untuk menampilkan section di homepage'),

                                TextInput::make('sort_order')
                                    ->label('Urutan')
                                    ->numeric()
                                    ->default(0)
                                    ->helperText('Angka kecil = tampil lebih dulu'),
                            ]),

                        Select::make('bg_color')
                            ->label('Warna Background')
                            ->options([
                                'white' => '⬜ Putih',
                                'gray-50' => '🔳 Abu-abu Terang',
                                'gray-100' => '⬛ Abu-abu',
                                'primary' => '🟦 Primary (Biru Muda)',
                                'secondary' => '🟧 Secondary (Orange Muda)',
                                'dark' => '⬛ Gelap',
                                'gradient-primary' => '🌊 Gradient Primary',
                                'gradient-secondary' => '🌅 Gradient Secondary',
                                'gradient-dark' => '🌑 Gradient Dark',
                            ])
                            ->default('white')
                            ->native(false),

                        TextInput::make('bg_gradient')
                            ->label('Custom Gradient Class (Opsional)')
                            ->placeholder('bg-gradient-to-br from-blue-500 to-purple-600')
                            ->helperText('Tailwind CSS class untuk gradient custom'),
                    ]),

                Section::make('Pengaturan Tambahan')
                    ->schema([
                        KeyValue::make('settings')
                            ->label('Settings')
                            ->keyLabel('Key')
                            ->valueLabel('Value')
                            ->addActionLabel('Tambah Setting')
                            ->helperText('Pengaturan tambahan untuk section (opsional)'),
                    ])
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sort_order')
                    ->label('#')
                    ->sortable()
                    ->alignCenter()
                    ->width(50),

                TextColumn::make('name')
                    ->label('Section')
                    ->description(fn ($record) => $record->key)
                    ->searchable()
                    ->sortable(),

                ToggleColumn::make('is_active')
                    ->label('Aktif')
                    ->alignCenter(),

                TextColumn::make('bg_color')
                    ->label('Background')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'white' => '⬜ Putih',
                        'gray-50' => '🔳 Abu Terang',
                        'gray-100' => '⬛ Abu',
                        'primary' => '🟦 Primary',
                        'secondary' => '🟧 Secondary',
                        'dark' => '⬛ Dark',
                        'gradient-primary' => '🌊 Grad Primary',
                        'gradient-secondary' => '🌅 Grad Secondary',
                        'gradient-dark' => '🌑 Grad Dark',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'white' => 'gray',
                        'gray-50', 'gray-100' => 'gray',
                        'primary', 'gradient-primary' => 'info',
                        'secondary', 'gradient-secondary' => 'warning',
                        'dark', 'gradient-dark' => 'gray',
                        default => 'gray',
                    }),

                TextColumn::make('updated_at')
                    ->label('Diupdate')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Status')
                    ->placeholder('Semua')
                    ->trueLabel('Aktif')
                    ->falseLabel('Nonaktif'),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    BulkAction::make('activate')
                        ->label('Aktifkan')
                        ->icon(Heroicon::OutlinedEye)
                        ->action(fn ($records) => $records->each->update(['is_active' => true]))
                        ->deselectRecordsAfterCompletion(),
                    BulkAction::make('deactivate')
                        ->label('Nonaktifkan')
                        ->icon(Heroicon::OutlinedEyeSlash)
                        ->action(fn ($records) => $records->each->update(['is_active' => false]))
                        ->deselectRecordsAfterCompletion(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHomepageSections::route('/'),
            'create' => Pages\CreateHomepageSection::route('/create'),
            'edit' => Pages\EditHomepageSection::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }
}
