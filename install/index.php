<?php
/**
 * Installation Wizard - PT. Berkah Mandiri Globalindo
 * 
 * Wizard ini akan membantu setup website di hosting baru
 * Hapus folder /install setelah instalasi selesai!
 */

session_start();

// Prevent access if already installed
if (file_exists('../.env') && !isset($_GET['force'])) {
    $envContent = file_get_contents('../.env');
    if (strpos($envContent, 'APP_KEY=base64:') !== false && strlen(trim(explode('APP_KEY=base64:', $envContent)[1] ?? '')) > 10) {
        die('<div style="font-family:Arial;text-align:center;padding:50px;"><h1>⚠️ Sudah Terinstall</h1><p>Website sudah dikonfigurasi. Hapus folder <code>/install</code> untuk keamanan.</p><p><a href="/">Ke Homepage</a> | <a href="/admin">Ke Admin</a></p></div>');
    }
}

$step = $_GET['step'] ?? 1;
$error = '';
$success = '';

// Process form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch ($step) {
        case 2:
            // Test database connection
            $dbHost = $_POST['db_host'] ?? 'localhost';
            $dbPort = $_POST['db_port'] ?? '3306';
            $dbName = $_POST['db_name'] ?? '';
            $dbUser = $_POST['db_user'] ?? '';
            $dbPass = $_POST['db_pass'] ?? '';
            
            try {
                $pdo = new PDO("mysql:host=$dbHost;port=$dbPort", $dbUser, $dbPass);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                // Check if database exists, create if not
                $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                
                $_SESSION['db'] = [
                    'host' => $dbHost,
                    'port' => $dbPort,
                    'name' => $dbName,
                    'user' => $dbUser,
                    'pass' => $dbPass,
                ];
                
                header('Location: ?step=3');
                exit;
            } catch (PDOException $e) {
                $error = 'Koneksi database gagal: ' . $e->getMessage();
            }
            break;
            
        case 3:
            // Save configuration
            $appUrl = rtrim($_POST['app_url'] ?? 'https://berkahmandiri.co.id', '/');
            $mailHost = $_POST['mail_host'] ?? '';
            $mailPort = $_POST['mail_port'] ?? '587';
            $mailUser = $_POST['mail_user'] ?? '';
            $mailPass = $_POST['mail_pass'] ?? '';
            $mailFrom = $_POST['mail_from'] ?? '';
            
            $db = $_SESSION['db'] ?? [];
            
            if (empty($db)) {
                header('Location: ?step=2');
                exit;
            }
            
            // Generate APP_KEY
            $appKey = 'base64:' . base64_encode(random_bytes(32));
            
            // Create .env content
            $envContent = <<<ENV
APP_NAME="PT. Berkah Mandiri Globalindo"
APP_ENV=production
APP_KEY=$appKey
APP_DEBUG=false
APP_URL=$appUrl

APP_LOCALE=id
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=id_ID

APP_MAINTENANCE_DRIVER=file

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST={$db['host']}
DB_PORT={$db['port']}
DB_DATABASE={$db['name']}
DB_USERNAME={$db['user']}
DB_PASSWORD={$db['pass']}

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=database

MAIL_MAILER=smtp
MAIL_HOST=$mailHost
MAIL_PORT=$mailPort
MAIL_USERNAME=$mailUser
MAIL_PASSWORD=$mailPass
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="$mailFrom"
MAIL_FROM_NAME="\${APP_NAME}"

