<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MovimientoInventario extends Model
{
    protected $table = 'movimientos_inventario';
    
    protected $fillable = [
        'id_producto',
        'tipo_movimiento',
        'cantidad',
        'costo_unitario',
        'costo_total',
        'numero_documento',
        'observaciones',
        'motivo',
        'usuario',
        'fecha_movimiento'
    ];

    protected $casts = [
        'costo_unitario' => 'decimal:2',
        'costo_total' => 'decimal:2',
        'fecha_movimiento' => 'datetime',
    ];

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'id_producto');
    }

    // Boot method para calcular automáticamente el costo total
    protected static function boot()
    {
        parent::boot();
        
        static::saving(function ($model) {
            $model->costo_total = $model->getAttribute('cantidad') * $model->getAttribute('costo_unitario');
        });
    }
}
