<?php
require __DIR__ . "/vendor/autoload.php";
$app = require_once __DIR__ . "/bootstrap/app.php";
Artisan::call("route:clear");
Artisan::call("config:clear");
Artisan::call("view:clear");
echo "Cache cleared!\n";
