<?php
require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

// Cargar Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Simular request de entrada
$request = Request::create('/movimientos', 'POST', [
    'producto_id' => 1,
    'tipo' => 'entrada',
    'cantidad' => 10,
    'observaciones' => 'Test de entrada'
]);

$request->headers->set('X-CSRF-TOKEN', 'test-token');

try {
    $response = $kernel->handle($request);
    echo "Status: " . $response->getStatusCode() . "\n";
    echo "Response: " . $response->getContent() . "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

$kernel->terminate($request, $response);
