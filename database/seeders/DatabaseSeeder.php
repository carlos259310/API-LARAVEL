<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            TiposDocumentosSeeder::class,
            DepartamentosSeeder::class,
            CiudadesSeeder::class,
            ClientesSeeder::class,
            CategoriasSeeder::class,
            MarcasSeeder::class,
            ProveedoresSeeder::class,
        ]);

        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
