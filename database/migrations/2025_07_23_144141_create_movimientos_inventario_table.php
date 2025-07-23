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
        Schema::create('movimientos_inventario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_producto')->constrained('productos');
            $table->enum('tipo_movimiento', ['entrada', 'salida']);
            $table->integer('cantidad');
            $table->decimal('costo_unitario', 10, 2)->default(0);
            $table->decimal('costo_total', 10, 2)->default(0);
            $table->string('numero_documento')->nullable();
            $table->text('observaciones')->nullable();
            $table->string('usuario')->nullable(); // Para saber quién hizo el movimiento
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimientos_inventario');
    }
};
