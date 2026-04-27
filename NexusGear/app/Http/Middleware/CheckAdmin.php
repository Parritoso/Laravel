<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificamos si el usuario está autenticado y si es administrador
        if (Auth::check() && Auth::user()->isAdmin()) {
            return $next($request);
        }

        // Si no es admin, lo redirigimos o lanzamos un error 403 (Prohibido)
        return redirect('/')->with('error', 'No tienes permisos de administrador.');
    }
}
