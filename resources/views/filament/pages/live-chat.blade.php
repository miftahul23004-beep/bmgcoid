<x-filament-panels::page>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6" wire:poll.5s="loadSessions">
        {{-- Sessions List --}}
        <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 overflow-hidden" style="height: 600px; display: flex; flex-direction: column;">
            <div class="fi-section-header flex items-center gap-3 px-6 py-4 border-b border-gray-200 dark:border-white/10">
                <h3 class="fi-section-header-heading text-base font-semibold leading-6 text-gray-950 dark:text-white">Active Chats</h3>
            </div>
            <div class="divide-y divide-gray-200 dark:divide-white/10 flex-1 overflow-y-auto">
                @forelse($sessions as $session)
                    <button 
                        wire:click="selectSession({{ $session->id }})"
                        class="w-full p-4 text-left hover:bg-gray-50 dark:hover:bg-white/5 transition-colors {{ $selectedSession?->id === $session->id ? 'bg-primary-50 dark:bg-primary-500/10' : '' }}"
                    >
                        <div class="flex items-start justify-between">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    <p class="font-medium text-gray-950 dark:text-white truncate">{{ $session->visitor_name }}</p>
                                    @if($session->status === 'waiting')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-400">New</span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-500 dark:text-gray-400 truncate">{{ $session->visitor_email }}</p>
                                @if($session->latestMessage)
                                    <p class="text-sm text-gray-600 dark:text-gray-300 truncate mt-1">{{ Str::limit($session->latestMessage->message, 50) }}</p>
                                @endif
                            </div>
                            <div class="text-right flex-shrink-0 ml-2">
                                <p class="text-xs text-gray-400">{{ $session->last_message_at?->diffForHumans() ?? $session->started_at?->diffForHumans() }}</p>
                                @php
                                    $unread = $session->messages->where('sender_type', 'visitor')->where('is_read', false)->count();
                                @endphp
                                @if($unread > 0)
                                    <span class="inline-flex items-center justify-center w-5 h-5 bg-red-500 text-white text-xs rounded-full mt-1">{{ $unread }}</span>
                                @endif
                            </div>
                        </div>
                    </button>
                @empty
                    <div class="h-full flex items-center justify-center p-8">
                        <div class="text-center">
                            <div style="width: 64px; height: 64px;" class="mx-auto mb-4 text-gray-300 dark:text-gray-600">
                                <svg width="64" height="64" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 01-.825-.242m9.345-8.334a2.126 2.126 0 00-.476-.095 48.64 48.64 0 00-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0011.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155" />
                                </svg>
                            </div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">No active chats</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Chat Window --}}
        <div class="lg:col-span-2 fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 overflow-hidden flex flex-col" style="height: 600px;">
            @if($selectedSession)
                {{-- Chat Header --}}
                <div class="flex items-center justify-between gap-3 px-6 py-4 border-b border-gray-200 dark:border-white/10">
                    <div>
                        <h3 class="text-base font-semibold leading-6 text-gray-950 dark:text-white">{{ $selectedSession->visitor_name }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $selectedSession->visitor_email }} • {{ $selectedSession->visitor_phone ?? 'No phone' }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $selectedSession->status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-400' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-400' }}">
                            {{ ucfirst($selectedSession->status) }}
                        </span>
                        <button wire:click="closeSession" class="text-red-600 hover:text-red-500 text-sm font-medium">
                            Close Chat
                        </button>
                    </div>
                </div>

                {{-- Messages --}}
                <div class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50 dark:bg-gray-950" wire:poll.3s="loadMessages">
                    @foreach($messages as $message)
                        <div class="flex {{ $message->sender_type === 'visitor' ? 'justify-start' : 'justify-end' }}">
                            <div class="max-w-[70%] {{ $message->sender_type === 'visitor' ? 'bg-white dark:bg-gray-800 ring-1 ring-gray-950/5 dark:ring-white/10' : ($message->sender_type === 'system' ? 'bg-gray-200 dark:bg-gray-700 italic' : 'bg-blue-600 text-white') }} rounded-xl px-4 py-2 shadow-sm">
                                @if($message->sender_type === 'operator' && $message->user)
                                    <p class="text-xs text-blue-200 mb-1">{{ $message->user->name }}</p>
                                @elseif($message->sender_type === 'ai')
                                    <p class="text-xs text-blue-200 mb-1">🤖 AI Assistant</p>
                                @endif
                                <p class="text-sm whitespace-pre-wrap">{{ $message->message }}</p>
                                <p class="text-xs {{ $message->sender_type === 'visitor' ? 'text-gray-400' : ($message->sender_type === 'operator' || $message->sender_type === 'ai' ? 'text-blue-200' : 'text-gray-500') }} mt-1">
                                    {{ $message->created_at->format('H:i') }}
                                    @if(($message->sender_type === 'operator' || $message->sender_type === 'ai') && $message->is_read)
                                        <span class="ml-1">✓✓</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Reply Input --}}
                <div class="px-6 py-4 border-t border-gray-200 dark:border-white/10 bg-white dark:bg-gray-900">
                    <form wire:submit="sendReply" class="flex gap-2">
                        <input 
                            type="text" 
                            wire:model="replyMessage" 
                            placeholder="Type your reply..." 
                            class="block w-full rounded-lg border-gray-300 shadow-sm dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                        >
                        <button type="submit" class="inline-flex items-center justify-center gap-1 px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-500 transition-colors">
                            Send
                        </button>
                    </form>
                </div>
            @else
                <div class="flex-1 flex items-center justify-center text-gray-500 dark:text-gray-400">
                    <div class="text-center">
                        <div style="width: 80px; height: 80px;" class="mx-auto mb-4 text-gray-300 dark:text-gray-600">
                            <svg width="80" height="80" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 01-.825-.242m9.345-8.334a2.126 2.126 0 00-.476-.095 48.64 48.64 0 00-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0011.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155" />
                            </svg>
                        </div>
                        <p class="text-base text-gray-500 dark:text-gray-400">Select a chat to start responding</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-filament-panels::page>
