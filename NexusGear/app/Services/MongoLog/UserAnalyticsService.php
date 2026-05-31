<?php

namespace App\Services\MongoLog;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserAnalyticsService
{
    public static function logSearch(array $filters, int $resultsCount): void
    {
        // Si no hay término de búsqueda ni filtros aplicados, ignoramos (visita normal al catálogo)
        if (empty($filters)) {
            return;
        }

        try {
            // Validaciones estructurales de seguridad
            if (!extension_loaded('mongodb') || 
                !class_exists(\MongoDB\Laravel\Eloquent\Model::class) || 
                !class_exists(\App\Models\MongoLog\UserSearchLog::class)) {
                return;
            }

            // Extraemos el texto de búsqueda y separamos los filtros técnicos
            $searchTerm = $filters['q'] ?? null;
            unset($filters['q']); 

            \App\Models\MongoLog\UserSearchLog::create([
                'user_id'       => Auth::id(), // Registra la ID si está logueado, si no, guarda null
                'search_term'   => $searchTerm,
                'filters'       => $filters, // Guardamos el resto (precios, categorías, etc.)
                'results_count' => $resultsCount,
                'ip_address'    => request()->ip(),
                'user_agent'    => request()->userAgent(),
            ]);

        } catch (\Throwable $e) {
            // Mitigación de errores: si Mongo falla, la experiencia del cliente no se inmuta
            Log::error("Error al registrar búsqueda de usuario en MongoDB: " . $e->getMessage());
        }
    }
}