<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Cliente;
use App\Models\TipoDocumento;
use App\Models\Departamento;
use App\Models\Ciudad;

class ClientesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener algunos registros para las relaciones
        $tipoCC = TipoDocumento::where('codigo', 'CC')->first();
        $tipoNIT = TipoDocumento::where('codigo', 'NIT')->first();
        
        $bogota = Ciudad::where('codigo', '11001')->first();
        $medellin = Ciudad::where('codigo', '05001')->first();
        $cali = Ciudad::where('codigo', '76001')->first();
        
        $deptBogota = Departamento::where('codigo', '11')->first();
        $deptAntioquia = Departamento::where('codigo', '05')->first();
        $deptValle = Departamento::where('codigo', '76')->first();

        $clientes = [
            [
                'nombre_1' => 'Juan',
                'nombre_2' => 'Carlos',
                'apellido_1' => 'Pérez',
                'apellido_2' => 'Gómez',
                'email' => 'juan.perez@email.com',
                'id_tipo_documento' => $tipoCC->getAttribute('id'),
                'numero_documento' => '1234567890',
                'tipo_cliente' => 'natural',
                'telefono' => '3001234567',
                'direccion' => 'Calle 123 #45-67',
                'id_ciudad' => $bogota->getAttribute('id'),
                'id_departamento' => $deptBogota->getAttribute('id'),
                'activo' => true,
            ],
            [
                'nombre_1' => 'María',
                'apellido_1' => 'González',
                'apellido_2' => 'Rodríguez',
                'email' => 'maria.gonzalez@email.com',
                'id_tipo_documento' => $tipoCC->getAttribute('id'),
                'numero_documento' => '9876543210',
                'tipo_cliente' => 'natural',
                'telefono' => '3109876543',
                'direccion' => 'Carrera 50 #30-20',
                'id_ciudad' => $medellin->getAttribute('id'),
                'id_departamento' => $deptAntioquia->getAttribute('id'),
                'activo' => true,
            ],
            [
                'nombre_1' => 'Luis',
                'apellido_1' => 'Martínez',
                'razon_social' => 'Empresa ABC S.A.S.',
                'email' => 'contacto@empresaabc.com',
                'id_tipo_documento' => $tipoNIT->getAttribute('id'),
                'numero_documento' => '9001234567',
                'tipo_cliente' => 'juridico',
                'telefono' => '6012345678',
                'direccion' => 'Avenida 6 #15-30 Oficina 201',
                'id_ciudad' => $cali->getAttribute('id'),
                'id_departamento' => $deptValle->getAttribute('id'),
                'activo' => true,
            ],
            [
                'nombre_1' => 'Ana',
                'nombre_2' => 'Sofía',
                'apellido_1' => 'López',
                'id_tipo_documento' => $tipoCC->getAttribute('id'),
                'numero_documento' => '5555666677',
                'tipo_cliente' => 'natural',
                'telefono' => '3205555666',
                'id_ciudad' => $bogota->getAttribute('id'),
                'id_departamento' => $deptBogota->getAttribute('id'),
                'activo' => false,
            ],
        ];

        foreach ($clientes as $cliente) {
            Cliente::create($cliente);
        }
    }
}
