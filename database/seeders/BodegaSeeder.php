<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bodega;

class BodegaSeeder extends Seeder
{
    public function run(): void
    {
        Bodega::create(['nombre' => 'Bodega Principal', 'ubicacion' => 'Central']);
        Bodega::create(['nombre' => 'Bodega Secundaria', 'ubicacion' => 'Sucursal Norte']);
    }
}
