<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class GuestMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            if ($user->hasAnyRole(['Super Admin', 'Admin', 'Content Manager']) || $user->role === 'admin') {
                return redirect('/admin');
            }

            if ($user->hasRole('Agent') || $user->role === 'agent') {
                return redirect()->route('agent.dashboard');
            }

            // Default to customer dashboard
            return redirect()->route('customer.dashboard');
        }

        return $next($request);
    }
}
