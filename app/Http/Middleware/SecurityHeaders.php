<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // ── Eliminar cabeceras que revelan tecnología ──────────────────────
        $response->headers->remove('X-Powered-By');
        $response->headers->remove('Server');

        // ── Cabeceras de seguridad estándar (OWASP) ────────────────────────
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        // ── HSTS (solo producción) ─────────────────────────────────────────
        if (app()->environment('production')) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }

        // ── Content Security Policy ────────────────────────────────────────
        // Nota: 'unsafe-inline' en script-src es requerido por el JS de
        // Chart.js en dashboard.blade.php que usa variables @json() de Blade.
        // Se elimina 'unsafe-eval' y todas las referencias CDN externas
        // ya que todos los assets ahora son locales (public/css/vendor, public/js/vendor).
        $csp = implode('; ', [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline'",
            "style-src 'self' 'unsafe-inline'",
            "font-src 'self' data:",
            "img-src 'self' data: blob:",
            "connect-src 'self'",
            "worker-src 'self' blob:",
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self'",
            "frame-ancestors 'none'",
        ]);
        $response->headers->set('Content-Security-Policy', $csp);

        // ── Reforzar HttpOnly en cookie de sesión ──────────────────────────
        // Laravel ya lo configura en session.php (http_only=true),
        // pero este bloque garantiza que se aplique en la respuesta HTTP.
        foreach ($response->headers->getCookies() as $cookie) {
            if (!$cookie->isHttpOnly()) {
                $response->headers->removeCookie($cookie->getName());
                $response->headers->setCookie(
                    new \Symfony\Component\HttpFoundation\Cookie(
                        $cookie->getName(),
                        $cookie->getValue(),
                        $cookie->getExpiresTime(),
                        $cookie->getPath(),
                        $cookie->getDomain(),
                        $cookie->isSecure(),
                        true, // HttpOnly = true
                        $cookie->isRaw(),
                        $cookie->getSameSite()
                    )
                );
            }
        }

        return $response;
    }
}

