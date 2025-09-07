<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    // public function handle(Request $request, Closure $next, $role)
    // {
    //     $user = $request->user();
    
    //     // If not authenticated or does not have the required role, redirect to login
    //     if (!$user || !$user->roles->contains('name', $role)) {
    //         return redirect()->route('login');
    //     }
    
    //     return $next($request);
    // }
    

    public function handle($request, Closure $next, $role)
{
    $user = $request->user();

    // Not authenticated
    if (! $user) {
        return response()->json(['message' => 'Unauthenticated'], 401);
    }

    // Check role
    if (! $user->hasRole($role)) {
        return response()->json(['message' => 'Forbidden. You do not have permission.'], 403);
    }

    return $next($request);
}

    
}
