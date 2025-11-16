<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PembayaranSpp extends Model
{
    use HasFactory;

    protected $table = 'pembayaran_spp';
    
    protected $fillable = [
        'tagihan_spp_id',
        'siswa_id',
        'jumlah_bayar',
        'tanggal_bayar',
        'metode_bayar',
        'bukti_bayar',
        'status_verifikasi',
        'catatan',
        'verified_by',
        'verified_at',
        // Tambahan untuk payment online
        'channel_pembayaran',
        'kode_referensi',
        'status_pembayaran',
        'url_pembayaran',
        'waktu_kadaluarsa',
        'biaya_admin',
        // Tambahan untuk upload bukti
        'bukti_upload',
        'tanggal_upload'
    ];

    protected $casts = [
    'tanggal_bayar' => 'date',
    'verified_at' => 'datetime',
    'jumlah_bayar' => 'decimal:2',
    'biaya_admin' => 'decimal:2',
    'waktu_kadaluarsa' => 'datetime', 
    'tanggal_upload' => 'datetime',
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_DITERIMA = 'diterima';
    const STATUS_DITOLAK = 'ditolak';

    // Metode bayar constants
    const METHOD_CASH = 'tunai';
    const METHOD_TRANSFER = 'transfer';
    const METHOD_QRIS = 'qris';
    const METHOD_VA = 'virtual_account';
    const METHOD_EWALLET = 'ewallet';

    // Status pembayaran online
    const PAYMENT_PENDING = 'pending';
    const PAYMENT_PAID = 'paid';
    const PAYMENT_FAILED = 'failed';
    const PAYMENT_EXPIRED = 'expired';

    // Channel pembayaran
    const CHANNEL_BCA = 'bca';
    const CHANNEL_BRI = 'bri';
    const CHANNEL_BNI = 'bni';
    const CHANNEL_MANDIRI = 'mandiri';
    const CHANNEL_GOPAY = 'gopay';
    const CHANNEL_OVO = 'ovo';
    const CHANNEL_DANA = 'dana';
    const CHANNEL_SHOPEEPAY = 'shopeepay';

    /**
     * Relasi ke TagihanSpp
     */
    public function tagihanSpp(): BelongsTo
    {
        return $this->belongsTo(TagihanSpp::class);
    }

    /**
     * Relasi ke Siswa
     */
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    /**
     * Relasi ke User (verifikator)
     */
    public function verifikator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // ================ SCOPES ================

    /**
     * Scope untuk status verifikasi
     */
    public function scopePending($query)
{
    return $query->where('status_verifikasi', self::STATUS_PENDING);
}

    public function scopeDiterima($query)
    {
        return $query->where('status_verifikasi', self::STATUS_DITERIMA);
    }

    public function scopeDitolak($query)
    {
        return $query->where('status_verifikasi', self::STATUS_DITOLAK);
    }

    /**
     * Scope untuk pembayaran yang perlu verifikasi (dari file pertama)
     */
    public function scopePerluVerifikasi($query)
    {
        return $query->where('status_verifikasi', self::STATUS_PENDING)
                    ->whereNotNull('bukti_upload');
    }

    /**
     * Scope untuk pembayaran online
     */
    public function scopeOnline($query)
    {
        return $query->whereIn('metode_bayar', [self::METHOD_VA, self::METHOD_EWALLET, self::METHOD_QRIS]);
    }

    public function scopeOffline($query)
    {
        return $query->whereIn('metode_bayar', [self::METHOD_CASH, self::METHOD_TRANSFER]);
    }

    /**
     * Scope untuk status pembayaran online
     */
    public function scopePaymentPending($query)
    {
        return $query->where('status_pembayaran', self::PAYMENT_PENDING);
    }

    public function scopePaymentPaid($query)
    {
        return $query->where('status_pembayaran', self::PAYMENT_PAID);
    }

    /**
     * Scope untuk pembayaran bulan tertentu
     */
    public function scopeBulanIni($query)
    {
        return $query->whereMonth('tanggal_bayar', now()->month)
                    ->whereYear('tanggal_bayar', now()->year);
    }

    // ================ ACCESSORS ================

    /**
     * Accessor untuk status badge
     */
    public function getStatusBadgeAttribute(): string
    {
        return match($this->status_verifikasi) {
            self::STATUS_DITERIMA => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
            self::STATUS_DITOLAK => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
            default => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200'
        };
    }

    /**
     * Accessor untuk status text
     */
    public function getStatusTextAttribute(): string
    {
        return match($this->status_verifikasi) {
            self::STATUS_DITERIMA => 'Diterima',
            self::STATUS_DITOLAK => 'Ditolak',
            default => 'Menunggu Verifikasi'
        };
    }

    /**
     * Accessor untuk status icon
     */
    public function getStatusIconAttribute(): string
    {
        return match($this->status_verifikasi) {
            self::STATUS_DITERIMA => 'fas fa-check-circle text-green-500',
            self::STATUS_DITOLAK => 'fas fa-times-circle text-red-500',
            default => 'fas fa-clock text-yellow-500'
        };
    }

    /**
     * Accessor untuk format jumlah bayar
     */
    public function getJumlahBayarFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->jumlah_bayar, 0, ',', '.');
    }

    /**
     * Accessor untuk metode bayar text
     */
    public function getMetodeBayarTextAttribute(): string
    {
        return match($this->metode_bayar) {
            self::METHOD_TRANSFER => 'Transfer Bank',
            self::METHOD_QRIS => 'QRIS',
            self::METHOD_VA => 'Virtual Account',
            self::METHOD_EWALLET => 'E-Wallet',
            default => 'Tunai'
        };
    }

    /**
     * Accessor untuk channel pembayaran text
     */
    public function getChannelPembayaranTextAttribute(): string
    {
        return match($this->channel_pembayaran) {
            self::CHANNEL_BCA => 'BCA',
            self::CHANNEL_BRI => 'BRI',
            self::CHANNEL_BNI => 'BNI',
            self::CHANNEL_MANDIRI => 'Mandiri',
            self::CHANNEL_GOPAY => 'GoPay',
            self::CHANNEL_OVO => 'OVO',
            self::CHANNEL_DANA => 'DANA',
            self::CHANNEL_SHOPEEPAY => 'ShopeePay',
            default => 'Lainnya'
        };
    }

    /**
     * Accessor untuk status pembayaran online
     */
    public function getStatusPembayaranTextAttribute(): string
    {
        return match($this->status_pembayaran) {
            self::PAYMENT_PAID => 'Berhasil',
            self::PAYMENT_FAILED => 'Gagal',
            self::PAYMENT_EXPIRED => 'Kadaluarsa',
            default => 'Menunggu Pembayaran'
        };
    }

    /**
     * Accessor untuk badge status pembayaran online
     */
    public function getStatusPembayaranBadgeAttribute(): string
    {
        return match($this->status_pembayaran) {
            self::PAYMENT_PAID => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
            self::PAYMENT_FAILED => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
            self::PAYMENT_EXPIRED => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200',
            default => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200'
        };
    }

    /**
     * Accessor untuk URL bukti bayar
     */
    public function getBuktiBayarUrlAttribute(): ?string
    {
        return $this->bukti_bayar ? asset('storage/bukti-bayar/' . $this->bukti_bayar) : null;
    }

    /**
     * Accessor untuk URL bukti upload (dari file pertama)
     */
    public function getBuktiUploadUrlAttribute(): ?string
    {
        return $this->bukti_upload ? asset('storage/bukti-upload/' . $this->bukti_upload) : null;
    }

    /**
     * Accessor untuk status verifikasi
     */
    public function getTerverifikasiAttribute(): bool
    {
        return !is_null($this->verified_at);
    }

    /**
     * Accessor untuk nama verifikator
     */
    public function getNamaVerifikatorAttribute(): ?string
    {
        return $this->verifikator ? $this->verifikator->name : null;
    }

    /**
     * Accessor untuk biaya admin formatted
     */
    public function getBiayaAdminFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->biaya_admin ?? 0, 0, ',', '.');
    }

    /**
     * Accessor untuk total bayar (tagihan + biaya admin)
     */
   public function getTotalBayarAttribute(): float
{
    return ($this->jumlah_bayar ?? 0) + ($this->biaya_admin ?? 0);
}

