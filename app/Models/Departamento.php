<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Departamento extends Model
{
    protected $table = 'departamentos';
    
    protected $fillable = [
        'nombre',
        'codigo',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function ciudades(): HasMany
    {
        return $this->hasMany(Ciudad::class, 'id_departamento');
    }

    public function clientes(): HasMany
    {
        return $this->hasMany(Cliente::class, 'id_departamento');
    }
}
