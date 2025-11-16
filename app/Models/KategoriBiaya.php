<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriBiaya extends Model
{
    use HasFactory;

    protected $table = 'kategori_biaya';

    protected $fillable = [
        'nama_kategori',
        'deskripsi',
        'jenis',
        'jumlah_biaya',
        'periode',
        'status'
    ];

    protected $casts = [
        'jumlah_biaya' => 'decimal:2',
        'status' => 'boolean',
    ];

    /**
     * Relasi ke model BiayaSpp
     */
    public function biayaSpp()
    {
        return $this->hasMany(BiayaSpp::class, 'kategori_biaya_id');
    }

    /**
     * Scope untuk kategori aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Scope untuk jenis SPP
     */
    public function scopeSpp($query)
    {
        return $query->where('jenis', 'spp');
    }

    /**
     * Accessor untuk format jumlah biaya
     */
    public function getJumlahBiayaFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->jumlah_biaya, 0, ',', '.');
    }

    /**
     * Accessor untuk jenis lengkap
     */
    public function getJenisLengkapAttribute(): string
    {
        return match($this->jenis) {
            'spp' => 'SPP',
            'dana_siswa' => 'Dana Siswa',
            'lainnya' => 'Lainnya',
            default => $this->jenis
        };
    }

    /**
     * Accessor untuk periode lengkap
     */
    public function getPeriodeLengkapAttribute(): string
    {
        return match($this->periode) {
            'bulanan' => 'Bulanan',
            'semester' => 'Semester',
            'tahunan' => 'Tahunan',
            default => $this->periode
        };
    }
}