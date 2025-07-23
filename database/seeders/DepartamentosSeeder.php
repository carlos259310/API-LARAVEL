<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Departamento;

class DepartamentosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departamentos = [
            ['nombre' => 'Antioquia', 'codigo' => '05'],
            ['nombre' => 'Bogotá D.C.', 'codigo' => '11'],
            ['nombre' => 'Valle del Cauca', 'codigo' => '76'],
            ['nombre' => 'Cundinamarca', 'codigo' => '25'],
            ['nombre' => 'Atlántico', 'codigo' => '08'],
            ['nombre' => 'Santander', 'codigo' => '68'],
            ['nombre' => 'Bolívar', 'codigo' => '13'],
            ['nombre' => 'Norte de Santander', 'codigo' => '54'],
            ['nombre' => 'Córdoba', 'codigo' => '23'],
            ['nombre' => 'Tolima', 'codigo' => '73'],
        ];

        foreach ($departamentos as $departamento) {
            Departamento::create($departamento);
        }
    }
}
