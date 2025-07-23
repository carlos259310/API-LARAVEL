<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventario;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;

class InventarioController extends Controller
{
    /**
     * Display a listing of inventario.
     */
    public function index()
    {
        // Obtener inventarios directamente sin relaciones complejas primero
        $inventarios = Inventario::with('producto')->paginate(15);

        // Estadísticas básicas
        $totalProductos = Producto::count();
        $totalStock = Inventario::sum('stock_actual') ?? 0;
        $stockBajo = 0;
        $valorTotal = 0;

        // Productos para el modal
        $productos = Producto::where('activo', true)->get();

        return view('inventarios.index', compact(
            'inventarios',
            'totalProductos',
            'totalStock',
            'stockBajo',
            'valorTotal',
            'productos'
        ));
    }

    /**
     * Mostrar movimientos de un producto específico
     */
    public function movimientos($productoId)
    {
        $producto = Producto::with(['movimientosInventario' => function($query) {
            $query->orderBy('created_at', 'desc');
        }, 'inventario'])->findOrFail($productoId);

        return view('inventarios.movimientos', compact('producto'));
    }
}
