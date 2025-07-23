<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Producto;
use App\Models\Inventario;

class InventarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Verificar que no existan inventarios
        if (Inventario::count() > 0) {
            $this->command->info('Los inventarios ya existen, no se crearán nuevos registros');
            return;
        }

        // Crear inventarios para todos los productos
        $productos = Producto::all();
        
        foreach ($productos as $producto) {
            Inventario::create([
                'id_producto' => $producto->getAttribute('id'),
                'stock_actual' => rand(10, 100),
                'stock_reservado' => 0,
                'stock_disponible' => rand(10, 100),
                'costo_promedio' => $producto->precio_compra ?? 0,
                'ultima_entrada' => now(),
                'ultima_salida' => null
            ]);
        }

        $this->command->info('Se crearon ' . $productos->count() . ' registros de inventario');
    }
}
