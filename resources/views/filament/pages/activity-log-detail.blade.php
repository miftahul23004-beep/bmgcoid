<div class="space-y-4">
    <div class="grid grid-cols-2 gap-4">
        <div>
            <h4 class="font-medium text-gray-500 dark:text-gray-400">Date</h4>
            <p class="text-gray-900 dark:text-white">{{ $activity->created_at->format('d M Y H:i:s') }}</p>
        </div>
        <div>
            <h4 class="font-medium text-gray-500 dark:text-gray-400">User</h4>
            <p class="text-gray-900 dark:text-white">{{ $activity->causer?->name ?? 'System' }}</p>
        </div>
        <div>
            <h4 class="font-medium text-gray-500 dark:text-gray-400">Action</h4>
            <p class="text-gray-900 dark:text-white">{{ ucfirst($activity->event ?? $activity->description) }}</p>
        </div>
        <div>
            <h4 class="font-medium text-gray-500 dark:text-gray-400">Model</h4>
            <p class="text-gray-900 dark:text-white">{{ class_basename($activity->subject_type ?? '-') }} #{{ $activity->subject_id ?? '-' }}</p>
        </div>
    </div>

    @if($activity->properties && $activity->properties->count() > 0)
        <div class="border-t pt-4 dark:border-gray-700">
            <h4 class="font-medium text-gray-500 dark:text-gray-400 mb-2">Changes</h4>
            
            @if($activity->properties->has('old') && $activity->properties->has('attributes'))
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <h5 class="text-sm font-medium text-red-600 dark:text-red-400 mb-1">Old Values</h5>
                        <pre class="text-xs bg-red-50 dark:bg-red-900/20 p-2 rounded overflow-auto max-h-60">{{ json_encode($activity->properties['old'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                    </div>
                    <div>
                        <h5 class="text-sm font-medium text-green-600 dark:text-green-400 mb-1">New Values</h5>
                        <pre class="text-xs bg-green-50 dark:bg-green-900/20 p-2 rounded overflow-auto max-h-60">{{ json_encode($activity->properties['attributes'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                    </div>
                </div>
            @else
                <pre class="text-xs bg-gray-50 dark:bg-gray-800 p-2 rounded overflow-auto max-h-60">{{ json_encode($activity->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
            @endif
        </div>
    @endif
</div>
