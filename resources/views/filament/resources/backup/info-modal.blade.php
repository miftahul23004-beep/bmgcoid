<div class="space-y-4">
    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
        <h3 class="font-semibold text-blue-900 dark:text-blue-100 mb-2">Backup Types</h3>
        <ul class="space-y-2 text-sm text-blue-800 dark:text-blue-200">
            <li><strong>Full Backup:</strong> Database + Files (storage, public assets, .env)</li>
            <li><strong>Database Only:</strong> MySQL database dump</li>
            <li><strong>Files Only:</strong> Storage and public files</li>
        </ul>
    </div>

    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
        <h3 class="font-semibold mb-2">Storage Location</h3>
        <code class="text-sm bg-gray-100 dark:bg-gray-900 px-2 py-1 rounded">storage/app/backups/</code>
    </div>

    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
        <h3 class="font-semibold mb-2">What Gets Backed Up?</h3>
        <ul class="list-disc list-inside space-y-1 text-sm">
            <li>Database tables and data</li>
            <li>Uploaded files (storage/app/public/)</li>
            <li>Public assets (build, images, fonts)</li>
            <li>Environment configuration (.env)</li>
        </ul>
    </div>

    <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg p-4">
        <h3 class="font-semibold text-amber-900 dark:text-amber-100 mb-2">⚠️ Important Notes</h3>
        <ul class="space-y-1 text-sm text-amber-800 dark:text-amber-200">
            <li>Restore operation will overwrite current data</li>
            <li>Always test restore in development first</li>
            <li>Download backups regularly for offline storage</li>
            <li>Keep multiple backup copies in different locations</li>
        </ul>
    </div>

    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
        <h3 class="font-semibold mb-2">Command Line Usage</h3>
        <div class="space-y-2 text-sm font-mono">
            <div>
                <div class="text-gray-600 dark:text-gray-400">Create backup:</div>
                <code class="bg-gray-100 dark:bg-gray-900 px-2 py-1 rounded block">php artisan backup:create --type=full</code>
            </div>
            <div>
                <div class="text-gray-600 dark:text-gray-400">List backups:</div>
                <code class="bg-gray-100 dark:bg-gray-900 px-2 py-1 rounded block">php artisan backup:list</code>
            </div>
            <div>
                <div class="text-gray-600 dark:text-gray-400">Restore backup:</div>
                <code class="bg-gray-100 dark:bg-gray-900 px-2 py-1 rounded block">php artisan backup:restore backup_name</code>
            </div>
        </div>
    </div>

    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
        <h3 class="font-semibold mb-2">Web Interface (Production)</h3>
        <div class="text-sm">
            <p class="mb-2">For production servers, you can also use:</p>
            <code class="bg-gray-100 dark:bg-gray-900 px-2 py-1 rounded block break-all">
                https://yoursite.com/backup.php?key=SECRET&action=list
            </code>
            <p class="text-gray-600 dark:text-gray-400 mt-2 text-xs">
                See BACKUP_README.md for complete documentation
            </p>
        </div>
    </div>
</div>
