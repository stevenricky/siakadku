<?php

namespace App\Livewire\Siswa;

use App\Models\Absensi;
use App\Models\Jadwal;
use App\Models\Nilai;
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
#[Title('Dashboard Siswa')]
class SiswaDashboard extends Component
{
    public $siswa;
    public $jadwalHariIni;
    public $rataRataNilai;
    public $statistikKehadiran;
    
    // Real-time properties
    public $pesanTerbaru = [];
    public $unreadCount = 0; // ✅ Hanya ini yang perlu
    public $onlineUsers = 0;
    
    // Chat properties
    public $showChat = false;
    public $chatWith = null;
    public $newMessage = '';
    public $percakapan = [];
    public $guruList = [];
    public $kontakList = [];

    // ❌ HAPUS properties ini karena sudah ada di NotificationPanel
    // public $notifications = [];
    
    public function mount()
    {
        $this->loadData();
        // ❌ HAPUS: $this->loadNotifications();
    }

    protected function loadData()
    {
        $user = Auth::user();
        
        if (!$user) {
            $this->setDefaultValues();
            return;
        }

        $this->siswa = $user->siswa;
        
        if (!$this->siswa) {
            $this->setDefaultValues();
            return;
        }

        $this->loadJadwalHariIni();
        $this->loadRataRataNilai();
        $this->loadStatistikKehadiran();
        $this->loadRealTimeData();
    }

    /**
     * ❌ HAPUS SEMUA METHOD NOTIFICATIONS INI
     * Karena sudah ditangani oleh NotificationPanel component terpisah
     */
    
    // Method untuk set default values
    protected function setDefaultValues()
    {
        $this->siswa = null;
        $this->jadwalHariIni = collect();
        $this->rataRataNilai = 0;
        $this->statistikKehadiran = [
            'hadir' => 0,
            'sakit' => 0,
            'izin' => 0,
            'alpha' => 0,
            'total' => 0
        ];
        $this->pesanTerbaru = collect();
        // ❌ HAPUS: $this->notifications = collect();
    }

    protected function loadJadwalHariIni()
    {
        if (!$this->siswa || !$this->siswa->kelas_id) {
            $this->jadwalHariIni = collect();
            return;
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

        $this->jadwalHariIni = Jadwal::with(['mapel', 'guru'])
            ->where('kelas_id', $this->siswa->kelas_id)
            ->where('hari', $hariMapping[$hariIni] ?? 'Senin')
            ->orderBy('jam_mulai')
            ->get();
    }

    protected function loadRataRataNilai()
    {
        if (!$this->siswa) {
            $this->rataRataNilai = 0;
            return;
        }

        $this->rataRataNilai = Nilai::where('siswa_id', $this->siswa->id)
            ->avg('nilai_akhir') ?? 0;
    }

    protected function loadStatistikKehadiran()
    {
        if (!$this->siswa) {
            $this->statistikKehadiran = [
                'hadir' => 0,
                'sakit' => 0,
                'izin' => 0,
                'alpha' => 0,
                'total' => 0
            ];
            return;
        }

        // Data real dari database
        $bulanIni = now()->startOfMonth();
        
        $hadir = Absensi::where('siswa_id', $this->siswa->id)
            ->where('status', 'hadir')
            ->where('tanggal', '>=', $bulanIni)
            ->count();

        $sakit = Absensi::where('siswa_id', $this->siswa->id)
            ->where('status', 'sakit')
            ->where('tanggal', '>=', $bulanIni)
            ->count();

        $izin = Absensi::where('siswa_id', $this->siswa->id)
            ->where('status', 'izin')
            ->where('tanggal', '>=', $bulanIni)
            ->count();

        $alpha = Absensi::where('siswa_id', $this->siswa->id)
            ->where('status', 'alpha')
            ->where('tanggal', '>=', $bulanIni)
            ->count();

        $total = $hadir + $sakit + $izin + $alpha;

        $this->statistikKehadiran = [
            'hadir' => $hadir,
            'sakit' => $sakit,
            'izin' => $izin,
            'alpha' => $alpha,
            'total' => $total
        ];
    }

    public function loadRealTimeData()
    {
        $userId = Auth::id();
        
        // Load pesan terbaru
        $this->pesanTerbaru = Pesan::with(['pengirim'])
            ->where('penerima_id', $userId)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Hitung pesan belum dibaca (untuk chat, bukan notifications)
        $this->unreadCount = Pesan::where('dibaca', false)
            ->where('penerima_id', $userId)
            ->count();

        // Hitung user online
        $this->onlineUsers = User::where('last_seen', '>=', now()->subMinutes(5))->count();

        // Load daftar guru untuk chat
        $this->loadGuruList();

        // Load kontak terakhir
        $this->loadKontakTerakhir($userId);
    }

    private function loadGuruList()
    {
        if (!$this->siswa || !$this->siswa->kelas_id) {
            $this->guruList = [];
            return;
        }

        $this->guruList = Jadwal::with(['guru.user'])
            ->where('kelas_id', $this->siswa->kelas_id)
            ->get()
            ->unique('guru_id')
            ->map(function($jadwal) {
                $guru = $jadwal->guru;
                return $guru ? [
                    'id' => $guru->user_id,
                    'nama' => $guru->nama_lengkap,
                    'mapel' => $jadwal->mapel->nama_mapel ?? 'Mata Pelajaran',
                    'online' => $guru->user && $guru->user->last_seen && $guru->user->last_seen->gt(now()->subMinutes(5))
                ] : null;
            })
            ->filter()
            ->values();
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

    // Chat Methods
    public function bukaChat($userId = null)
    {
        $this->showChat = true;
        if ($userId) {
            $this->chatWith = User::with(['guru', 'siswa'])->find($userId);
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
                'subjek' => 'Pesan dari ' . Auth::user()->name, // Field yang ada di tabel
                'pesan' => trim($this->newMessage), // Field yang ada di tabel
                'dibaca' => false,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $this->newMessage = '';
            $this->loadPercakapan();
            $this->loadRealTimeData();

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
        $this->loadRataRataNilai();
        $this->loadRealTimeData();
    }

    #[On('jadwalDiupdate')]
    public function onJadwalDiupdate()
    {
        $this->loadJadwalHariIni();
    }

    #[On('absensiDiupdate')]
    public function onAbsensiDiupdate()
    {
        $this->loadStatistikKehadiran();
    }

    #[On('dashboardRefresh')]
    public function refreshDashboard()
    {
        $this->loadJadwalHariIni();
        $this->loadRataRataNilai();
        $this->loadStatistikKehadiran();
        $this->loadRealTimeData();
        session()->flash('message', 'Dashboard diperbarui! ' . now()->format('H:i:s'));
    }

    #[On('pesanDiterima')]
    public function onPesanDiterima()
    {
        $this->loadRealTimeData();
        if ($this->showChat && $this->chatWith) {
            $this->loadPercakapan();
        }
    }

    // Method untuk refresh otomatis
    public function refreshStats()
    {
        $this->loadData();
    }

    public function isMobile()
    {
        return preg_match("/(android|webos|iphone|ipad|ipod|blackberry|windows phone)/i", 
                        request()->header('User-Agent') ?? '');
    }

    public function render()
    {
        return view('livewire.siswa.siswa-dashboard');
    }

    public function getSubtitleProperty()
    {
        return 'Selamat belajar! ' . now()->format('H:i');
    }
}