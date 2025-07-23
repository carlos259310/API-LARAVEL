<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cliente extends Model
{
    protected $table = 'clientes';
    
    protected $fillable = [
        'nombre_1',
        'nombre_2',
        'apellido_1',
        'apellido_2',
        'email',
        'id_tipo_documento',
        'numero_documento',
        'razon_social',
        'tipo_cliente',
        'telefono',
        'direccion',
        'id_ciudad',
        'id_departamento',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function tipoDocumento(): BelongsTo
    {
        return $this->belongsTo(TipoDocumento::class, 'id_tipo_documento');
    }

    public function ciudad(): BelongsTo
    {
        return $this->belongsTo(Ciudad::class, 'id_ciudad');
    }

    public function departamento(): BelongsTo
    {
        return $this->belongsTo(Departamento::class, 'id_departamento');
    }

    public function getNombreCompletoAttribute(): string
    {
        $nombre = $this->getAttribute('nombre_1');
        if ($this->getAttribute('nombre_2')) {
            $nombre .= ' ' . $this->getAttribute('nombre_2');
        }
        $nombre .= ' ' . $this->getAttribute('apellido_1');
        if ($this->getAttribute('apellido_2')) {
            $nombre .= ' ' . $this->getAttribute('apellido_2');
        }
        return $nombre;
    }
}
