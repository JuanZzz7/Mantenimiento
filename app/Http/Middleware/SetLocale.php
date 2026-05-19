<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Prioridad: Sesión (para cambios inmediatos)
        $locale = session()->get('locale');

        // 2. Si no hay sesión, usar el del Usuario logueado
        if (!$locale && Auth::check()) {
            $locale = Auth::user()->locale;
        }

        // 3. Fallback: Configuración por defecto
        $locale = $locale ?: config('app.locale', 'es');

        App::setLocale($locale);

        return $next($request);
    }
}
