<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Create Backup Section --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold mb-4">Create New Backup</h2>
            
            <div class="flex items-end gap-4">
                <div class="flex-1">
                    <label class="block text-sm font-medium mb-2">Backup Type</label>
                    <select wire:model="selectedType" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                        <option value="full">Full (Database + Files)</option>
                        <option value="database">Database Only</option>
                        <option value="files">Files Only</option>
                    </select>
                </div>
                
                <x-filament::button wire:click="createBackup" color="primary">
                    <x-heroicon-o-plus class="w-5 h-5 mr-2"/>
                    Create Backup
                </x-filament::button>
            </div>

            <div class="mt-4 text-sm text-gray-600 dark:text-gray-400">
                <p><strong>Full:</strong> Backs up database and important files (storage, public assets)</p>
                <p><strong>Database:</strong> Backs up database only</p>
                <p><strong>Files:</strong> Backs up files only (storage, public)</p>
            </div>
        </div>

        {{-- Backups List --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold">Available Backups</h2>
            </div>

            @if(count($backups) > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Filename
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Size
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Created
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($backups as $backup)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-900">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <x-heroicon-o-archive-box class="w-5 h-5 text-gray-400 mr-3"/>
                                            <span class="font-mono text-sm">{{ $backup['filename'] }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                        {{ $backup['size'] }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                        {{ $backup['date'] }}
                                    </td>
                                    <td class="px-6 py-4 text-right space-x-2">
                                        <x-filament::button 
                                            wire:click="downloadBackup('{{ $backup['id'] }}')"
                                            color="gray"
                                            size="sm"
                                            outlined
                                        >
                                            <x-heroicon-o-arrow-down-tray class="w-4 h-4"/>
                                        </x-filament::button>

                                        <x-filament::button 
                                            wire:click="restoreBackup('{{ $backup['id'] }}')"
                                            wire:confirm="Are you sure? This will overwrite current data!"
                                            color="warning"
                                            size="sm"
                                            outlined
                                        >
                                            <x-heroicon-o-arrow-path class="w-4 h-4"/>
                                        </x-filament::button>

                                        <x-filament::button 
                                            wire:click="deleteBackup('{{ $backup['id'] }}')"
                                            wire:confirm="Are you sure you want to delete this backup?"
                                            color="danger"
                                            size="sm"
                                            outlined
                                        >
                                            <x-heroicon-o-trash class="w-4 h-4"/>
                                        </x-filament::button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-12 text-center text-gray-500 dark:text-gray-400">
                    <x-heroicon-o-archive-box class="w-16 h-16 mx-auto mb-4 opacity-50"/>
                    <p class="text-lg font-medium">No backups found</p>
                    <p class="text-sm">Create your first backup using the form above</p>
                </div>
            @endif
        </div>

        {{-- Info Box --}}
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
            <div class="flex">
                <x-heroicon-o-information-circle class="w-5 h-5 text-blue-500 mr-3 flex-shrink-0 mt-0.5"/>
                <div class="text-sm text-blue-700 dark:text-blue-300">
                    <p class="font-semibold mb-2">Backup Information:</p>
                    <ul class="list-disc list-inside space-y-1">
                        <li>Backups are stored in <code class="bg-blue-100 dark:bg-blue-900 px-1 rounded">storage/app/backups/</code></li>
                        <li>Download backups to keep them safe offline</li>
                        <li>Restore operation will overwrite current database and files</li>
                        <li>Always test restore in a development environment first</li>
                        <li>For production, you can also use the web backup script</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
