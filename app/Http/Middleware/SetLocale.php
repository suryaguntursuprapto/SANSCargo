<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Get locale from session, default to 'id' (Indonesian)
        $locale = session('locale', 'id');
        
        // Add debug logging to verify locale is being set correctly
        Log::info('Setting locale to: ' . $locale);
        
        // Set application locale
        app()->setLocale($locale);
        
        return $next($request);
    }
}