<?php

namespace App\Livewire\Guru;

use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Nilai;
use App\Models\Siswa;
use App\Models\Pesan;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\On;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

#[Layout('layouts.app-new')]
class GuruDashboard extends Component
{
    public $totalKelasDiampu;
    public $totalSiswa;
    public $rataRataNilai = 0;
    public $jadwalHariIni;
    public $recentNilai;
    
    // Real-time properties
    public $pesanTerbaru = [];
    public $notifications = [];
    public $unreadCount = 0;
    public $onlineUsers = 0;
    
    // Chat properties
    public $showChat = false;
    public $chatWith = null;
    public $newMessage = '';
    public $percakapan = [];
    public $siswaList = [];
    public $kontakList = [];

    public function mount()
    {
        $this->loadData();
        $this->loadNotifications();
    }

    public function loadData()
    {
        $guru = auth()->user()->guru;
        
        if (!$guru) {
            $this->resetData();
            return;
        }

        $guruId = $guru->id;
        
        $this->totalKelasDiampu = Jadwal::where('guru_id', $guruId)
            ->distinct('kelas_id')
            ->count('kelas_id');
            
        $this->totalSiswa = Siswa::whereHas('kelas.jadwal', function($query) use ($guruId) {
            $query->where('guru_id', $guruId);
        })->count();

        // Hitung rata-rata nilai
        $nilaiData = Nilai::where('guru_id', $guruId)->get();
        if ($nilaiData->count() > 0) {
            $this->rataRataNilai = round($nilaiData->avg('nilai') ?? 0, 1);
        }

        $hariIni = now()->translatedFormat('l');
        $hariMapping = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa', 
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu'
        ];

        $this->jadwalHariIni = Jadwal::with(['kelas', 'mapel'])
            ->where('guru_id', $guruId)
            ->where('hari', $hariMapping[$hariIni] ?? 'Senin')
            ->orderBy('jam_mulai')
            ->get();

