<!DOCTYPE html>
<html>
<head>
    <title>BMG Cache Fix</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #f5f5f5; }
        h1 { color: #333; border-bottom: 3px solid #28a745; padding-bottom: 10px; }
        pre { background: #fff; padding: 15px; border: 1px solid #ddd; overflow: auto; border-radius: 4px; }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
    </style>
</head>
<body>
    <h1>🔧 Laravel Cache Cleared</h1>
    <p><strong>Completed:</strong> {{ now()->format('Y-m-d H:i:s') }}</p>
    <hr>

    <h2>Results:</h2>
    <pre>
@foreach($results as $result)
{{ $result }}
@endforeach
    </pre>

    <hr>
    <h2>🔄 Next Steps</h2>
    <pre>
1. Wait 2-3 minutes for changes to take effect
2. Try accessing admin panel in incognito window:
   <a href="/admin">https://berkahmandiri.co.id/admin</a>
3. If still 403, check Imunify360 in cPanel
4. Delete diagnostic routes from routes/web.php
    </pre>

    <p><a href="{{ route('diagnostic', ['key' => 'bmg2024diagnosis']) }}">← Back to Diagnostic</a></p>
</body>
</html>
