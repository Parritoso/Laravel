<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model as MongoModel;

class AdminAuditLog extends MongoModel
{
    // Forzamos el uso de la conexión a MongoDB definida en tu config/database.php
    protected $connection = 'mongodb';
    
    // Nombre de la colección en Mongo
    protected $collection = 'admin_audit_logs';

    protected $fillable = [
        'user_id',       // ID del administrador que ejecuta la acción
        'user_name',     // Nombre (evita hacer un JOIN relacional más tarde)
        'action',        // 'store', 'update', 'destroy'
        'model_type',    // 'Producto', 'Categoria', 'Descuento', 'Pedido'
        'model_id',      // ID numérico de MySQL del recurso afectado
        'old_values',    // Array con el estado viejo (para updates y deletes)
        'new_values',    // Array con el nuevo estado (para stores y updates)
        'ip_address',
        'user_agent'
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];
}