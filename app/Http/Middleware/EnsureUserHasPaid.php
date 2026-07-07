<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasPaid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && !auth()->user()->is_paid) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Payment required to access this resource.'], 403);
            }
            return redirect()->route('payment.checkout');
        }

        return $next($request);
    }
}
