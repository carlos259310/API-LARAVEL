<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria;
use App\Models\Marca;
use App\Models\Proveedor;

class CatalogoSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Crear categorías
        $categorias = [
            ['nombre' => 'Computadoras', 'codigo' => 'COMP', 'descripcion' => 'Laptops y computadoras de escritorio'],
            ['nombre' => 'Accesorios', 'codigo' => 'ACC', 'descripcion' => 'Mouse, teclados y accesorios de computadora'],
            ['nombre' => 'Monitores', 'codigo' => 'MON', 'descripcion' => 'Pantallas y monitores'],
            ['nombre' => 'Impresoras', 'codigo' => 'IMP', 'descripcion' => 'Impresoras y equipos de impresión'],
            ['nombre' => 'Tablets', 'codigo' => 'TAB', 'descripcion' => 'Tablets y dispositivos móviles'],
        ];

        foreach ($categorias as $categoria) {
            Categoria::create($categoria);
        }

        // Crear marcas
        $marcas = [
            ['nombre' => 'Dell', 'codigo' => 'DELL', 'descripcion' => 'Marca de computadoras Dell'],
            ['nombre' => 'HP', 'codigo' => 'HP', 'descripcion' => 'Hewlett-Packard'],
            ['nombre' => 'Logitech', 'codigo' => 'LOG', 'descripcion' => 'Accesorios de computadora'],
            ['nombre' => 'Samsung', 'codigo' => 'SAM', 'descripcion' => 'Electrónicos Samsung'],
            ['nombre' => 'Canon', 'codigo' => 'CAN', 'descripcion' => 'Equipos de impresión Canon'],
        ];

        foreach ($marcas as $marca) {
            Marca::create($marca);
        }

        // Crear proveedores
        $proveedores = [
            [
                'nombre' => 'Distribuidora TecnoMax',
                'contacto' => 'Juan Pérez',
                'telefono' => '+57 300 123 4567',
                'email' => 'ventas@tecnomaxdist.com',
                'direccion' => 'Calle 123 #45-67, Bogotá'
            ],
            [
                'nombre' => 'Importadora Digital S.A.S',
                'contacto' => 'María González',
                'telefono' => '+57 301 987 6543',
                'email' => 'compras@impdigital.com',
                'direccion' => 'Carrera 78 #12-34, Medellín'
            ],
            [
                'nombre' => 'TechSupply Colombia',
                'contacto' => 'Carlos Rodríguez',
                'telefono' => '+57 302 456 7890',
                'email' => 'info@techsupply.co',
                'direccion' => 'Avenida 68 #25-89, Cali'
            ]
        ];

        foreach ($proveedores as $proveedor) {
            Proveedor::create($proveedor);
        }

        $this->command->info('Catálogo creado: ' . count($categorias) . ' categorías, ' . count($marcas) . ' marcas, ' . count($proveedores) . ' proveedores');
    }
}
