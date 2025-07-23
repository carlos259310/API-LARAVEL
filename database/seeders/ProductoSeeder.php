<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;
use App\Models\Inventario;
use App\Models\Bodega;
use App\Models\Categoria;
use App\Models\Marca;
use App\Models\Proveedor;
use Carbon\Carbon;

class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Primero obtener las categorías, marcas y proveedores existentes
        $categoria = Categoria::first();
        $marca = Marca::first();
        $proveedor = Proveedor::first();

        if (!$categoria || !$marca || !$proveedor) {
            $this->command->error('Faltan datos base: categorías, marcas o proveedores');
            return;
        }

        $productos = [
            [
                'codigo' => 'LAP001',
                'nombre' => 'Laptop Dell Inspiron',
                'descripcion' => 'Laptop para oficina con procesador Intel Core i5',
                'precio_venta' => 1200.00,
                'precio_compra' => 800.00,
                'stock_minimo' => 5,
                'stock_maximo' => 50,
                'unidad_medida' => 'UND',
                'id_categoria' => $categoria->getAttribute('id'),
                'id_marca' => $marca->getAttribute('id'),
                'id_proveedor' => $proveedor->getAttribute('id'),
                'activo' => true
            ],
            [
                'codigo' => 'MOU001', 
                'nombre' => 'Mouse Logitech',
                'descripcion' => 'Mouse inalámbrico ergonómico',
                'precio_venta' => 25.00,
                'precio_compra' => 15.00,
                'stock_minimo' => 10,
                'stock_maximo' => 100,
                'unidad_medida' => 'UND',
                'id_categoria' => $categoria->getAttribute('id'),
                'id_marca' => $marca->getAttribute('id'),
                'id_proveedor' => $proveedor->getAttribute('id'),
                'activo' => true
            ],
            [
                'codigo' => 'TEC001',
                'nombre' => 'Teclado HP Mecánico',
                'descripcion' => 'Teclado mecánico con retroiluminación',
                'precio_venta' => 75.00,
                'precio_compra' => 45.00,
                'stock_minimo' => 8,
                'stock_maximo' => 80,
                'unidad_medida' => 'UND',
                'id_categoria' => $categoria->getAttribute('id'),
                'id_marca' => $marca->getAttribute('id'),
                'id_proveedor' => $proveedor->getAttribute('id'),
                'activo' => true
            ],
            [
                'codigo' => 'MON001',
                'nombre' => 'Monitor Samsung 24"',
                'descripcion' => 'Monitor LED de 24 pulgadas Full HD',
                'precio_venta' => 300.00,
                'precio_compra' => 200.00,
                'stock_minimo' => 3,
                'stock_maximo' => 30,
                'unidad_medida' => 'UND',
                'id_categoria' => $categoria->getAttribute('id'),
                'id_marca' => $marca->getAttribute('id'),
                'id_proveedor' => $proveedor->getAttribute('id'),
                'activo' => true
            ],
            [
                'codigo' => 'IMP001',
                'nombre' => 'Impresora Canon',
                'descripcion' => 'Impresora multifuncional láser',
                'precio_venta' => 150.00,
                'precio_compra' => 100.00,
                'stock_minimo' => 2,
                'stock_maximo' => 20,
                'unidad_medida' => 'UND',
                'id_categoria' => $categoria->getAttribute('id'),
                'id_marca' => $marca->getAttribute('id'),
                'id_proveedor' => $proveedor->getAttribute('id'),
                'activo' => true
            ]
        ];

        foreach ($productos as $productoData) {
            // Verificar si el producto ya existe
            $existe = Producto::where('codigo', $productoData['codigo'])->first();
            if ($existe) {
                $this->command->info('Producto ya existe: ' . $productoData['nombre']);
                continue;
            }

            $producto = Producto::create($productoData);
            
            // Crear inventario inicial (usando la primera bodega disponible)
            $bodega = Bodega::first();
            if ($bodega) {
                $stockInicial = rand(50, 200);
                // Verificar si ya existe inventario para este producto
                $inventarioExiste = Inventario::where('id_producto', $producto->getAttribute('id'))->exists();
                if (!$inventarioExiste) {
                    Inventario::create([
                        'id_producto' => $producto->getAttribute('id'),
                        'id_bodega' => $bodega->getAttribute('id'),
                        'stock_actual' => $stockInicial,
                        'stock_reservado' => 0,
                        'stock_disponible' => $stockInicial,
                        'costo_promedio' => $producto->getAttribute('precio_compra'),
                        'ultima_entrada' => Carbon::now()->subDays(rand(1, 30)),
                    ]);
                }
            }
            
            $this->command->info('Producto creado: ' . $producto->getAttribute('nombre'));
        }

        $this->command->info('Proceso completado. Total productos en BD: ' . Producto::count());
    }
}
