<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiPermission
{
    public function handle(Request $request, Closure $next, string $endpoint, string $action = 'read'): Response
    {
        $apiKey = $request->attributes->get('api_key');
        
        if (!$apiKey) {
            return response()->json([
                'error' => 'Unauthorized',
                'message' => 'API key not found'
            ], 401);
        }

        \Log::info('API Permission Check', [
            'api_key' => $apiKey->name,
            'endpoint' => $endpoint,
            'action' => $action,
            'permissions' => $apiKey->permissions
        ]);

        if (!$apiKey->canAccess($endpoint, $action)) {
            return response()->json([
                'error' => 'Permission denied',
                'message' => "You don't have permission to {$action} {$endpoint}",
                'required_permission' => "{$endpoint}.{$action}",
                'available_permissions' => $apiKey->permissions
            ], 403);
        }

        return $next($request);
    }
}