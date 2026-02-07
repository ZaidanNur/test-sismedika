<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceJsonResponse
{
    /**
     * Handle an incoming request.
     *
     * Ensures that the API only accepts JSON requests.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->expectsJson() && !$request->wantsJson()) {
            return response()->json([
                'message' => 'Please request with HTTP header: Accept: application/json',
            ], Response::HTTP_NOT_ACCEPTABLE);
        }

        return $next($request);
    }
}
