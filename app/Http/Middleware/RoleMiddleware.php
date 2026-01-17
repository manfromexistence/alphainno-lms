<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware to check if the authenticated user has any of the required roles.
 * 
 * Usage in routes:
 * - Single role: Route::middleware('role:super-admin')
 * - Multiple roles: Route::middleware('role:super-admin,teacher')
 */
class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles  The roles to check against (comma-separated or multiple parameters)
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Check if user is authenticated
        if (!$request->user()) {
            return redirect()->route('login');
        }

        // If no roles specified, allow access
        if (empty($roles)) {
            return $next($request);
        }

        // Super Admin has access to everything
        if ($request->user()->isSuperAdmin()) {
            return $next($request);
        }

        // Check if user has any of the required roles
        if ($request->user()->hasAnyRole($roles)) {
            return $next($request);
        }

        // User doesn't have the required role - redirect to unauthorized page
        return $this->unauthorized($request);
    }

    /**
     * Handle unauthorized access.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function unauthorized(Request $request): Response
    {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to access this resource.',
                'code' => 'UNAUTHORIZED_ROLE'
            ], 403);
        }

        return redirect()->route('unauthorized');
    }
}
