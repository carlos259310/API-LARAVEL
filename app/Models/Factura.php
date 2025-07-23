<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Factura extends Model
{
    protected $fillable = [
        'prefijo',
        'consecutivo',
        'cliente_id',
        'bodega_id',
        'fecha_factura',
        'fecha_vencimiento',
        'estado',
        'subtotal',
        'impuestos',
        'descuento',
        'total',
        'observaciones',
        'fecha_pago',
        'fecha_anulacion',
        'motivo_anulacion'
    ];

    protected $casts = [
        'fecha_factura' => 'date',
        'fecha_vencimiento' => 'date',
        'fecha_pago' => 'datetime',
        'fecha_anulacion' => 'datetime',
        'subtotal' => 'decimal:2',
        'impuestos' => 'decimal:2',
        'descuento' => 'decimal:2',
        'total' => 'decimal:2'
    ];

    // Usar getAttribute() para evitar alertas de IDE
    public function getAttribute($key)
    {
        return parent::getAttribute($key);
    }

    // Relaciones
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function bodega(): BelongsTo
    {
        return $this->belongsTo(Bodega::class);
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(DetalleFactura::class);
    }

    // Mutadores y Accesorios
    public function getNumeroFacturaAttribute(): string
    {
        return $this->getAttribute('prefijo') . '-' . str_pad($this->getAttribute('consecutivo'), 6, '0', STR_PAD_LEFT);
    }

    public function getEstaPagadaAttribute(): bool
    {
        return $this->getAttribute('estado') === 'pagada';
    }

    public function getEstaAnuladaAttribute(): bool
    {
        return $this->getAttribute('estado') === 'anulada';
    }

    public function getPuedeModificarseAttribute(): bool
    {
        return $this->getAttribute('estado') === 'pendiente';
    }

    // Métodos de negocio
    public static function obtenerSiguienteConsecutivo(string $prefijo = 'FAC'): int
    {
        $ultimo = static::where('prefijo', $prefijo)->max('consecutivo');
        return $ultimo ? $ultimo + 1 : 1;
    }

    public function calcularTotales(): void
    {
        $subtotal = $this->detalles()->sum('subtotal');
        $this->update([
            'subtotal' => $subtotal,
            'total' => $subtotal + $this->getAttribute('impuestos') - $this->getAttribute('descuento')
        ]);
    }

    public function marcarComoPagada(): bool
    {
        if ($this->getAttribute('estado') !== 'pendiente') {
            return false;
        }

        return $this->update([
            'estado' => 'pagada',
            'fecha_pago' => Carbon::now()
        ]);
    }

    public function anular(string $motivo = null): bool
    {
        if ($this->getAttribute('estado') === 'anulada') {
            return false;
        }

        DB::beginTransaction();
        try {
            // Restaurar inventario si la factura estaba pendiente o pagada
            if (in_array($this->getAttribute('estado'), ['pendiente', 'pagada'])) {
                $this->restaurarInventario();
            }

            // Anular factura
            $this->update([
                'estado' => 'anulada',
                'fecha_anulacion' => Carbon::now(),
                'motivo_anulacion' => $motivo
            ]);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }

    public function descontarInventario(): bool
    {
        DB::beginTransaction();
        try {
            foreach ($this->detalles as $detalle) {
                $inventario = Inventario::where('id_producto', $detalle->getAttribute('producto_id'))
                    ->where('id_bodega', $this->getAttribute('bodega_id'))
                    ->first();

                if (!$inventario || $inventario->getAttribute('stock_disponible') < $detalle->getAttribute('cantidad')) {
                    throw new \Exception("Stock insuficiente para el producto {$detalle->producto->getAttribute('nombre')}");
                }

                $inventario->decrement('stock_actual', $detalle->getAttribute('cantidad'));
                $inventario->decrement('stock_disponible', $detalle->getAttribute('cantidad'));
                $inventario->update(['ultima_salida' => Carbon::now()]);

                // Registrar movimiento
                MovimientoInventario::create([
                    'id_producto' => $detalle->getAttribute('producto_id'),
                    'id_bodega' => $this->getAttribute('bodega_id'),
                    'tipo_movimiento' => 'salida',
                    'cantidad' => $detalle->getAttribute('cantidad'),
                    'motivo' => 'Venta - Factura ' . $this->getNumeroFacturaAttribute(),
                    'documento_referencia' => $this->getNumeroFacturaAttribute()
                ]);
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function restaurarInventario(): void
    {
        foreach ($this->detalles as $detalle) {
            $inventario = Inventario::where('id_producto', $detalle->getAttribute('producto_id'))
                ->where('id_bodega', $this->getAttribute('bodega_id'))
                ->first();

            if ($inventario) {
                $inventario->increment('stock_actual', $detalle->getAttribute('cantidad'));
                $inventario->increment('stock_disponible', $detalle->getAttribute('cantidad'));

                // Registrar movimiento de devolución
                MovimientoInventario::create([
                    'id_producto' => $detalle->getAttribute('producto_id'),
                    'id_bodega' => $this->getAttribute('bodega_id'),
                    'tipo_movimiento' => 'entrada',
                    'cantidad' => $detalle->getAttribute('cantidad'),
                    'motivo' => 'Anulación - Factura ' . $this->getNumeroFacturaAttribute(),
                    'documento_referencia' => $this->getNumeroFacturaAttribute()
                ]);
            }
        }
    }

    // Scopes
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    public function scopePagadas($query)
    {
        return $query->where('estado', 'pagada');
    }

    public function scopeAnuladas($query)
    {
        return $query->where('estado', 'anulada');
    }

    public function scopeDelCliente($query, $clienteId)
    {
        return $query->where('cliente_id', $clienteId);
    }

    public function scopeDeLaBodega($query, $bodegaId)
    {
        return $query->where('bodega_id', $bodegaId);
    }
}
