<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Spp extends Model
{
    use HasFactory;

    protected $table = 'spp';

    protected $fillable = [
        'siswa_id',
        'tahun_ajaran_id',
        'nominal',
        'bulan',
        'tahun',
        'tanggal_bayar',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
        'tanggal_bayar' => 'date',
        'tahun' => 'integer',
    ];

    /**
     * Relasi ke model Siswa
     */
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    /**
     * Relasi ke model TahunAjaran
     */
    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }

    /**
     * Scope untuk filter status
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope untuk filter tahun ajaran
     */
    public function scopeTahunAjaran($query, $tahunAjaranId)
    {
        return $query->where('tahun_ajaran_id', $tahunAjaranId);
    }

    /**
     * Scope untuk filter bulan
     */
    public function scopeBulan($query, $bulan)
    {
        return $query->where('bulan', $bulan);
    }

    /**
     * Scope untuk filter tahun
     */
    public function scopeTahun($query, $tahun)
    {
        return $query->where('tahun', $tahun);
    }

    /**
     * Scope untuk mencari berdasarkan nama siswa
     */
    public function scopeSearch($query, $search)
    {
        return $query->whereHas('siswa', function ($q) use ($search) {
            $q->where('nama', 'like', '%' . $search . '%')
              ->orWhere('nis', 'like', '%' . $search . '%')
              ->orWhere('nisn', 'like', '%' . $search . '%');
        });
    }

    /**
     * Accessor untuk status lengkap
     */
    public function getStatusLengkapAttribute(): string
    {
        return match($this->status) {
            'belum_bayar' => 'Belum Bayar',
            'lunas' => 'Lunas',
            'tertunggak' => 'Tertunggak',
            default => 'Tidak Diketahui'
        };
    }

    /**
     * Accessor untuk warna status (untuk Tailwind CSS)
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'belum_bayar' => 'red',
            'lunas' => 'green',
            'tertunggak' => 'orange',
            default => 'gray'
        };
    }

    /**
     * Accessor untuk format nominal
     */
    public function getNominalFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->nominal, 0, ',', '.');
    }

    /**
     * Accessor untuk periode lengkap
     */
    public function getPeriodeLengkapAttribute(): string
    {
        return $this->bulan . ' ' . $this->tahun;
    }

    /**
     * Method untuk menandai sebagai lunas
     */
    public function markAsPaid(): bool
    {
        return $this->update([
            'status' => 'lunas',
            'tanggal_bayar' => now()
        ]);
    }

    /**
     * Method untuk menandai sebagai tertunggak
     */
    public function markAsOverdue(): bool
    {
        return $this->update([
            'status' => 'tertunggak',
            'tanggal_bayar' => null
        ]);
    }

    /**
     * Method untuk menandai sebagai belum bayar
     */
    public function markAsUnpaid(): bool
    {
        return $this->update([
            'status' => 'belum_bayar',
            'tanggal_bayar' => null
        ]);
    }

    /**
     * Cek apakah SPP sudah lunas
     */
    public function isPaid(): bool
    {
        return $this->status === 'lunas';
    }

    /**
     * Cek apakah SPP tertunggak
     */
    public function isOverdue(): bool
    {
        return $this->status === 'tertunggak';
    }

    /**
     * Cek apakah SPP belum bayar
     */
    public function isUnpaid(): bool
    {
        return $this->status === 'belum_bayar';
    }

    /**
     * Cek apakah SPP sudah lewat jatuh tempo (contoh logic)
     */
    public function isOverdueDate(): bool
    {
        if ($this->isPaid()) {
            return false;
        }

        $currentMonth = now()->month;
        $currentYear = now()->year;
        
        $bulanToNumber = [
            'Januari' => 1, 'Februari' => 2, 'Maret' => 3, 'April' => 4,
            'Mei' => 5, 'Juni' => 6, 'Juli' => 7, 'Agustus' => 8,
            'September' => 9, 'Oktober' => 10, 'November' => 11, 'Desember' => 12
        ];

        $sppMonth = $bulanToNumber[$this->bulan] ?? 1;
        
        // Jika tahun SPP < tahun sekarang, pasti telat
        if ($this->tahun < $currentYear) {
            return true;
        }
        
        // Jika tahun sama, cek bulan
        if ($this->tahun == $currentYear && $sppMonth < $currentMonth) {
            return true;
        }
        
        return false;
    }
}