<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ciudad extends Model
{
    protected $table = 'ciudades';
    
    protected $fillable = [
        'nombre',
        'codigo',
        'id_departamento',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function departamento(): BelongsTo
    {
        return $this->belongsTo(Departamento::class, 'id_departamento');
    }

    public function clientes(): HasMany
    {
        return $this->hasMany(Cliente::class, 'id_ciudad');
    }
}
