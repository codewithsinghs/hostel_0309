<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiPermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // public function handle(Request $request, Closure $next): Response
    // {
    //     return $next($request);
    // }

    public function handle(Request $request, Closure $next, $permission = null)
    {
        $user = $request->user(); // Sanctum authenticated user

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Admin bypass
        if ($user->hasRole('admin')) {
            return $next($request);
        }

        // Permission check
        if ($permission && !$user->hasPermissionTo($permission)) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return $next($request);
    }
}

