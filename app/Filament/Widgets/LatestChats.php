<?php

namespace App\Filament\Widgets;

use App\Models\ChatSession;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestChats extends BaseWidget
{
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 1;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ChatSession::query()
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('visitor_name')
                    ->label('Visitor')
                    ->default('Anonymous')
                    ->limit(15),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'waiting',
                        'info' => 'ai_handling',
                        'primary' => 'operator_handling',
                        'success' => 'closed',
                    ]),
                Tables\Columns\TextColumn::make('started_at')
                    ->label('Started')
                    ->since(),
            ])
            ->heading('Recent Chats')
            ->paginated(false);
    }
}
