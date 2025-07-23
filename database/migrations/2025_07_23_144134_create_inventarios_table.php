<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inventarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_producto')->constrained('productos');
            $table->integer('stock_actual')->default(0);
            $table->integer('stock_reservado')->default(0);
            $table->integer('stock_disponible')->default(0);
            $table->decimal('costo_promedio', 10, 2)->default(0);
            $table->timestamp('ultima_entrada')->nullable();
            $table->timestamp('ultima_salida')->nullable();
            $table->timestamps();
            
            $table->unique('id_producto'); // Un producto solo puede tener un registro de inventario
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventarios');
    }
};
