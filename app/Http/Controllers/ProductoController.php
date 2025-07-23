<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Marca;
use App\Models\Proveedor;
use App\Models\Inventario;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productos = Producto::with(['categoria', 'marca', 'proveedor', 'inventario'])
            ->paginate(15);
            
        return view('productos.index', compact('productos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categorias = Categoria::where('activo', true)->get();
        $marcas = Marca::where('activo', true)->get();
        $proveedores = Proveedor::where('activo', true)->get();
        
        return view('productos.create', compact('categorias', 'marcas', 'proveedores'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|max:50|unique:productos,codigo',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'stock_minimo' => 'nullable|integer|min:0',
            'categoria_id' => 'required|exists:categorias,id',
            'marca_id' => 'required|exists:marcas,id',
            'proveedor_id' => 'required|exists:proveedores,id',
            'activo' => 'boolean'
        ]);

        // Mapear los campos del formulario a los de la base de datos
        $productoData = [
            'nombre' => $validated['nombre'],
            'codigo' => $validated['codigo'],
            'descripcion' => $validated['descripcion'] ?? null,
            'precio_venta' => $validated['precio'],
            'precio_compra' => $validated['precio'] * 0.7, // 30% de margen por defecto
            'stock_minimo' => $validated['stock_minimo'] ?? 5,
            'stock_maximo' => ($validated['stock_minimo'] ?? 5) * 10, // Por defecto 10 veces el mínimo
            'unidad_medida' => 'UND',
            'id_categoria' => $validated['categoria_id'],
            'id_marca' => $validated['marca_id'],
            'id_proveedor' => $validated['proveedor_id'],
            'activo' => $request->has('activo') ? true : false
        ];

        $producto = Producto::create($productoData);
        
        // Crear registro de inventario para el producto con stock inicial en 0
        Inventario::create([
            'id_producto' => $producto->getAttribute('id'),
            'stock_actual' => 0,
            'stock_reservado' => 0,
            'stock_disponible' => 0,
            'costo_promedio' => $validated['precio'] * 0.7
        ]);
        
        return redirect()->route('productos.index')->with('success', 'Producto creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $producto = Producto::with(['categoria', 'marca', 'proveedor', 'inventario', 'movimientosInventario'])
            ->findOrFail($id);
            
        return view('productos.show', compact('producto'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $producto = Producto::findOrFail($id);
        $categorias = Categoria::where('activo', true)->get();
        $marcas = Marca::where('activo', true)->get();
        $proveedores = Proveedor::where('activo', true)->get();
        
        return view('productos.edit', compact('producto', 'categorias', 'marcas', 'proveedores'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $producto = Producto::findOrFail($id);
        
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|max:50|unique:productos,codigo,' . $producto->getAttribute('id'),
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'stock_minimo' => 'required|integer|min:0',
            'categoria_id' => 'required|exists:categorias,id',
            'marca_id' => 'required|exists:marcas,id',
            'proveedor_id' => 'required|exists:proveedores,id',
            'activo' => 'boolean'
        ]);

        // Mapear los campos del formulario a los de la base de datos
        $productoData = [
            'nombre' => $validated['nombre'],
            'codigo' => $validated['codigo'],
            'descripcion' => $validated['descripcion'] ?? null,
            'precio_venta' => $validated['precio'],
            'precio_compra' => $validated['precio'] * 0.7, // Mantener margen del 30%
            'stock_minimo' => $validated['stock_minimo'],
            'stock_maximo' => $validated['stock_minimo'] * 10,
            'id_categoria' => $validated['categoria_id'],
            'id_marca' => $validated['marca_id'],
            'id_proveedor' => $validated['proveedor_id'],
            'activo' => $request->has('activo') ? true : false
        ];

        $producto->update($productoData);
        
        return redirect()->route('productos.index')->with('success', 'Producto actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $producto = Producto::findOrFail($id);
        
        // Verificar si tiene stock antes de eliminar
        if ($producto->inventario && $producto->inventario->getAttribute('stock_actual') > 0) {
            return redirect()->route('productos.index')
                ->with('error', 'No se puede eliminar un producto que tiene stock en inventario.');
        }
        
        $producto->delete();
        
        return redirect()->route('productos.index')->with('success', 'Producto eliminado exitosamente.');
    }
}
