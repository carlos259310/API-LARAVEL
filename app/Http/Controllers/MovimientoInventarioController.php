<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MovimientoInventario;
use App\Models\Inventario;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;

class MovimientoInventarioController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
                $validated = $request->validate([
            'id_producto' => 'required|exists:productos,id',
            'id_bodega' => 'required|exists:bodegas,id',
            'tipo_movimiento' => 'required|in:entrada,salida',
            'cantidad' => 'required|integer|min:1',
            'observaciones' => 'nullable|string|max:500'
        ]);

        try {
            DB::beginTransaction();

            $producto = Producto::findOrFail($validated['id_producto']);
            $inventario = Inventario::where('id_producto', $producto->getAttribute('id'))
                ->where('id_bodega', $validated['id_bodega'])->first();

            // Si no existe inventario, crearlo
            if (!$inventario) {
                $inventario = Inventario::create([
                    'id_producto' => $producto->getAttribute('id'),
                    'id_bodega' => $validated['id_bodega'],
                    'stock_actual' => 0,
                    'stock_reservado' => 0,
                    'stock_disponible' => 0,
                    'costo_promedio' => $producto->getAttribute('precio_compra') ?? 0
                ]);
            }

            // Validar stock para salidas
            if ($validated['tipo_movimiento'] === 'salida') {
                if ($inventario->getAttribute('stock_actual') < $validated['cantidad']) {
                    return back()->withErrors(['cantidad' => 'No hay suficiente stock disponible. Stock actual: ' . $inventario->getAttribute('stock_actual')]);
                }
            }

            // Crear el movimiento
            $movimiento = MovimientoInventario::create([
                'id_producto' => $producto->getAttribute('id'),
                'tipo_movimiento' => $validated['tipo_movimiento'],
                'cantidad' => $validated['cantidad'],
                'observaciones' => $validated['observaciones']
            ]);

            // Actualizar el inventario
            $stockAnterior = $inventario->getAttribute('stock_actual');
            
            if ($validated['tipo_movimiento'] === 'entrada') {
                $nuevoStock = $stockAnterior + $validated['cantidad'];
                $inventario->update([
                    'stock_actual' => $nuevoStock,
                    'stock_disponible' => $nuevoStock,
                    'ultima_entrada' => now()
                ]);
            } else {
                $nuevoStock = $stockAnterior - $validated['cantidad'];
                $inventario->update([
                    'stock_actual' => $nuevoStock,
                    'stock_disponible' => $nuevoStock,
                    'ultima_salida' => now()
                ]);
            }

            DB::commit();

            return back()->with('success', 'Movimiento registrado exitosamente. Stock actualizado: ' . $nuevoStock);

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error al procesar el movimiento: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $movimiento = MovimientoInventario::with(['producto'])->findOrFail($id);
        return view('movimientos.show', compact('movimiento'));
    }
}
