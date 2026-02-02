<!DOCTYPE html>
<html>
<head>
    <title>BMG System Diagnostic</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #f5f5f5; }
        h1 { color: #333; border-bottom: 3px solid #007bff; padding-bottom: 10px; }
        h2 { color: #555; border-bottom: 2px solid #007bff; padding-bottom: 5px; margin-top: 30px; }
        pre { background: #fff; padding: 15px; border: 1px solid #ddd; overflow: auto; border-radius: 4px; }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
        .warning { color: orange; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; background: #fff; margin: 10px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #007bff; color: white; }
        .badge { padding: 3px 8px; border-radius: 3px; font-size: 12px; }
        .badge-success { background: #28a745; color: white; }
        .badge-danger { background: #dc3545; color: white; }
        .badge-warning { background: #ffc107; color: #333; }
    </style>
</head>
<body>
    <h1>🔍 BMG System Diagnostic Report</h1>
    <p><strong>Generated:</strong> {{ now()->format('Y-m-d H:i:s') }}</p>
    <p><strong>⚠️ Security:</strong> Remove this route after diagnosis!</p>
    <hr>

    <h2>1. PHP & Laravel Environment</h2>
    <table>
        <tr><th>Property</th><th>Value</th></tr>
        <tr><td>PHP Version</td><td>{{ $data['php_version'] }}</td></tr>
        <tr><td>Laravel Version</td><td>{{ $data['laravel_version'] }}</td></tr>
        <tr><td>Server Software</td><td>{{ $data['server_software'] }}</td></tr>
        <tr><td>APP_ENV</td><td><span class="badge badge-{{ $data['app_env'] === 'production' ? 'success' : 'warning' }}">{{ $data['app_env'] }}</span></td></tr>
        <tr><td>APP_DEBUG</td><td><span class="badge badge-{{ $data['app_debug'] ? 'danger' : 'success' }}">{{ $data['app_debug'] ? 'ON (Dangerous!)' : 'OFF' }}</span></td></tr>
        <tr><td>APP_URL</td><td>{{ $data['app_url'] }}</td></tr>
    </table>

    <h2>2. File Permissions</h2>
    <table>
        <tr><th>Directory</th><th>Exists</th><th>Writable</th><th>Permissions</th></tr>
        @foreach($data['permissions'] as $dir => $perm)
        <tr>
            <td>{{ $dir }}</td>
            <td>
                @if($perm['exists'])
                    <span class="success">✓ Yes</span>
                @else
                    <span class="error">✗ No</span>
                @endif
            </td>
            <td>
                @if($perm['exists'] && $perm['writable'])
                    <span class="success">✓ Writable</span>
                @elseif($perm['exists'])
                    <span class="error">✗ Not Writable</span>
                @else
                    <span class="error">N/A</span>
                @endif
            </td>
            <td>{{ $perm['exists'] ? $perm['permissions'] : 'N/A' }}</td>
        </tr>
        @endforeach
    </table>

    <h2>3. Cache Status</h2>
    <table>
        <tr><th>Cache Type</th><th>Status</th></tr>
        <tr>
            <td>Config Cache</td>
            <td>
                @if($data['cache_files']['config'])
                    <span class="success">✓ Cached</span>
                @else
                    <span class="warning">⚠ Not Cached</span>
                @endif
            </td>
        </tr>
        <tr>
            <td>Route Cache</td>
            <td>
                @if($data['cache_files']['routes'])
                    <span class="success">✓ Cached</span>
                @else
                    <span class="warning">⚠ Not Cached</span>
                @endif
            </td>
        </tr>
        <tr>
            <td>Services Cache</td>
            <td>
                @if($data['cache_files']['services'])
                    <span class="success">✓ Cached</span>
                @else
                    <span class="warning">⚠ Not Cached</span>
                @endif
            </td>
        </tr>
    </table>

    <h2>4. Database Connection</h2>
    <pre>{{ $data['database'] }}</pre>

    <h2>5. Session Configuration</h2>
    <table>
        <tr><th>Setting</th><th>Value</th></tr>
        @foreach($data['session'] as $key => $value)
        <tr>
            <td>{{ $key }}</td>
            <td>{{ $value === null ? 'null' : ($value === true ? 'true' : ($value === false ? 'false' : $value)) }}</td>
        </tr>
        @endforeach
    </table>

    <h2>6. Routes Check</h2>
    <pre>
Admin Routes Registered: 
@if($data['admin_routes_registered'])
<span class="success">✓ YES</span>
@else
<span class="error">✗ NO - Filament routes may not be loaded!</span>
@endif

@if(isset($data['routes_error']))
<span class="error">Error checking routes: {{ $data['routes_error'] }}</span>
@endif
    </pre>

    <h2>7. Recent Laravel Logs (Last 30 lines)</h2>
    <pre>{{ implode('', array_slice($data['recent_logs'], 0, 30)) }}</pre>

    <hr>
    <h2>🔧 Actions</h2>
    <p>
        <a href="{{ route('diagnostic.fix', ['key' => 'bmg2024fix']) }}" style="display: inline-block; padding: 10px 20px; background: #28a745; color: white; text-decoration: none; border-radius: 4px;">
            Clear All Caches
        </a>
    </p>

    <hr>
    <p><strong>⚠️ IMPORTANT:</strong> Delete diagnostic routes from web.php after troubleshooting!</p>
</body>
</html>
