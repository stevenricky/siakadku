<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DarkModeMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Get theme from cookie or default to system preference
        $theme = $request->cookie('theme') ?? 
                (isset($_COOKIE['theme']) ? $_COOKIE['theme'] : null);
        
        // If no theme in cookie, use system preference
        if (!$theme) {
            $theme = 'light'; // Default to light
        }
        
        // Share theme with all views
        view()->share('currentTheme', $theme);
        
        $response = $next($request);
        
        // Ensure theme cookie is set
        if (!$request->cookie('theme')) {
            $response->cookie('theme', $theme, 60 * 24 * 30); // 30 days
        }
        
        return $response;
    }
}