<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Producto extends Model
{
    protected $table = 'productos';
    
    protected $fillable = [
        'nombre',
        'codigo',
        'codigo_barras',
        'descripcion',
        'precio_compra',
        'precio_venta',
        'stock_minimo',
        'stock_maximo',
        'unidad_medida',
        'id_categoria',
        'id_marca',
        'id_proveedor',
        'activo'
    ];

    protected $casts = [
        'precio_compra' => 'decimal:2',
        'precio_venta' => 'decimal:2',
        'activo' => 'boolean',
    ];

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class, 'id_categoria');
    }

    public function marca(): BelongsTo
    {
        return $this->belongsTo(Marca::class, 'id_marca');
    }

    public function proveedor(): BelongsTo
    {
        return $this->belongsTo(Proveedor::class, 'id_proveedor');
    }

    public function inventario(): HasOne
    {
        return $this->hasOne(Inventario::class, 'id_producto');
    }

    public function movimientosInventario(): HasMany
    {
        return $this->hasMany(MovimientoInventario::class, 'id_producto');
    }

    // Accessor para obtener el stock actual
    public function getStockActualAttribute(): int
    {
        return $this->inventario ? $this->inventario->getAttribute('stock_actual') : 0;
    }

    // Accessor para verificar si está en stock mínimo
    public function getEsStockMinimoAttribute(): bool
    {
        return $this->getStockActualAttribute() <= $this->getAttribute('stock_minimo');
    }
}
