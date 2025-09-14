<?php
use Illuminate\Support\Facades\Artisan;

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

Artisan::call('migrate', ["--force" => true]);

echo "Migraciones ejecutadas correctamente!";