<?php
// app/Http/Controllers/Api/SiswaController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = Siswa::with(['kelas', 'tahunAjaran']);
        
        // Filter by kelas
        if ($request->has('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }
        
        // Filter by tahun ajaran
        if ($request->has('tahun_ajaran_id')) {
            $query->where('tahun_ajaran_id', $request->tahun_ajaran_id);
        }
        
        // Search by nama or NIS
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }
        
        $siswa = $query->paginate($request->get('per_page', 15));
        
        return response()->json([
            'success' => true,
            'data' => $siswa->items(),
            'meta' => [
                'current_page' => $siswa->currentPage(),
                'last_page' => $siswa->lastPage(),
                'per_page' => $siswa->perPage(),
                'total' => $siswa->total(),
            ]
        ]);
    }

    public function show($id)
    {
        $siswa = Siswa::with(['kelas', 'tahunAjaran'])->find($id);
        
        if (!$siswa) {
            return response()->json([
                'success' => false,
                'message' => 'Siswa not found'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => $siswa
        ]);
    }
}