<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'title', 
        'message',
        'data',
        'icon',
        'action_url',
        'action_text',
        'is_public',
        'target_roles',
        'read_by',
        'expires_at',
        'created_by'
    ];

    protected $casts = [
        'data' => 'array',
        'target_roles' => 'array',
        'read_by' => 'array',
        'expires_at' => 'datetime'
    ];

    // Relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where(function($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }

    public function scopeUnreadByUser($query, $userId)
    {
        return $query->where(function($q) use ($userId) {
            $q->whereNull('read_by')
              ->orWhereJsonDoesntContain('read_by', $userId);
        });
    }

    public function scopeForUserRole($query, $userRole)
    {
        return $query->where(function($q) use ($userRole) {
            $q->whereNull('target_roles')
              ->orWhereJsonContains('target_roles', $userRole);
        });
    }

    public function scopeRecent($query, $hours = 24)
    {
        return $query->where('created_at', '>=', now()->subHours($hours));
    }

    // Methods
    public function markAsRead($userId)
    {
        $readBy = $this->read_by ?? [];
        if (!in_array($userId, $readBy)) {
            $readBy[] = $userId;
            $this->update(['read_by' => $readBy]);
        }
    }

    public function isReadBy($userId)
    {
        $readBy = $this->read_by ?? [];
        return in_array($userId, $readBy);
    }

    public function getUnreadCount($userId, $userRole)
    {
        return self::active()
            ->forUserRole($userRole)
            ->unreadByUser($userId)
            ->count();
    }

    public static function getForUser($userId, $userRole, $limit = 10)
    {
        return self::with('creator')
            ->active()
            ->forUserRole($userRole)
            ->unreadByUser($userId)
            ->recent()
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    // Helper untuk mendapatkan icon
    public function getIconAttribute($value)
    {
        if ($value) return $value;

        return match($this->type) {
            'agenda' => 'fas fa-calendar',
            'pengumuman' => 'fas fa-bullhorn',
            'nilai' => 'fas fa-chart-bar',
            'tugas' => 'fas fa-tasks',
            'ekskul' => 'fas fa-futbol',
            'jadwal' => 'fas fa-clock',
            default => 'fas fa-bell'
        };
    }
}