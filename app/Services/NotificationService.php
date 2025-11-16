<?php

namespace App\Services;

use App\Models\User;
use App\Models\SystemNotification;
use App\Events\PublicNotificationEvent;
use App\Events\PrivateNotificationEvent;
use Illuminate\Support\Str;

class NotificationService
{
    /**
     * Send PUBLIC notification (semua role bisa lihat)
     */
    public static function sendPublicNotification($title, $message, $type = 'info', $data = [], $actionUrl = null)
    {
        $notification = SystemNotification::create([
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
            'icon' => self::getIcon($type),
            'action_url' => $actionUrl,
            'action_text' => 'Lihat Detail',
            'is_public' => true,
            'target_roles' => null,
            'expires_at' => now()->addDays(7),
            'created_by' => auth()->id() ?? 1,
        ]);

        // ✅ KOMENTARI INI SEMENTARA - MASALAH EVENT
        /*
        event(new PublicNotificationEvent(
            $notification->title,
            $notification->message,
            $notification->type,
            $notification->data,
            null,
            $actionUrl
        ));
        */

        self::createLaravelNotifications($title, $message, $type, $data, null);

        \Log::info("📢 PUBLIC Notification: {$title}");

        return $notification;
    }

    /**
     * Send PRIVATE notification (hanya role tertentu)
     */
    public static function sendPrivateNotification($title, $message, $roles, $type = 'info', $data = [], $actionUrl = null)
    {
        $notification = SystemNotification::create([
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
            'icon' => self::getIcon($type),
            'action_url' => $actionUrl,
            'action_text' => 'Lihat Detail',
            'is_public' => false,
            'target_roles' => $roles,
            'expires_at' => now()->addDays(7),
            'created_by' => auth()->id() ?? 1,
        ]);

        // ✅ KOMENTARI INI SEMENTARA - MASALAH EVENT
        /*
        foreach ($roles as $role) {
            event(new PrivateNotificationEvent(
                $notification->title,
                $notification->message,
                $notification->type,
                $notification->data,
                $role,
                $actionUrl
            ));
        }
        */

        self::createLaravelNotifications($title, $message, $type, $data, $roles);

        \Log::info("🔒 PRIVATE Notification: {$title}", ['roles' => $roles]);

        return $notification;
    }

    /**
     * Buat Laravel notifications dengan UUID
     */
    private static function createLaravelNotifications($title, $message, $type, $data = [], $roles = null)
    {
        $query = User::query();
        
        if ($roles) {
            $query->whereIn('role', $roles);
        }

        $users = $query->get();
        
        foreach ($users as $user) {
            try {
                $user->notifications()->create([
                    'id' => Str::uuid(), // ✅ FIX: Tambahkan UUID untuk primary key
                    'type' => 'system.' . $type,
                    'data' => json_encode([
                        'title' => $title,
                        'message' => $message,
                        'type' => $type,
                        'data' => $data,
                        'is_public' => is_null($roles),
                        'timestamp' => now()->toISOString()
                    ]),
                    'read_at' => null,
                ]);
                
                \Log::info("✅ Notification created for user: {$user->id}");
                
            } catch (\Exception $e) {
                \Log::error("❌ Failed to create notification for user {$user->id}: " . $e->getMessage());
                continue;
            }
        }

        return $users->count();
    }

    // ==================== NOTIFIKASI PUBLIC ====================

    /**
     * NOTIFIKASI AGENDA (PUBLIC - Semua role)
     */
    public static function agendaCreated($agenda)
    {
        return self::sendPublicNotification(
            '📅 Agenda Baru',
            "Agenda: {$agenda->judul} - {$agenda->tanggal->format('d/m/Y')}",
            'agenda',
            [
                'agenda_id' => $agenda->id,
                'judul' => $agenda->judul,
                'tanggal' => $agenda->tanggal,
                'route' => 'agenda'
            ],
            '/siswa/agenda-sekolah'
        );
    }

    /**
     * NOTIFIKASI PENGUMUMAN (PUBLIC - Semua role)
     */
    public static function pengumumanCreated($pengumuman)
    {
        return self::sendPublicNotification(
            '📢 Pengumuman Baru',
            $pengumuman->judul,
            'pengumuman',
            [
                'pengumuman_id' => $pengumuman->id,
                'judul' => $pengumuman->judul,
                'route' => 'pengumuman'
            ],
            '/siswa/pengumuman'
        );
    }

    // ==================== NOTIFIKASI PRIVATE ====================

    /**
     * NOTIFIKASI NILAI (PRIVATE - Hanya guru & siswa terkait)
     */
    public static function nilaiDitambahkan($nilai)
    {
        // Hanya guru pengampu dan siswa yang bersangkutan
        $targetRoles = ['guru', 'siswa'];
        
        return self::sendPrivateNotification(
            '📊 Nilai Baru',
            "Nilai {$nilai->mapel->nama_mapel} untuk {$nilai->siswa->nama}",
            $targetRoles,
            'nilai',
            [
                'nilai_id' => $nilai->id,
                'mapel_id' => $nilai->mapel_id,
                'siswa_id' => $nilai->siswa_id,
                'mapel' => $nilai->mapel->nama_mapel,
                'siswa' => $nilai->siswa->nama,
                'route' => 'nilai'
            ],
            '/siswa/nilai'
        );
    }

