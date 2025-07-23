<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('inventarios', function (Blueprint $table) {
            $table->foreignId('id_bodega')->after('id_producto')->constrained('bodegas');
        });
    }
    public function down(): void
    {
        Schema::table('inventarios', function (Blueprint $table) {
            $table->dropForeign(['id_bodega']);
            $table->dropColumn('id_bodega');
        });
    }
};
