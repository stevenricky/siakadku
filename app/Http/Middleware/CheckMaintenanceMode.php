<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Helpers\SettingsHelper;
use Illuminate\Support\Facades\Auth;

class CheckMaintenanceMode
{
    public function handle(Request $request, Closure $next)
    {
        // Cek apakah maintenance mode aktif
        if (SettingsHelper::maintenanceMode()) {
            
            // 1. Allow access jika sudah ada session maintenance access
            if (session('maintenance_access_granted')) {
                return $next($request);
            }

            // 2. Allow access untuk admin yang sudah login
            if (Auth::check() && Auth::user()->role === 'admin') {
                return $next($request);
            }

            // 3. Allow access untuk routes maintenance dan logout
            $allowedRoutes = ['maintenance.page', 'maintenance.access', 'logout'];
            $currentRoute = $request->route() ? $request->route()->getName() : null;
            
            if (in_array($currentRoute, $allowedRoutes)) {
                return $next($request);
            }

            // 4. Allow akses ke static assets
            if ($this->isStaticAsset($request)) {
                return $next($request);
            }

            // 5. Untuk API requests - return JSON
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'message' => 'Sistem sedang dalam maintenance',
                    'maintenance' => true
                ], 503);
            }

            // 6. Redirect SEMUA user lainnya ke maintenance page
            return redirect()->route('maintenance.page');
        }

        return $next($request);
    }
    
    /**
     * Check if the request is for static assets
     */
    private function isStaticAsset(Request $request)
    {
        $path = $request->path();
        
        $staticExtensions = [
            'css', 'js', 'png', 'jpg', 'jpeg', 'gif', 'ico', 'svg', 
            'woff', 'woff2', 'ttf', 'eot', 'map', 'txt'
        ];
        
        foreach ($staticExtensions as $ext) {
            if (str_ends_with($path, '.' . $ext)) {
                return true;
            }
        }
        
        return $request->is('css/*') || 
               $request->is('js/*') || 
               $request->is('images/*') || 
               $request->is('storage/*') ||
               $request->is('fonts/*') ||
               $request->is('vendor/*');
    }
}