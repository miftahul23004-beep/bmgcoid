<x-filament-panels::page>
    {{-- Connection Status --}}
    @if($connectionStatus)
    <div class="mb-4">
        @if($connectionStatus === 'connected')
        <x-filament::section>
            <div class="flex items-center gap-2 text-success-600">
                <x-heroicon-o-check-circle class="w-5 h-5" />
                <span class="font-medium">FTP Connected - berkahmandiri.co.id</span>
            </div>
        </x-filament::section>
        @else
        <x-filament::section>
            <div class="flex items-center gap-2 text-danger-600">
                <x-heroicon-o-x-circle class="w-5 h-5" />
                <span class="font-medium">FTP Connection Failed</span>
            </div>
        </x-filament::section>
        @endif
    </div>
    @endif

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <x-filament::section>
            <div class="text-center">
                <div class="text-3xl font-bold text-primary-600">{{ count($changedFiles) }}</div>
                <div class="text-sm text-gray-500">Total File Berubah</div>
            </div>
        </x-filament::section>

        <x-filament::section>
            <div class="text-center">
                <div class="text-3xl font-bold text-success-600">
                    {{ count(array_filter($changedFiles, fn($f) => $f['status'] === 'new')) }}
                </div>
                <div class="text-sm text-gray-500">File Baru</div>
            </div>
        </x-filament::section>

        <x-filament::section>
            <div class="text-center">
                <div class="text-3xl font-bold text-warning-600">
                    {{ count(array_filter($changedFiles, fn($f) => $f['status'] === 'modified')) }}
                </div>
                <div class="text-sm text-gray-500">File Dimodifikasi</div>
            </div>
        </x-filament::section>

        <x-filament::section>
            <div class="text-center">
                <div class="text-3xl font-bold text-info-600">{{ count($selectedFiles) }}</div>
                <div class="text-sm text-gray-500">File Dipilih</div>
            </div>
        </x-filament::section>
    </div>

    {{-- FTP Info --}}
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center gap-2">
                <x-heroicon-o-server class="w-5 h-5" />
                Informasi FTP Server
            </div>
        </x-slot>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
            <div>
                <span class="text-gray-500">Host:</span>
                <span class="font-medium ml-1">berkahmandiri.co.id</span>
            </div>
            <div>
                <span class="text-gray-500">Port:</span>
                <span class="font-medium ml-1">21</span>
            </div>
            <div>
                <span class="text-gray-500">User:</span>
                <span class="font-medium ml-1">admin@berkahmandiri.co.id</span>
            </div>
            <div>
                <span class="text-gray-500">Remote Path:</span>
                <span class="font-medium ml-1">/public_html/berkahmandiri.co.id</span>
            </div>
        </div>
    </x-filament::section>

    {{-- Selection Actions --}}
    @if(count($changedFiles) > 0)
    <div class="flex items-center gap-2 mb-4">
        <x-filament::button size="sm" color="gray" wire:click="selectAll">
            <x-heroicon-m-check class="w-4 h-4 mr-1" />
            Pilih Semua
        </x-filament::button>
        <x-filament::button size="sm" color="gray" wire:click="deselectAll">
            <x-heroicon-m-x-mark class="w-4 h-4 mr-1" />
            Batal Pilih
        </x-filament::button>
        <span class="text-sm text-gray-500 ml-4">
            {{ count($selectedFiles) }} dari {{ count($changedFiles) }} file dipilih
        </span>
    </div>
    @endif

    {{-- File List --}}
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center gap-2">
                <x-heroicon-o-document-text class="w-5 h-5" />
                Daftar File yang Berubah
            </div>
        </x-slot>

        @if(count($changedFiles) === 0)
        <div class="text-center py-8 text-gray-500">
            <x-heroicon-o-check-circle class="w-12 h-12 mx-auto mb-2 text-success-500" />
            <p class="font-medium">Semua file sudah tersinkronisasi!</p>
            <p class="text-sm">Tidak ada file baru atau yang dimodifikasi.</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th class="px-4 py-3 text-left w-10">
                            <input type="checkbox" 
                                   class="rounded border-gray-300 text-primary-600 focus:ring-primary-500"
                                   wire:click="selectAll"
                                   @checked(count($selectedFiles) === count($changedFiles))>
                        </th>
                        <th class="px-4 py-3 text-left">File Path</th>
                        <th class="px-4 py-3 text-left w-24">Status</th>
                        <th class="px-4 py-3 text-left w-24">Ukuran</th>
                        <th class="px-4 py-3 text-left w-40">Terakhir Diubah</th>
                        <th class="px-4 py-3 text-center w-24">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($changedFiles as $path => $file)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                        <td class="px-4 py-3">
                            <input type="checkbox" 
                                   class="rounded border-gray-300 text-primary-600 focus:ring-primary-500"
                                   wire:click="toggleFile('{{ $path }}')"
                                   @checked(in_array($path, $selectedFiles))>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                @if(str_ends_with($path, '.php'))
                                    <x-heroicon-o-code-bracket class="w-4 h-4 text-purple-500 flex-shrink-0" />
                                @elseif(str_ends_with($path, '.blade.php'))
                                    <x-heroicon-o-document-text class="w-4 h-4 text-orange-500 flex-shrink-0" />
                                @elseif(str_ends_with($path, '.js'))
                                    <x-heroicon-o-code-bracket-square class="w-4 h-4 text-yellow-500 flex-shrink-0" />
                                @elseif(str_ends_with($path, '.css'))
                                    <x-heroicon-o-paint-brush class="w-4 h-4 text-blue-500 flex-shrink-0" />
                                @elseif(str_ends_with($path, '.json'))
                                    <x-heroicon-o-document class="w-4 h-4 text-green-500 flex-shrink-0" />
                                @else
                                    <x-heroicon-o-document class="w-4 h-4 text-gray-400 flex-shrink-0" />
                                @endif
                                <code class="text-xs break-all">{{ $path }}</code>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            @if($file['status'] === 'new')
                                <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium rounded-full bg-success-100 text-success-700 dark:bg-success-500/20 dark:text-success-400">
                                    <x-heroicon-m-plus class="w-3 h-3" />
                                    Baru
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium rounded-full bg-warning-100 text-warning-700 dark:bg-warning-500/20 dark:text-warning-400">
                                    <x-heroicon-m-pencil class="w-3 h-3" />
                                    Diubah
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-500">
                            {{ $file['formatted_size'] }}
                        </td>
                        <td class="px-4 py-3 text-gray-500 text-xs">
                            {{ $file['formatted_date'] }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            <x-filament::button 
                                size="xs" 
                                color="primary"
                                wire:click="syncSingleFile('{{ $path }}')"
                                wire:loading.attr="disabled"
                            >
                                <x-heroicon-m-cloud-arrow-up class="w-3 h-3" />
                            </x-filament::button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </x-filament::section>

    {{-- Sync Logs --}}
    @if(count($syncLogs) > 0)
    <x-filament::section class="mt-6">
        <x-slot name="heading">
            <div class="flex items-center gap-2">
                <x-heroicon-o-command-line class="w-5 h-5" />
                Log Sinkronisasi
            </div>
        </x-slot>

        <div class="bg-gray-900 rounded-lg p-4 font-mono text-sm max-h-64 overflow-y-auto">
            @foreach($syncLogs as $log)
            <div class="flex items-start gap-2 mb-1">
                <span class="text-gray-500">[{{ $log['time'] }}]</span>
                @if($log['type'] === 'success')
                    <span class="text-success-400">✓</span>
                    <span class="text-success-300">{{ $log['message'] }}</span>
                @elseif($log['type'] === 'error')
                    <span class="text-danger-400">✗</span>
                    <span class="text-danger-300">{{ $log['message'] }}</span>
                @else
                    <span class="text-gray-400">→</span>
                    <span class="text-gray-300">{{ $log['message'] }}</span>
                @endif
            </div>
            @endforeach
        </div>
    </x-filament::section>
    @endif

    {{-- Loading Overlay --}}
    <div wire:loading.flex wire:target="syncFiles,syncSingleFile" class="fixed inset-0 bg-black/50 z-50 items-center justify-center">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-8 text-center shadow-2xl">
            <x-filament::loading-indicator class="w-12 h-12 mx-auto text-primary-500" />
            <p class="mt-4 font-medium text-gray-700 dark:text-gray-300">Mengupload file ke server...</p>
            <p class="text-sm text-gray-500">Mohon tunggu, jangan tutup halaman ini.</p>
        </div>
    </div>
</x-filament-panels::page>