DEBUGBAR_ENABLED=false
ENV;

            // Write .env file
            if (file_put_contents('../.env', $envContent)) {
                $_SESSION['env_created'] = true;
                header('Location: ?step=4');
                exit;
            } else {
                $error = 'Gagal menulis file .env. Pastikan folder memiliki permission yang benar (755).';
            }
            break;
            
        case 4:
            // Run migrations and setup
            $output = [];
            $returnCode = 0;
            
            chdir('..');
            
            // Clear any cached config
            exec('php artisan config:clear 2>&1', $output, $returnCode);
            
            // Run migrations
            exec('php artisan migrate --force 2>&1', $output, $returnCode);
            
            if ($returnCode !== 0) {
                $error = 'Migration gagal: ' . implode("\n", $output);
            } else {
                // Run seeders if needed
                exec('php artisan db:seed --force 2>&1', $output, $returnCode);
                
                // Create storage link
                exec('php artisan storage:link --force 2>&1', $output, $returnCode);
                
                // Cache config for production
                exec('php artisan config:cache 2>&1', $output, $returnCode);
                exec('php artisan route:cache 2>&1', $output, $returnCode);
                exec('php artisan view:cache 2>&1', $output, $returnCode);
                
                $_SESSION['migration_done'] = true;
                header('Location: ?step=5');
                exit;
            }
            break;
            
        case 5:
            // Create admin user
            $name = $_POST['admin_name'] ?? '';
            $email = $_POST['admin_email'] ?? '';
            $password = $_POST['admin_password'] ?? '';
            
            if (strlen($password) < 8) {
                $error = 'Password minimal 8 karakter';
            } else {
                chdir('..');
                require 'vendor/autoload.php';
                $app = require 'bootstrap/app.php';
                $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
                
                try {
                    $user = \App\Models\User::updateOrCreate(
                        ['email' => $email],
                        [
                            'name' => $name,
                            'password' => bcrypt($password),
                            'email_verified_at' => now(),
                        ]
                    );
                    
                    $_SESSION['admin_created'] = true;
                    $_SESSION['admin_email'] = $email;
                    header('Location: ?step=6');
                    exit;
                } catch (Exception $e) {
                    $error = 'Gagal membuat admin: ' . $e->getMessage();
                }
            }
            break;
    }
}

// Check requirements
function checkRequirements() {
    $requirements = [
        'PHP Version >= 8.2' => version_compare(PHP_VERSION, '8.2.0', '>='),
        'BCMath Extension' => extension_loaded('bcmath'),
        'Ctype Extension' => extension_loaded('ctype'),
        'Fileinfo Extension' => extension_loaded('fileinfo'),
        'JSON Extension' => extension_loaded('json'),
        'Mbstring Extension' => extension_loaded('mbstring'),
        'OpenSSL Extension' => extension_loaded('openssl'),
        'PDO Extension' => extension_loaded('pdo'),
        'PDO MySQL Extension' => extension_loaded('pdo_mysql'),
        'Tokenizer Extension' => extension_loaded('tokenizer'),
        'XML Extension' => extension_loaded('xml'),
        'GD Extension' => extension_loaded('gd'),
    ];
    
    $folders = [
        'storage/framework/cache' => is_writable('../storage/framework/cache'),
        'storage/framework/sessions' => is_writable('../storage/framework/sessions'),
        'storage/framework/views' => is_writable('../storage/framework/views'),
        'storage/logs' => is_writable('../storage/logs'),
        'bootstrap/cache' => is_writable('../bootstrap/cache'),
    ];
    
    return ['php' => $requirements, 'folders' => $folders];
}

