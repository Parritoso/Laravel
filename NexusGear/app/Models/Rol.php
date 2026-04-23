<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Rol extends Model
{
    protected $table = 'roles';

    public $timestamps = false;

    protected $fillable = [
        'nombre_rol',
    ];

    public function usuarios(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'rol_usuario', 'rol_id', 'usuario_id')
                    ->withPivot('asignado_el');
    }
}
