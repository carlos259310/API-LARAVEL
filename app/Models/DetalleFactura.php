<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetalleFactura extends Model
{
    protected $fillable = [
        'factura_id',
        'producto_id',
        'cantidad',
        'precio_unitario',
        'descuento_linea',
        'subtotal'
    ];

    protected $casts = [
        'cantidad' => 'integer',
        'precio_unitario' => 'decimal:2',
        'descuento_linea' => 'decimal:2',
        'subtotal' => 'decimal:2'
    ];

    // Usar getAttribute() para evitar alertas de IDE
    public function getAttribute($key)
    {
        return parent::getAttribute($key);
    }

    // Relaciones
    public function factura(): BelongsTo
    {
        return $this->belongsTo(Factura::class);
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    // Mutadores
    public function setPrecioUnitarioAttribute($value)
    {
        $this->attributes['precio_unitario'] = $value;
        $this->calcularSubtotal();
    }

    public function setCantidadAttribute($value)
    {
        $this->attributes['cantidad'] = $value;
        $this->calcularSubtotal();
    }

    public function setDescuentoLineaAttribute($value)
    {
        $this->attributes['descuento_linea'] = $value;
        $this->calcularSubtotal();
    }

    // Métodos de negocio
    private function calcularSubtotal(): void
    {
        $cantidad = $this->attributes['cantidad'] ?? 0;
        $precio = $this->attributes['precio_unitario'] ?? 0;
        $descuento = $this->attributes['descuento_linea'] ?? 0;
        
        $this->attributes['subtotal'] = ($cantidad * $precio) - $descuento;
    }

    public function getTotalAttribute(): float
    {
        return $this->getAttribute('subtotal');
    }
}
