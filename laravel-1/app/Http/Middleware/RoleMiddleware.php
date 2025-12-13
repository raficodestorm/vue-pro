<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string  $role
     */
    public function handle(Request $request, Closure $next, string $role)
    {
        $user = $request->user();

        // Not logged in
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Role mismatch
        if ($user->role !== $role) {
            return response()->json(['message' => 'Forbidden: Access denied'], 403);
        }

        return $next($request);
    }
}