$requirements = checkRequirements();
$allPassed = !in_array(false, $requirements['php']) && !in_array(false, $requirements['folders']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Install - PT. Berkah Mandiri Globalindo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .step-active { @apply bg-primary-600 text-white; }
        .step-done { @apply bg-green-500 text-white; }
        .step-pending { @apply bg-gray-200 text-gray-500; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="max-w-4xl mx-auto py-10 px-4">
        <!-- Header -->
        <div class="text-center mb-10">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-primary-600 rounded-full mb-4">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900">Installation Wizard</h1>
            <p class="text-gray-600 mt-2">PT. Berkah Mandiri Globalindo</p>
        </div>

        <!-- Progress Steps -->
        <div class="flex justify-center mb-10">
            <div class="flex items-center space-x-4">
                <?php
                $steps = [
                    1 => 'Cek Sistem',
                    2 => 'Database',
                    3 => 'Konfigurasi',
                    4 => 'Migrasi',
                    5 => 'Admin',
                    6 => 'Selesai'
                ];
                foreach ($steps as $num => $label):
                    $class = 'step-pending';
                    if ($num < $step) $class = 'step-done';
                    elseif ($num == $step) $class = 'step-active';
                ?>
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold <?= $class ?>">
                            <?php if ($num < $step): ?>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            <?php else: ?>
                                <?= $num ?>
                            <?php endif; ?>
                        </div>
                        <span class="ml-2 text-sm hidden sm:inline <?= $num == $step ? 'text-primary-600 font-medium' : 'text-gray-500' ?>"><?= $label ?></span>
                        <?php if ($num < 6): ?>
                            <div class="w-8 h-0.5 bg-gray-300 ml-4"></div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Error/Success Messages -->
        <?php if ($error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <strong>Error:</strong> <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <!-- Content Card -->
        <div class="bg-white rounded-xl shadow-lg p-8">
            <?php if ($step == 1): ?>
                <!-- Step 1: System Check -->
                <h2 class="text-2xl font-bold mb-6">📋 Cek Persyaratan Sistem</h2>
                
                <div class="mb-6">
                    <h3 class="font-semibold text-lg mb-3">PHP Extensions</h3>
                    <div class="grid grid-cols-2 gap-2">
                        <?php foreach ($requirements['php'] as $name => $passed): ?>
                            <div class="flex items-center p-2 rounded <?= $passed ? 'bg-green-50' : 'bg-red-50' ?>">
                                <?php if ($passed): ?>
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                <?php else: ?>
                                    <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                <?php endif; ?>
                                <span class="<?= $passed ? 'text-green-700' : 'text-red-700' ?>"><?= $name ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="font-semibold text-lg mb-3">Folder Permissions</h3>
                    <div class="grid grid-cols-2 gap-2">
                        <?php foreach ($requirements['folders'] as $name => $passed): ?>
                            <div class="flex items-center p-2 rounded <?= $passed ? 'bg-green-50' : 'bg-red-50' ?>">
                                <?php if ($passed): ?>
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                <?php else: ?>
                                    <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                <?php endif; ?>
                                <span class="<?= $passed ? 'text-green-700' : 'text-red-700' ?> text-sm"><?= $name ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <?php if ($allPassed): ?>
                    <a href="?step=2" class="inline-flex items-center px-6 py-3 bg-primary-600 text-white font-semibold rounded-lg hover:bg-primary-700 transition">
                        Lanjutkan
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                <?php else: ?>
                    <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
                        <strong>Perhatian:</strong> Beberapa persyaratan belum terpenuhi. Perbaiki terlebih dahulu sebelum melanjutkan.
                    </div>
                <?php endif; ?>

            <?php elseif ($step == 2): ?>
                <!-- Step 2: Database -->
                <h2 class="text-2xl font-bold mb-6">🗄️ Konfigurasi Database</h2>
                
                <form method="POST" class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Database Host</label>
                            <input type="text" name="db_host" value="localhost" required
                                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Database Port</label>
                            <input type="text" name="db_port" value="3306" required
                                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Database</label>
                        <input type="text" name="db_name" placeholder="bmg_db" required
                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <p class="text-xs text-gray-500 mt-1">Database akan dibuat otomatis jika belum ada</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Username Database</label>
                        <input type="text" name="db_user" placeholder="root" required
                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password Database</label>
                        <input type="password" name="db_pass" placeholder="Password database"
                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    </div>

                    <div class="flex justify-between pt-4">
                        <a href="?step=1" class="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition">
                            Kembali
                        </a>
                        <button type="submit" class="px-6 py-3 bg-primary-600 text-white font-semibold rounded-lg hover:bg-primary-700 transition">
                            Test Koneksi & Lanjutkan
                        </button>
                    </div>
                </form>

            <?php elseif ($step == 3): ?>
                <!-- Step 3: Configuration -->
                <h2 class="text-2xl font-bold mb-6">⚙️ Konfigurasi Website</h2>
                
                <form method="POST" class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">URL Website</label>
                        <input type="url" name="app_url" value="https://berkahmandiri.co.id" required
                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <p class="text-xs text-gray-500 mt-1">Gunakan https:// untuk keamanan</p>
                    </div>

                    <hr class="my-6">
                    <h3 class="font-semibold text-lg">📧 Konfigurasi Email (Opsional)</h3>
                    <p class="text-sm text-gray-500 mb-4">Untuk mengirim email notifikasi inquiry</p>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">SMTP Host</label>
                            <input type="text" name="mail_host" placeholder="smtp.gmail.com"
                                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">SMTP Port</label>
                            <input type="text" name="mail_port" value="587"
                                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email Username</label>
                            <input type="email" name="mail_user" placeholder="info@berkahmandiri.co.id"
                                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email Password</label>
                            <input type="password" name="mail_pass" placeholder="App password"
                                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">From Email Address</label>
                        <input type="email" name="mail_from" placeholder="info@berkahmandiri.co.id"
                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    </div>

                    <div class="flex justify-between pt-4">
                        <a href="?step=2" class="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition">
                            Kembali
                        </a>
                        <button type="submit" class="px-6 py-3 bg-primary-600 text-white font-semibold rounded-lg hover:bg-primary-700 transition">
                            Simpan & Lanjutkan
                        </button>
                    </div>
                </form>

            <?php elseif ($step == 4): ?>
                <!-- Step 4: Migration -->
                <h2 class="text-2xl font-bold mb-6">🔄 Setup Database</h2>
                
                <div class="text-center py-8">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-primary-100 rounded-full mb-4">
                        <svg class="w-8 h-8 text-primary-600 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                    <p class="text-lg text-gray-600 mb-6">Klik tombol di bawah untuk menjalankan migrasi database</p>
                    
                    <form method="POST">
                        <button type="submit" class="px-8 py-4 bg-primary-600 text-white font-semibold rounded-lg hover:bg-primary-700 transition text-lg">
                            🚀 Jalankan Migrasi
                        </button>
                    </form>
                    
                    <p class="text-sm text-gray-500 mt-4">Proses ini akan membuat semua tabel yang diperlukan</p>
                </div>

            <?php elseif ($step == 5): ?>
                <!-- Step 5: Admin User -->
                <h2 class="text-2xl font-bold mb-6">👤 Buat Akun Admin</h2>
                
                <form method="POST" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="admin_name" placeholder="Administrator" required
                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="admin_email" placeholder="admin@berkahmandiri.co.id" required
                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input type="password" name="admin_password" placeholder="Minimal 8 karakter" required minlength="8"
                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full px-6 py-3 bg-primary-600 text-white font-semibold rounded-lg hover:bg-primary-700 transition">
                            Buat Akun Admin
                        </button>
                    </div>
                </form>

            <?php elseif ($step == 6): ?>
                <!-- Step 6: Complete -->
                <div class="text-center py-8">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-green-100 rounded-full mb-6">
                        <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">🎉 Instalasi Selesai!</h2>
                    <p class="text-lg text-gray-600 mb-8">Website PT. Berkah Mandiri Globalindo berhasil diinstall.</p>
                    
                    <div class="bg-gray-50 rounded-xl p-6 mb-8 text-left max-w-md mx-auto">
                        <h3 class="font-semibold text-lg mb-4">📋 Informasi Login Admin:</h3>
                        <div class="space-y-2">
                            <p><span class="text-gray-500">URL Admin:</span> <a href="/admin" class="text-primary-600 font-medium">berkahmandiri.co.id/admin</a></p>
                            <p><span class="text-gray-500">Email:</span> <span class="font-medium"><?= htmlspecialchars($_SESSION['admin_email'] ?? 'admin@berkahmandiri.co.id') ?></span></p>
                        </div>
                    </div>

                    <div class="bg-red-50 border border-red-200 rounded-xl p-6 mb-8 text-left">
                        <h3 class="font-semibold text-red-800 mb-2">⚠️ PENTING - Keamanan!</h3>
                        <p class="text-red-700">Hapus folder <code class="bg-red-100 px-2 py-1 rounded">/install</code> setelah instalasi untuk keamanan website.</p>
                    </div>

                    <div class="flex justify-center gap-4">
                        <a href="/" class="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition">
                            🏠 Ke Homepage
                        </a>
                        <a href="/admin" class="px-6 py-3 bg-primary-600 text-white font-semibold rounded-lg hover:bg-primary-700 transition">
                            🔐 Ke Admin Panel
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Footer -->
        <div class="text-center mt-8 text-gray-500 text-sm">
            <p>PT. Berkah Mandiri Globalindo &copy; <?= date('Y') ?></p>
            <p>Supplier Besi dan Baja Terpercaya</p>
        </div>
    </div>
</body>
</html>
