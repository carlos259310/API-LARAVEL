<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Marca;
use App\Models\Proveedor;
use App\Models\Inventario;

class ProductosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productos = [
            [
                'codigo' => 'LAP001',
                'nombre' => 'Laptop Dell Inspiron 15',
                'descripcion' => 'Laptop Dell Inspiron 15 3000, Intel Core i5, 8GB RAM, 256GB SSD',
                'categoria' => 'Computadoras',
                'marca' => 'Dell',
                'proveedor' => 'TechnoPlus',
                'precio' => 1250000,
                'stock_minimo' => 3,
                'stock_inicial' => 8
            ],
            [
                'codigo' => 'CEL001',
                'nombre' => 'Samsung Galaxy A54',
                'descripcion' => 'Smartphone Samsung Galaxy A54 5G, 128GB, 6GB RAM',
                'categoria' => 'Celulares',
                'marca' => 'Samsung',
                'proveedor' => 'Distribuidora El Sol',
                'precio' => 850000,
                'stock_minimo' => 5,
                'stock_inicial' => 12
            ],
            [
                'codigo' => 'TV001',
                'nombre' => 'Smart TV LG 55"',
                'descripcion' => 'Smart TV LG 55" 4K UHD webOS, HDR10',
                'categoria' => 'Televisores',
                'marca' => 'LG',
                'proveedor' => 'TechnoPlus',
                'precio' => 2100000,
                'stock_minimo' => 2,
                'stock_inicial' => 5
            ],
            [
                'codigo' => 'TAB001',
                'nombre' => 'iPad Air 10.9"',
                'descripcion' => 'iPad Air 10.9" M1, 64GB, WiFi',
                'categoria' => 'Tablets',
                'marca' => 'Apple',
                'proveedor' => 'Importadora Bogotá',
                'precio' => 2800000,
                'stock_minimo' => 2,
                'stock_inicial' => 4
            ],
            [
                'codigo' => 'AUD001',
                'nombre' => 'Audífonos Sony WH-1000XM4',
                'descripcion' => 'Audífonos inalámbricos Sony con cancelación de ruido',
                'categoria' => 'Audio',
                'marca' => 'Sony',
                'proveedor' => 'TechnoPlus',
                'precio' => 680000,
                'stock_minimo' => 4,
                'stock_inicial' => 10
            ],
            [
                'codigo' => 'IMP001',
                'nombre' => 'Impresora HP LaserJet Pro',
                'descripcion' => 'Impresora HP LaserJet Pro M404n, monocromática',
                'categoria' => 'Impresoras',
                'marca' => 'HP',
                'proveedor' => 'Comercial Medellín',
                'precio' => 450000,
                'stock_minimo' => 3,
                'stock_inicial' => 6
            ],
            [
                'codigo' => 'MON001',
                'nombre' => 'Monitor Samsung 24"',
                'descripcion' => 'Monitor Samsung 24" Full HD, 75Hz',
                'categoria' => 'Monitores',
                'marca' => 'Samsung',
                'proveedor' => 'Distribuidora El Sol',
                'precio' => 320000,
                'stock_minimo' => 4,
                'stock_inicial' => 8
            ],
            [
                'codigo' => 'GAM001',
                'nombre' => 'PlayStation 5',
                'descripcion' => 'Consola PlayStation 5, 825GB SSD',
                'categoria' => 'Gaming',
                'marca' => 'Sony',
                'proveedor' => 'Importadora Bogotá',
                'precio' => 2500000,
                'stock_minimo' => 1,
                'stock_inicial' => 3
            ]
        ];

        foreach ($productos as $productoData) {
            // Buscar o crear categoría
            $categoria = Categoria::where('nombre', $productoData['categoria'])->first();
            if (!$categoria) {
                $categoria = Categoria::create([
                    'nombre' => $productoData['categoria'],
                    'codigo' => strtoupper(substr($productoData['categoria'], 0, 3)) . rand(100, 999)
                ]);
            }

            // Buscar o crear marca
            $marca = Marca::where('nombre', $productoData['marca'])->first();
            if (!$marca) {
                $marca = Marca::create([
                    'nombre' => $productoData['marca'],
                    'codigo' => strtoupper(substr($productoData['marca'], 0, 3)) . rand(100, 999)
                ]);
            }

            // Buscar o crear proveedor
            $proveedor = Proveedor::where('nombre', $productoData['proveedor'])->first();
            if (!$proveedor) {
                $proveedor = Proveedor::create([
                    'nombre' => $productoData['proveedor'],
                    'codigo' => strtoupper(substr(str_replace(' ', '', $productoData['proveedor']), 0, 3)) . rand(100, 999),
                    'nit' => rand(100000000, 999999999) . '-' . rand(1, 9),
                    'contacto_principal' => 'Contacto ' . $productoData['proveedor'],
                    'telefono' => '601' . rand(1000000, 9999999),
                    'email' => strtolower(str_replace(' ', '', $productoData['proveedor'])) . '@email.com',
                    'direccion' => 'Dirección ' . $productoData['proveedor']
                ]);
            }

            // Crear producto
            $producto = Producto::create([
                'codigo' => $productoData['codigo'],
                'nombre' => $productoData['nombre'],
                'descripcion' => $productoData['descripcion'],
                'id_categoria' => $categoria->getAttribute('id'),
                'id_marca' => $marca->getAttribute('id'),
                'id_proveedor' => $proveedor->getAttribute('id'),
                'precio_venta' => $productoData['precio'],
                'precio_compra' => $productoData['precio'] * 0.7, // 30% de margen
                'stock_minimo' => $productoData['stock_minimo'],
                'activo' => true
            ]);

            // Crear inventario inicial
            Inventario::create([
                'id_producto' => $producto->getAttribute('id'),
                'stock_actual' => $productoData['stock_inicial'],
                'stock_disponible' => $productoData['stock_inicial'],
                'costo_promedio' => $productoData['precio'] * 0.7,
                'ultima_entrada' => now()
            ]);
        }
    }
}
