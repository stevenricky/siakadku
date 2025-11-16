<?php
// app/Http/Middleware/ApiAuthentication.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\ApiKey;
use Symfony\Component\HttpFoundation\Response;

class ApiAuthentication
{
  public function handle(Request $request, Closure $next): Response
{
    $apiKey = $request->header('X-API-Key') ?: $request->query('api_key');
    
    if (!$apiKey) {
        return response()->json([
            'error' => 'API key required',
            'message' => 'Please provide a valid API key'
        ], 401);
    }

    $key = ApiKey::where('api_key', $apiKey)->first();

    if (!$key) {
        return response()->json([
            'error' => 'Invalid API key',
            'message' => 'The provided API key is invalid'
        ], 401);
    }

    if (!$key->isActive()) {
        return response()->json([
            'error' => 'API key inactive',
            'message' => 'The API key is not active'
        ], 403);
    }

    if ($key->isExpired()) {
        return response()->json([
            'error' => 'API key expired',
            'message' => 'The API key has expired'
        ], 403);
    }

    // Check IP whitelist
    if ($key->allowed_ips && !in_array($request->ip(), $key->allowed_ips)) {
        return response()->json([
            'error' => 'IP not allowed',
            'message' => 'Your IP address is not whitelisted'
        ], 403);
    }

    // Attach API key to request
    $request->attributes->set('api_key', $key);

    // Update last used
    $key->update(['last_used_at' => now()]);

    // ✅ TAMBAHKAN LOGGING DI SINI
    $response = $next($request);

    // Create log entry
    \App\Models\ApiLog::create([
        'api_key_id' => $key->id,
        'endpoint' => $request->path(),
        'method' => $request->method(),
        'ip_address' => $request->ip(),
        'response_code' => $response->getStatusCode(),
        'response_time' => 0.1, // You can calculate actual response time
        'requested_at' => now()
    ]);

    return $response;
}
}