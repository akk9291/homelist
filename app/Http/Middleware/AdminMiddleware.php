<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to access this page.');
        }

        $user = Auth::user();

        if ($user->hasAnyRole(['Super Admin', 'Admin', 'Content Manager']) || $user->role === 'admin') {
            return $next($request);
        }

        // Redirect based on role if they are logged in but not an admin
        if ($user->hasRole('Agent') || $user->role === 'agent') {
            return redirect()->route('agent.dashboard');
        }

        if ($user->hasRole('Customer') || $user->role === 'customer' || $user->role === 'user') {
            return redirect()->route('customer.dashboard');
        }

        return redirect()->route('home')->with('error', 'Unauthorized access.');
    }
}
