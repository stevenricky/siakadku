<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BiayaSpp extends Model
{
    use HasFactory;

    protected $table = 'biaya_spp';

    protected $fillable = [
        'tahun_ajaran_id',
        'kelas_id',
        'kategori_biaya_id',
        'jumlah',
        'tanggal_mulai',
        'tanggal_selesai',
        'status'
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'status' => 'boolean',
    ];

    /**
     * Relasi ke model TahunAjaran
     */
    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }

    /**
     * Relasi ke model Kelas
     */
    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    /**
     * Relasi ke model KategoriBiaya
     */
    public function kategoriBiaya(): BelongsTo
    {
        return $this->belongsTo(\App\Models\KategoriBiaya::class, 'kategori_biaya_id');
    }

    /**
     * Relasi ke model TagihanSpp
     */
    public function tagihanSpp()
    {
        return $this->hasMany(TagihanSpp::class, 'biaya_spp_id');
    }

    /**
     * Scope untuk biaya aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Accessor untuk format jumlah
     */
    public function getJumlahFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->jumlah, 0, ',', '.');
    }

    /**
     * Accessor untuk nama biaya
     */
    public function getNamaBiayaAttribute(): string
    {
        return $this->kategoriBiaya->nama_kategori ?? 'SPP ' . ($this->kelas->nama_kelas ?? '');
    }
}