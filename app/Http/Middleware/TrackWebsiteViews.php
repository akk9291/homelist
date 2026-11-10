<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;

class TrackWebsiteViews
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only track GET requests for main pages (exclude assets, livewire updates, filament, api)
        if ($request->isMethod('GET') && !$request->ajax() && !$request->is('livewire/*', 'admin*', 'api/*')) {
            if (!$request->session()->has('homelist_visited')) {
                $request->session()->put('homelist_visited', true);

                try {
                    DB::table('settings')->limit(1)->increment('website_views');
                    Setting::clearCache();
                } catch (\Throwable $e) {
                    // Suppress if any issue
                }
            }
        }

        return $next($request);
    }
}
