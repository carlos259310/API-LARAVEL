<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Categoria;

class CategoriasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorias = [
            ['nombre' => 'Electrónicos', 'codigo' => 'ELEC', 'descripcion' => 'Productos electrónicos y tecnológicos'],
            ['nombre' => 'Hogar y Jardín', 'codigo' => 'HOGAR', 'descripcion' => 'Artículos para el hogar y jardinería'],
            ['nombre' => 'Ropa y Accesorios', 'codigo' => 'ROPA', 'descripcion' => 'Vestimenta y accesorios de moda'],
            ['nombre' => 'Deportes', 'codigo' => 'DEPORT', 'descripcion' => 'Artículos deportivos y recreativos'],
            ['nombre' => 'Alimentación', 'codigo' => 'ALIM', 'descripcion' => 'Productos alimenticios y bebidas'],
            ['nombre' => 'Salud y Belleza', 'codigo' => 'SALUD', 'descripcion' => 'Productos de cuidado personal y salud'],
            ['nombre' => 'Oficina', 'codigo' => 'OFIC', 'descripcion' => 'Suministros y mobiliario de oficina'],
            ['nombre' => 'Automotriz', 'codigo' => 'AUTO', 'descripcion' => 'Repuestos y accesorios para vehículos'],
        ];

        foreach ($categorias as $categoria) {
            Categoria::create($categoria);
        }
    }
}
