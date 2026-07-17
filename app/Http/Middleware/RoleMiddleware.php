<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to access this page.');
        }

        $user = Auth::user();
        $roleArray = explode('|', $roles);

        foreach ($roleArray as $role) {
            // Check both Spatie role and raw column
            if ($user->hasRole($role) || strtolower($user->role) === strtolower($role)) {
                return $next($request);
            }
        }

        abort(403, 'Unauthorized. You do not have the required role to access this route.');
    }
}
