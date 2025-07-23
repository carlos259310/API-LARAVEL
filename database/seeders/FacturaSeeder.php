<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Factura;
use App\Models\DetalleFactura;
use App\Models\Cliente;
use App\Models\Bodega;
use App\Models\Producto;
use App\Models\Inventario;
use Carbon\Carbon;

class FacturaSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Obtener datos necesarios
        $clientes = Cliente::take(3)->get();
        $bodegas = Bodega::take(2)->get();
        $productos = Producto::take(5)->get();

        if ($clientes->isEmpty() || $bodegas->isEmpty() || $productos->isEmpty()) {
            $this->command->info('No hay suficientes datos base para crear facturas de prueba');
            return;
        }

        // Ya no necesitamos crear inventario aquí porque se creó en ProductoSeeder
        // Solo verificamos que existan productos con inventario
        $productosConInventario = Producto::whereHas('inventario')->get();
        
        if ($productosConInventario->isEmpty()) {
            $this->command->info('No hay productos con inventario para crear facturas');
            return;
        }

        // Crear facturas de prueba
        foreach ($clientes as $index => $cliente) {
            $factura = Factura::create([
                'prefijo' => 'FAC',
                'consecutivo' => $index + 1,
                'cliente_id' => $cliente->getAttribute('id'),
                'bodega_id' => $bodegas->first()->getAttribute('id'),
                'fecha_factura' => Carbon::now()->subDays($index),
                'fecha_vencimiento' => Carbon::now()->addDays(30 - $index),
                'estado' => $index === 0 ? 'pendiente' : ($index === 1 ? 'pagada' : 'pendiente'),
                'subtotal' => 0,
                'impuestos' => 0,
                'descuento' => 0,
                'total' => 0,
                'observaciones' => 'Factura de prueba ' . ($index + 1),
                'fecha_pago' => $index === 1 ? Carbon::now()->subDays($index) : null,
            ]);

            // Crear detalles de factura
            $subtotal = 0;
            $productosFactura = $productos->take(rand(2, 3));

            foreach ($productosFactura as $producto) {
                $cantidad = rand(1, 3);
                $precio = $producto->getAttribute('precio_venta') ?? 100.00;
                $subtotalLinea = $cantidad * $precio;

                DetalleFactura::create([
                    'factura_id' => $factura->getAttribute('id'),
                    'producto_id' => $producto->getAttribute('id'),
                    'cantidad' => $cantidad,
                    'precio_unitario' => $precio,
                    'descuento_linea' => 0,
                    'subtotal' => $subtotalLinea,
                ]);

                $subtotal += $subtotalLinea;

                // Descontar del inventario para facturas que no están anuladas
                if ($factura->getAttribute('estado') !== 'anulada') {
                    $inventario = Inventario::where('id_producto', $producto->getAttribute('id'))
                        ->first(); // Removí where bodega porque puede que no coincida

                    if ($inventario) {
                        $inventario->decrement('stock_actual', $cantidad);
                        $inventario->decrement('stock_disponible', $cantidad);
                    }
                }
            }

            // Actualizar totales de la factura
            $factura->update([
                'subtotal' => $subtotal,
                'total' => $subtotal,
            ]);
        }

        $this->command->info('Se crearon ' . $clientes->count() . ' facturas de prueba');
    }
}
