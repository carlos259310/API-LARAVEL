<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\MovimientoInventarioController;
use App\Http\Controllers\FacturaController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::resource('clientes', ClienteController::class);
Route::resource('productos', ProductoController::class);
Route::get('inventarios', [InventarioController::class, 'index'])->name('inventarios.index');
Route::get('inventarios/{producto}/movimientos', [InventarioController::class, 'movimientos'])->name('inventarios.movimientos');
Route::post('movimientos', [MovimientoInventarioController::class, 'store'])->name('movimientos.store');

// Facturación
Route::resource('facturas', FacturaController::class);
Route::post('facturas/{factura}/anular', [FacturaController::class, 'anular'])->name('facturas.anular');
Route::post('facturas/{factura}/pagar', [FacturaController::class, 'marcarPagada'])->name('facturas.pagar');
Route::get('api/productos-por-bodega', [FacturaController::class, 'obtenerProductosPorBodega'])->name('api.productos.bodega');
