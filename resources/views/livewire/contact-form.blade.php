<div>
    @if($submitted)
        <div class="bg-green-50 border border-green-200 rounded-xl p-6 text-center">
            <svg class="w-16 h-16 text-green-500 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <h3 class="text-xl font-semibold text-green-800 mb-2">
                @if(app()->getLocale() === 'en') Thank You! @else Terima Kasih! @endif
            </h3>
            <p class="text-green-700 mb-4">
                @if(app()->getLocale() === 'en')
                    Your message has been sent successfully. We will contact you soon.
                @else
                    Pesan Anda telah berhasil dikirim. Kami akan segera menghubungi Anda.
                @endif
            </p>
            <button wire:click="resetForm" class="btn btn-outline text-green-700 border-green-300 hover:bg-green-100">
                @if(app()->getLocale() === 'en') Send Another Message @else Kirim Pesan Lain @endif
            </button>
        </div>
    @else
        <form wire:submit="submit" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                        @if(app()->getLocale() === 'en') Full Name @else Nama Lengkap @endif
                        <span class="text-red-600">*</span>
                    </label>
                    <input type="text" id="name" wire:model="name" class="input w-full @error('name') border-red-500 @enderror" placeholder="@if(app()->getLocale() === 'en') Enter your name @else Masukkan nama Anda @endif">
                    @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                        @if(app()->getLocale() === 'en') Email Address @else Alamat Email @endif
                        <span class="text-red-600">*</span>
                    </label>
                    <input type="email" id="email" wire:model="email" class="input w-full @error('email') border-red-500 @enderror" placeholder="@if(app()->getLocale() === 'en') Enter your email @else Masukkan email Anda @endif">
                    @error('email') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- Phone & Company --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                        @if(app()->getLocale() === 'en') Phone Number @else Nomor Telepon @endif
                        <span class="text-red-600">*</span>
                    </label>
                    <input type="tel" id="phone" wire:model="phone" class="input w-full @error('phone') border-red-500 @enderror" placeholder="@if(app()->getLocale() === 'en') Enter your phone number @else Masukkan nomor telepon Anda @endif">
                    @error('phone') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="company" class="block text-sm font-medium text-gray-700 mb-1">
                        @if(app()->getLocale() === 'en') Company @else Perusahaan @endif
                    </label>
                    <input type="text" id="company" wire:model="company" class="input w-full" placeholder="@if(app()->getLocale() === 'en') Enter your company name @else Masukkan nama perusahaan Anda @endif">
                </div>
            </div>

            {{-- Subject Type --}}
            <div>
                <label for="subject_type" class="block text-sm font-medium text-gray-700 mb-1">
                    @if(app()->getLocale() === 'en') Subject Type @else Tipe Subjek @endif
                    <span class="text-red-600">*</span>
                </label>
                <select id="subject_type" wire:model="subject_type" class="input w-full @error('subject_type') border-red-500 @enderror">
                    @foreach($subjectTypes as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
                @error('subject_type') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            {{-- Subject --}}
            <div>
                <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">
                    @if(app()->getLocale() === 'en') Subject @else Subjek @endif
                    <span class="text-red-600">*</span>
                </label>
                <input type="text" id="subject" wire:model="subject" class="input w-full @error('subject') border-red-500 @enderror" placeholder="@if(app()->getLocale() === 'en') Enter your subject @else Masukkan subjek Anda @endif">
                @error('subject') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            {{-- Message --}}
            <div>
                <label for="message" class="block text-sm font-medium text-gray-700 mb-1">
                    @if(app()->getLocale() === 'en') Message @else Pesan @endif
                    <span class="text-red-600">*</span>
                </label>
                <textarea id="message" wire:model="message" rows="5" class="input w-full @error('message') border-red-500 @enderror" placeholder="@if(app()->getLocale() === 'en') Write your message here... (minimum 20 characters) @else Tulis pesan Anda di sini... (minimal 20 karakter) @endif"></textarea>
                @error('message') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            {{-- Submit Button --}}
            <button type="submit" class="btn btn-primary w-full py-3" wire:loading.attr="disabled">
                <span wire:loading.remove>
                    @if(app()->getLocale() === 'en') Send Message @else Kirim Pesan @endif
                </span>
                <span wire:loading class="flex items-center justify-center">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    @if(app()->getLocale() === 'en') Sending... @else Mengirim... @endif
                </span>
            </button>

            {{-- Privacy Notice --}}
            <p class="text-sm text-gray-500 text-center">
                @if(app()->getLocale() === 'en')
                    By submitting this form, you agree to our
                @else
                    Dengan mengirimkan formulir ini, Anda menyetujui
                @endif
                <a href="{{ route('privacy') }}" class="text-primary-600 hover:text-primary-700">
                    @if(app()->getLocale() === 'en') Privacy Policy @else Kebijakan Privasi @endif
                </a>@if(app()->getLocale() === 'en').@else  kami.@endif
            </p>
        </form>
    @endif
</div>
