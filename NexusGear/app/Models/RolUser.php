<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RolUser extends Model
{
    protected $table = 'rol_usuario';

    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'usuario_id',
        'rol_id',
        'asignado_el',
    ];

    protected $casts = [
        'asignado_el' => 'datetime',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function rol(): BelongsTo
    {
        return $this->belongsTo(Rol::class, 'rol_id');
    }
}
