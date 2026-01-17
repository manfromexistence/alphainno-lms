<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware to check if the authenticated user has a specific permission through their roles.
 * 
 * Usage in routes:
 * - Single permission: Route::middleware('permission:students.view')
 * - Multiple permissions (any): Route::middleware('permission:students.view,students.create')
 */
class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$permissions  The permissions to check against
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string ...$permissions): Response
    {
        // Check if user is authenticated
        if (!$request->user()) {
            return redirect()->route('login');
        }

        // If no permissions specified, allow access
        if (empty($permissions)) {
            return $next($request);
        }

        // Super Admin has all permissions
        if ($request->user()->isSuperAdmin()) {
            return $next($request);
        }

        // Check if user has any of the required permissions
        foreach ($permissions as $permission) {
            if ($request->user()->hasPermission($permission)) {
                return $next($request);
            }
        }

        // User doesn't have the required permission - redirect to unauthorized page
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
                'message' => 'You do not have permission to perform this action.',
                'code' => 'UNAUTHORIZED_PERMISSION'
            ], 403);
        }

        return redirect()->route('unauthorized');
    }
}