    /**
     * NOTIFIKASI TUGAS (PRIVATE - Hanya guru & siswa kelas)
     */
    public static function tugasDitambahkan($tugas)
    {
        return self::sendPrivateNotification(
            '📝 Tugas Baru',
            "Tugas: {$tugas->judul} - Deadline: {$tugas->deadline->format('d/m/Y')}",
            ['guru', 'siswa'],
            'tugas',
            [
                'tugas_id' => $tugas->id,
                'judul' => $tugas->judul,
                'deadline' => $tugas->deadline,
                'mapel' => $tugas->mapel->nama_mapel ?? '',
                'route' => 'tugas'
            ],
            '/siswa/tugas'
        );
    }

    /**
     * NOTIFIKASI EKSKUL (PRIVATE - Hanya admin & siswa)
     */
    public static function kegiatanEkskulCreated($kegiatan)
    {
        return self::sendPrivateNotification(
            '⚽ Kegiatan Ekskul',
            "Kegiatan: {$kegiatan->nama_kegiatan} - {$kegiatan->tanggal->format('d/m/Y')}",
            ['admin', 'siswa'],
            'ekskul',
            [
                'kegiatan_id' => $kegiatan->id,
                'nama_kegiatan' => $kegiatan->nama_kegiatan,
                'tanggal' => $kegiatan->tanggal,
                'route' => 'kegiatan-ekskul'
            ],
            '/siswa/kegiatan-ekskul'
        );
    }

    /**
     * NOTIFIKASI PEMBAYARAN (PRIVATE - Hanya admin & siswa terkait)
     */
    public static function pembayaranDibuat($pembayaran)
    {
        return self::sendPrivateNotification(
            '💳 Pembayaran Baru',
            "Pembayaran {$pembayaran->jenis} - {$pembayaran->siswa->nama}",
            ['admin', 'siswa'],
            'pembayaran',
            [
                'pembayaran_id' => $pembayaran->id,
                'jenis' => $pembayaran->jenis,
                'jumlah' => $pembayaran->jumlah,
                'siswa_id' => $pembayaran->siswa_id,
                'route' => 'pembayaran'
            ],
            '/admin/pembayaran'
        );
    }

    /**
     * NOTIFIKASI CHAT/INBOX (PRIVATE - Hanya pengirim & penerima)
     */
    public static function pesanDiterima($pesan)
    {
        // Ini sangat private - hanya untuk user tertentu
        // Tidak disimpan di SystemNotification, langsung ke Laravel notifications
        $penerima = User::find($pesan->penerima_id);
        
        if ($penerima) {
            $penerima->notifications()->create([
                'id' => Str::uuid(), // ✅ FIX: Tambahkan UUID
                'type' => 'chat.message',
                'data' => json_encode([
                    'title' => '💬 Pesan Baru',
                    'message' => "Pesan dari {$pesan->pengirim->name}",
                    'type' => 'chat',
                    'data' => [
                        'pesan_id' => $pesan->id,
                        'pengirim_id' => $pesan->pengirim_id,
                        'pengirim' => $pesan->pengirim->name,
                        'route' => 'chat'
                    ],
                    'is_public' => false,
                    'timestamp' => now()->toISOString()
                ]),
                'read_at' => null,
            ]);

            // Broadcast ke user tertentu
            // ✅ KOMENTARI INI SEMENTARA - MASALAH EVENT
            /*
            event(new PrivateNotificationEvent(
                '💬 Pesan Baru',
                "Pesan dari {$pesan->pengirim->name}",
                'chat',
                ['pesan_id' => $pesan->id, 'pengirim_id' => $pesan->pengirim_id],
                $penerima->id, // User spesifik
                '/siswa/pesan'
            ));
            */
        }

        return true;
    }

    // ==================== UTILITY METHODS ====================

    /**
     * Get icon berdasarkan type
     */
    private static function getIcon($type)
    {
        $icons = [
            'info' => 'fas fa-info-circle',
            'success' => 'fas fa-check-circle',
            'warning' => 'fas fa-exclamation-triangle',
            'danger' => 'fas fa-times-circle',
            'agenda' => 'fas fa-calendar',
            'pengumuman' => 'fas fa-bullhorn',
            'nilai' => 'fas fa-chart-bar',
            'tugas' => 'fas fa-tasks',
            'ekskul' => 'fas fa-futbol',
            'jadwal' => 'fas fa-clock',
            'pembayaran' => 'fas fa-credit-card',
            'chat' => 'fas fa-comment'
        ];

        return $icons[$type] ?? 'fas fa-bell';
    }

    /**
     * Get system notifications untuk user
     */
    public static function getUserSystemNotifications($userId, $userRole, $limit = 10)
    {
        return SystemNotification::getForUser($userId, $userRole, $limit);
    }

    /**
     * Get unread count dari system notifications
     */
    public static function getSystemUnreadCount($userId, $userRole)
    {
        return SystemNotification::getUnreadCount($userId, $userRole);
    }
}