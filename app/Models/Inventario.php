<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Inventario extends Model
{
    protected $table = 'inventarios';
    
    protected $fillable = [
        'id_producto',
        'id_bodega',
        'stock_actual',
        'stock_reservado',
        'stock_disponible',
        'costo_promedio',
        'ultima_entrada',
        'ultima_salida'
    ];

    protected $casts = [
        'costo_promedio' => 'decimal:2',
        'ultima_entrada' => 'datetime',
        'ultima_salida' => 'datetime',
    ];

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'id_producto');
    }

    public function bodega(): BelongsTo
    {
        return $this->belongsTo(Bodega::class, 'id_bodega');
    }

    public function movimientos(): HasMany
    {
        return $this->hasMany(MovimientoInventario::class, 'id_inventario');
    }

    // Método para actualizar el stock disponible
    public function actualizarStockDisponible(): void
    {
        $this->stock_disponible = $this->getAttribute('stock_actual') - $this->getAttribute('stock_reservado');
        $this->save();
    }
}
