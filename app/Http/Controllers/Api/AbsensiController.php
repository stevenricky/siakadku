<?php
// app/Http/Controllers/Api/AbsensiController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $query = Absensi::with(['siswa', 'guru']);
        
        // Filter by tanggal
        if ($request->has('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }
        
        // Filter by siswa
        if ($request->has('siswa_id')) {
            $query->where('siswa_id', $request->siswa_id);
        }
        
        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        $absensi = $query->paginate($request->get('per_page', 15));
        
        return response()->json([
            'success' => true,
            'data' => $absensi->items(),
            'meta' => [
                'current_page' => $absensi->currentPage(),
                'last_page' => $absensi->lastPage(),
                'per_page' => $absensi->perPage(),
                'total' => $absensi->total(),
            ]
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'tanggal' => 'required|date',
            'status' => 'required|in:H,S,I,A,T',
            'keterangan' => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            // Cek apakah absensi sudah ada
            $existing = Absensi::where('siswa_id', $request->siswa_id)
                ->whereDate('tanggal', $request->tanggal)
                ->first();

            if ($existing) {
                $absensi = $existing->update($request->all());
            } else {
                $absensi = Absensi::create($request->all());
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Absensi berhasil disimpan',
                'data' => $absensi
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan absensi',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}