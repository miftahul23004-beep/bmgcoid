<div 
    x-data="{ 
        chatOpen: false,
        scrollToBottom() {
            this.$nextTick(() => {
                const container = document.getElementById('chat-messages');
                if (container) {
                    container.scrollTop = container.scrollHeight;
                }
            });
        },
        toggleChat() {
            this.chatOpen = !this.chatOpen;
            if (this.chatOpen) {
                setTimeout(() => this.scrollToBottom(), 100);
            }
        }
    }"
    x-init="
        Livewire.on('chat-started', () => { 
            chatOpen = true;
            setTimeout(() => scrollToBottom(), 100);
        });
    "
    @if($isStarted)
        wire:poll.5s="refreshChat"
    @endif
    class="fixed bottom-6 right-6 z-50"
>
    {{-- Chat Toggle Button --}}
    <button 
        @click="toggleChat(); @if($isStarted) $wire.toggleChat() @endif"
        class="relative w-14 h-14 bg-primary-600 hover:bg-primary-700 text-white rounded-full shadow-lg flex items-center justify-center transition-all duration-300 hover:scale-110"
    >
        <svg x-show="!chatOpen" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
        </svg>
        <svg x-show="chatOpen" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
        
        {{-- Unread Badge --}}
        @if($this->unreadCount > 0)
            <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">
                {{ $this->unreadCount }}
            </span>
        @endif
    </button>

    {{-- Chat Window --}}
    <div 
        x-show="chatOpen"
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 scale-95"
        class="absolute bottom-20 right-0 w-80 sm:w-96 bg-white rounded-2xl shadow-2xl overflow-hidden max-h-[500px]"
    >
        {{-- Header --}}
        <div class="bg-primary-600 text-white p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold">Live Chat</h3>
                        <p class="text-xs text-primary-200">{{ __('We typically reply within minutes') }}</p>
                    </div>
                </div>
                @if($isStarted)
                    <button wire:click="endChat" class="text-primary-200 hover:text-white text-sm">
                        {{ __('End') }}
                    </button>
                @endif
            </div>
        </div>

        @if(!$isStarted)
            {{-- Start Chat Form --}}
            <div class="p-4">
                <p class="text-gray-600 text-sm mb-4">{{ __('Please fill in your details to start chatting with us.') }}</p>
                <form wire:submit="startChat" class="space-y-3">
                    <div>
                        <input type="text" wire:model="visitorName" placeholder="{{ __('Your Name') }} *" class="input w-full text-sm @error('visitorName') border-red-500 @enderror">
                        @error('visitorName') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <input type="email" wire:model="visitorEmail" placeholder="{{ __('Your Email') }} *" class="input w-full text-sm @error('visitorEmail') border-red-500 @enderror">
                        @error('visitorEmail') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <input type="tel" wire:model="visitorPhone" placeholder="{{ __('Phone (optional)') }}" class="input w-full text-sm">
                    </div>
                    <button type="submit" class="btn btn-primary w-full py-2.5" wire:loading.attr="disabled" wire:target="startChat">
                        <span wire:loading.remove wire:target="startChat">{{ __('Start Chat') }}</span>
                        <span wire:loading wire:target="startChat">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            {{ __('Starting...') }}
                        </span>
                    </button>
                </form>
            </div>
        @else
            {{-- Messages --}}
            <div id="chat-messages" class="h-72 overflow-y-auto p-4 space-y-3 bg-gray-50" wire:poll.5s="loadMessages">
                @foreach($messages as $msg)
                    <div class="flex {{ $msg['sender_type'] === 'visitor' ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-[80%] {{ $msg['sender_type'] === 'visitor' ? 'bg-primary-600 text-white' : ($msg['sender_type'] === 'system' ? 'bg-gray-200 text-gray-700 italic' : ($msg['sender_type'] === 'ai' ? 'bg-blue-50 text-gray-800 border border-blue-200' : 'bg-white text-gray-800 shadow')) }} rounded-xl px-4 py-2">
                            @if($msg['sender_type'] === 'ai')
                                <div class="flex items-center gap-1 mb-1">
                                    <svg class="w-3 h-3 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 2a8 8 0 100 16 8 8 0 000-16zm1 11H9v-2h2v2zm0-4H9V5h2v4z"/>
                                    </svg>
                                    <span class="text-xs text-blue-600 font-medium">AI Assistant</span>
                                </div>
                            @elseif($msg['sender_type'] === 'operator')
                                <div class="flex items-center gap-1 mb-1">
                                    <svg class="w-3 h-3 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-xs text-green-700 font-medium">Customer Service</span>
                                </div>
                            @endif
                            <p class="text-sm whitespace-pre-wrap">{{ $msg['message'] }}</p>
                            <p class="text-xs {{ $msg['sender_type'] === 'visitor' ? 'text-primary-200' : 'text-gray-500' }} mt-1">{{ $msg['created_at'] }}</p>
                        </div>
                    </div>
                @endforeach

                {{-- Typing Indicator --}}
                <div wire:loading wire:target="sendMessage" class="flex justify-start">
                    <div class="bg-gray-200 rounded-xl px-4 py-2">
                        <div class="flex space-x-1">
                            <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"></div>
                            <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce [animation-delay:150ms]"></div>
                            <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce [animation-delay:300ms]"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Message Input --}}
            <div class="p-4 bg-white border-t">
                <form wire:submit="sendMessage" class="flex gap-2">
                    <input 
                        type="text" 
                        wire:model="message" 
                        placeholder="{{ __('Type a message...') }}" 
                        class="input flex-1 text-sm"
                        wire:loading.attr="disabled"
                        wire:target="sendMessage"
                    >
                    <button type="submit" class="btn btn-primary px-4" wire:loading.attr="disabled" wire:target="sendMessage">
                        <svg wire:loading.remove wire:target="sendMessage" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        <svg wire:loading wire:target="sendMessage" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>
