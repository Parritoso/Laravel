<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductoCategoria extends Model
{
    protected $table = 'producto_categoria';

    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'producto_id',
        'categoria_id',
    ];

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'producto_id');
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }
}
