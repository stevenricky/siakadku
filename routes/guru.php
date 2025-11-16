<?php
use Illuminate\Support\Facades\Route;

use App\Livewire\Guru\GuruDashboard;
use App\Livewire\Guru\KelasDiampu;
use App\Livewire\Guru\InputNilai;
use App\Livewire\Guru\AbsensiSiswa;
use App\Livewire\Guru\JadwalMengajar;
use App\Livewire\Guru\LaporanNilai;
use App\Livewire\Guru\RppModul;
use App\Livewire\Guru\MateriPembelajaran;
use App\Livewire\Guru\TugasKuis;
use App\Livewire\Guru\PengumumanKelas;
use App\Livewire\Guru\PesanChat;
use App\Livewire\Guru\MonitoringKehadiran;
use App\Livewire\Guru\MonitoringPerforma;

Route::middleware(['auth', 'guru'])->group(function () {
    // Dashboard
    Route::get('/dashboard', GuruDashboard::class)->name('dashboard');

    // Pengajaran
    Route::get('/kelas', KelasDiampu::class)->name('kelas.index');
    Route::get('/nilai', InputNilai::class)->name('nilai.index');
    Route::get('/absensi', AbsensiSiswa::class)->name('absensi.index');
    Route::get('/jadwal', JadwalMengajar::class)->name('jadwal.index');

    // Perencanaan
    Route::get('/rpp', RppModul::class)->name('rpp.index');
    Route::get('/materi', MateriPembelajaran::class)->name('materi.index');
    Route::get('/tugas', TugasKuis::class)->name('tugas.index');

    // Komunikasi
    Route::get('/pengumuman', PengumumanKelas::class)->name('pengumuman.index');
    Route::get('/pesan', PesanChat::class)->name('pesan.index');

    // Monitoring
    Route::get('/kehadiran', MonitoringKehadiran::class)->name('kehadiran.index');
    Route::get('/performa', MonitoringPerforma::class)->name('performa.index');

    // Laporan
    Route::get('/laporan', LaporanNilai::class)->name('laporan.index');

    // ==================== ROUTE BARU UNTUK GURU ====================

    // Pembayaran SPP
    Route::get('/tagihan', \App\Livewire\Guru\TagihanSiswa::class)->name('tagihan.index');
    Route::get('/pembayaran', \App\Livewire\Guru\KonfirmasiPembayaran::class)->name('pembayaran.index');
    
    // Perpustakaan
    Route::get('/peminjaman-buku', \App\Livewire\Guru\PeminjamanBukuPage::class)->name('peminjaman-buku.index');
    
    // Inventaris
    Route::get('/peminjaman-inventaris', \App\Livewire\Guru\PeminjamanInventarisPage::class)->name('peminjaman-inventaris.index');
    
    // Konseling
    Route::get('/konseling', \App\Livewire\Guru\KonselingSiswa::class)->name('konseling.index');
    
    // Sistem Pendukung
    Route::get('/agenda', \App\Livewire\Guru\AgendaKelas::class)->name('agenda.index');
    Route::get('/surat', \App\Livewire\Guru\BuatSurat::class)->name('surat.index');
});