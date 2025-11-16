<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BarangInventaris extends Model
{
    protected $table = 'barang_inventaris';
    
    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'kategori_id',
        'ruangan_id',
        'merk',
        'tipe',
        'jumlah',
        'satuan',
        'harga',
        'tanggal_pembelian',
        'sumber_dana',
        'spesifikasi',
        'kondisi',
        'keterangan',
        'foto',
        'jumlah_tersedia',
        'status',
    ];

    // Accessor untuk status badge
public function getStatusBadgeAttribute(): string
{
    return match($this->status) {
        'tersedia' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
        'dipinjam' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
        'rusak' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
        'hilang' => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200',
        default => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200'
    };
}

public function getStatusTextAttribute(): string
{
    return match($this->status) {
        'tersedia' => 'Tersedia',
        'dipinjam' => 'Dipinjam',
        'rusak' => 'Rusak',
        'hilang' => 'Hilang',
        default => $this->status
    };
}

    protected $casts = [
        'tanggal_pembelian' => 'date',
        'harga' => 'decimal:2',
        'jumlah' => 'integer',
    ];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriInventaris::class, 'kategori_id');
    }

    public function ruangan(): BelongsTo
    {
        return $this->belongsTo(Ruangan::class, 'ruangan_id');
    }
}