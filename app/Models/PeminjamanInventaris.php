<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class PeminjamanInventaris extends Model
{
    protected $table = 'peminjaman_inventaris';
    
    protected $fillable = [
        'kode_peminjaman',
        'barang_id',
        'peminjam_id',
        'tanggal_pinjam',
        'tanggal_kembali',
        'tanggal_dikembalikan',
        'jumlah',
        'tujuan_peminjaman',
        'status',
        'keterangan',
        'penanggung_jawab_id',
        'kondisi_pengembalian',
        'keterangan_pengembalian',
        'denda'
    ];

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_kembali' => 'date',
        'tanggal_dikembalikan' => 'date',
        'jumlah' => 'integer',
        'denda' => 'integer'
    ];

    // Relasi ke BarangInventaris
    public function barang(): BelongsTo
    {
        return $this->belongsTo(BarangInventaris::class);
    }

    // Relasi ke User (peminjam)
    public function peminjam(): BelongsTo
    {
        return $this->belongsTo(User::class, 'peminjam_id');
    }

    // Relasi ke User (penanggung jawab)
    public function penanggungJawab(): BelongsTo
    {
        return $this->belongsTo(User::class, 'penanggung_jawab_id');
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

    // Method untuk cek apakah terlambat
    public function getIsTerlambatAttribute(): bool
    {
        if ($this->status === 'dikembalikan' || $this->status === 'hilang') {
            return false;
        }

        return Carbon::today()->gt(Carbon::parse($this->tanggal_kembali));
    }

    // Accessor untuk durasi peminjaman
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
}