        $this->recentNilai = Nilai::with(['siswa', 'mapel'])
            ->where('guru_id', $guruId)
            ->latest()
            ->take(5)
            ->get();
    }

    /**
     * Load notifications from database
     */
    public function loadNotifications()
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                $this->notifications = [];
                $this->unreadCount = 0;
                return;
            }

            // Load pesan terbaru
            $this->pesanTerbaru = Pesan::with(['pengirim'])
                ->where('penerima_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            // Hitung pesan belum dibaca
            $this->unreadCount = Pesan::where('dibaca', false)
                ->where('penerima_id', $user->id)
                ->count();

            // Load notifikasi Laravel
            if (Schema::hasTable('notifications')) {
                $this->notifications = $user->notifications()
                    ->orderBy('created_at', 'desc')
                    ->take(10)
                    ->get()
                    ->map(function($notification) {
                        $data = json_decode($notification->data, true);
                        return [
                            'id' => $notification->id,
                            'title' => $data['title'] ?? 'Notifikasi',
                            'message' => $data['message'] ?? '',
                            'type' => $data['type'] ?? 'info',
                            'data' => $data['data'] ?? [],
                            'read_at' => $notification->read_at,
                            'created_at' => $notification->created_at->diffForHumans(),
                            'is_read' => !is_null($notification->read_at)
                        ];
                    });
                    
                $this->unreadCount += $user->unreadNotifications()->count();
            } else {
                $this->notifications = collect();
            }

            // Hitung user online
            $this->onlineUsers = User::where('last_seen', '>=', now()->subMinutes(5))->count();

            // Load daftar siswa untuk chat
            $guru = $user->guru;
            if ($guru) {
                $this->siswaList = Siswa::whereHas('kelas.jadwal', function($query) use ($guru) {
                    $query->where('guru_id', $guru->id);
                })
                ->with(['user', 'kelas'])
                ->get()
                ->map(function($siswa) {
                    return [
                        'id' => $siswa->user_id,
                        'nama' => $siswa->nama,
                        'kelas' => $siswa->kelas->nama_kelas ?? '-',
                        'online' => $siswa->user && $siswa->user->last_seen && $siswa->user->last_seen->gt(now()->subMinutes(5))
                    ];
                });

                // Load kontak terakhir
                $this->loadKontakTerakhir($user->id);
            }
            
        } catch (\Exception $e) {
            $this->notifications = collect();
            $this->unreadCount = 0;
            $this->onlineUsers = 0;
        }
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($notificationId)
    {
        try {
            $user = Auth::user();
            $notification = $user->notifications()->find($notificationId);
            
            if ($notification) {
                $notification->update(['read_at' => now()]);
                $this->loadNotifications();
            }
        } catch (\Exception $e) {
            // Skip error
        }
    }

    /**
     * Mark all as read
     */
    public function markAllAsRead()
    {
        try {
            $user = Auth::user();
            $user->notifications()->whereNull('read_at')->update(['read_at' => now()]);
            $this->loadNotifications();
            session()->flash('message', 'Semua notifikasi telah dibaca');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menandai notifikasi sebagai dibaca');
        }
    }

    /**
     * Auto-refresh notifications
     */
    #[On('refresh-notifications')]
    public function refreshNotifications()
    {
        $this->loadNotifications();
    }

    /**
     * Listeners for real-time updates
     */
    public function getListeners()
    {
        return [
            'refresh-notifications' => 'refreshNotifications',
        ];
    }

    private function loadKontakTerakhir($userId)
    {
        $pesanMasuk = Pesan::with(['pengirim', 'pengirim.siswa', 'pengirim.guru'])
            ->where('penerima_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        $pesanKeluar = Pesan::with(['penerima', 'penerima.siswa', 'penerima.guru'])
            ->where('pengirim_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        $kontakIds = [];
        $this->kontakList = collect();

        foreach ($pesanMasuk as $pesan) {
            if ($pesan->pengirim && !in_array($pesan->pengirim_id, $kontakIds)) {
                $kontakIds[] = $pesan->pengirim_id;
                $this->kontakList->push([
                    'user' => $pesan->pengirim,
                    'last_message' => $pesan,
                    'type' => 'incoming'
                ]);
            }
        }

        foreach ($pesanKeluar as $pesan) {
            if ($pesan->penerima && !in_array($pesan->penerima_id, $kontakIds)) {
                $kontakIds[] = $pesan->penerima_id;
                $this->kontakList->push([
                    'user' => $pesan->penerima,
                    'last_message' => $pesan,
                    'type' => 'outgoing'
                ]);
            }
        }

        $this->kontakList = $this->kontakList->sortByDesc(function($kontak) {
            return $kontak['last_message']->created_at;
        })->take(10);
    }

    private function resetData()
    {
        $this->totalKelasDiampu = 0;
        $this->totalSiswa = 0;
        $this->rataRataNilai = 0;
        $this->jadwalHariIni = collect();
        $this->recentNilai = collect();
        $this->pesanTerbaru = collect();
        $this->notifications = collect();
        $this->kontakList = collect();
    }

    public function isMobile()
    {
        return preg_match("/(android|webos|iphone|ipad|ipod|blackberry|windows phone)/i", 
                         request()->header('User-Agent') ?? '');
    }

    // Chat Methods
    public function bukaChat($userId = null)
    {
        $this->showChat = true;
        if ($userId) {
            $this->chatWith = User::with(['siswa', 'guru'])->find($userId);
            $this->loadPercakapan();
        }
    }

    public function tutupChat()
    {
        $this->showChat = false;
        $this->chatWith = null;
        $this->percakapan = [];
        $this->newMessage = '';
    }

    public function loadPercakapan()
    {
        if ($this->chatWith) {
            $userId = Auth::id();
            $otherUserId = $this->chatWith->id;

            $this->percakapan = Pesan::where(function($query) use ($userId, $otherUserId) {
                $query->where('pengirim_id', $userId)
                      ->where('penerima_id', $otherUserId);
            })->orWhere(function($query) use ($userId, $otherUserId) {
                $query->where('pengirim_id', $otherUserId)
                      ->where('penerima_id', $userId);
            })
            ->with(['pengirim', 'penerima'])
            ->orderBy('created_at', 'asc')
            ->get();

            // Tandai pesan sebagai dibaca
            Pesan::where('penerima_id', $userId)
                ->where('pengirim_id', $otherUserId)
                ->where('dibaca', false)
                ->update([
                    'dibaca' => true,
                    'dibaca_pada' => now()
                ]);
            
            $this->unreadCount = Pesan::where('dibaca', false)
                ->where('penerima_id', $userId)
                ->count();
        }
    }

    public function kirimPesan()
    {
        $this->validate([
            'newMessage' => 'required|string|min:1|max:1000'
        ]);

        if ($this->chatWith && trim($this->newMessage) !== '') {
            try {
                // Gunakan field yang sesuai dengan struktur tabel
                Pesan::create([
                    'pengirim_id' => Auth::id(),
                    'penerima_id' => $this->chatWith->id,
                    'subjek' => 'Pesan dari ' . Auth::user()->name,
                    'pesan' => trim($this->newMessage),
                    'dibaca' => false,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                $this->newMessage = '';
                $this->loadPercakapan();
                $this->loadNotifications();

                $this->dispatch('pesanTerkirim');

            } catch (\Exception $e) {
                session()->flash('error', 'Gagal mengirim pesan: ' . $e->getMessage());
            }
        }
    }

    // Real-time Event Listeners
    #[On('nilaiDitambahkan')]
    public function onNilaiDitambahkan()
    {
        $this->loadData();
        $this->loadNotifications();
    }

    #[On('jadwalDiupdate')]
    public function onJadwalDiupdate()
    {
        $this->loadData();
    }

    #[On('absensiDiupdate')]
    public function onAbsensiDiupdate()
    {
        $this->loadData();
    }

    #[On('dashboardRefresh')]
    public function refreshDashboard()
    {
        $this->loadData();
        $this->loadNotifications();
        session()->flash('message', 'Dashboard diperbarui! ' . now()->format('H:i:s'));
    }

    #[On('pesanDiterima')]
    public function onPesanDiterima()
    {
        $this->loadNotifications();
        if ($this->showChat && $this->chatWith) {
            $this->loadPercakapan();
        }
    }

    public function render()
    {
        return view('livewire.guru.guru-dashboard');
    }

    public function getSubtitleProperty()
    {
        return 'Selamat mengajar! ' . now()->format('H:i');
    }
}