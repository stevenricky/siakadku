<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class PeminjamanBuku extends Model
{
    protected $table = 'peminjaman_buku';
    
    protected $fillable = [
        'kode_peminjaman',
        'siswa_id',
        'buku_id',
        'tanggal_pinjam',
        'tanggal_kembali',
        'tanggal_dikembalikan',
        'status',
        'denda',
        'keterangan',
        'petugas_id'
    ];

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_kembali' => 'date',
        'tanggal_dikembalikan' => 'date',
        'denda' => 'decimal:2'
    ];

    // Relasi ke Siswa
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    // Relasi ke Buku
    public function buku(): BelongsTo
    {
        return $this->belongsTo(Buku::class);
    }

    // Relasi ke Petugas/User
    public function petugas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }

    // Accessor untuk status badge
    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'dipinjam' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
            'dikembalikan' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
            'terlambat' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
            'hilang' => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200'
        };
    }

    // Accessor untuk status text
    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            'dipinjam' => 'Dipinjam',
            'dikembalikan' => 'Dikembalikan',
            'terlambat' => 'Terlambat',
            'hilang' => 'Hilang',
            default => $this->status
        };
    }

    public function getDurasiAttribute(): int
    {
        if ($this->tanggal_dikembalikan) {
            return Carbon::parse($this->tanggal_pinjam)->diffInDays($this->tanggal_dikembalikan);
        }
        return Carbon::parse($this->tanggal_pinjam)->diffInDays(now());
    }

    public function getDurasiFormattedAttribute(): string
    {
        return $this->durasi . ' hari';
    }

    // Method untuk cek apakah terlambat
    public function getIsTerlambatAttribute(): bool
    {
        if ($this->status === 'dikembalikan' || $this->status === 'hilang') {
            return false;
        }

        return Carbon::today()->gt(Carbon::parse($this->tanggal_kembali));
    }

    // Method untuk menghitung denda otomatis
    public function hitungDenda(): float
    {
        if ($this->status === 'dikembalikan' || $this->status === 'hilang') {
            return $this->denda;
        }

        $tanggalKembali = Carbon::parse($this->tanggal_kembali);
        $today = Carbon::today();
        
        if ($today->lte($tanggalKembali)) {
            return 0;
        }

        $hariTerlambat = $today->diffInDays($tanggalKembali);
        $dendaPerHari = 5000; // Rp 5.000 per hari
        $totalDenda = $hariTerlambat * $dendaPerHari;

        // Update denda di database
        $this->update(['denda' => $totalDenda]);

        return $totalDenda;
    }

    // Method untuk format denda
    public function getDendaFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->denda, 0, ',', '.');
    }

    // Method untuk update status otomatis
    public static function updateStatusTerlambat()
    {
        $peminjamanTerlambat = static::where('status', 'dipinjam')
            ->whereDate('tanggal_kembali', '<', today())
            ->get();

        foreach ($peminjamanTerlambat as $peminjaman) {
            $peminjaman->update(['status' => 'terlambat']);
        }
    }

    // Scope untuk peminjaman aktif
    public function scopeAktif($query)
    {
        return $query->whereIn('status', ['dipinjam', 'terlambat']);
    }

    // Scope untuk riwayat (selesai)
    public function scopeSelesai($query)
    {
        return $query->whereIn('status', ['dikembalikan', 'hilang']);
    }

    // Scope untuk pencarian
    public function scopeSearch($query, $search)
    {
        return $query->where('kode_peminjaman', 'like', "%{$search}%")
                    ->orWhereHas('siswa', function($q) use ($search) {
                        $q->where('nama', 'like', "%{$search}%");
                    })
                    ->orWhereHas('buku', function($q) use ($search) {
                        $q->where('judul', 'like', "%{$search}%");
                    });
    }
}