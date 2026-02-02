<?php

namespace App\Filament\Pages;

use App\Models\ChatMessage;
use App\Models\ChatSession;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use UnitEnum;

class LiveChat extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationLabel = 'Live Chat';
    protected static string|UnitEnum|null $navigationGroup = null;
    protected static ?int $navigationSort = 4;
    protected string $view = 'filament.pages.live-chat';

    public static function getNavigationGroup(): ?string
    {
        return __('Customers');
    }

    public ?ChatSession $selectedSession = null;
    public string $replyMessage = '';
    public $sessions = [];
    public $messages = [];

    public function mount(): void
    {
        $this->loadSessions();
    }

    public function loadSessions(): void
    {
        $this->sessions = ChatSession::with('latestMessage')
            ->whereIn('status', ['waiting', 'active'])
            ->orderByDesc('last_message_at')
            ->get();
    }

    public function selectSession(int $sessionId): void
    {
        $this->selectedSession = ChatSession::find($sessionId);
        $this->loadMessages();
        $this->markAsRead();
    }

    public function loadMessages(): void
    {
        if (!$this->selectedSession) {
            return;
        }

        $this->messages = $this->selectedSession->messages()
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function markAsRead(): void
    {
        if (!$this->selectedSession) {
            return;
        }

        $this->selectedSession->messages()
            ->where('sender_type', 'visitor')
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);
    }

    public function sendReply(): void
    {
        if (!$this->selectedSession || empty(trim($this->replyMessage))) {
            return;
        }

        ChatMessage::create([
            'chat_session_id' => $this->selectedSession->id,
            'user_id' => auth()->id(),
            'sender_type' => 'operator',
            'message' => $this->replyMessage,
            'is_read' => false,
        ]);

        $this->selectedSession->update([
            'status' => 'active',
            'handler' => 'operator',
            'assigned_to' => auth()->id(),
            'last_message_at' => now(),
        ]);

        $this->replyMessage = '';
        $this->loadMessages();
        $this->loadSessions();

        Notification::make()
            ->success()
            ->title('Message sent')
            ->send();
    }

    public function closeSession(): void
    {
        if (!$this->selectedSession) {
            return;
        }

        // Send closing message
        ChatMessage::create([
            'chat_session_id' => $this->selectedSession->id,
            'sender_type' => 'system',
            'message' => 'Chat session has been closed. Thank you for contacting us!',
            'is_read' => true,
        ]);

        $this->selectedSession->update([
            'status' => 'closed',
            'ended_at' => now(),
        ]);

        $this->selectedSession = null;
        $this->messages = [];
        $this->loadSessions();

        Notification::make()
            ->success()
            ->title('Chat session closed')
            ->send();
    }

    public static function getNavigationBadge(): ?string
    {
        $count = ChatSession::whereIn('status', ['waiting', 'active'])
            ->whereHas('messages', function ($query) {
                $query->where('sender_type', 'visitor')
                    ->where('is_read', false);
            })
            ->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}
