<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use App\Events\PembayaranDiupdate;
use Illuminate\Support\Str;

class TagihanSpp extends Model
{
    use HasFactory;

    protected $table = 'tagihan_spp';

    protected $fillable = [
        'siswa_id',
        'biaya_spp_id',
        'bulan',
        'tahun',
        'jumlah_tagihan',
        'denda',
        'status',
        'tanggal_jatuh_tempo',
        'tanggal_bayar',
        'keterangan',
        'metode_pembayaran',
        'bukti_pembayaran',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'jumlah_tagihan' => 'decimal:2',
        'denda' => 'decimal:2',
        'tanggal_jatuh_tempo' => 'date',
        'tanggal_bayar' => 'date',
        'verified_at' => 'datetime',
        'tahun' => 'integer',
    ];

    // Status constants
    const STATUS_BELUM_BAYAR = 'belum_bayar';
    const STATUS_LUNAS = 'lunas';
    const STATUS_TERTUNGGAK = 'tertunggak';
    const STATUS_DIVERIFIKASI = 'diverifikasi';

    // Metode pembayaran constants
    const METHOD_CASH = 'cash';
    const METHOD_TRANSFER = 'transfer';
    const METHOD_QRIS = 'qris';

    // Accessors yang diperlukan untuk view
    public function getJumlahTagihanFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->jumlah_tagihan ?? 0, 0, ',', '.');
    }

    public function getDendaFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->denda ?? 0, 0, ',', '.');
    }

    public function getTotalPembayaranAttribute(): float
    {
        // Pastikan nilai tidak null
        $jumlahTagihan = $this->jumlah_tagihan ?? 0;
        $denda = $this->denda ?? 0;
        
        return (float) $jumlahTagihan + (float) $denda;
    }

    public function getTotalPembayaranFormattedAttribute(): string
    {
        $total = $this->total_pembayaran;
        return 'Rp ' . number_format($total, 0, ',', '.');
    }

    public function getStatusLengkapAttribute(): string
    {
        if (!$this->status) {
            return 'Tidak Diketahui';
        }
        
        return match($this->status) {
            self::STATUS_BELUM_BAYAR => 'Belum Bayar',
            self::STATUS_LUNAS => 'Lunas',
            self::STATUS_TERTUNGGAK => 'Tertunggak',
            self::STATUS_DIVERIFIKASI => 'Terverifikasi',
            default => 'Tidak Diketahui'
        };
    }

    public function getJatuhTempoRelativeAttribute(): string
    {
        if (!$this->tanggal_jatuh_tempo) {
            return '-';
        }
        
        $now = now();
        $jatuhTempo = Carbon::parse($this->tanggal_jatuh_tempo);
        
        if ($jatuhTempo->isPast()) {
            return $jatuhTempo->diffForHumans() . ' lalu';
        }
        
        return $jatuhTempo->diffForHumans();
    }

    /**
     * Accessor untuk warna status
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            self::STATUS_BELUM_BAYAR => 'yellow',
            self::STATUS_LUNAS => 'green',
            self::STATUS_TERTUNGGAK => 'red',
            self::STATUS_DIVERIFIKASI => 'blue',
            default => 'gray'
        };
    }

    /**
     * Accessor untuk icon status
     */
    public function getStatusIconAttribute(): string
    {
        return match($this->status) {
            self::STATUS_BELUM_BAYAR => 'fas fa-clock',
            self::STATUS_LUNAS => 'fas fa-check-circle',
            self::STATUS_TERTUNGGAK => 'fas fa-exclamation-triangle',
            self::STATUS_DIVERIFIKASI => 'fas fa-shield-check',
            default => 'fas fa-question-circle'
        };
    }

    /**
     * Accessor untuk nama bulan lengkap
     */
    public function getBulanLengkapAttribute(): string
    {
        $bulanList = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        
        return $bulanList[array_search($this->bulan, $bulanList)] ?? $this->bulan;
    }

    /**
     * Accessor untuk periode lengkap
     */
    public function getPeriodeLengkapAttribute(): string
    {
        return $this->bulan_lengkap . ' ' . $this->tahun;
    }

    /**
     * Accessor untuk metode pembayaran lengkap
     */
    public function getMetodePembayaranLengkapAttribute(): string
    {
        return match($this->metode_pembayaran) {
            self::METHOD_CASH => 'Tunai',
            self::METHOD_TRANSFER => 'Transfer Bank',
            self::METHOD_QRIS => 'QRIS',
            default => 'Belum Dipilih'
        };
    }

    /**
     * Accessor untuk URL bukti pembayaran
     */
    public function getBuktiPembayaranUrlAttribute(): ?string
    {
        return $this->bukti_pembayaran ? asset('storage/bukti-pembayaran/' . $this->bukti_pembayaran) : null;
    }

    /**
     * Accessor untuk status verifikasi
     */
    public function getTerverifikasiAttribute(): bool
    {
        return !is_null($this->verified_at);
    }

    /**
     * Accessor untuk hari tersisa sampai jatuh tempo
     */
    public function getHariTersisaAttribute(): int
    {
        return now()->diffInDays($this->tanggal_jatuh_tempo, false);
    }

    /**
     * Accessor untuk status urgensi
     */
    public function getStatusUrgensiAttribute(): string
    {
        if ($this->status === self::STATUS_LUNAS) {
            return 'selesai';
        }

        $hariTersisa = $this->hari_tersisa;

        if ($hariTersisa < 0) {
            return 'terlambat';
        } elseif ($hariTersisa <= 3) {
            return 'mendesak';
        } elseif ($hariTersisa <= 7) {
            return 'peringatan';
        } else {
            return 'normal';
        }
    }

    /**
     * Cek apakah tagihan memiliki pembayaran online yang pending
     */
    public function getHasPendingOnlinePaymentAttribute(): bool
    {
        return $this->pembayaranOnlinePending()->exists();
    }

    /**
     * Cek apakah tagihan sudah memiliki pembayaran online yang berhasil
     */
    public function getHasSuccessfulOnlinePaymentAttribute(): bool
    {
        return $this->pembayaranOnlineSuccess()->exists();
    }

    /**
     * Get pembayaran online terbaru yang pending
     */
    public function getLatestPendingPaymentAttribute(): ?PembayaranSpp
    {
        return $this->pembayaranOnlinePending()
                    ->orderBy('created_at', 'desc')
                    ->first();
    }

    /**
     * Cek apakah tagihan bisa dibayar online
     */
    public function getCanPayOnlineAttribute(): bool
{
    return in_array($this->status, [self::STATUS_BELUM_BAYAR, self::STATUS_TERTUNGGAK]) && 
           is_null($this->tanggal_bayar);
}

    /**
     * Accessor untuk jumlah pembayaran online pending
     */
    public function getJumlahPembayaranPendingAttribute(): int
    {
        return $this->pembayaranOnlinePending()->count();
    }

    // Relationships
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    public function biayaSpp(): BelongsTo
    {
        return $this->belongsTo(BiayaSpp::class);
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Relasi ke pembayaran (jika ada sistem pembayaran terpisah)
     */
    public function pembayaran(): HasMany
    {
        return $this->hasMany(PembayaranSpp::class, 'tagihan_spp_id');
    }

    /**
     * Relasi ke pembayaran online yang pending
     */
    public function pembayaranOnlinePending(): HasMany
    {
        return $this->hasMany(PembayaranSpp::class, 'tagihan_spp_id')
                    ->where('status_pembayaran', 'pending');
    }

    /**
     * Relasi ke pembayaran online yang berhasil
     */
    public function pembayaranOnlineSuccess(): HasMany
    {
        return $this->hasMany(PembayaranSpp::class, 'tagihan_spp_id')
                    ->where('status_pembayaran', 'paid');
    }

    // Scopes
    /**
     * Scope untuk filter status
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope untuk filter bulan
     */
    public function scopeBulan($query, $bulan)
    {
        return $query->where('bulan', $bulan);
    }

    /**
     * Scope untuk filter tahun
     */
    public function scopeTahun($query, $tahun)
    {
        return $query->where('tahun', $tahun);
    }

    /**
     * Scope untuk tagihan aktif (belum lunas)
     */
    public function scopeAktif($query)
    {
        return $query->whereIn('status', [self::STATUS_BELUM_BAYAR, self::STATUS_TERTUNGGAK]);
    }

    /**
     * Scope untuk tagihan yang sudah lunas
     */
    public function scopeLunas($query)
    {
        return $query->where('status', self::STATUS_LUNAS);
    }

    /**
     * Scope untuk tagihan tertunggak
     */
    public function scopeTertunggak($query)
    {
        return $query->where('status', self::STATUS_TERTUNGGAK);
    }

    /**
     * Scope untuk tagihan berdasarkan periode
     */
    public function scopePeriode($query, $bulan, $tahun)
    {
        return $query->where('bulan', $bulan)->where('tahun', $tahun);
    }

    /**
     * Scope untuk tagihan siswa tertentu
     */
    public function scopeSiswa($query, $siswaId)
    {
        return $query->where('siswa_id', $siswaId);
    }

    /**
     * Scope untuk tagihan yang perlu diingatkan (mendekati jatuh tempo)
     */
    public function scopePerluPengingat($query)
    {
        return $query->whereIn('status', [self::STATUS_BELUM_BAYAR, self::STATUS_TERTUNGGAK])
                    ->where('tanggal_jatuh_tempo', '<=', now()->addDays(3));
    }

    // Methods
    /**
     * Method untuk menandai sebagai lunas
     */
    public function markAsPaid($metode = self::METHOD_CASH, $buktiPembayaran = null): bool
    {
        return $this->update([
            'status' => self::STATUS_LUNAS,
            'tanggal_bayar' => now(),
            'metode_pembayaran' => $metode,
            'bukti_pembayaran' => $buktiPembayaran,
        ]);
    }

    /**
     * Method untuk verifikasi pembayaran
     */
    public function verify($userId): bool
    {
        return $this->update([
            'status' => self::STATUS_DIVERIFIKASI,
            'verified_by' => $userId,
            'verified_at' => now(),
        ]);
    }

    /**
     * Method untuk batalkan verifikasi
     */
    public function unverify(): bool
    {
        return $this->update([
            'status' => self::STATUS_LUNAS,
            'verified_by' => null,
            'verified_at' => null,
        ]);
    }

    /**
     * Cek apakah tagihan sudah lewat jatuh tempo
     */
    public function isOverdue(): bool
    {
        return $this->status === self::STATUS_BELUM_BAYAR && $this->tanggal_jatuh_tempo->isPast();
    }

    /**
     * Cek apakah tagihan mendekati jatuh tempo
     */
    public function isDueSoon(): bool
    {
        return $this->status === self::STATUS_BELUM_BAYAR && 
               $this->tanggal_jatuh_tempo->between(now(), now()->addDays(3));
    }

    /**
     * Hitung denda otomatis berdasarkan keterlambatan
     */
    public function calculateDenda(): float
    {
        if (!$this->isOverdue()) {
            return 0;
        }

        $daysLate = now()->diffInDays($this->tanggal_jatuh_tempo);
        $dendaPerDay = 5000; // Rp 5.000 per hari

        return min($daysLate * $dendaPerDay, $this->jumlah_tagihan * 0.2); // Maksimal 20% dari tagihan
    }

    /**
     * Update denda otomatis berdasarkan keterlambatan
     */
    public function updateDenda(): bool
    {
        if ($this->status === self::STATUS_LUNAS || $this->status === self::STATUS_DIVERIFIKASI) {
            return false;
        }

        $dendaBaru = $this->calculateDenda();
        
        if ($dendaBaru > 0 && $this->status !== self::STATUS_TERTUNGGAK) {
            $this->update([
                'status' => self::STATUS_TERTUNGGAK,
                'denda' => $dendaBaru,
            ]);
        } elseif ($dendaBaru > 0) {
            $this->update(['denda' => $dendaBaru]);
        }

        return true;
    }

    /**
     * Method untuk membuat pembayaran online
    /**
 * Method untuk membuat pembayaran online (FIXED)
 */
/**
 * Method untuk membuat pembayaran online (FIXED untuk tanggal_bayar)
 */
public function createOnlinePayment($metodeBayar, $channelPembayaran, $biayaAdmin = 0): PembayaranSpp
{
    $totalBayar = $this->total_pembayaran + $biayaAdmin;

    // Generate kode referensi yang unik
    $kodeReferensi = 'PAY-' . date('Ymd') . '-' . strtoupper(Str::random(6));

    return PembayaranSpp::create([
        'tagihan_spp_id' => $this->id,
        'siswa_id' => $this->siswa_id,
        'jumlah_bayar' => $this->total_pembayaran,
        'biaya_admin' => $biayaAdmin,
        'tanggal_bayar' => now(), // ✅ WAJIB DIISI KARENA KOLOM NO NULL
        'metode_bayar' => $metodeBayar,
        'channel_pembayaran' => $channelPembayaran,
        'status_verifikasi' => PembayaranSpp::STATUS_PENDING,
        'status_pembayaran' => PembayaranSpp::PAYMENT_PENDING,
        'kode_referensi' => $kodeReferensi,
        'waktu_kadaluarsa' => now()->addHours(24),
        'catatan' => 'Pembayaran online via ' . $this->getChannelName($channelPembayaran),
    ]);
}

    /**
     * Method untuk menandai pembayaran online berhasil
     */
    public function markOnlinePaymentAsPaid($pembayaranId): bool
    {
        $pembayaran = PembayaranSpp::find($pembayaranId);
        
        if ($pembayaran && $pembayaran->tagihan_spp_id === $this->id) {
            // Update pembayaran
            $pembayaran->update([
                'status_pembayaran' => 'paid',
                'status_verifikasi' => 'diterima',
                'verified_at' => now()
            ]);

            // Update tagihan - hanya field yang ada
            $updateData = [
                'status' => self::STATUS_LUNAS,
                'tanggal_bayar' => now(),
            ];

            // Hanya update metode_pembayaran jika kolomnya ada
            if (Schema::hasColumn('tagihan_spp', 'metode_pembayaran')) {
                $updateData['metode_pembayaran'] = $pembayaran->metode_bayar;
            }

            // Hanya update bukti_pembayaran jika kolomnya ada
            if (Schema::hasColumn('tagihan_spp', 'bukti_pembayaran')) {
                $updateData['bukti_pembayaran'] = 'online_payment_' . $pembayaran->kode_referensi;
            }

            return $this->update($updateData);
        }

        return false;
    }

    /**
     * Method untuk menandai pembayaran online gagal
     */
    public function markOnlinePaymentAsFailed($pembayaranId): bool
    {
        $pembayaran = PembayaranSpp::find($pembayaranId);
        
        if ($pembayaran && $pembayaran->tagihan_spp_id === $this->id) {
            return $pembayaran->update([
                'status_pembayaran' => 'failed'
            ]);
        }

        return false;
    }

    /**
     * Get channel name
     */
    private function getChannelName($channel): string
    {
        $channels = [
            'bca' => 'BCA Virtual Account',
            'bri' => 'BRI Virtual Account', 
            'bni' => 'BNI Virtual Account',
            'mandiri' => 'Mandiri Virtual Account',
            'gopay' => 'GoPay',
            'ovo' => 'OVO',
            'dana' => 'DANA',
            'shopeepay' => 'ShopeePay',
            'qris' => 'QRIS'
        ];

        return $channels[$channel] ?? 'Online Payment';
    }

    /**
     * Generate kode tagihan unik
     */
    public function generateKodeTagihan(): string
    {
        $bulan = str_pad(array_search($this->bulan, [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ]) + 1, 2, '0', STR_PAD_LEFT);

        return 'SPP-' . $this->siswa_id . '-' . $bulan . substr($this->tahun, -2) . '-' . $this->id;
    }

    /**
     * Cek apakah bisa dihapus
     */
    public function canBeDeleted(): bool
    {
        return $this->status === self::STATUS_BELUM_BAYAR && 
               is_null($this->tanggal_bayar) && 
               is_null($this->verified_at);
    }

    /**
     * Cek apakah bisa diedit
     */
    public function canBeEdited(): bool
    {
        return $this->status !== self::STATUS_DIVERIFIKASI;
    }

    /**
     * Get history status
     */
    public function getStatusHistoryAttribute(): array
    {
        $history = [];

        // Created
        $history[] = [
            'status' => 'created',
            'title' => 'Tagihan Dibuat',
            'description' => 'Tagihan SPP periode ' . $this->periode_lengkap . ' telah dibuat',
            'timestamp' => $this->created_at,
            'icon' => 'fas fa-file-invoice',
            'color' => 'blue'
        ];

        // Jika sudah bayar
        if ($this->tanggal_bayar) {
            $history[] = [
                'status' => 'paid',
                'title' => 'Pembayaran Diterima',
                'description' => 'Pembayaran sebesar ' . $this->total_pembayaran_formatted . ' via ' . $this->metode_pembayaran_lengkap,
                'timestamp' => $this->tanggal_bayar,
                'icon' => 'fas fa-money-bill-wave',
                'color' => 'green'
            ];
        }

        // Jika terverifikasi
        if ($this->verified_at) {
            $history[] = [
                'status' => 'verified',
                'title' => 'Pembayaran Diverifikasi',
                'description' => 'Pembayaran telah diverifikasi oleh admin',
                'timestamp' => $this->verified_at,
                'icon' => 'fas fa-shield-check',
                'color' => 'purple'
            ];
        }

        return $history;
    }

    /**
     * Boot method untuk event listeners
     */
    protected static function boot()
    {
        parent::boot();



        // Auto update denda ketika model di-retrieve
        static::retrieved(function ($tagihan) {
            if ($tagihan->isOverdue()) {
                $tagihan->updateDenda();
            }
        });

        // Auto generate keterangan berdasarkan status
        static::saving(function ($tagihan) {
            if (empty($tagihan->keterangan)) {
                $tagihan->keterangan = match($tagihan->status) {
                    self::STATUS_BELUM_BAYAR => 'Menunggu pembayaran',
                    self::STATUS_LUNAS => 'Pembayaran lunas',
                    self::STATUS_TERTUNGGAK => 'Terlambat bayar, dikenakan denda',
                    self::STATUS_DIVERIFIKASI => 'Pembayaran telah diverifikasi',
                    default => 'Tagihan SPP'
                };
            }
        });
    }

    
}