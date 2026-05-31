<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model as MongoModel;

class ErrorLog extends MongoModel
{
    protected $connection = 'mongodb';
    protected $collection = 'error_logs';

    protected $fillable = [
        'error_ref',       // El código 'REF-XXXXXX' para cruzar datos
        'user_id',         // ID del usuario afectado (si estaba logueado)
        'exception',       // Clase del error (ej: QueryException)
        'message',         // Mensaje aclaratorio de PHP
        'file',            // Archivo exacto que rompió
        'line',            // Línea exacta del fallo
        'stack_trace',     // Toda la traza de ejecución
        'url',             // URL exacta donde ocurrió
        'method',          // GET, POST, PUT...
        'payload',         // Datos del formulario enviado (¡Sanitizados!)
        'headers',         // Cabeceras HTTP
        'ip',
    ];

    protected $casts = [
        'payload' => 'array',
        'headers' => 'array',
    ];
}