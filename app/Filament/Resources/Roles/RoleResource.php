<?php

namespace App\Filament\Resources\Roles;

use App\Filament\Resources\Roles\Schemas\RoleForm;
use App\Filament\Resources\Roles\Tables\RolesTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('view roles') ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->can('create roles') ?? false;
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()?->can('edit roles') ?? false;
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()?->can('delete roles') ?? false;
    }

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldCheck;

    protected static ?int $navigationSort = 11;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationGroup(): ?string
    {
        return __('Settings');
    }

    public static function getModelLabel(): string
    {
        return __('Role');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Roles');
    }

    public static function form(Schema $schema): Schema
    {
        return RoleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RolesTable::configure($table);
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
            'index' => \App\Filament\Resources\Roles\Pages\ListRoles::route('/'),
            'create' => \App\Filament\Resources\Roles\Pages\CreateRole::route('/create'),
            'edit' => \App\Filament\Resources\Roles\Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
