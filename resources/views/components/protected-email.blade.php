@props([
    'email' => '',
    'class' => '',
    'showIcon' => false,
    'iconClass' => 'w-4 h-4 mr-2'
])

@php
    $parts = explode('@', $email);
    $user = $parts[0] ?? '';
    $domain = $parts[1] ?? '';
    $uniqueId = 'email-' . uniqid();
@endphp

<a 
    href="javascript:void(0)" 
    id="{{ $uniqueId }}"
    class="protected-email {{ $class }}"
    data-u="{{ base64_encode($user) }}"
    data-d="{{ base64_encode($domain) }}"
    onclick="return false;"
>
    @if($showIcon)
    <svg class="{{ $iconClass }} inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
    </svg>
    @endif
    <span class="email-text">[{{ __('Loading...') }}]</span>
</a>
