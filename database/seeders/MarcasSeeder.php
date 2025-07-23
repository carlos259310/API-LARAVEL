<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Marca;

class MarcasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $marcas = [
            ['nombre' => 'Samsung', 'codigo' => 'SAMS', 'descripcion' => 'Productos Samsung'],
            ['nombre' => 'Apple', 'codigo' => 'APPL', 'descripcion' => 'Productos Apple'],
            ['nombre' => 'Sony', 'codigo' => 'SONY', 'descripcion' => 'Productos Sony'],
            ['nombre' => 'LG', 'codigo' => 'LG', 'descripcion' => 'Productos LG'],
            ['nombre' => 'Nike', 'codigo' => 'NIKE', 'descripcion' => 'Productos Nike'],
            ['nombre' => 'Adidas', 'codigo' => 'ADID', 'descripcion' => 'Productos Adidas'],
            ['nombre' => 'Genérica', 'codigo' => 'GEN', 'descripcion' => 'Marca genérica'],
            ['nombre' => 'HP', 'codigo' => 'HP', 'descripcion' => 'Productos Hewlett-Packard'],
        ];

        foreach ($marcas as $marca) {
            Marca::create($marca);
        }
    }
}
