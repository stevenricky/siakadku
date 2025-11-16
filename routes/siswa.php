<?php
use Illuminate\Support\Facades\Route;

use App\Livewire\Siswa\SiswaDashboard;
use App\Livewire\Siswa\ProfilSaya;
use App\Livewire\Siswa\JadwalPelajaran;
use App\Livewire\Siswa\NilaiRapor;
use App\Livewire\Siswa\DataAbsensi;
use App\Livewire\Siswa\TugasKuis;
use App\Livewire\Siswa\MateriPembelajaran;
use App\Livewire\Siswa\UjianOnline;
use App\Livewire\Siswa\RencanaBelajar;
use App\Livewire\Siswa\PengumumanSekolah;
use App\Livewire\Siswa\PesanGuru;
use App\Livewire\Siswa\ForumDiskusi;
use App\Livewire\Siswa\CetakRapor;
use App\Livewire\Siswa\RekapAbsensi;
use App\Livewire\Siswa\PeringkatKelas;

Route::middleware(['auth', 'siswa'])->group(function () {
    // Dashboard
    Route::get('/dashboard', SiswaDashboard::class)->name('dashboard');

    // Akademik
    Route::get('/profil', ProfilSaya::class)->name('profil');
    Route::get('/jadwal', JadwalPelajaran::class)->name('jadwal');
    Route::get('/nilai', NilaiRapor::class)->name('nilai');
    Route::get('/absensi', DataAbsensi::class)->name('absensi');
    Route::get('/tugas', TugasKuis::class)->name('tugas.index');
    Route::get('/materi', MateriPembelajaran::class)->name('materi.index');
    Route::get('/ujian', UjianOnline::class)->name('ujian.index');
    Route::get('/rencana', RencanaBelajar::class)->name('rencana.index');

    // Interaksi
    Route::get('/pengumuman', PengumumanSekolah::class)->name('pengumuman.index');
    Route::get('/pesan', PesanGuru::class)->name('pesan.index');
    Route::get('/forum', ForumDiskusi::class)->name('forum.index');

    // Laporan & Arsip
    Route::get('/rapor', CetakRapor::class)->name('rapor.index');
    Route::get('/rekap', RekapAbsensi::class)->name('rekap.index');
    Route::get('/peringkat', PeringkatKelas::class)->name('peringkat.index');

    // ==================== ROUTE BARU UNTUK SISWA ====================

    // Pembayaran SPP - PERBAIKI INI
    Route::get('/tagihan', \App\Livewire\Siswa\TagihanSppPage::class)->name('tagihan.index');
    Route::get('/pembayaran', \App\Livewire\Siswa\RiwayatPembayaran::class)->name('pembayaran.index');
    Route::get('/pembayaran-online', \App\Livewire\Siswa\PembayaranOnline::class)->name('pembayaran-online.index');
    
    // Perpustakaan - PERBAIKI INI
    Route::get('/katalog-buku', \App\Livewire\Siswa\KatalogBuku::class)->name('katalog-buku.index');
    Route::get('/peminjaman-buku', \App\Livewire\Siswa\PeminjamanBukuPage::class)->name('peminjaman-buku.index');
    Route::get('/peminjaman-buku/create/{buku_id}', \App\Livewire\Siswa\PeminjamanBukuPage::class)->name('peminjaman-buku.create');
    Route::get('/riwayat-peminjaman', \App\Livewire\Siswa\RiwayatPeminjaman::class)->name('riwayat-peminjaman.index');
    
    // Ekstrakurikuler - PERBAIKI INI
    Route::get('/daftar-ekskul', \App\Livewire\Siswa\DaftarEkskul::class)->name('daftar-ekskul.index');
    Route::get('/kegiatan-ekskul', \App\Livewire\Siswa\KegiatanEkskulPage::class)->name('kegiatan-ekskul.index');
    Route::get('/prestasi-saya', \App\Livewire\Siswa\PrestasiSaya::class)->name('prestasi-saya.index');
    
    // Konseling & BK - PERBAIKI INI
    Route::get('/layanan-bk', \App\Livewire\Siswa\LayananBkPage::class)->name('layanan-bk.index');
    Route::get('/jadwal-konseling', \App\Livewire\Siswa\JadwalKonseling::class)->name('jadwal-konseling.index');
    Route::get('/riwayat-konseling', \App\Livewire\Siswa\RiwayatKonseling::class)->name('riwayat-konseling.index');
    
    // Karir & Beasiswa - PERBAIKI INI
    Route::get('/lowongan-kerja', \App\Livewire\Siswa\LowonganKerjaPage::class)->name('lowongan-kerja.index');
    Route::get('/beasiswa', \App\Livewire\Siswa\BeasiswaPage::class)->name('beasiswa.index');
    Route::get('/informasi-karir', \App\Livewire\Siswa\InformasiKarir::class)->name('informasi-karir.index');
    
    // Agenda & Informasi - PERBAIKI INI
    Route::get('/agenda-sekolah', \App\Livewire\Siswa\AgendaSekolahPage::class)->name('agenda-sekolah.index');
});