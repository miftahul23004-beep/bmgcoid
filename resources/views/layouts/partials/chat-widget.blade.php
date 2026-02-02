{{-- Live Chat Widget --}}
<div 
    x-data="chatWidget()"
    x-init="init()"
    class="fixed bottom-6 right-6 z-50"
>
    {{-- Chat Button --}}
    <button 
        @click="toggleChat()"
        x-show="!isOpen"
        class="w-14 h-14 rounded-full bg-primary-600 text-white shadow-lg flex items-center justify-center hover:bg-primary-700 transition-all duration-300 relative"
        aria-label="{{ __('Open chat') }}"
    >
        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
        </svg>
        {{-- Notification badge --}}
        <span 
            x-show="unreadCount > 0"
            x-text="unreadCount"
            class="absolute -top-1 -right-1 w-5 h-5 bg-secondary-600 text-white text-xs rounded-full flex items-center justify-center"
        ></span>
    </button>

    {{-- Chat Window --}}
    <div 
        x-show="isOpen"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95 translate-y-4"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-4"
        class="absolute bottom-0 right-0 w-96 max-w-[calc(100vw-3rem)] bg-white rounded-2xl shadow-2xl overflow-hidden"
    >
        {{-- Header --}}
        <div class="bg-primary-600 text-white p-4 flex items-center justify-between">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold">{{ config('app.name') }}</h3>
                    <p class="text-sm text-white/80">
                        <span x-show="isOnline" class="flex items-center">
                            <span class="w-2 h-2 bg-green-400 rounded-full mr-1.5"></span>
                            {{ __('Online') }}
                        </span>
                        <span x-show="!isOnline">{{ __('Leave a message') }}</span>
                    </p>
                </div>
            </div>
            <button @click="toggleChat()" class="p-1 hover:bg-white/10 rounded-lg transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Messages Container --}}
        <div 
            x-ref="messagesContainer"
            class="h-80 overflow-y-auto p-4 bg-gray-50 space-y-4"
        >
            {{-- Welcome Message --}}
            <div x-show="messages.length === 0" class="text-center py-8">
                <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                    </svg>
                </div>
                <h4 class="font-semibold text-gray-900 mb-1">{{ __('Welcome!') }}</h4>
                <p class="text-sm text-gray-600">{{ config('chat.widget.welcome_message') }}</p>
            </div>

            {{-- Message Bubbles --}}
            <template x-for="message in messages" :key="message.id">
                <div :class="message.sender === 'visitor' ? 'flex justify-end' : 'flex justify-start'">
                    <div 
                        :class="message.sender === 'visitor' 
                            ? 'bg-primary-600 text-white rounded-2xl rounded-br-md' 
                            : 'bg-white text-gray-800 rounded-2xl rounded-bl-md shadow-sm'"
                        class="max-w-[80%] px-4 py-2"
                    >
                        <p x-text="message.text" class="text-sm"></p>
                        <span 
                            :class="message.sender === 'visitor' ? 'text-white/60' : 'text-gray-400'"
                            class="text-xs mt-1 block"
                            x-text="formatTime(message.time)"
                        ></span>
                    </div>
                </div>
            </template>

            {{-- Typing Indicator --}}
            <div x-show="isTyping" class="flex justify-start">
                <div class="bg-white rounded-2xl rounded-bl-md shadow-sm px-4 py-3">
                    <div class="flex space-x-1">
                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0ms"></div>
                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 150ms"></div>
                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 300ms"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Input Area --}}
        <div class="p-4 bg-white border-t">
            <form @submit.prevent="sendMessage()" class="flex items-center space-x-2">
                <input 
                    x-model="newMessage"
                    type="text"
                    :placeholder="'{{ config('chat.widget.placeholder') }}'"
                    class="flex-1 px-4 py-2.5 bg-gray-100 rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-primary-500"
                >
                <button 
                    type="submit"
                    :disabled="!newMessage.trim()"
                    class="w-10 h-10 bg-primary-600 text-white rounded-full flex items-center justify-center hover:bg-primary-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                </button>
            </form>
            <p class="text-xs text-gray-400 text-center mt-2">
                {{ __('Powered by') }} {{ config('app.name') }}
            </p>
        </div>
    </div>
</div>

@push('scripts')
<script>
function chatWidget() {
    return {
        isOpen: false,
        isOnline: true,
        isTyping: false,
        messages: [],
        newMessage: '',
        unreadCount: 0,
        sessionId: null,

        init() {
            // Check working hours
            this.checkOnlineStatus();
            
            // Load session from localStorage
            this.sessionId = localStorage.getItem('chat_session_id');
            if (this.sessionId) {
                this.loadMessages();
            }
        },

        checkOnlineStatus() {
            const now = new Date();
            const day = now.toLocaleDateString('en-US', { weekday: 'lowercase' });
            const hours = @json(config('chat.working_hours.schedule'));
            
            if (hours[day]) {
                const [start, end] = hours[day];
                const currentTime = now.getHours() * 100 + now.getMinutes();
                const startTime = parseInt(start.replace(':', ''));
                const endTime = parseInt(end.replace(':', ''));
                this.isOnline = currentTime >= startTime && currentTime <= endTime;
            } else {
                this.isOnline = false;
            }
        },

        toggleChat() {
            this.isOpen = !this.isOpen;
            if (this.isOpen) {
                this.unreadCount = 0;
                this.$nextTick(() => {
                    this.scrollToBottom();
                });
            }
        },

        async sendMessage() {
            if (!this.newMessage.trim()) return;

            const message = {
                id: Date.now(),
                text: this.newMessage,
                sender: 'visitor',
                time: new Date()
            };

            this.messages.push(message);
            this.newMessage = '';
            this.scrollToBottom();

            // Simulate AI response
            this.isTyping = true;
            setTimeout(() => {
                this.isTyping = false;
                this.messages.push({
                    id: Date.now(),
                    text: 'Terima kasih telah menghubungi kami. Tim kami akan segera merespons pesan Anda.',
                    sender: 'ai',
                    time: new Date()
                });
                this.scrollToBottom();
            }, 1500);
        },

        scrollToBottom() {
            this.$nextTick(() => {
                if (this.$refs.messagesContainer) {
                    this.$refs.messagesContainer.scrollTop = this.$refs.messagesContainer.scrollHeight;
                }
            });
        },

        formatTime(date) {
            return new Date(date).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
        },

        loadMessages() {
            // Load from API if needed
        }
    }
}
</script>
@endpush

