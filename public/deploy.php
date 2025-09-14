<?php
use Illuminate\Support\Facades\Artisan;

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

// Obtener token desde query string
$token = $_GET['token'] ?? null;

// Leer el token correcto desde el .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
$validToken = $_ENV['DEPLOY_SECRET'] ?? null;

// Validar
if ($token !== $validToken) {
    http_response_code(403);
    exit("Acceso denegado.");
}

// Ejecutar migraciones
Artisan::call('migrate', ['--force' => true]);

echo "Migraciones ejecutadas correctamente ✅\n";
