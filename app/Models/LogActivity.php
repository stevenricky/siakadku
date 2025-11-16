<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class LogActivity extends Model
{
    use HasFactory;

    protected $table = 'log_activities';

    protected $fillable = [
        'user_id',
        'action',
        'description',
        'ip_address',
        'user_agent',
        'method',
        'url',
        'referrer',
        'extra_data',
        'subject_type',
        'subject_id'
    ];

    protected $casts = [
        'extra_data' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Relasi ke user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi polymorphic ke model apapun
     */
    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope untuk filter berdasarkan user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope untuk filter berdasarkan aksi
     */
    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope untuk filter berdasarkan tanggal
     */
    public function scopeByDate($query, $date)
    {
        return $query->whereDate('created_at', $date);
    }

    /**
     * Scope untuk aktivitas terbaru
     */
    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Method untuk membuat log activity
     */
    public static function createLog($userId, $action, $description, $ipAddress = null, $extraData = [], $subject = null)
    {
        return self::create([
            'user_id' => $userId,
            'action' => $action,
            'description' => $description,
            'ip_address' => $ipAddress ?? request()->ip(),
            'user_agent' => request()->userAgent(),
            'method' => request()->method(),
            'url' => request()->fullUrl(),
            'referrer' => request()->header('referer'),
            'extra_data' => $extraData,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id' => $subject ? $subject->id : null,
        ]);
    }

    /**
     * Helper method untuk log login
     */
    public static function logLogin($user, $ipAddress = null)
    {
        return self::createLog(
            $user->id,
            'login',
            "User {$user->name} berhasil login",
            $ipAddress
        );
    }

    /**
     * Helper method untuk log logout
     */
    public static function logLogout($user, $ipAddress = null)
    {
        return self::createLog(
            $user->id,
            'logout',
            "User {$user->name} logout dari sistem",
            $ipAddress
        );
    }

    /**
     * Helper method untuk log create
     */
    public static function logCreate($user, $subject, $description = null)
    {
        $description = $description ?? "Membuat data " . class_basename($subject);
        
        return self::createLog(
            $user->id,
            'create',
            $description,
            null,
            ['new_data' => $subject->toArray()],
            $subject
        );
    }

    /**
     * Helper method untuk log update
     */
    public static function logUpdate($user, $subject, $oldData = [], $description = null)
    {
        $description = $description ?? "Memperbarui data " . class_basename($subject);
        
        return self::createLog(
            $user->id,
            'update',
            $description,
            null,
            [
                'old_data' => $oldData,
                'new_data' => $subject->toArray()
            ],
            $subject
        );
    }

    /**
     * Helper method untuk log delete
     */
    public static function logDelete($user, $subject, $description = null)
    {
        $description = $description ?? "Menghapus data " . class_basename($subject);
        
        return self::createLog(
            $user->id,
            'delete',
            $description,
            null,
            ['deleted_data' => $subject->toArray()],
            $subject
        );
    }

    /**
     * Accessor untuk waktu yang diformat
     */
    public function getTimeAgoAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Accessor untuk display action dengan icon
     */
    public function getActionIconAttribute(): string
    {
        return match($this->action) {
            'login' => '🔐',
            'logout' => '🚪',
            'create' => '➕',
            'update' => '✏️',
            'delete' => '🗑️',
            'export' => '📤',
            'import' => '📥',
            'backup' => '💾',
            'restore' => '🔄',
            default => '📝'
        };
    }
}