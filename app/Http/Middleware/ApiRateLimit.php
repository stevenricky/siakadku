<?php
// app/Http/Middleware/ApiRateLimit.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\ApiQuota;
use Symfony\Component\HttpFoundation\Response;

class ApiRateLimit
{
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->attributes->get('api_key');
        
        if (!$apiKey) {
            return $next($request);
        }

        // Get or create today's quota
        $quota = ApiQuota::firstOrCreate(
            [
                'api_key_id' => $apiKey->id,
                'date' => today(),
            ],
            [
                'request_count' => 0,
                'limit_exceeded' => 0,
            ]
        );

        // Check rate limit
        if ($quota->request_count >= $apiKey->rate_limit) {
            $quota->increment('limit_exceeded');
            
            return response()->json([
                'error' => 'Rate limit exceeded',
                'message' => 'API rate limit exceeded. Please try again later.',
                'retry_after' => 60 // seconds
            ], 429);
        }

        // Increment request count
        $quota->increment('request_count');

        return $next($request);
    }
}