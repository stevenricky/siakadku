<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\AdminDashboard;
use App\Livewire\Admin\DataGuru;
use App\Livewire\Admin\DataSiswa;
use App\Livewire\Admin\ManajemenKelas;
use App\Livewire\Admin\ManajemenMapel;
use App\Livewire\Admin\ManajemenJadwal;
use App\Livewire\Admin\ManajemenNilai;
use App\Livewire\Admin\LaporanAkademik;
use App\Livewire\Admin\TahunAjaranPage;
use App\Livewire\Admin\SemesterComponent;
use App\Livewire\Admin\Ruangan;
use App\Livewire\Admin\Ekstrakurikuler;
use App\Livewire\Admin\Pengumuman;
use App\Livewire\Admin\ManajemenPengguna;
use App\Livewire\Admin\RolePermission;
use App\Livewire\Admin\PengaturanUmum;
use App\Livewire\Admin\BackupRestore;
use App\Livewire\Admin\LogAktivitas;
use App\Livewire\Admin\ApiManajement;
use App\Livewire\Admin\DapodikSync;
use App\Livewire\Admin\ManajemenPesan;

Route::middleware(['auth', 'admin'])->group(function () {
    // Dashboard
    Route::get('/dashboard', AdminDashboard::class)->name('dashboard');

    // Data Master
    Route::get('/guru', DataGuru::class)->name('guru.index');
    Route::get('/siswa', DataSiswa::class)->name('siswa.index');
    Route::get('/kelas', ManajemenKelas::class)->name('kelas.index');
    Route::get('/mapel', ManajemenMapel::class)->name('mapel.index');
    
    // Data Master Baru
    Route::get('/tahun-ajaran', TahunAjaranPage::class)->name('tahun-ajaran.index');
    Route::get('/semester', SemesterComponent::class)->name('semester.index');
    Route::get('/ruangan', Ruangan::class)->name('ruangan.index');
    Route::get('/ekstrakurikuler', Ekstrakurikuler::class)->name('ekstrakurikuler.index');

    // Akademik
    Route::get('/jadwal', ManajemenJadwal::class)->name('jadwal.index');
    Route::get('/nilai', ManajemenNilai::class)->name('nilai.index');

    // Komunikasi
    Route::get('/pengumuman', Pengumuman::class)->name('pengumuman.index');
    Route::get('/pesan', ManajemenPesan::class)->name('pesan.index');

    // Laporan
    Route::get('/laporan', LaporanAkademik::class)->name('laporan.index');

    // Manajemen Sistem
    Route::get('/pengguna', ManajemenPengguna::class)->name('pengguna.index');
    Route::get('/role', RolePermission::class)->name('role.index');
    Route::get('/pengaturan', PengaturanUmum::class)->name('pengaturan.index');
    Route::get('/backup', BackupRestore::class)->name('backup.index');
    Route::get('/log', LogAktivitas::class)->name('log.index');
Route::get('/maintenance', \App\Livewire\Admin\MaintenanceSetting::class)->name('maintenance.index');

    // Integrasi
    Route::get('/api', ApiManajement::class)->name('api.index');
    Route::get('/dapodik', DapodikSync::class)->name('dapodik.index');

    // ==================== ROUTE BARU UNTUK ADMIN ====================
    
    // Keuangan & SPP
   Route::get('/spp', \App\Livewire\Admin\ManajemenSpp::class)->name('spp.index');
    Route::get('/tagihan', \App\Livewire\Admin\TagihanSpp::class)->name('tagihan.index');
    Route::get('/pembayaran', \App\Livewire\Admin\PembayaranSppManagement   ::class)->name('pembayaran.index');
    Route::get('/biaya', \App\Livewire\Admin\KategoriBiaya::class)->name('biaya.index');
    Route::get('/laporan-keuangan', \App\Livewire\Admin\LaporanKeuangan::class)->name('laporan-keuangan.index');
    Route::get('/verifikasi-pembayaran', \App\Livewire\Admin\VerifikasiPembayaran::class)->name('verifikasi-pembayaran.index');
    // Perpustakaan
    Route::get('/buku', \App\Livewire\Admin\ManajemenBuku::class)->name('buku.index');
    Route::get('/peminjaman', \App\Livewire\Admin\PeminjamanBukuPage::class)->name('peminjaman.index');
    Route::get('/inventaris-perpustakaan', \App\Livewire\Admin\InventarisPerpustakaan::class)->name('inventaris-perpustakaan.index');
    Route::get('/laporan-peminjaman', \App\Livewire\Admin\LaporanPeminjaman::class)->name('laporan-peminjaman.index');
    Route::get('/kategori-buku', \App\Livewire\Admin\ManajemenKategoriBuku::class)->name('kategori-buku.index');


    // Inventaris Sekolah
    Route::get('/inventaris', \App\Livewire\Admin\ManajemenInventaris::class)->name('inventaris.index');
    Route::get('/pemeliharaan', \App\Livewire\Admin\PemeliharaanBarang::class)->name('pemeliharaan.index');
    Route::get('/laporan-inventaris', \App\Livewire\Admin\LaporanInventaris::class)->name('laporan-inventaris.index');
    
    // Ekstrakurikuler & Prestasi
    Route::get('/pendaftaran-ekskul', \App\Livewire\Admin\PendaftaranEkskulPage::class)->name('pendaftaran-ekskul.index');
    Route::get('/prestasi', \App\Livewire\Admin\PrestasiSiswa::class)->name('prestasi.index');
    Route::get('/kegiatan-ekskul', \App\Livewire\Admin\KegiatanEkskulPage::class)->name('kegiatan-ekskul.index');
    
    // Konseling & BK
    Route::get('/layanan-bk', \App\Livewire\Admin\LayananBk::class)->name('layanan-bk.index');
    Route::get('/konseling', \App\Livewire\Admin\JadwalKonseling::class)->name('konseling.index');
    Route::get('/catatan-bk', \App\Livewire\Admin\CatatanBkPage::class)->name('catatan-bk.index');
    
    // Alumni & Karir
    Route::get('/alumni', \App\Livewire\Admin\DataAlumni::class)->name('alumni.index');
    Route::get('/tracer-study', \App\Livewire\Admin\TracerStudyPage::class)->name('tracer-study.index');
    Route::get('/beasiswa', \App\Livewire\Admin\BeasiswaPage::class)->name('beasiswa.index');
    
    // Sistem Pendukung
    Route::get('/agenda', \App\Livewire\Admin\AgendaSekolah::class)->name('agenda.index');
    Route::get('/surat', \App\Livewire\Admin\ManajemenSurat::class)->name('surat.index');
    Route::get('/arsip', \App\Livewire\Admin\ArsipDokumen::class)->name('arsip.index');
});