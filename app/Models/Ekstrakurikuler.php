<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ekstrakurikuler extends Model
{
    use HasFactory;
    
    protected $table = 'ekstrakurikulers';
    
    protected $fillable = [
        'nama_ekstra',
        'deskripsi',
        'pembina',
        'pembina_id',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'tempat',
        'status',
        'kuota',
        'jumlah_peserta'
    ];
    
    protected $casts = [
        'jam_mulai' => 'datetime:H:i',
        'jam_selesai' => 'datetime:H:i',
        'status' => 'boolean',
        'kuota' => 'integer',
        'jumlah_peserta' => 'integer'
    ];
    
    // Relasi dengan Siswa (Many to Many)
    public function siswa()
    {
        return $this->belongsToMany(Siswa::class, 'ekstrakurikuler_siswa')
                    ->withPivot('status', 'tanggal_daftar')
                    ->withTimestamps();
    }
    
    // Relasi dengan Guru sebagai Pembina
    public function guruPembina()
    {
        return $this->belongsTo(Guru::class, 'pembina_id');
    }
    
    // Scope untuk ekstrakurikuler aktif
    public function scopeAktif($query)
    {
        return $query->where('status', true);
    }
    
    // Accessor untuk jam kegiatan
    public function getJamKegiatanAttribute()
    {
        return $this->jam_mulai->format('H:i') . ' - ' . $this->jam_selesai->format('H:i');
    }
    
    // Accessor untuk status teks
    public function getStatusTextAttribute()
    {
        return $this->status ? 'Aktif' : 'Tidak Aktif';
    }
    
    // Accessor untuk status badge
    public function getStatusBadgeAttribute()
    {
        return $this->status ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200';
    }
    
    // Method untuk cek kuota tersedia
    public function getKuotaTersediaAttribute()
    {
        return $this->kuota - $this->jumlah_peserta;
    }
    
    // Method untuk cek apakah masih bisa mendaftar
    public function getBisaDaftarAttribute()
    {
        return $this->status && $this->kuota_tersedia > 0;
    }
    
    // Method untuk cek apakah siswa sudah terdaftar
    public function isTerdaftar($siswaId)
    {
        return $this->siswa()->where('siswa_id', $siswaId)->exists();
    }
}