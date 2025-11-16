<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasNotifications;

class AgendaSekolah extends Model
{

    use HasNotifications;

    protected $table = 'agenda_sekolah';
    
    protected $fillable = [
        'judul_agenda',
        'deskripsi',
        'tanggal_mulai',
        'tanggal_selesai',
        'waktu_mulai',
        'waktu_selesai',
        'tempat',
        'penanggung_jawab',
        'sasaran',
        'jenis_agenda',
        'dokumentasi',
        'status'
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'waktu_mulai' => 'datetime:H:i',
        'waktu_selesai' => 'datetime:H:i',
    ];

    protected $appends = [
        'jenis_agenda_formatted',
        'sasaran_formatted',
        'status_badge',
        'status_text',
        'tanggal_format',
        'waktu_format'
    ];

    /**
     * Scope untuk agenda aktif
     */
    public function scopeAktif($query)
    {
        return $query->whereIn('status', ['terjadwal', 'berlangsung']);
    }

    /**
     * Scope untuk agenda berdasarkan status
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope untuk agenda mendatang
     */
    public function scopeMendatang($query)
    {
        return $query->where('status', 'terjadwal')
                    ->where('tanggal_mulai', '>=', now())
                    ->orderBy('tanggal_mulai', 'asc');
    }

    /**
     * Scope untuk agenda berlangsung
     */
    public function scopeBerlangsung($query)
    {
        $today = now()->format('Y-m-d');
        return $query->where('status', 'berlangsung')
                    ->orWhere(function($q) use ($today) {
                        $q->where('status', 'terjadwal')
                          ->where('tanggal_mulai', '<=', $today)
                          ->where('tanggal_selesai', '>=', $today);
                    });
    }

    /**
     * Accessor untuk jenis agenda formatted
     */
    public function getJenisAgendaFormattedAttribute()
    {
        $jenis = [
            'akademik' => 'Akademik',
            'non_akademik' => 'Non-Akademik',
            'sosial' => 'Sosial',
            'lainnya' => 'Lainnya'
        ];

        return $jenis[$this->jenis_agenda] ?? $this->jenis_agenda;
    }

    /**
     * Accessor untuk sasaran formatted
     */
    public function getSasaranFormattedAttribute()
    {
        $sasaran = [
            'seluruh_sekolah' => 'Seluruh Sekolah',
            'guru' => 'Guru',
            'siswa' => 'Siswa',
            'kelas_x' => 'Kelas X',
            'kelas_xi' => 'Kelas XI',
            'kelas_xii' => 'Kelas XII'
        ];

        return $sasaran[$this->sasaran] ?? $this->sasaran;
    }

    /**
     * Accessor untuk status badge
     */
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'terjadwal' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
            'berlangsung' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
            'selesai' => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200',
            'dibatalkan' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200'
        };
    }

    /**
     * Accessor untuk status text
     */
    public function getStatusTextAttribute()
    {
        return match($this->status) {
            'terjadwal' => 'Terjadwal',
            'berlangsung' => 'Berlangsung',
            'selesai' => 'Selesai',
            'dibatalkan' => 'Dibatalkan',
            default => $this->status
        };
    }

    /**
     * Accessor untuk tanggal format
     */
    public function getTanggalFormatAttribute()
    {
        if ($this->tanggal_mulai->format('Y-m-d') === $this->tanggal_selesai->format('Y-m-d')) {
            return $this->tanggal_mulai->translatedFormat('d F Y');
        }
        
        return $this->tanggal_mulai->translatedFormat('d F Y') . ' - ' . $this->tanggal_selesai->translatedFormat('d F Y');
    }

    /**
     * Accessor untuk waktu format
     */
    public function getWaktuFormatAttribute()
    {
        if ($this->waktu_mulai && $this->waktu_selesai) {
            return $this->waktu_mulai->format('H:i') . ' - ' . $this->waktu_selesai->format('H:i');
        }
        
        return '-';
    }

    /**
     * Cek apakah agenda untuk siswa
     */
    public function getUntukSiswaAttribute()
    {
        return in_array($this->sasaran, ['siswa', 'seluruh_sekolah', 'kelas_x', 'kelas_xi', 'kelas_xii']);
    }
}