<?php

namespace App\Filament\Resources\Inquiries;

use App\Filament\Resources\Inquiries\Pages\CreateInquiry;
use App\Filament\Resources\Inquiries\Pages\EditInquiry;
use App\Filament\Resources\Inquiries\Pages\ListInquiries;
use App\Filament\Resources\Inquiries\Schemas\InquiryForm;
use App\Filament\Resources\Inquiries\Tables\InquiriesTable;
use App\Models\Inquiry;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class InquiryResource extends Resource
{
    protected static ?string $model = Inquiry::class;

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('view inquiries') ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->can('manage inquiries') ?? false;
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()?->can('manage inquiries') ?? false;
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()?->can('manage inquiries') ?? false;
    }

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedEnvelope;

    protected static ?int $navigationSort = 2;

    public static function getNavigationGroup(): ?string
    {
        return __('Customers');
    }

    public static function getNavigationLabel(): string
    {
        return __('Inquiries');
    }

    public static function getModelLabel(): string
    {
        return __('Inquiry');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Inquiries');
    }

    public static function form(Schema $schema): Schema
    {
        return InquiryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InquiriesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListInquiries::route('/'),
            'create' => CreateInquiry::route('/create'),
            'edit' => EditInquiry::route('/{record}/edit'),
        ];
    }
}
