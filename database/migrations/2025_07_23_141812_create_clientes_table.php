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
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_1');
            $table->string('nombre_2')->nullable();
            $table->string('apellido_1');
            $table->string('apellido_2')->nullable();
            $table->string('email')->unique()->nullable();
            $table->foreignId('id_tipo_documento')->constrained('tipos_documentos');
            $table->string('numero_documento')->unique();
            $table->string('razon_social')->nullable();
            $table->enum('tipo_cliente', ['natural', 'juridico'])->default('natural');
            $table->string('telefono')->nullable();
            $table->text('direccion')->nullable();
            $table->foreignId('id_ciudad')->constrained('ciudades');
            $table->foreignId('id_departamento')->constrained('departamentos');
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
