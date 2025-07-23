<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Ciudad;
use App\Models\Departamento;

class CiudadesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener departamentos
        $antioquia = Departamento::where('codigo', '05')->first();
        $bogota = Departamento::where('codigo', '11')->first();
        $valle = Departamento::where('codigo', '76')->first();
        $cundinamarca = Departamento::where('codigo', '25')->first();
        $atlantico = Departamento::where('codigo', '08')->first();

        $ciudades = [
            // Antioquia
            ['nombre' => 'Medellín', 'codigo' => '05001', 'id_departamento' => $antioquia->getAttribute('id')],
            ['nombre' => 'Bello', 'codigo' => '05088', 'id_departamento' => $antioquia->getAttribute('id')],
            ['nombre' => 'Itagüí', 'codigo' => '05360', 'id_departamento' => $antioquia->getAttribute('id')],
            
            // Bogotá D.C.
            ['nombre' => 'Bogotá D.C.', 'codigo' => '11001', 'id_departamento' => $bogota->getAttribute('id')],
            
            // Valle del Cauca
            ['nombre' => 'Cali', 'codigo' => '76001', 'id_departamento' => $valle->getAttribute('id')],
            ['nombre' => 'Palmira', 'codigo' => '76520', 'id_departamento' => $valle->getAttribute('id')],
            ['nombre' => 'Buenaventura', 'codigo' => '76109', 'id_departamento' => $valle->getAttribute('id')],
            
            // Cundinamarca
            ['nombre' => 'Soacha', 'codigo' => '25754', 'id_departamento' => $cundinamarca->getAttribute('id')],
            ['nombre' => 'Girardot', 'codigo' => '25307', 'id_departamento' => $cundinamarca->getAttribute('id')],
            ['nombre' => 'Zipaquirá', 'codigo' => '25899', 'id_departamento' => $cundinamarca->getAttribute('id')],
            
            // Atlántico
            ['nombre' => 'Barranquilla', 'codigo' => '08001', 'id_departamento' => $atlantico->getAttribute('id')],
            ['nombre' => 'Soledad', 'codigo' => '08758', 'id_departamento' => $atlantico->getAttribute('id')],
            ['nombre' => 'Malambo', 'codigo' => '08433', 'id_departamento' => $atlantico->getAttribute('id')],
        ];

        foreach ($ciudades as $ciudad) {
            Ciudad::create($ciudad);
        }
    }
}
