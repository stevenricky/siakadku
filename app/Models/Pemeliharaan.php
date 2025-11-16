<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pemeliharaan extends Model
{
    protected $table = 'pemeliharaan';
    
    protected $fillable = [
        'barang_id',
        'tanggal_pemeliharaan',
        'jenis_pemeliharaan',
        'deskripsi_kerusakan',
        'tindakan',
        'biaya',
        'teknisi',
        'status',
        'catatan',
        'pelapor_id',
    ];

    protected $casts = [
        'tanggal_pemeliharaan' => 'date',
        'biaya' => 'decimal:2',
    ];

    public function barang(): BelongsTo
    {
        return $this->belongsTo(BarangInventaris::class, 'barang_id');
    }

    public function pelapor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pelapor_id');
    }
}