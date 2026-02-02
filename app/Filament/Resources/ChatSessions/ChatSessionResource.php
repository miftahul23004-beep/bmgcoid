<?php

namespace App\Filament\Resources\ChatSessions;

use App\Filament\Resources\ChatSessions\Pages\CreateChatSession;
use App\Filament\Resources\ChatSessions\Pages\EditChatSession;
use App\Filament\Resources\ChatSessions\Pages\ListChatSessions;
use App\Filament\Resources\ChatSessions\Schemas\ChatSessionForm;
use App\Filament\Resources\ChatSessions\Tables\ChatSessionsTable;
use App\Models\ChatSession;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ChatSessionResource extends Resource
{
    protected static ?string $model = ChatSession::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleOvalLeftEllipsis;

    protected static ?int $navigationSort = 3;

    public static function getNavigationGroup(): ?string
    {
        return __('Customers');
    }

    public static function getNavigationLabel(): string
    {
        return __('Chat Sessions');
    }

    public static function getModelLabel(): string
    {
        return __('Chat Session');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Chat Sessions');
    }

    public static function form(Schema $schema): Schema
    {
        return ChatSessionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ChatSessionsTable::configure($table);
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
            'index' => ListChatSessions::route('/'),
            'create' => CreateChatSession::route('/create'),
            'edit' => EditChatSession::route('/{record}/edit'),
        ];
    }
}
