<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\Inventario;
use App\Models\Departamento;
use App\Models\Ciudad;
use App\Models\TipoDocumento;

class DashboardController extends Controller
{
    public function index()
    {
        // Estadísticas generales
        $totalClientes = Cliente::count();
        $totalProductos = Producto::where('activo', true)->count();
        $totalInventario = Inventario::sum('stock_actual');
        
        // Productos con stock bajo
        $stockBajo = Inventario::whereHas('producto', function($query) {
            $query->whereRaw('inventarios.stock_actual <= productos.stock_minimo');
        })->count();
        
        $productosStockBajo = Producto::with('inventario')
            ->whereHas('inventario', function($query) {
                $query->whereRaw('inventarios.stock_actual <= productos.stock_minimo');
            })
            ->limit(5)
            ->get();
        
        return view('dashboard.index', compact(
            'totalClientes',
            'totalProductos', 
            'totalInventario',
            'stockBajo',
            'productosStockBajo'
        ));
    }
}
