<?php
// app/Http/Controllers/Api/AuthController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ApiKey;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function validateKey(Request $request)
    {
        $apiKey = $request->header('X-API-Key') ?: $request->query('api_key');
        
        if (!$apiKey) {
            return response()->json([
                'success' => false,
                'error' => 'API key required'
            ], 401);
        }

        $key = ApiKey::where('api_key', $apiKey)->first();

        if (!$key) {
            return response()->json([
                'success' => false,
                'error' => 'Invalid API key'
            ], 401);
        }

        if (!$key->isActive()) {
            return response()->json([
                'success' => false,
                'error' => 'API key inactive'
            ], 403);
        }

        if ($key->isExpired()) {
            return response()->json([
                'success' => false,
                'error' => 'API key expired'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'name' => $key->name,
                'permissions' => $key->permissions,
                'rate_limit' => $key->rate_limit,
                'remaining_requests' => $key->rate_limit - $key->getTodayRequestCount(),
            ]
        ]);
    }
}