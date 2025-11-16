<?php
// app/Http/Controllers/Api/GuruController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use Illuminate\Http\Request;

class GuruController extends Controller
{
    public function index(Request $request)
    {
        $query = Guru::query();
        
        // Search by nama or NIP
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%");
            });
        }
        
        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        $guru = $query->paginate($request->get('per_page', 15));
        
        return response()->json([
            'success' => true,
            'data' => $guru->items(),
            'meta' => [
                'current_page' => $guru->currentPage(),
                'last_page' => $guru->lastPage(),
                'per_page' => $guru->perPage(),
                'total' => $guru->total(),
            ]
        ]);
    }

    public function show($id)
    {
        $guru = Guru::find($id);
        
        if (!$guru) {
            return response()->json([
                'success' => false,
                'message' => 'Guru not found'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => $guru
        ]);
    }
}