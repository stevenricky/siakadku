<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasNotifications;

class Nilai extends Model
{


        use HasNotifications;

    use HasFactory;
    
    protected $table = 'nilais';
    
    protected $fillable = [
        'siswa_id', 
        'mapel_id', 
        'guru_id', 
        'tahun_ajaran_id', 
        'semester', 
        'nilai_uh1', 
        'nilai_uh2', 
        'nilai_uts', 
        'nilai_uas', 
        'nilai_akhir', 
        'predikat', 
        'deskripsi',
    ];
    
    protected $casts = [
        'nilai_uh1' => 'decimal:2',
        'nilai_uh2' => 'decimal:2',
        'nilai_uts' => 'decimal:2',
        'nilai_uas' => 'decimal:2',
        'nilai_akhir' => 'decimal:2',
    ];
    
    // Relationships
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
    
    public function mapel()
    {
        return $this->belongsTo(Mapel::class);
    }
    
    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }
    
    public function tahun_ajaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }
    
    // Alias untuk tahunAjaran (camelCase)
    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }
    
    // Accessor untuk status kelulusan
    public function getStatusAttribute()
    {
        if ($this->mapel && $this->mapel->kkm) {
            return $this->nilai_akhir >= $this->mapel->kkm ? 'Lulus' : 'Tidak Lulus';
        }
        
        return $this->nilai_akhir >= 75 ? 'Lulus' : 'Tidak Lulus';
    }


    // Accessor untuk handle null relations
public function getNamaSiswaAttribute()
{
    return $this->siswa ? $this->siswa->nama_lengkap : 'Siswa tidak ditemukan';
}

public function getNisSiswaAttribute()
{
    return $this->siswa ? $this->siswa->nis : 'N/A';
}

public function getNamaMapelAttribute()
{
    return $this->mapel ? $this->mapel->nama_mapel : 'Mapel tidak ditemukan';
}

public function getNamaGuruAttribute()
{
    return $this->guru ? $this->guru->nama_lengkap : 'Guru tidak ditemukan';
}

public function getNamaKelasAttribute()
{
    return $this->siswa && $this->siswa->kelas 
        ? $this->siswa->kelas->nama_kelas 
        : '-';
}
    
    // Method untuk menentukan predikat berdasarkan nilai
    public function getPredikatAttribute($value)
    {
        if ($value) {
            return $value;
        }
        
        if ($this->nilai_akhir >= 90) return 'A';
        if ($this->nilai_akhir >= 80) return 'B';
        if ($this->nilai_akhir >= 70) return 'C';
        return 'D';
    }
    
    // Method untuk menghitung nilai akhir
    public static function hitungNilaiAkhir($uh1, $uh2, $uts, $uas)
    {
        return ($uh1 * 0.2) + ($uh2 * 0.2) + ($uts * 0.3) + ($uas * 0.3);
    }
    
    // Scopes untuk filtering
    public function scopeLulus($query)
    {
        return $query->whereHas('mapel', function ($q) {
            $q->whereRaw('nilais.nilai_akhir >= mapels.kkm');
        });
    }
    
    public function scopeTidakLulus($query)
    {
        return $query->whereHas('mapel', function ($q) {
            $q->whereRaw('nilais.nilai_akhir < mapels.kkm');
        });
    }

    public function scopeTahunAjaranAktif($query)
    {
        return $query->whereHas('tahun_ajaran', function ($q) {
            $q->where('status', 'aktif');
        });
    }

    // Scope untuk guru tertentu
    public function scopeByGuru($query, $guruId)
    {
        return $query->where('guru_id', $guruId);
    }

    // Scope untuk mapel tertentu
    public function scopeByMapel($query, $mapelId)
    {
        return $query->where('mapel_id', $mapelId);
    }

    // Event untuk auto-calculate nilai akhir
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($nilai) {
            if ($nilai->isDirty(['nilai_uh1', 'nilai_uh2', 'nilai_uts', 'nilai_uas'])) {
                $nilai->nilai_akhir = self::hitungNilaiAkhir(
                    $nilai->nilai_uh1 ?? 0,
                    $nilai->nilai_uh2 ?? 0,
                    $nilai->nilai_uts ?? 0,
                    $nilai->nilai_uas ?? 0
                );
            }

            $nilai->predikat = $nilai->getPredikatAttribute(null);
        });
    }
}