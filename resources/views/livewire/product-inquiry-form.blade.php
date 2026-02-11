<div>
    @if($submitted)
        <div class="bg-green-50 border border-green-200 rounded-xl p-6 text-center">
            <svg class="w-16 h-16 text-green-500 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <h3 class="text-xl font-semibold text-green-800 mb-2">{{ __('Inquiry Sent Successfully!') }}</h3>
            <p class="text-green-700 mb-4">{{ __('Thank you for your interest. Our team will contact you soon.') }}</p>
            <button wire:click="resetForm" class="btn btn-outline text-green-700 border-green-300 hover:bg-green-100">
                {{ __('Send Another Inquiry') }}
            </button>
        </div>
    @else
        <form wire:submit="submit" class="space-y-6">
            {{-- Contact Information Section --}}
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-8 h-8 bg-primary-100 text-primary-600 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900">@if(app()->getLocale() === 'en') Contact Information @else Informasi Kontak @endif</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('Name') }} <span class="text-red-600">*</span></label>
                        <input type="text" id="name" wire:model.blur="name" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all @error('name') border-red-500 @enderror" placeholder="@if(app()->getLocale() === 'en') Your full name @else Nama lengkap Anda @endif">
                        @error('name') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('Email') }} <span class="text-red-600">*</span></label>
                        <input type="email" id="email" wire:model.blur="email" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all @error('email') border-red-500 @enderror" placeholder="email@example.com">
                        @error('email') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('Phone') }} <span class="text-red-600">*</span></label>
                        <input type="tel" id="phone" wire:model.blur="phone" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all @error('phone') border-red-500 @enderror" placeholder="08xxxxxxxxxx">
                        @error('phone') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="company" class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('Company') }}</label>
                        <input type="text" id="company" wire:model.blur="company" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all" placeholder="@if(app()->getLocale() === 'en') Company name (optional) @else Nama perusahaan (opsional) @endif">
                    </div>
                </div>
            </div>

            {{-- Quantity Section --}}
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-8 h-8 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900">{{ __('Quantity') }}</h3>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('Quantity') }} <span class="text-red-600">*</span></label>
                        <input type="number" id="quantity" wire:model.blur="quantity" min="1" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all @error('quantity') border-red-500 @enderror">
                        @error('quantity') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="unit" class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('Unit') }}</label>
                        <select id="unit" wire:model="unit" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all bg-white">
                            <option value="pcs">Pcs</option>
                            <option value="kg">Kg</option>
                            <option value="ton">Ton</option>
                            <option value="meter">Meter</option>
                            <option value="batang">Batang</option>
                            <option value="lonjor">Lonjor</option>
                            <option value="lembar">Lembar</option>
                            <option value="roll">Roll</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Message Section --}}
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-8 h-8 bg-secondary-100 text-secondary-600 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900">{{ __('Message') }}</h3>
                </div>
                <div>
                    <label for="subject" class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('Subject') }} <span class="text-red-600">*</span></label>
                    <input type="text" id="subject" wire:model.blur="subject" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all @error('subject') border-red-500 @enderror">
                    @error('subject') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
                <div class="mt-4">
                    <label for="message" class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('Message') }} <span class="text-red-600">*</span></label>
                    <textarea id="message" wire:model.blur="message" rows="4" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all @error('message') border-red-500 @enderror" placeholder="@if(app()->getLocale() === 'en') Tell us about your requirements... @else Ceritakan kebutuhan Anda... @endif"></textarea>
                    @error('message') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- Submit Button --}}
            <button type="submit" class="w-full bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white font-semibold py-4 px-6 rounded-xl transition-all duration-200 shadow-lg shadow-primary-500/25 hover:shadow-primary-500/40 flex items-center justify-center gap-2" wire:loading.attr="disabled">
                <span wire:loading.remove class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                    {{ __('Send Inquiry') }}
                </span>
                <span wire:loading class="flex items-center justify-center">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    {{ __('Sending...') }}
                </span>
            </button>
        </form>
    @endif
</div>
