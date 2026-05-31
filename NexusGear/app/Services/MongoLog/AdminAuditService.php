<?php

namespace App\Services\MongoLog;

use App\Models\MongoLog\AdminAuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminAuditService
{
    public static function track(string $action, string $modelType, mixed $modelId, ?array $oldValues = null, ?array $newValues = null): void
    {
        try {
            if (!extension_loaded('mongodb')) {
                Log::warning("Auditoría omitida: La extensión nativa 'mongodb' de PHP no está instalada o activa.");
                return;
            }
            if (!class_exists(\MongoDB\Laravel\Eloquent\Model::class)) {
                Log::warning("Auditoría omitida: El paquete 'mongodb/laravel-mongodb' no está instalado en composer.");
                return;
            }
            if (!class_exists(\App\Models\MongoLog\AdminAuditLog::class)) {
                return;
            }
            AdminAuditLog::create([
                'user_id'    => Auth::id(),
                'user_name'  => Auth::user()?->name ?? 'Sistema/Automatización',
                'action'     => $action,
                'model_type' => $modelType,
                'model_id'   => $modelId,
                'old_values' => $oldValues,
                'new_values' => $newValues,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        } catch (\Throwable $e) {
            Log::error("Fallo crítico en el motor de MongoDB (Auditoría): " . $e->getMessage(), [
                'action'     => $action,
                'model_type' => $modelType,
                'model_id'   => $modelId,
            ]);
        }
    }
}