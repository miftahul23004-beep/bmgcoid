<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Backup extends Model
{
    // This is a virtual model for Filament resource
    // Actual backups are stored as files
    
    public $incrementing = false;
    protected $keyType = 'string';
    
    public static function all($columns = ['*'])
    {
        $backupPath = storage_path('app/backups');
        
        if (!is_dir($backupPath)) {
            return collect([]);
        }

        $backups = glob("{$backupPath}/*.zip");
        
        return collect($backups)->map(function ($backup) {
            $filename = basename($backup);
            return (object)[
                'id' => str_replace('.zip', '', $filename),
                'filename' => $filename,
                'size' => filesize($backup),
                'created_at' => date('Y-m-d H:i:s', filemtime($backup)),
                'path' => $backup,
            ];
        })->sortByDesc('created_at');
    }

    public static function find($id)
    {
        $backupPath = storage_path("app/backups/{$id}.zip");
        
        if (!file_exists($backupPath)) {
            return null;
        }

        return (object)[
            'id' => $id,
            'filename' => basename($backupPath),
            'size' => filesize($backupPath),
            'created_at' => date('Y-m-d H:i:s', filemtime($backupPath)),
            'path' => $backupPath,
        ];
    }

    public static function delete($id)
    {
        $backupPath = storage_path("app/backups/{$id}.zip");
        
        if (file_exists($backupPath)) {
            return unlink($backupPath);
        }
        
        return false;
    }
}
