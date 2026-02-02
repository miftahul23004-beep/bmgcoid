<?php

namespace App\Filament\Resources\ChatSessions\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ChatSessionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('session_id')
                    ->required(),
                Select::make('operator_id')
                    ->relationship('operator', 'name')
                    ->default(null),
                TextInput::make('visitor_name')
                    ->default(null),
                TextInput::make('visitor_email')
                    ->email()
                    ->default(null),
                TextInput::make('visitor_phone')
                    ->tel()
                    ->default(null),
                TextInput::make('visitor_ip')
                    ->default(null),
                TextInput::make('visitor_user_agent')
                    ->default(null),
                TextInput::make('page_url')
                    ->url()
                    ->default(null),
                Select::make('status')
                    ->options(['waiting' => 'Waiting', 'active' => 'Active', 'closed' => 'Closed', 'abandoned' => 'Abandoned'])
                    ->default('waiting')
                    ->required(),
                Select::make('handler')
                    ->options(['ai' => 'Ai', 'operator' => 'Operator', 'mixed' => 'Mixed'])
                    ->default('ai')
                    ->required(),
                TextInput::make('message_count')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('rating')
                    ->numeric()
                    ->default(null),
                Textarea::make('feedback')
                    ->default(null)
                    ->columnSpanFull(),
                DateTimePicker::make('started_at'),
                DateTimePicker::make('closed_at'),
            ]);
    }
}
