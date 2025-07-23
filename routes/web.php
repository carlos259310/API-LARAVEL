<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\MovimientoInventarioController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::resource('clientes', ClienteController::class);
Route::resource('productos', ProductoController::class);
Route::get('inventarios', [InventarioController::class, 'index'])->name('inventarios.index');
Route::get('inventarios/{producto}/movimientos', [InventarioController::class, 'movimientos'])->name('inventarios.movimientos');
Route::post('movimientos', [MovimientoInventarioController::class, 'store'])->name('movimientos.store');
