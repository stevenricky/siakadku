<?php
// app/Http/Controllers/Api/NilaiController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Nilai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NilaiController extends Controller
{
    public function index(Request $request)
    {
        $query = Nilai::with(['siswa', 'mapel', 'guru']);
        
        // Filter by siswa
        if ($request->has('siswa_id')) {
            $query->where('siswa_id', $request->siswa_id);
        }
        
        // Filter by mapel
        if ($request->has('mapel_id')) {
            $query->where('mapel_id', $request->mapel_id);
        }
        
        // Filter by semester
        if ($request->has('semester_id')) {
            $query->where('semester_id', $request->semester_id);
        }
        
        $nilai = $query->paginate($request->get('per_page', 15));
        
        return response()->json([
            'success' => true,
            'data' => $nilai->items(),
            'meta' => [
                'current_page' => $nilai->currentPage(),
                'last_page' => $nilai->lastPage(),
                'per_page' => $nilai->perPage(),
                'total' => $nilai->total(),
            ]
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'mapel_id' => 'required|exists:mapels,id',
            'semester_id' => 'required|exists:semesters,id',
            'nilai' => 'required|numeric|min:0|max:100',
            'jenis_nilai' => 'required|in:UH,UAS,UTS,TUGAS',
        ]);

        try {
            DB::beginTransaction();

            $nilai = Nilai::create($request->all());

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Nilai berhasil disimpan',
                'data' => $nilai->load(['siswa', 'mapel'])
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan nilai',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}