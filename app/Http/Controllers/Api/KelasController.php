<?php
// app/Http/Controllers/Api/KelasController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index(Request $request)
    {
        $query = Kelas::with(['waliKelas', 'tahunAjaran']);
        
        // Filter by tahun ajaran
        if ($request->has('tahun_ajaran_id')) {
            $query->where('tahun_ajaran_id', $request->tahun_ajaran_id);
        }
        
        // Filter by tingkat
        if ($request->has('tingkat')) {
            $query->where('tingkat', $request->tingkat);
        }
        
        $kelas = $query->paginate($request->get('per_page', 15));
        
        return response()->json([
            'success' => true,
            'data' => $kelas->items(),
            'meta' => [
                'current_page' => $kelas->currentPage(),
                'last_page' => $kelas->lastPage(),
                'per_page' => $kelas->perPage(),
                'total' => $kelas->total(),
            ]
        ]);
    }

    public function show($id)
    {
        $kelas = Kelas::with(['waliKelas', 'tahunAjaran', 'siswa'])->find($id);
        
        if (!$kelas) {
            return response()->json([
                'success' => false,
                'message' => 'Kelas not found'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => $kelas
        ]);
    }
}