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
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('codigo', 50)->unique();
            $table->string('codigo_barras')->unique()->nullable();
            $table->text('descripcion')->nullable();
            $table->decimal('precio_compra', 10, 2)->default(0);
            $table->decimal('precio_venta', 10, 2)->default(0);
            $table->integer('stock_minimo')->default(0);
            $table->integer('stock_maximo')->default(0);
            $table->string('unidad_medida', 20)->default('UND'); // UND, KG, LT, etc
            $table->foreignId('id_categoria')->constrained('categorias');
            $table->foreignId('id_marca')->constrained('marcas');
            $table->foreignId('id_proveedor')->constrained('proveedores');
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
