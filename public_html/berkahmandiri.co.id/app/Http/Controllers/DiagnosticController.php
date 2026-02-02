<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class DiagnosticController extends Controller
{
    public function index(Request $request)
    {
        // Security key
        if ($request->get('key') !== 'bmg2024diagnosis') {
            abort(403, 'Invalid diagnostic key');
        }

        $data = [];
        
        // 1. PHP & Server Info
        $data['php_version'] = PHP_VERSION;
        $data['server_software'] = $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown';
        $data['laravel_version'] = app()->version();
        $data['app_env'] = config('app.env');
        $data['app_debug'] = config('app.debug');
        $data['app_url'] = config('app.url');
        
        // 2. File Permissions
        $data['permissions'] = [
            'storage' => $this->getPermissions(storage_path()),
            'storage/logs' => $this->getPermissions(storage_path('logs')),
            'storage/framework/cache' => $this->getPermissions(storage_path('framework/cache')),
            'storage/framework/sessions' => $this->getPermissions(storage_path('framework/sessions')),
            'storage/framework/views' => $this->getPermissions(storage_path('framework/views')),
            'bootstrap/cache' => $this->getPermissions(base_path('bootstrap/cache')),
        ];
        
        // 3. Cache Status
        $data['cache_files'] = [
            'config' => file_exists(base_path('bootstrap/cache/config.php')),
            'routes' => file_exists(base_path('bootstrap/cache/routes-v7.php')),
            'services' => file_exists(base_path('bootstrap/cache/services.php')),
        ];
        
        // 4. Database Connection
        try {
            DB::connection()->getPdo();
            $data['database'] = 'Connected ✓';
        } catch (\Exception $e) {
            $data['database'] = 'Error: ' . $e->getMessage();
        }
        
        // 5. Session Config
        $data['session'] = [
            'driver' => config('session.driver'),
            'domain' => config('session.domain'),
            'secure' => config('session.secure'),
            'same_site' => config('session.same_site'),
        ];
        
        // 6. Recent Logs
        $logFile = storage_path('logs/laravel.log');
        if (file_exists($logFile)) {
            $logs = file($logFile);
            $data['recent_logs'] = array_slice($logs, -30);
        } else {
            $data['recent_logs'] = ['No log file found'];
        }
        
        // 7. Routes Check
        $data['admin_routes_registered'] = false;
        try {
            $routes = app('router')->getRoutes();
            foreach ($routes as $route) {
                if (str_contains($route->uri(), 'admin')) {
                    $data['admin_routes_registered'] = true;
                    break;
                }
            }
        } catch (\Exception $e) {
            $data['routes_error'] = $e->getMessage();
        }
        
        // 8. Middleware Check
        $data['middleware'] = [
            'global' => app('Illuminate\Contracts\Http\Kernel')->getMiddleware(),
            'route' => app('Illuminate\Contracts\Http\Kernel')->getMiddlewareGroups(),
        ];
        
        return view('diagnostic', compact('data'));
    }
    
    public function fixCache(Request $request)
    {
        // Security key
        if ($request->get('key') !== 'bmg2024fix') {
            abort(403, 'Invalid fix key');
        }
        
        $results = [];
        
        try {
            Artisan::call('cache:clear');
            $results[] = '✓ Cache cleared';
        } catch (\Exception $e) {
            $results[] = '✗ Cache clear failed: ' . $e->getMessage();
        }
        
        try {
            Artisan::call('config:clear');
            $results[] = '✓ Config cache cleared';
        } catch (\Exception $e) {
            $results[] = '✗ Config clear failed: ' . $e->getMessage();
        }
        
        try {
            Artisan::call('route:clear');
            $results[] = '✓ Route cache cleared';
        } catch (\Exception $e) {
            $results[] = '✗ Route clear failed: ' . $e->getMessage();
        }
        
        try {
            Artisan::call('view:clear');
            $results[] = '✓ View cache cleared';
        } catch (\Exception $e) {
            $results[] = '✗ View clear failed: ' . $e->getMessage();
        }
        
        // Clear old sessions
        $sessionsPath = storage_path('framework/sessions');
        if (is_dir($sessionsPath)) {
            $files = File::files($sessionsPath);
            $deleted = 0;
            foreach ($files as $file) {
                if ($file->getMTime() < (time() - 7200)) { // 2 hours old
                    File::delete($file);
                    $deleted++;
                }
            }
            $results[] = "✓ Deleted {$deleted} old session files";
        }
        
        return view('diagnostic-fix', compact('results'));
    }
    
    private function getPermissions($path)
    {
        if (!file_exists($path)) {
            return ['exists' => false];
        }
        
        return [
            'exists' => true,
            'writable' => is_writable($path),
            'permissions' => substr(sprintf('%o', fileperms($path)), -4),
        ];
    }
}
