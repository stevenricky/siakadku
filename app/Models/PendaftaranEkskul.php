<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PendaftaranEkskul extends Model
{
    protected $table = 'pendaftaran_ekskul';
    
    protected $fillable = [
        'siswa_id',
        'ekstrakurikuler_id',
        'tahun_ajaran',
        'status_pendaftaran',
        'alasan_ditolak',
        'disetujui_oleh',
        'disetujui_pada',
    ];

    protected $casts = [
        'tahun_ajaran' => 'integer',
        'disetujui_pada' => 'datetime',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function ekstrakurikuler(): BelongsTo
    {
        return $this->belongsTo(Ekstrakurikuler::class, 'ekstrakurikuler_id');
    }

    public function disetujuiOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }
}