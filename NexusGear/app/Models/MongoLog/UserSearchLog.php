<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model as MongoModel;

class UserSearchLog extends MongoModel
{
    protected $connection = 'mongodb';
    protected $collection = 'user_search_logs';

    protected $fillable = [
        'user_id',       // null si es un visitante anónimo
        'search_term',   // El parámetro 'q'
        'filters',       // Array con el resto de filtros (precios, categorías...)
        'results_count', // Cuántos productos devolvió la búsqueda
        'ip_address',
        'user_agent'
    ];

    protected $casts = [
        'filters' => 'array',
    ];
}