<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FtpSyncService
{
    protected $connection;
    protected string $host;
    protected string $username;
    protected string $password;
    protected int $port;
    protected string $remotePath;
    protected array $logs = [];

    public function __construct()
    {
        $this->host = config('services.ftp.host', 'berkahmandiri.co.id');
        $this->username = config('services.ftp.username', 'admin@berkahmandiri.co.id');
        $this->password = config('services.ftp.password', 'Ulum@280700');
        $this->port = config('services.ftp.port', 21);
        $this->remotePath = config('services.ftp.remote_path', '/public_html/berkahmandiri.co.id');
    }

    /**
     * Connect to FTP server
     */
    public function connect(): bool
    {
        try {
            $this->connection = ftp_connect($this->host, $this->port, 30);
            
            if (!$this->connection) {
                $this->log('error', "Failed to connect to FTP server: {$this->host}:{$this->port}");
                return false;
            }

            $login = ftp_login($this->connection, $this->username, $this->password);
            
            if (!$login) {
                $this->log('error', "FTP login failed for user: {$this->username}");
                return false;
            }

            // Enable passive mode
            ftp_pasv($this->connection, true);
            
            $this->log('success', "Connected to FTP server: {$this->host}");
            return true;
        } catch (\Exception $e) {
            $this->log('error', "FTP connection error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Disconnect from FTP server
     */
    public function disconnect(): void
    {
        if ($this->connection) {
            ftp_close($this->connection);
            $this->connection = null;
        }
    }

    /**
     * Upload a single file
     */
    public function uploadFile(string $localPath, string $remotePath): bool
    {
        if (!$this->connection) {
            $this->log('error', "Not connected to FTP server");
            return false;
        }

        try {
            // Ensure remote directory exists
            $remoteDir = dirname($remotePath);
            $this->createRemoteDirectory($remoteDir);

            // Upload file
            $result = ftp_put($this->connection, $remotePath, $localPath, FTP_BINARY);
            
            if ($result) {
                $this->log('success', "Uploaded: {$localPath} -> {$remotePath}");
                return true;
            } else {
                $this->log('error', "Failed to upload: {$localPath}");
                return false;
            }
        } catch (\Exception $e) {
            $this->log('error', "Upload error for {$localPath}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Create remote directory recursively
     */
    protected function createRemoteDirectory(string $directory): bool
    {
        if (empty($directory) || $directory === '/' || $directory === '.') {
            return true;
        }

        try {
            // Check if directory exists
            $currentDir = ftp_pwd($this->connection);
            
            if (@ftp_chdir($this->connection, $directory)) {
                ftp_chdir($this->connection, $currentDir);
                return true;
            }

            // Create directory recursively
            $parts = explode('/', trim($directory, '/'));
            $path = '';
            
            foreach ($parts as $part) {
                $path .= '/' . $part;
                
                if (!@ftp_chdir($this->connection, $path)) {
                    if (!@ftp_mkdir($this->connection, $path)) {
                        // Directory might already exist, continue
                    }
                }
            }
            
            ftp_chdir($this->connection, $currentDir);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Sync multiple files
     */
    public function syncFiles(array $files): array
    {
        $results = [
            'success' => [],
            'failed' => [],
        ];

        if (!$this->connect()) {
            return $results;
        }

        $uploadPath = base_path('upload');

        foreach ($files as $file) {
            $localPath = $uploadPath . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $file);
            $remotePath = $this->remotePath . '/' . $file;

            if (file_exists($localPath)) {
                if ($this->uploadFile($localPath, $remotePath)) {
                    $results['success'][] = $file;
                } else {
                    $results['failed'][] = $file;
                }
            } else {
                $this->log('error', "Local file not found: {$localPath}");
                $results['failed'][] = $file;
            }
        }

        $this->disconnect();

        return $results;
    }

    /**
     * Get list of changed files in upload folder
     */
    public function getChangedFiles(): array
    {
        $uploadPath = base_path('upload');
        $trackingFile = storage_path('app/ftp_sync_tracking.json');
        
        $previousState = [];
        if (file_exists($trackingFile)) {
            $previousState = json_decode(file_get_contents($trackingFile), true) ?? [];
        }

        $currentFiles = $this->scanDirectory($uploadPath);
        $changedFiles = [];

        foreach ($currentFiles as $relativePath => $fileInfo) {
            $isNew = !isset($previousState[$relativePath]);
            $isModified = isset($previousState[$relativePath]) && 
                          $previousState[$relativePath]['mtime'] < $fileInfo['mtime'];

            if ($isNew || $isModified) {
                $changedFiles[$relativePath] = [
                    'path' => $relativePath,
                    'size' => $fileInfo['size'],
                    'mtime' => $fileInfo['mtime'],
                    'status' => $isNew ? 'new' : 'modified',
                    'formatted_size' => $this->formatBytes($fileInfo['size']),
                    'formatted_date' => date('Y-m-d H:i:s', $fileInfo['mtime']),
                ];
            }
        }

        // Sort by modification time descending
        uasort($changedFiles, fn($a, $b) => $b['mtime'] <=> $a['mtime']);

        return $changedFiles;
    }

    /**
     * Update tracking file after successful sync
     */
    public function updateTracking(array $syncedFiles): void
    {
        $uploadPath = base_path('upload');
        $trackingFile = storage_path('app/ftp_sync_tracking.json');
        
        $previousState = [];
        if (file_exists($trackingFile)) {
            $previousState = json_decode(file_get_contents($trackingFile), true) ?? [];
        }

        foreach ($syncedFiles as $file) {
            $fullPath = $uploadPath . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $file);
            if (file_exists($fullPath)) {
                $previousState[$file] = [
                    'size' => filesize($fullPath),
                    'mtime' => filemtime($fullPath),
                    'synced_at' => time(),
                ];
            }
        }

        file_put_contents($trackingFile, json_encode($previousState, JSON_PRETTY_PRINT));
    }

    /**
     * Scan directory recursively
     */
    protected function scanDirectory(string $path, string $basePath = null): array
    {
        $basePath = $basePath ?? $path;
        $files = [];

        // Excluded directories and files
        $excludes = [
            'node_modules',
            '.git',
            'vendor/bin',
            'storage/logs',
            'storage/framework/cache',
            'storage/framework/sessions',
            'storage/framework/views',
            'bootstrap/cache',
            '.env',
            '.env.example',
            'install',
            'database.sql',
        ];

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($path, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $relativePath = str_replace($basePath . DIRECTORY_SEPARATOR, '', $file->getPathname());
                $relativePath = str_replace(DIRECTORY_SEPARATOR, '/', $relativePath);

                // Check exclusions
                $skip = false;
                foreach ($excludes as $exclude) {
                    if (str_starts_with($relativePath, $exclude) || $relativePath === $exclude) {
                        $skip = true;
                        break;
                    }
                }

                if (!$skip) {
                    $files[$relativePath] = [
                        'size' => $file->getSize(),
                        'mtime' => $file->getMTime(),
                    ];
                }
            }
        }

        return $files;
    }

    /**
     * Format bytes to human readable
     */
    protected function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Add log entry
     */
    protected function log(string $type, string $message): void
    {
        $this->logs[] = [
            'type' => $type,
            'message' => $message,
            'time' => now()->format('H:i:s'),
        ];

        if ($type === 'error') {
            Log::error("[FTP Sync] {$message}");
        } else {
            Log::info("[FTP Sync] {$message}");
        }
    }

    /**
     * Get logs
     */
    public function getLogs(): array
    {
        return $this->logs;
    }

    /**
     * Test FTP connection
     */
    public function testConnection(): array
    {
        $connected = $this->connect();
        
        if ($connected) {
            $this->disconnect();
            return [
                'success' => true,
                'message' => "Successfully connected to {$this->host}",
            ];
        }

        return [
            'success' => false,
            'message' => $this->logs[0]['message'] ?? 'Connection failed',
        ];
    }
}
