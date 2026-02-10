<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$p = App\Models\Product::where('slug', 'besi-wf')->first();
if ($p) {
    echo "FOUND: " . $p->name . PHP_EOL;
    $desc = $p->getTranslation('description', 'id');
    echo "--- DESC (ID) ---" . PHP_EOL;
    echo $desc . PHP_EOL;
    echo "--- END DESC ---" . PHP_EOL;
    
    // Check for href=""
    if (preg_match_all('/href\s*=\s*["\'][\s]*["\']/', $desc, $matches)) {
        echo PHP_EOL . "ISSUE FOUND: href='' (empty href)" . PHP_EOL;
        foreach ($matches[0] as $m) {
            echo "  -> " . $m . PHP_EOL;
        }
    }
    
    // Check for <img without alt
    if (preg_match_all('/<img(?![^>]*\balt\s*=)[^>]*>/', $desc, $matches)) {
        echo PHP_EOL . "ISSUE FOUND: <img> without alt attribute" . PHP_EOL;
        foreach ($matches[0] as $m) {
            echo "  -> " . $m . PHP_EOL;
        }
    }
    
    // Check for <a> wrapping <img>
    if (preg_match_all('/<a[^>]*>[\s]*<img[^>]*>[\s]*<\/a>/', $desc, $matches)) {
        echo PHP_EOL . "FOUND: <a> wrapping <img>" . PHP_EOL;
        foreach ($matches[0] as $m) {
            echo "  -> " . $m . PHP_EOL;
        }
    }
} else {
    echo "NOT FOUND" . PHP_EOL;
}
