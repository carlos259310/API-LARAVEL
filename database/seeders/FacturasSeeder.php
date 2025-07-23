<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Factura;
use App\Models\DetalleFactura;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\Bodega;
use App\Models\Inventario;
use App\Models\MovimientoInventario;
use Illuminate\Support\Facades\DB;

class FacturasSeeder extends Seeder
{
    public function run(): void
    {
        $clientes = Cliente::limit(3)->get();
        $productos = Producto::limit(5)->get();
        $bodegas = Bodega::all();

        if ($clientes->isEmpty() || $productos->isEmpty() || $bodegas->isEmpty()) {
            echo "No hay suficientes datos para crear facturas.\n";
            return;
        }

        for ($i = 1; $i <= 5; $i++) {
            DB::beginTransaction();
            try {
                $cliente = $clientes->random();
                $bodega = $bodegas->random();

                $factura = Factura::create([
                    'prefijo' => 'FAC',
                    'consecutivo' => $i,
                    'cliente_id' => $cliente->getAttribute('id'),
                    'id_bodega' => $bodega->getAttribute('id'),
                    'estado' => collect(['pendiente', 'pagada'])->random(),
                    'total' => 0
                ]);

                $total = 0;
                $numProductos = rand(1, 3);
                
                for ($j = 0; $j < $numProductos; $j++) {
                    $producto = $productos->random();
                    $cantidad = rand(1, 3);
                    
                    // Verificar si hay inventario
                    $inventario = Inventario::where('id_producto', $producto->getAttribute('id'))
                        ->where('id_bodega', $bodega->getAttribute('id'))
                        ->first();
                    
                    if (!$inventario || $inventario->getAttribute('stock_actual') < $cantidad) {
                        // Crear o actualizar inventario con stock suficiente
                        if (!$inventario) {
                            $inventario = Inventario::create([
                                'id_producto' => $producto->getAttribute('id'),
                                'id_bodega' => $bodega->getAttribute('id'),
                                'stock_actual' => $cantidad + 10,
                                'stock_disponible' => $cantidad + 10,
                                'costo_promedio' => $producto->getAttribute('precio_compra') ?? 0
                            ]);
                        } else {
                            $inventario->update([
                                'stock_actual' => $inventario->getAttribute('stock_actual') + $cantidad + 10,
                                'stock_disponible' => $inventario->getAttribute('stock_disponible') + $cantidad + 10
                            ]);
                        }
                    }

                    $subtotal = $producto->getAttribute('precio_venta') * $cantidad;
                    
                    DetalleFactura::create([
                        'factura_id' => $factura->getAttribute('id'),
                        'producto_id' => $producto->getAttribute('id'),
                        'cantidad' => $cantidad,
                        'precio_unitario' => $producto->getAttribute('precio_venta'),
                        'subtotal' => $subtotal
                    ]);

                    // Descontar del inventario
                    $inventario->update([
                        'stock_actual' => $inventario->getAttribute('stock_actual') - $cantidad,
                        'stock_disponible' => $inventario->getAttribute('stock_disponible') - $cantidad
                    ]);

                    // Registrar movimiento
                    MovimientoInventario::create([
                        'id_producto' => $producto->getAttribute('id'),
                        'id_bodega' => $bodega->getAttribute('id'),
                        'tipo_movimiento' => 'salida',
                        'cantidad' => $cantidad,
                        'observaciones' => 'Salida por facturación FAC-' . $i,
                    ]);

                    $total += $subtotal;
                }

                $factura->update(['total' => $total]);
                DB::commit();
                
            } catch (\Exception $e) {
                DB::rollBack();
                echo "Error creando factura $i: " . $e->getMessage() . "\n";
            }
        }
    }
}
