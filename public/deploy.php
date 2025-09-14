<?php

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

// Token de seguridad
$token = $_GET['token'] ?? null;
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();
$validToken = $_ENV['DEPLOY_SECRET'] ?? null;

if ($token !== $validToken) {
    http_response_code(403);
    exit("Acceso denegado ❌");
}

// Comando a ejecutar
$command = $_GET['command'] ?? 'storage:link';

try {
    // ✅ Ejecutar usando el kernel
    $status = $kernel->call($command);
    $output = $kernel->output();

    echo "Comando ejecutado: php artisan {$command}\n";
    echo "<pre>" . htmlspecialchars($output) . "</pre>";
} catch (\Exception $e) {
    echo "Error al ejecutar el comando: " . $e->getMessage();
}
