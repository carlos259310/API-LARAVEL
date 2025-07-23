<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use App\Models\DetalleFactura;
use App\Models\Cliente;
use App\Models\Bodega;
use App\Models\Producto;
use App\Models\Inventario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class FacturaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $facturas = Factura::with(['cliente', 'bodega'])
                ->orderBy('fecha_factura', 'desc')
                ->orderBy('consecutivo', 'desc')
                ->paginate(15);

            Log::info('Facturas controller - facturas count: ' . $facturas->count());
            
            return view('facturas.index', compact('facturas'));
        } catch (\Exception $e) {
            Log::error('Error en FacturaController@index: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            // Retornar vista simple en caso de error
            return view('facturas.simple', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clientes = Cliente::where('activo', true)
            ->orderBy('nombre_1')
            ->get();

        $bodegas = Bodega::orderBy('nombre')->get();

        $productos = Producto::where('activo', true)
            ->with(['categoria', 'marca'])
            ->orderBy('nombre')
            ->get();

        return view('facturas.create', compact('clientes', 'bodegas', 'productos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'bodega_id' => 'required|exists:bodegas,id',
            'fecha_factura' => 'required|date',
            'fecha_vencimiento' => 'nullable|date|after_or_equal:fecha_factura',
            'observaciones' => 'nullable|string|max:1000',
            'productos' => 'required|array|min:1',
            'productos.*.id' => 'required|exists:productos,id',
            'productos.*.cantidad' => 'required|integer|min:1',
            'productos.*.precio' => 'required|numeric|min:0.01',
            'productos.*.descuento' => 'nullable|numeric|min:0'
        ]);

        DB::beginTransaction();
        try {
            // Validar stock disponible
            foreach ($request->input('productos') as $productoData) {
                $inventario = Inventario::where('id_producto', $productoData['id'])
                    ->where('id_bodega', $request->input('bodega_id'))
                    ->first();

                if (!$inventario || $inventario->getAttribute('stock_disponible') < $productoData['cantidad']) {
                    $producto = Producto::find($productoData['id']);
                    throw new \Exception("Stock insuficiente para el producto: {$producto->getAttribute('nombre')}. Disponible: " . ($inventario ? $inventario->getAttribute('stock_disponible') : 0));
                }
            }

            // Crear factura
            $factura = Factura::create([
                'prefijo' => 'FAC',
                'consecutivo' => Factura::obtenerSiguienteConsecutivo('FAC'),
                'cliente_id' => $request->input('cliente_id'),
                'bodega_id' => $request->input('bodega_id'),
                'fecha_factura' => $request->input('fecha_factura'),
                'fecha_vencimiento' => $request->input('fecha_vencimiento'),
                'estado' => 'pendiente',
                'observaciones' => $request->input('observaciones'),
                'subtotal' => 0,
                'impuestos' => $request->input('impuestos', 0),
                'descuento' => $request->input('descuento', 0),
                'total' => 0
            ]);

            // Crear detalles y descontar inventario
            $subtotal = 0;
            foreach ($request->input('productos') as $productoData) {
                $cantidad = $productoData['cantidad'];
                $precio = $productoData['precio'];
                $descuentoLinea = $productoData['descuento'] ?? 0;
                $subtotalLinea = ($cantidad * $precio) - $descuentoLinea;

                DetalleFactura::create([
                    'factura_id' => $factura->getAttribute('id'),
                    'producto_id' => $productoData['id'],
                    'cantidad' => $cantidad,
                    'precio_unitario' => $precio,
                    'descuento_linea' => $descuentoLinea,
                    'subtotal' => $subtotalLinea
                ]);

                $subtotal += $subtotalLinea;
            }

            // Actualizar totales de la factura
            $total = $subtotal + $factura->getAttribute('impuestos') - $factura->getAttribute('descuento');
            $factura->update([
                'subtotal' => $subtotal,
                'total' => $total
            ]);

            // Descontar inventario
            $factura->descontarInventario();

            DB::commit();
            return redirect()->route('facturas.show', $factura)
                ->with('success', 'Factura creada exitosamente.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                ->with('error', 'Error al crear la factura: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Factura $factura)
    {
        $factura->load(['cliente', 'bodega', 'detalles.producto']);
        return view('facturas.show', compact('factura'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Factura $factura)
    {
        if (!$factura->getPuedeModificarseAttribute()) {
            return redirect()->route('facturas.show', $factura)
                ->with('error', 'No se puede modificar una factura pagada o anulada.');
        }

        $clientes = Cliente::where('activo', true)
            ->orderBy('nombre_1')
            ->get();

        $bodegas = Bodega::orderBy('nombre')->get();

        $productos = Producto::where('activo', true)
            ->with(['categoria', 'marca'])
            ->orderBy('nombre')
            ->get();

        $factura->load(['detalles.producto']);

        return view('facturas.edit', compact('factura', 'clientes', 'bodegas', 'productos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Factura $factura)
    {
        if (!$factura->getPuedeModificarseAttribute()) {
            return redirect()->route('facturas.show', $factura)
                ->with('error', 'No se puede modificar una factura pagada o anulada.');
        }

        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'bodega_id' => 'required|exists:bodegas,id',
            'fecha_factura' => 'required|date',
            'fecha_vencimiento' => 'nullable|date|after_or_equal:fecha_factura',
            'observaciones' => 'nullable|string|max:1000',
            'productos' => 'required|array|min:1',
            'productos.*.id' => 'required|exists:productos,id',
            'productos.*.cantidad' => 'required|integer|min:1',
            'productos.*.precio' => 'required|numeric|min:0.01',
            'productos.*.descuento' => 'nullable|numeric|min:0'
        ]);

        DB::beginTransaction();
        try {
            // Restaurar inventario de la factura original
            $factura->restaurarInventario();

            // Validar nuevo stock disponible
            foreach ($request->input('productos') as $productoData) {
                $inventario = Inventario::where('id_producto', $productoData['id'])
                    ->where('id_bodega', $request->input('bodega_id'))
                    ->first();

                if (!$inventario || $inventario->getAttribute('stock_disponible') < $productoData['cantidad']) {
                    $producto = Producto::find($productoData['id']);
                    throw new \Exception("Stock insuficiente para el producto: {$producto->getAttribute('nombre')}. Disponible: " . ($inventario ? $inventario->getAttribute('stock_disponible') : 0));
                }
            }

            // Actualizar factura
            $factura->update([
                'cliente_id' => $request->input('cliente_id'),
                'bodega_id' => $request->input('bodega_id'),
                'fecha_factura' => $request->input('fecha_factura'),
                'fecha_vencimiento' => $request->input('fecha_vencimiento'),
                'observaciones' => $request->input('observaciones'),
                'impuestos' => $request->input('impuestos', 0),
                'descuento' => $request->input('descuento', 0)
            ]);

            // Eliminar detalles existentes
            $factura->detalles()->delete();

            // Crear nuevos detalles
            $subtotal = 0;
            foreach ($request->input('productos') as $productoData) {
                $cantidad = $productoData['cantidad'];
                $precio = $productoData['precio'];
                $descuentoLinea = $productoData['descuento'] ?? 0;
                $subtotalLinea = ($cantidad * $precio) - $descuentoLinea;

                DetalleFactura::create([
                    'factura_id' => $factura->getAttribute('id'),
                    'producto_id' => $productoData['id'],
                    'cantidad' => $cantidad,
                    'precio_unitario' => $precio,
                    'descuento_linea' => $descuentoLinea,
                    'subtotal' => $subtotalLinea
                ]);

                $subtotal += $subtotalLinea;
            }

            // Actualizar totales
            $total = $subtotal + $factura->getAttribute('impuestos') - $factura->getAttribute('descuento');
            $factura->update([
                'subtotal' => $subtotal,
                'total' => $total
            ]);

            // Descontar nuevo inventario
            $factura->descontarInventario();

            DB::commit();
            return redirect()->route('facturas.show', $factura)
                ->with('success', 'Factura actualizada exitosamente.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                ->with('error', 'Error al actualizar la factura: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Factura $factura)
    {
        // Las facturas no se pueden eliminar, solo anular
        return redirect()->route('facturas.index')
            ->with('error', 'Las facturas no se pueden eliminar. Use la opción "Anular" si es necesario.');
    }

    /**
     * Anular una factura
     */
    public function anular(Request $request, Factura $factura)
    {
        $request->validate([
            'motivo_anulacion' => 'required|string|max:500'
        ]);

        if ($factura->anular($request->input('motivo_anulacion'))) {
            return redirect()->route('facturas.show', $factura)
                ->with('success', 'Factura anulada exitosamente.');
        }

        return back()->with('error', 'No se pudo anular la factura.');
    }

    /**
     * Marcar una factura como pagada
     */
    public function marcarPagada(Factura $factura)
    {
        if ($factura->marcarComoPagada()) {
            return redirect()->route('facturas.show', $factura)
                ->with('success', 'Factura marcada como pagada.');
        }

        return back()->with('error', 'No se pudo marcar la factura como pagada.');
    }

    /**
     * Obtener productos por bodega para AJAX
     */
    public function obtenerProductosPorBodega(Request $request)
    {
        $bodegaId = $request->get('bodega_id');
        
        $productos = Producto::whereHas('inventarios', function ($query) use ($bodegaId) {
            $query->where('id_bodega', $bodegaId)
                  ->where('stock_disponible', '>', 0);
        })
        ->with(['inventarios' => function ($query) use ($bodegaId) {
            $query->where('id_bodega', $bodegaId);
        }])
        ->get()
        ->map(function ($producto) use ($bodegaId) {
            $inventario = $producto->getAttribute('inventarios')->first();
            return [
                'id' => $producto->getAttribute('id'),
                'nombre' => $producto->getAttribute('nombre'),
                'codigo' => $producto->getAttribute('codigo'),
                'precio_venta' => $producto->getAttribute('precio_venta'),
                'stock_disponible' => $inventario ? $inventario->getAttribute('stock_disponible') : 0
            ];
        });

        return response()->json($productos);
    }
}
