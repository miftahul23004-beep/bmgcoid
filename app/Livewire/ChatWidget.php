<?php

namespace App\Livewire;

use App\Models\ChatMessage;
use App\Models\ChatSession;
use App\Services\AiChatService;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Attributes\On;

class ChatWidget extends Component
{
    public bool $isOpen = false;
    public bool $isStarted = false;
    public bool $isTyping = false;
    public ?ChatSession $session = null;
    public string $visitorName = '';
    public string $visitorEmail = '';
    public string $visitorPhone = '';
    public string $message = '';
    public $messages = [];

    public function mount(): void
    {
        // Check for existing session in cookie
        $sessionToken = request()->cookie('chat_session_token');
        
        if ($sessionToken) {
            $this->session = ChatSession::where('session_token', $sessionToken)
                ->where('status', '!=', 'closed')
                ->first();
            
            if ($this->session) {
                $this->isStarted = true;
                $this->loadMessages();
            }
        }
    }

    public function toggleChat(): void
    {
        $this->isOpen = !$this->isOpen;
        
        if ($this->isOpen && $this->isStarted) {
            $this->loadMessages();
            $this->markAsRead();
        }
        
        // Dispatch event to Alpine
        $this->dispatch($this->isOpen ? 'chat-opened' : 'chat-closed');
    }

    public function startChat(): void
    {
        $this->validate([
            'visitorName' => 'required|string|max:255',
            'visitorEmail' => 'required|email|max:255',
            'visitorPhone' => 'nullable|string|max:20',
        ]);

        $sessionToken = Str::uuid()->toString();

        $this->session = ChatSession::create([
            'session_token' => $sessionToken,
            'visitor_name' => $this->visitorName,
            'visitor_email' => $this->visitorEmail,
            'visitor_phone' => $this->visitorPhone ?: null,
            'visitor_ip' => request()->ip(),
            'visitor_user_agent' => request()->userAgent(),
            'page_url' => url()->previous(),
            'status' => 'waiting',
            'handler' => 'ai', // Default to AI handler
            'started_at' => now(),
        ]);

        // Set cookie for session persistence
        cookie()->queue('chat_session_token', $sessionToken, 60 * 24 * 7); // 7 days

        // Send welcome message
        ChatMessage::create([
            'chat_session_id' => $this->session->id,
            'sender_type' => 'system',
            'message' => 'Terima kasih telah menghubungi kami! Tim kami akan segera merespons pesan Anda.',
            'is_read' => true,
        ]);

        $this->isStarted = true;
        $this->isOpen = true; // Keep chat window open
        $this->loadMessages();
        
        // Dispatch browser event to scroll to bottom
        $this->dispatch('chat-started');
    }

    public function sendMessage(): void
    {
        if (!$this->session || empty(trim($this->message))) {
            return;
        }

        $this->validate([
            'message' => 'required|string|max:5000',
        ]);

        $userMessage = $this->message;

        // Save visitor message
        ChatMessage::create([
            'chat_session_id' => $this->session->id,
            'sender_type' => 'visitor',
            'message' => $userMessage,
            'is_read' => false,
        ]);

        // Update session
        $this->session->update([
            'status' => 'active',
            'last_message_at' => now(),
        ]);

        $this->message = '';
        $this->loadMessages();

        // Generate AI response if handler is AI
        if ($this->session->handler === 'ai') {
            $this->generateAiResponse($userMessage);
        }
    }

    /**
     * Generate AI response
     */
    protected function generateAiResponse(string $userMessage): void
    {
        $aiService = app(AiChatService::class);
        
        // Check if should handover to operator
        if ($aiService->shouldHandoverToOperator($userMessage)) {
            $this->session->update([
                'handler' => 'operator',
                'status' => 'waiting',
            ]);
            
            // System message for handover
            ChatMessage::create([
                'chat_session_id' => $this->session->id,
                'sender_type' => 'system',
                'message' => 'Menghubungkan Anda dengan customer service kami. Mohon tunggu sebentar...',
                'is_read' => true,
            ]);
            
            $this->loadMessages();
            return;
        }

        // Generate AI response
        $aiResponse = $aiService->generateResponse($this->session, $userMessage);
        
        if ($aiResponse) {
            ChatMessage::create([
                'chat_session_id' => $this->session->id,
                'sender_type' => 'ai',
                'message' => $aiResponse,
                'is_read' => true,
            ]);
            
            $this->session->increment('message_count');
        }
        
        $this->loadMessages();
    }

    public function loadMessages(): void
    {
        if (!$this->session) {
            return;
        }

        $this->messages = $this->session->messages()
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($msg) {
                return [
                    'id' => $msg->id,
                    'sender_type' => $msg->sender_type,
                    'message' => $msg->message,
                    'created_at' => $msg->created_at->format('H:i'),
                    'is_read' => $msg->is_read,
                ];
            })
            ->toArray();
    }

    public function markAsRead(): void
    {
        if (!$this->session) {
            return;
        }

        $this->session->messages()
            ->where('sender_type', '!=', 'visitor')
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);
    }

    #[On('refresh-chat')]
    public function refreshChat(): void
    {
        $this->loadMessages();
    }

    public function endChat(): void
    {
        if ($this->session) {
            $this->session->update([
                'status' => 'closed',
                'ended_at' => now(),
            ]);
        }

        // Clear cookie
        cookie()->queue(cookie()->forget('chat_session_token'));

        $this->session = null;
        $this->isStarted = false;
        $this->isOpen = false;
        $this->messages = [];
        $this->reset(['visitorName', 'visitorEmail', 'visitorPhone', 'message']);
    }

    public function getUnreadCountProperty(): int
    {
        if (!$this->session) {
            return 0;
        }

        return $this->session->messages()
            ->where('sender_type', '!=', 'visitor')
            ->where('is_read', false)
            ->count();
    }

    public function render()
    {
        return view('livewire.chat-widget');
    }
}
