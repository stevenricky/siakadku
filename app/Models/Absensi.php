<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $fillable = [
        'siswa_id',
        'jadwal_id',
        'tanggal',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    // Relationships
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }

    // Scope untuk absensi berdasarkan tanggal
    public function scopeBulan($query, $bulan, $tahun)
    {
        return $query->whereYear('tanggal', $tahun)
                    ->whereMonth('tanggal', $bulan);
    }

    // Method untuk statistik kehadiran
    public static function getStatistikKehadiran($siswaId, $bulan, $tahun)
    {
        $absensi = self::where('siswa_id', $siswaId)
            ->bulan($bulan, $tahun)
            ->get();

        $total = $absensi->count();
        $hadir = $absensi->where('status', 'hadir')->count();
        $sakit = $absensi->where('status', 'sakit')->count();
        $izin = $absensi->where('status', 'izin')->count();
        $alpha = $absensi->where('status', 'alpha')->count();

        return [
            'total' => $total,
            'hadir' => $hadir,
            'sakit' => $sakit,
            'izin' => $izin,
            'alpha' => $alpha,
            'persentase_hadir' => $total > 0 ? round(($hadir / $total) * 100, 2) : 0,
        ];
    }
}