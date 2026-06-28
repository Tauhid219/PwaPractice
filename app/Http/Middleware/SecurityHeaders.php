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
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');

        // Content Security Policy
        // Added Vite dev server origins (localhost:5173, etc) to prevent breaking the design in local development
        $viteOrigins = "http://127.0.0.1:5173 http://localhost:5173 http://[::1]:5173 ws://127.0.0.1:5173 ws://localhost:5173 ws://[::1]:5173";
        $csp = "default-src 'self'; " .
               "script-src 'self' 'unsafe-inline' 'unsafe-eval' $viteOrigins; " .
               "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdnjs.cloudflare.com $viteOrigins; " .
               "font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com data: $viteOrigins; " .
               "img-src 'self' data: https: http:; " .
               "media-src 'self' https: http:; " .
               "connect-src 'self' $viteOrigins;";
        // HTTP Strict Transport Security (HSTS) and CSP Enforcement for production
        if (app()->environment('production')) {
            $response->headers->set('Content-Security-Policy', $csp);
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        } else {
            $response->headers->set('Content-Security-Policy-Report-Only', $csp);
        }

        return $response;
    }
}
