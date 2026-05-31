<?php

namespace App\Services;

use App\Models\ErrorLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Throwable;

class ErrorAnalyticsService
{
    public static function logException(Throwable $e, string $errorRef): void
    {
        try {
            // Protección Fail-Safe elemental
            if (!extension_loaded('mongodb') || 
                !class_exists(\MongoDB\Laravel\Eloquent\Model::class) || 
                !class_exists(ErrorLog::class)) {
                return;
            }

            // Sanitización: Filtramos campos sensibles del formulario para cumplir normativas de seguridad
            $sensitiveFields = ['password', 'password_confirmation', 'card_number', 'cvv', 'token', 'secret'];
            $payload = request()->except($sensitiveFields);

            // Recolección exhaustiva de cabeceras útiles
            $headers = [
                'user-agent' => request()->header('User-Agent'),
                'referer'    => request()->header('Referer'),
                'lang'       => request()->header('Accept-Language'),
            ];

            ErrorLog::create([
                'error_ref'   => $errorRef,
                'user_id'     => Auth::id(),
                'exception'   => get_class($e),
                'message'     => $e->getMessage(),
                'file'        => $e->getFile(),
                'line'        => $e->getLine(),
                'stack_trace' => $e->getTraceAsString(), // Traza completa legible
                'url'         => request()->fullUrl(),
                'method'      => request()->method(),
                'payload'     => $payload,
                'headers'     => $headers,
                'ip'          => request()->ip(),
            ]);

        } catch (Throwable $mongoError) {
            // Si MongoDB falla aquí, lo tiramos al log de archivos estándar para no provocar un bucle infinito
            Log::emergency("MongoDB falló al intentar registrar un error del sistema: " . $mongoError->getMessage());
        }
    }
}