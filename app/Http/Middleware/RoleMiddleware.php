<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Debug logging
        Log::info('RoleMiddleware: Starting middleware check', [
            'url' => $request->url(),
            'method' => $request->method(),
            'roles_required' => $roles,
        ]);

        if (!Auth::check()) {
            Log::info('RoleMiddleware: User not authenticated, redirecting to login');
            return redirect()->route('login');
        }

        /** @var User $user */
        $user = Auth::user();
        
        Log::info('RoleMiddleware: User authenticated', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'user_role' => $user->role,
            'user_role_type' => gettype($user->role),
            'required_roles' => $roles,
            'request_url' => $request->url(),
        ]);
        
        // Check if user role matches any of the required roles
        $hasAccess = in_array($user->role, $roles);
        
        Log::info('RoleMiddleware: Access check result', [
            'has_access' => $hasAccess,
            'user_role' => $user->role,
            'required_roles' => $roles,
            'comparison_results' => array_map(function($role) use ($user) {
                return [
                    'required_role' => $role,
                    'user_role' => $user->role,
                    'match' => $user->role === $role,
                    'in_array' => in_array($user->role, [$role])
                ];
            }, $roles)
        ]);
        
        if (!$hasAccess) {
            Log::warning('RoleMiddleware: Access denied - aborting with 403', [
                'user_role' => $user->role,
                'required_roles' => $roles,
            ]);
            abort(403, 'Unauthorized access. Your role: ' . $user->role . ', Required roles: ' . implode(', ', $roles));
        }

        Log::info('RoleMiddleware: Access granted, proceeding to next middleware');
        return $next($request);
    }
}
