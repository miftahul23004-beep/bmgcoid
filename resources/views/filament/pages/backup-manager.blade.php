<x-filament-panels::page>
    @php $backups = $this->getBackups(); @endphp
    
    {{-- Stats Overview --}}
    <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 mb-6">
        <div class="grid gap-6 p-6 md:grid-cols-3">
            <div class="flex items-center gap-x-3">
                <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-primary-50 dark:bg-primary-500/10">
                    <x-filament::icon icon="heroicon-o-archive-box" class="w-6 h-6 text-primary-600 dark:text-primary-400"/>
                </div>
                <div>
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Backups</div>
                    <div class="text-2xl font-semibold">{{ count($backups) }}</div>
                </div>
            </div>
            
            <div class="flex items-center gap-x-3">
                <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-success-50 dark:bg-success-500/10">
                    <x-filament::icon icon="heroicon-o-server-stack" class="w-6 h-6 text-success-600 dark:text-success-400"/>
                </div>
                <div>
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Size</div>
                    <div class="text-2xl font-semibold">{{ $this->getTotalSize() }}</div>
                </div>
            </div>
            
            <div class="flex items-center gap-x-3">
                <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-warning-50 dark:bg-warning-500/10">
                    <x-filament::icon icon="heroicon-o-clock" class="w-6 h-6 text-warning-600 dark:text-warning-400"/>
                </div>
                <div>
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Last Backup</div>
                    <div class="text-lg font-semibold">
                        {{ count($backups) > 0 ? \Carbon\Carbon::parse($backups[0]['created_at'])->diffForHumans() : 'Never' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Create Backup Section --}}
    <x-filament::section class="mb-6">
        <x-slot name="heading">
            <div class="flex items-center gap-2">
                <x-filament::icon icon="heroicon-o-plus-circle" class="w-5 h-5"/>
                Create New Backup
            </div>
        </x-slot>
        
        <x-slot name="description">
            Select backup type and create a new backup of your data
        </x-slot>
        
        <div class="flex flex-col sm:flex-row gap-4 items-end">
            <div class="flex-1 w-full">
                <x-filament::input.wrapper>
                    <x-filament::input.select wire:model="selectedType">
                        <option value="full">🗄️ Full Backup (Database + Files)</option>
                        <option value="database">💾 Database Only</option>
                        <option value="files">📁 Files Only</option>
                    </x-filament::input.select>
                </x-filament::input.wrapper>
            </div>
            
            <x-filament::button 
                wire:click="createBackup"
                icon="heroicon-o-arrow-up-on-square"
                wire:loading.attr="disabled"
            >
                <span wire:loading.remove wire:target="createBackup">Create Backup</span>
                <span wire:loading wire:target="createBackup">Creating...</span>
            </x-filament::button>

            <x-filament::button 
                wire:click="cleanupBackups"
                color="gray"
                icon="heroicon-o-trash"
                wire:loading.attr="disabled"
            >
                <span wire:loading.remove wire:target="cleanupBackups">Cleanup Old</span>
                <span wire:loading wire:target="cleanupBackups">Cleaning...</span>
            </x-filament::button>
        </div>
    </x-filament::section>

    {{-- Backups List --}}
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center gap-2">
                <x-filament::icon icon="heroicon-o-folder-open" class="w-5 h-5"/>
                Backup History
            </div>
        </x-slot>
        
        <x-slot name="description">
            {{ count($backups) }} backup(s) available for download
        </x-slot>

        @if(count($backups) > 0)
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($backups as $index => $backup)
                    <div class="py-4 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                        <div class="flex items-center gap-4">
                            {{-- Icon --}}
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-primary-50 dark:bg-primary-500/10">
                                    <x-filament::icon icon="heroicon-o-archive-box" class="w-6 h-6 text-primary-600 dark:text-primary-400"/>
                                </div>
                            </div>

                            {{-- Info --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <h4 class="font-mono text-sm font-semibold truncate" title="{{ $backup['filename'] }}">
                                        {{ $backup['filename'] }}
                                    </h4>
                                    @if($index === 0)
                                        <x-filament::badge color="success" size="sm">Latest</x-filament::badge>
                                    @endif
                                    <x-filament::badge :color="$backup['type_color']" size="sm">
                                        {{ ucfirst($backup['type']) }}
                                    </x-filament::badge>
                                </div>
                                
                                <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-sm text-gray-500 dark:text-gray-400">
                                    <div class="flex items-center gap-1.5">
                                        <x-filament::icon icon="heroicon-o-server" class="w-4 h-4"/>
                                        <span class="font-medium">{{ $backup['size'] }}</span>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <x-filament::icon icon="heroicon-o-calendar" class="w-4 h-4"/>
                                        <span>{{ \Carbon\Carbon::parse($backup['created_at'])->format('M d, Y') }}</span>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <x-filament::icon icon="heroicon-o-clock" class="w-4 h-4"/>
                                        <span>{{ \Carbon\Carbon::parse($backup['created_at'])->format('H:i:s') }}</span>
                                    </div>
                                    <div class="flex items-center gap-1.5 text-gray-400">
                                        <x-filament::icon icon="heroicon-o-clock" class="w-4 h-4"/>
                                        <span>{{ \Carbon\Carbon::parse($backup['created_at'])->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="flex-shrink-0 flex items-center gap-2">
                                <x-filament::button
                                    tag="a"
                                    :href="route('backup.download', ['file' => $backup['filename']])"
                                    target="_blank"
                                    size="sm"
                                    icon="heroicon-o-arrow-down-tray"
                                >
                                    Download
                                </x-filament::button>
                                
                                <x-filament::icon-button
                                    icon="heroicon-o-trash"
                                    color="danger"
                                    size="sm"
                                    wire:click="deleteBackup('{{ $backup['filename'] }}')"
                                    wire:confirm="Are you sure you want to delete this backup?"
                                    tooltip="Delete"
                                />
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <x-filament::icon icon="heroicon-o-archive-box" class="mx-auto h-12 w-12 text-gray-400 mb-4"/>
                <h3 class="text-lg font-medium mb-2">No backups yet</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                    Create your first backup using the form above
                </p>
            </div>
        @endif
    </x-filament::section>

    {{-- Info --}}
    <x-filament::section icon="heroicon-o-information-circle" icon-color="info">
        <x-slot name="heading">Backup Information</x-slot>
        <x-slot name="description">Important details about the backup system</x-slot>
        
        <ul class="space-y-2 text-sm">
            <li class="flex gap-2">
                <x-filament::icon icon="heroicon-o-check-circle" class="w-5 h-5 text-success-500 flex-shrink-0"/>
                <span>Backups stored in <code class="px-1 py-0.5 rounded bg-gray-100 dark:bg-gray-800 text-xs">storage/app/backups/</code></span>
            </li>
            <li class="flex gap-2">
                <x-filament::icon icon="heroicon-o-check-circle" class="w-5 h-5 text-success-500 flex-shrink-0"/>
                <span>Database backed up using mysqldump for reliability</span>
            </li>
            <li class="flex gap-2">
                <x-filament::icon icon="heroicon-o-check-circle" class="w-5 h-5 text-success-500 flex-shrink-0"/>
                <span>Download backups to keep them safe offline</span>
            </li>
            <li class="flex gap-2">
                <x-filament::icon icon="heroicon-o-check-circle" class="w-5 h-5 text-success-500 flex-shrink-0"/>
                <span>Powered by Spatie Laravel Backup</span>
            </li>
        </ul>
    </x-filament::section>
</x-filament-panels::page>