public function getTotalBayarFormattedAttribute(): string
{
    return 'Rp ' . number_format($this->total_bayar, 0, ',', '.');
}

    /**
     * Accessor untuk waktu_kadaluarsa dengan format
     */
    public function getWaktuKadaluarsaFormattedAttribute(): string
    {
        if (!$this->waktu_kadaluarsa) {
            return '-';
        }
        
        // Pastikan selalu return Carbon object
        $waktu = $this->waktu_kadaluarsa instanceof \Carbon\Carbon 
            ? $this->waktu_kadaluarsa 
            : \Carbon\Carbon::parse($this->waktu_kadaluarsa);
        
        return $waktu->timezone('Asia/Jakarta')->format('d M Y H:i') . ' WIB';
    }

    /**
     * Accessor untuk created_at dengan format
     */
    public function getCreatedAtFormattedAttribute(): string
    {
        $waktu = $this->created_at instanceof \Carbon\Carbon 
            ? $this->created_at 
            : \Carbon\Carbon::parse($this->created_at);
        
        return $waktu->timezone('Asia/Jakarta')->format('d M Y H:i') . ' WIB';
    }

    // ================ BOOLEAN CHECK ACCESSORS ================

    /**
     * Cek apakah pembayaran online
     */
    public function getIsOnlineAttribute(): bool
    {
        return in_array($this->metode_bayar, [self::METHOD_VA, self::METHOD_EWALLET, self::METHOD_QRIS]);
    }

    /**
     * Cek apakah pembayaran online sudah kadaluarsa
     */
    public function getIsExpiredAttribute(): bool
    {
        return $this->waktu_kadaluarsa && $this->waktu_kadaluarsa->isPast();
    }

    /**
     * Cek apakah pembayaran online berhasil
     */
    public function getIsPaidAttribute(): bool
    {
        return $this->status_pembayaran === self::PAYMENT_PAID;
    }

    /**
     * Cek apakah pembayaran online masih pending
     */
   public function getIsPendingAttribute(): bool
{
    return $this->status_pembayaran === self::PAYMENT_PENDING && 
           $this->status_verifikasi === self::STATUS_PENDING;
}
    /**
     * Cek apakah ada bukti upload (dari file pertama)
     */
    public function getHasBuktiUploadAttribute(): bool
    {
        return !is_null($this->bukti_upload);
    }

    /**
     * Cek apakah bisa diverifikasi
     */
    public function getCanVerifyAttribute(): bool
    {
        return $this->status_verifikasi === self::STATUS_PENDING;
    }

    /**
     * Cek apakah sudah diverifikasi
     */
    public function getIsVerifiedAttribute(): bool
    {
        return $this->status_verifikasi === self::STATUS_DITERIMA;
    }

    /**
     * Cek apakah ditolak
     */
    public function getIsRejectedAttribute(): bool
    {
        return $this->status_verifikasi === self::STATUS_DITOLAK;
    }

    // ================ METHODS ================

    /**
     * Method untuk verifikasi
     */
    public function verify($userId, $catatan = null): bool
    {
        return $this->update([
            'status_verifikasi' => self::STATUS_DITERIMA,
            'verified_by' => $userId,
            'verified_at' => now(),
            'catatan' => $catatan
        ]);
    }

    /**
     * Method untuk tolak
     */
    public function reject($userId, $catatan = null): bool
    {
        return $this->update([
            'status_verifikasi' => self::STATUS_DITOLAK,
            'verified_by' => $userId,
            'verified_at' => now(),
            'catatan' => $catatan
        ]);
    }

    /**
     * Method untuk menandai pembayaran online berhasil
     */
    public function markAsPaid(): bool
    {
        return $this->update([
            'status_pembayaran' => self::PAYMENT_PAID,
            'status_verifikasi' => self::STATUS_DITERIMA,
            'verified_at' => now()
        ]);
    }

    /**
     * Method untuk menandai pembayaran online gagal
     */
    public function markAsFailed(): bool
    {
        return $this->update([
            'status_pembayaran' => self::PAYMENT_FAILED
        ]);
    }

    /**
     * Method untuk menandai pembayaran online kadaluarsa
     */
    public function markAsExpired(): bool
    {
        return $this->update([
            'status_pembayaran' => self::PAYMENT_EXPIRED
        ]);
    }
}