<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Prestasi extends Model
{
    protected $table = 'prestasi';
    
    protected $fillable = [
        'siswa_id',
        'jenis_prestasi',
        'tingkat',
        'nama_prestasi',
        'penyelenggara',
        'tanggal_prestasi',
        'peringkat',
        'deskripsi',
        'sertifikat',
        'foto',
        'status',
    ];

    protected $casts = [
        'tanggal_prestasi' => 'date',
        'status' => 'boolean',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }
}