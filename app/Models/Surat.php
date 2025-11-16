<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Surat extends Model
{
    protected $table = 'surat';
    
    protected $fillable = [
        'nomor_surat',
        'jenis_surat',
        'perihal',
        'isi_singkat',
        'pengirim',
        'penerima',
        'tanggal_surat',
        'tanggal_terima',
        'file_surat',
        'disposisi_ke',
        'catatan_disposisi',
        'status',
        'created_by'
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
        'tanggal_terima' => 'date',
    ];

    /**
     * Relationship dengan user yang membuat surat
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope untuk surat masuk
     */
    public function scopeMasuk($query)
    {
        return $query->where('jenis_surat', 'masuk');
    }

    /**
     * Scope untuk surat keluar
     */
    public function scopeKeluar($query)
    {
        return $query->where('jenis_surat', 'keluar');
    }

    /**
     * Scope untuk surat berdasarkan status
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope untuk surat perlu tindakan
     */
    public function scopePerluTindakan($query)
    {
        return $query->whereIn('status', ['baru', 'diproses']);
    }

    /**
     * Accessor untuk jenis surat formatted
     */
    protected function jenisSuratFormatted(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->jenis_surat == 'masuk' ? 'Surat Masuk' : 'Surat Keluar',
        );
    }

    /**
     * Accessor untuk status formatted
     */
    protected function statusFormatted(): Attribute
    {
        return Attribute::make(
            get: function () {
                $statuses = [
                    'baru' => 'Baru',
                    'diproses' => 'Diproses',
                    'selesai' => 'Selesai',
                    'arsip' => 'Arsip'
                ];

                return $statuses[$this->status] ?? $this->status;
            },
        );
    }

    /**
     * Accessor untuk disposisi formatted
     */
    protected function disposisiFormatted(): Attribute
    {
        return Attribute::make(
            get: function () {
                $disposisi = [
                    'kepala_sekolah' => 'Kepala Sekolah',
                    'wakil_kepala_sekolah' => 'Wakil Kepala Sekolah',
                    'guru' => 'Guru',
                    'tata_usaha' => 'Tata Usaha',
                    'lainnya' => 'Lainnya'
                ];

                return $disposisi[$this->disposisi_ke] ?? $this->disposisi_ke;
            },
        );
    }

    /**
     * Check jika surat memiliki file
     */
    protected function hasFile(): Attribute
    {
        return Attribute::make(
            get: fn () => !empty($this->file_surat),
        );
    }

    /**
     * Get file URL
     */
    protected function fileUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->file_surat ? asset('storage/' . $this->file_surat) : null,
        );
    }

    /**
     * Get badge color berdasarkan status
     */
    protected function statusBadge(): Attribute
    {
        return Attribute::make(
            get: function () {
                $colors = [
                    'baru' => 'bg-primary',
                    'diproses' => 'bg-warning',
                    'selesai' => 'bg-success',
                    'arsip' => 'bg-secondary'
                ];

                return $colors[$this->status] ?? 'bg-secondary';
            },
        );
    }

    /**
     * Get badge color berdasarkan jenis surat
     */
    protected function jenisSuratBadge(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->jenis_surat == 'masuk' ? 'bg-info' : 'bg-success',
        );
    }
}