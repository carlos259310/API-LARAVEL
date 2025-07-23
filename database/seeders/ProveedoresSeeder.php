<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Proveedor;

class ProveedoresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $proveedores = [
            [
                'nombre' => 'Distribuidora Electrónicos Ltda',
                'codigo' => 'DELEC001',
                'nit' => '900123456-1',
                'email' => 'ventas@electronicos.com',
                'telefono' => '6012345678',
                'direccion' => 'Calle 100 #50-25, Bogotá',
                'contacto_principal' => 'Carlos Rodríguez'
            ],
            [
                'nombre' => 'Suministros Industriales S.A.S',
                'codigo' => 'SUMIN001',
                'nit' => '900654321-2',
                'email' => 'compras@suministros.com',
                'telefono' => '6019876543',
                'direccion' => 'Carrera 30 #80-15, Bogotá',
                'contacto_principal' => 'María González'
            ],
            [
                'nombre' => 'Importadora Tecnológica',
                'codigo' => 'IMPOR001',
                'nit' => '900111222-3',
                'email' => 'info@importech.com',
                'telefono' => '6015555666',
                'direccion' => 'Zona Franca, Bogotá',
                'contacto_principal' => 'Luis Martínez'
            ],
            [
                'nombre' => 'Mayorista Nacional',
                'codigo' => 'MAYOR001',
                'nit' => '900333444-4',
                'email' => 'mayorista@nacional.com',
                'telefono' => '6017777888',
                'direccion' => 'Avenida 68 #40-50, Bogotá',
                'contacto_principal' => 'Ana López'
            ],
        ];

        foreach ($proveedores as $proveedor) {
            Proveedor::create($proveedor);
        }
    }
}
