<?php

namespace App\Models;
use App\Models\Traits\ProtectedDataTrait;
use Illuminate\Database\Eloquent\SoftDeletes; 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Siswa extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'user_id',
        'nis',
        'nisn',
        'nama',
        'nama_lengkap',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'no_telp',
        'kelas_id',
        'tahun_ajaran_id',
        'status'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }

    /**
     * Relasi ke model SPP
     */
    public function spp(): HasMany
    {
        return $this->hasMany(Spp::class, 'siswa_id');
    }

    /**
     * Relasi ke model PembayaranSpp
     */
    public function pembayaranSpp(): HasMany
    {
        return $this->hasMany(PembayaranSpp::class, 'siswa_id');
    }

    public function tagihanSpp(): HasMany
    {
        return $this->hasMany(TagihanSpp::class, 'siswa_id');
    }

    public function nilai(): HasMany
    {
        return $this->hasMany(Nilai::class);
    }

    public function absensi(): HasMany
    {
        return $this->hasMany(Absensi::class);
    }

    // Relasi dengan Ekstrakurikuler
public function ekstrakurikuler()
{
    return $this->belongsToMany(Ekstrakurikuler::class, 'ekstrakurikuler_siswa')
                ->withPivot('status', 'tanggal_daftar')
                ->withTimestamps();
}



    

    // Accessor untuk display jenis kelamin
    public function getJenisKelaminDisplayAttribute(): string
    {
        return match($this->jenis_kelamin) {
            'L' => 'Laki-laki',
            'P' => 'Perempuan',
            default => $this->jenis_kelamin
        };
    }

    public function getRataRataNilaiAttribute(): float
    {
        return $this->nilai()->avg('nilai_akhir') ?? 0;
    }

    // Method untuk format jenis kelamin
    public function getFormattedJenisKelamin(): string
    {
        return $this->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan';
    }

    /**
     * Method untuk mendapatkan total tunggakan SPP
     */
    public function getTotalTunggakanAttribute(): float
    {
        return $this->spp()
            ->whereIn('status', ['belum_bayar', 'tertunggak'])
            ->sum('nominal');
    }

    /**
     * Method untuk mendapatkan total SPP lunas
     */
    public function getTotalLunasAttribute(): float
    {
        return $this->spp()
            ->where('status', 'lunas')
            ->sum('nominal');
    }

    /**
     * Method untuk mendapatkan riwayat pembayaran SPP
     */
    public function getRiwayatPembayaranAttribute()
    {
        return $this->pembayaranSpp()
            ->with('tagihanSpp')
            ->orderBy('tanggal_bayar', 'desc')
            ->get();
    }

    /**
     * Method untuk mendapatkan total pembayaran diterima
     */
    public function getTotalPembayaranDiterimaAttribute(): float
    {
        return $this->pembayaranSpp()
            ->where('status_verifikasi', 'diterima')
            ->sum('jumlah_bayar');
    }

    /**
     * Method untuk mendapatkan pembayaran pending
     */
    public function getPembayaranPendingAttribute()
    {
        return $this->pembayaranSpp()
            ->where('status_verifikasi', 'pending')
            ->get();
    }

    /**
     * Method untuk cek apakah memiliki tunggakan
     */
    public function getMemilikiTunggakanAttribute(): bool
    {
        return $this->total_tunggakan > 0;
    }

    /**
     * Method untuk mendapatkan statistik pembayaran
     */
    public function getStatistikPembayaranAttribute(): array
    {
        $totalPembayaran = $this->pembayaranSpp()->count();
        $pembayaranDiterima = $this->pembayaranSpp()->where('status_verifikasi', 'diterima')->count();
        $pembayaranPending = $this->pembayaranSpp()->where('status_verifikasi', 'pending')->count();
        $pembayaranDitolak = $this->pembayaranSpp()->where('status_verifikasi', 'ditolak')->count();

        return [
            'total' => $totalPembayaran,
            'diterima' => $pembayaranDiterima,
            'pending' => $pembayaranPending,
            'ditolak' => $pembayaranDitolak,
            'persentase_diterima' => $totalPembayaran > 0 ? round(($pembayaranDiterima / $totalPembayaran) * 100, 2) : 0
        ];
    }

    /**
     * Scope untuk siswa aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    /**
     * Scope untuk siswa dengan tunggakan
     */
    public function scopeMemilikiTunggakan($query)
    {
        return $query->whereHas('spp', function($q) {
            $q->whereIn('status', ['belum_bayar', 'tertunggak']);
        });
    }

    /**
     * Scope untuk mencari siswa berdasarkan nama atau NIS
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('nama', 'like', "%{$search}%")
                    ->orWhere('nis', 'like', "%{$search}%")
                    ->orWhere('nisn', 'like', "%{$search}%");
    }
}