<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TipoDocumento;

class TiposDocumentosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipos = [
            ['nombre' => 'Cédula de Ciudadanía', 'codigo' => 'CC', 'descripcion' => 'Cédula de Ciudadanía Colombiana'],
            ['nombre' => 'Cédula de Extranjería', 'codigo' => 'CE', 'descripcion' => 'Cédula de Extranjería'],
            ['nombre' => 'NIT', 'codigo' => 'NIT', 'descripcion' => 'Número de Identificación Tributaria'],
            ['nombre' => 'Pasaporte', 'codigo' => 'PA', 'descripcion' => 'Pasaporte'],
            ['nombre' => 'Tarjeta de Identidad', 'codigo' => 'TI', 'descripcion' => 'Tarjeta de Identidad para menores'],
        ];

        foreach ($tipos as $tipo) {
            TipoDocumento::create($tipo);
        }
    }
}
