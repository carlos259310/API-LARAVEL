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
        Schema::create('facturas', function (Blueprint $table) {
            $table->id();
            $table->string('prefijo', 10)->default('FAC');
            $table->unsignedBigInteger('consecutivo');
            $table->foreignId('cliente_id')->constrained('clientes');
            $table->foreignId('bodega_id')->constrained('bodegas');
            $table->date('fecha_factura');
            $table->date('fecha_vencimiento')->nullable();
            $table->enum('estado', ['pendiente', 'pagada', 'anulada'])->default('pendiente');
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('impuestos', 12, 2)->default(0);
            $table->decimal('descuento', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->text('observaciones')->nullable();
            $table->timestamp('fecha_pago')->nullable();
            $table->timestamp('fecha_anulacion')->nullable();
            $table->string('motivo_anulacion')->nullable();
            $table->timestamps();
            
            // Índices únicos
            $table->unique(['prefijo', 'consecutivo']);
            $table->index(['cliente_id', 'fecha_factura']);
            $table->index(['bodega_id', 'fecha_factura']);
            $table->index(['estado', 'fecha_factura']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facturas');
    }
};
