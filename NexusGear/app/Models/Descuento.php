<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Descuento extends Model
{
    protected $table = 'descuentos';

    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'tipo',
        'valor',
        'fecha_fin',
    ];

    protected $casts = [
        'fecha_fin' => 'datetime',
    ];

    public function productos(): BelongsToMany
    {
        return $this->belongsToMany(Producto::class, 'descuento_producto', 'descuento_id', 'producto_id');
    }
}
