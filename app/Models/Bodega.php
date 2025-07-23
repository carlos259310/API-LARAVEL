<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bodega extends Model
{
    protected $fillable = ['nombre', 'ubicacion'];

    public function inventarios(): HasMany
    {
        return $this->hasMany(Inventario::class, 'id_bodega');
    }
    public function facturas(): HasMany
    {
        return $this->hasMany(Factura::class, 'id_bodega');
    }
}
