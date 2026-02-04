# Backup & Restore System

Sistem backup dan restore lengkap untuk database dan file, support lokal (command line) dan web (production).

## 📋 Fitur

- ✅ Backup database (MySQL dump)
- ✅ Backup files (storage, public assets)
- ✅ Full backup (database + files)
- ✅ Compress ke ZIP
- ✅ List semua backup
- ✅ Download backup
- ✅ Restore dari backup
- ✅ Command line interface (artisan)
- ✅ Web interface (production-ready)

## 🖥️ Penggunaan Lokal (Command Line)

### 1. Create Backup

```bash
# Full backup (database + files)
php artisan backup:create --type=full

# Database only
php artisan backup:create --type=database

# Files only
php artisan backup:create --type=files
```

### 2. List Backups

```bash
php artisan backup:list
```

Output:
```
Available backups:

+-------------------------------------+-----------+---------------------+
| Backup Name                         | Size      | Created             |
+-------------------------------------+-----------+---------------------+
| backup_full_2026-02-05_01-30-00    | 25.5 MB   | 2026-02-05 01:30:00 |
| backup_database_2026-02-04_23-15   | 140.57 KB | 2026-02-04 23:15:30 |
+-------------------------------------+-----------+---------------------+
```

### 3. Restore Backup

```bash
# Restore dengan konfirmasi
php artisan backup:restore backup_full_2026-02-05_01-30-00

# Restore tanpa konfirmasi (force)
php artisan backup:restore backup_full_2026-02-05_01-30-00 --force
```

**⚠️ WARNING**: Restore akan menimpa data yang ada sekarang!

## 🌐 Penggunaan Web (Production)

### Setup

1. Upload `public/backup.php` ke server production
2. Edit security key di `public/backup.php`:
   ```php
   $secretKey = 'bm2026backup'; // CHANGE THIS!
   ```

### Akses Web Interface

Base URL:
```
https://berkahmandiri.co.id/backup.php?key=YOUR_SECRET_KEY
```

### 1. Create Backup

```
https://berkahmandiri.co.id/backup.php?key=bm2026backup&action=create&type=full
```

Parameters:
- `type=full` - Full backup (database + files)
- `type=database` - Database only
- `type=files` - Files only

### 2. List Backups

```
https://berkahmandiri.co.id/backup.php?key=bm2026backup&action=list
```

Output:
```
=== Available Backups ===

• backup_full_2026-02-05_01-30-00.zip
  Size: 25.5 MB
  Created: 2026-02-05 01:30:00
  Download: backup.php?key=xxx&action=download&file=backup_full_2026-02-05_01-30-00.zip

• backup_database_2026-02-04_23-15.zip
  Size: 140.57 KB
  Created: 2026-02-04 23:15:30
  Download: backup.php?key=xxx&action=download&file=backup_database_2026-02-04_23-15.zip
```

### 3. Download Backup

```
https://berkahmandiri.co.id/backup.php?key=bm2026backup&action=download&file=backup_full_2026-02-05_01-30-00.zip
```

### 4. Restore Backup

```
https://berkahmandiri.co.id/backup.php?key=bm2026backup&action=restore&file=backup_full_2026-02-05_01-30-00.zip
```

**⚠️ WARNING**: Restore akan menimpa data yang ada!

## 📁 Lokasi Backup

Semua backup disimpan di:
```
storage/app/backups/
```

Format nama file:
```
backup_{type}_{timestamp}.zip

Contoh:
- backup_full_2026-02-05_01-30-00.zip
- backup_database_2026-02-05_01-30-00.zip
- backup_files_2026-02-05_01-30-00.zip
```

## 📦 Isi Backup

### Full Backup
- ✅ Database (SQL dump)
- ✅ `storage/app/public/` - Uploaded files
- ✅ `public/storage/` - Public storage link
- ✅ `public/build/` - Compiled assets
- ✅ `public/images/` - Static images
- ✅ `public/fonts/` - Fonts
- ✅ `public/css/` - CSS files
- ✅ `public/js/` - JS files
- ✅ `.env` file (optional on restore)

### Database Only
- ✅ Database SQL dump

### Files Only
- ✅ Semua file di atas kecuali database

## 🔧 Automation (Scheduled Backups)

Tambahkan ke `app/Console/Kernel.php`:

```php
protected function schedule(Schedule $schedule)
{
    // Daily full backup at 2 AM
    $schedule->command('backup:create --type=full')
        ->daily()
        ->at('02:00');
    
    // Hourly database backup (business hours)
    $schedule->command('backup:create --type=database')
        ->hourly()
        ->between('9:00', '18:00');
}
```

## 🔐 Security

1. **Change Secret Key**: Edit security key di `public/backup.php`
2. **Restrict IP**: Tambahkan IP whitelist jika perlu
3. **Delete After Download**: Hapus backup lama secara berkala
4. **Offsite Storage**: Simpan backup di cloud storage (Google Drive, AWS S3)

## ⚠️ Best Practices

1. **Test Restore**: Selalu test restore di environment development dulu
2. **Regular Backups**: Buat jadwal backup otomatis
3. **Multiple Copies**: Simpan backup di multiple locations
4. **Monitor Size**: Check disk space untuk backup storage
5. **Retention Policy**: Hapus backup lama (misal: simpan 30 hari terakhir)

## 🐛 Troubleshooting

### Error: mysqldump not found
Backup akan otomatis fallback ke PHP export. Tapi untuk database besar, install mysqldump:
- Windows XAMPP: Sudah include di `C:\xampp\mysql\bin\mysqldump.exe`
- Linux: `sudo apt install mysql-client`

### Error: Permission denied
```bash
chmod -R 755 storage/app/backups
```

### Error: Disk space full
Hapus backup lama atau pindah ke storage lain:
```bash
php artisan backup:list
# Manual delete file di storage/app/backups/
```

### Error: Timeout on large database
Edit `public/backup.php`, tambahkan:
```php
set_time_limit(600); // 10 minutes
ini_set('memory_limit', '512M');
```

## 📞 Support

Untuk issue atau pertanyaan, hubungi tim development.

---

**Last Updated**: 2026-02-05  
**Version**: 1.0.0
