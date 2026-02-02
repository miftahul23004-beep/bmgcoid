{{-- Language Switcher --}}
<div x-data="{ open: false }" class="relative">
    <button 
        @click="open = !open"
        @click.away="open = false"
        class="flex items-center gap-2 px-3 py-2 text-gray-700 hover:text-primary-600 transition-colors"
        aria-label="Change Language"
    >
        @if(app()->getLocale() === 'id')
            <svg class="w-5 h-5" viewBox="0 0 640 480" xmlns="http://www.w3.org/2000/svg">
                <path fill="#e70011" d="M0 0h640v240H0z"/>
                <path fill="#fff" d="M0 240h640v240H0z"/>
            </svg>
            <span class="text-sm font-medium hidden lg:inline">ID</span>
        @else
            <svg class="w-5 h-5" viewBox="0 0 640 480" xmlns="http://www.w3.org/2000/svg">
                <path fill="#012169" d="M0 0h640v480H0z"/>
                <path fill="#FFF" d="m75 0 244 181L562 0h78v62L400 241l240 178v61h-80L320 301 81 480H0v-60l239-178L0 64V0h75z"/>
                <path fill="#C8102E" d="m424 281 216 159v40L369 281h55zm-184 20 6 35L54 480H0l240-179zM640 0v3L391 191l2-44L590 0h50zM0 0l239 176h-60L0 42V0z"/>
                <path fill="#FFF" d="M241 0v480h160V0H241zM0 160v160h640V160H0z"/>
                <path fill="#C8102E" d="M0 193v96h640v-96H0zM273 0v480h96V0h-96z"/>
            </svg>
            <span class="text-sm font-medium hidden lg:inline">EN</span>
        @endif
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>

    <div 
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50 border border-gray-100"
        style="display: none;"
    >
        <a href="{{ route('language.switch', 'id') }}" class="flex items-center gap-3 px-4 py-2 hover:bg-primary-50 transition-colors {{ app()->getLocale() === 'id' ? 'bg-primary-50 text-primary-600' : 'text-gray-700' }}">
            <svg class="w-6 h-6" viewBox="0 0 640 480" xmlns="http://www.w3.org/2000/svg">
                <path fill="#e70011" d="M0 0h640v240H0z"/>
                <path fill="#fff" d="M0 240h640v240H0z"/>
            </svg>
            <div class="flex-1">
                <div class="font-medium">Indonesia</div>
                <div class="text-xs text-gray-500">Bahasa Indonesia</div>
            </div>
            @if(app()->getLocale() === 'id')
                <svg class="w-5 h-5 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
            @endif
        </a>
        <a href="{{ route('language.switch', 'en') }}" class="flex items-center gap-3 px-4 py-2 hover:bg-primary-50 transition-colors {{ app()->getLocale() === 'en' ? 'bg-primary-50 text-primary-600' : 'text-gray-700' }}">
            <svg class="w-6 h-6" viewBox="0 0 640 480" xmlns="http://www.w3.org/2000/svg">
                <path fill="#012169" d="M0 0h640v480H0z"/>
                <path fill="#FFF" d="m75 0 244 181L562 0h78v62L400 241l240 178v61h-80L320 301 81 480H0v-60l239-178L0 64V0h75z"/>
                <path fill="#C8102E" d="m424 281 216 159v40L369 281h55zm-184 20 6 35L54 480H0l240-179zM640 0v3L391 191l2-44L590 0h50zM0 0l239 176h-60L0 42V0z"/>
                <path fill="#FFF" d="M241 0v480h160V0H241zM0 160v160h640V160H0z"/>
                <path fill="#C8102E" d="M0 193v96h640v-96H0zM273 0v480h96V0h-96z"/>
            </svg>
            <div class="flex-1">
                <div class="font-medium">English</div>
                <div class="text-xs text-gray-500">English</div>
            </div>
            @if(app()->getLocale() === 'en')
                <svg class="w-5 h-5 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
            @endif
        </a>
    </div>
</div>
