<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TracerStudy extends Model
{
    protected $table = 'tracer_study';
    
    protected $fillable = [
        'alumni_id',
        'tahun_survey',
        'status_pekerjaan',
        'nama_perusahaan',
        'bidang_pekerjaan',
        'jabatan',
        'gaji',
        'nama_kampus',
        'jurusan_kuliah',
        'tahun_masuk_kuliah',
        'relevansi_pendidikan',
        'saran_untuk_sekolah',
        'status_survey'
    ];

    protected $casts = [
        'gaji' => 'decimal:2'
    ];

    /**
     * Relasi ke alumni
     */
    public function alumni(): BelongsTo
    {
        return $this->belongsTo(Alumni::class);
    }

    /**
     * Scope untuk survey terjawab
     */
    public function scopeDijawab($query)
    {
        return $query->where('status_survey', 'dijawab');
    }

    /**
     * Scope untuk status pekerjaan tertentu
     */
    public function scopeStatusPekerjaan($query, $status)
    {
        return $query->where('status_pekerjaan', $status);
    }

    /**
     * Scope untuk tahun survey tertentu
     */
    public function scopeTahunSurvey($query, $tahun)
    {
        return $query->where('tahun_survey', $tahun);
    }
}