<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoDocumento extends Model
{
    protected $table = 'tipos_documentos';
    
    protected $fillable = [
        'nombre',
        'codigo',
        'descripcion',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function clientes(): HasMany
    {
        return $this->hasMany(Cliente::class, 'id_tipo_documento');
    }
}
