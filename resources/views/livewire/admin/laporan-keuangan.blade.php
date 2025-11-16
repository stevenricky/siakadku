<div class="p-4 sm:p-6">
    <!-- Header dan Tombol -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">Laporan Keuangan</h1>
        <div class="flex flex-col sm:flex-row gap-2">
            <button wire:click="exportPdf" 
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition flex items-center justify-center">
                <i class="fas fa-download mr-2"></i>Export PDF
            </button>
            <button wire:click="exportExcel" 
                    class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition flex items-center justify-center">
                <i class="fas fa-file-excel mr-2"></i>Export Excel
            </button>
        </div>
    </div>

    <!-- Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400 truncate">Total Pemasukan</p>
                    <p class="text-lg sm:text-xl font-bold text-blue-600 dark:text-blue-400">
                        Rp {{ number_format($statistik['totalPemasukan'], 0, ',', '.') }}
                    </p>
                </div>
                <div class="flex-shrink-0 ml-2">
                    <i class="fas fa-money-bill-wave text-blue-600 dark:text-blue-400 text-sm sm:text-base"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400 truncate">Pembayaran Lunas</p>
                    <p class="text-lg sm:text-xl font-bold text-green-600 dark:text-green-400">
                        {{ $statistik['persentaseLunas'] }}%
                    </p>
                </div>
                <div class="flex-shrink-0 ml-2">
                    <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-sm sm:text-base"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400 truncate">Tunggakan</p>
                    <p class="text-lg sm:text-xl font-bold text-yellow-600 dark:text-yellow-400">
                        Rp {{ number_format($statistik['tunggakan'], 0, ',', '.') }}
                    </p>
                </div>
                <div class="flex-shrink-0 ml-2">
                    <i class="fas fa-clock text-yellow-600 dark:text-yellow-400 text-sm sm:text-base"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400 truncate">Belum Bayar</p>
                    <p class="text-lg sm:text-xl font-bold text-red-600 dark:text-red-400">
                        {{ $statistik['siswaBelumBayar'] }} Siswa
                    </p>
                </div>
                <div class="flex-shrink-0 ml-2">
                    <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400 text-sm sm:text-base"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex flex-col gap-4">
                <!-- Jenis Laporan -->
                <div class="flex flex-col sm:flex-row gap-4">
                    <div class="w-full sm:w-48">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jenis Laporan</label>
                        <select wire:model.live="jenisLaporan" 
                                class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            <option value="bulanan">Bulanan</option>
                            <option value="custom">Custom</option>
                        </select>
                    </div>

                    @if($jenisLaporan === 'bulanan')
                    <div class="w-full sm:w-32">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tahun</label>
                        <select wire:model.live="tahunAjaran" 
                                class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            @foreach($tahunOptions as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="w-full sm:w-32">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Bulan</label>
                        <select wire:model.live="bulan" 
                                class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            @foreach(range(1, 12) as $month)
                            <option value="{{ $month }}">{{ \Carbon\Carbon::create()->month($month)->translatedFormat('F') }}</option>
                            @endforeach
                        </select>
                    </div>
                    @else
                    <div class="w-full sm:w-48">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Mulai</label>
                        <input type="date" wire:model.live="tanggalMulai" 
                               class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    </div>
                    <div class="w-full sm:w-48">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Selesai</label>
                        <input type="date" wire:model.live="tanggalSelesai" 
                               class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    </div>
                    @endif

                    <div class="w-full sm:w-48">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kelas</label>
                        <select wire:model.live="kelasId" 
                                class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            <option value="">Semua Kelas</option>
                            @foreach($kelasOptions as $id => $nama)
                            <option value="{{ $id }}">{{ $nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Search -->
                <div class="flex-1">
                    <input type="text" 
                           wire:model.live="search"
                           placeholder="Cari nama siswa, NIS, atau NISN..." 
                           class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
            </div>
        </div>
    </div>

    <!-- Charts dan Rekap -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Chart Pembayaran -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Statistik Pembayaran Tahun {{ $tahunAjaran }}</h3>
            <div class="h-64">
                <canvas id="paymentChart"
                        wire:ignore
                        x-data="{
                            init() {
                                const ctx = this.$el.getContext('2d');
                                const chartData = {
                                    labels: @js($chartData['labels']),
                                    datasets: [{
                                        label: 'Total Pembayaran (Rp)',
                                        data: @js($chartData['values']),
                                        backgroundColor: 'rgba(59, 130, 246, 0.6)',
                                        borderColor: 'rgba(59, 130, 246, 1)',
                                        borderWidth: 2,
                                        fill: true,
                                        tension: 0.4
                                    }]
                                };

                                new Chart(ctx, {
                                    type: 'line',
                                    data: chartData,
                                    options: {
                                        responsive: true,
                                        maintainAspectRatio: false,
                                        plugins: {
                                            legend: {
                                                position: 'top',
                                            },
                                            tooltip: {
                                                callbacks: {
                                                    label: function(context) {
                                                        return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                                    }
                                                }
                                            }
                                        },
                                        scales: {
                                            y: {
                                                beginAtZero: true,
                                                ticks: {
                                                    callback: function(value) {
                                                        return 'Rp ' + value.toLocaleString('id-ID');
                                                    }
                                                }
                                            }
                                        }
                                    }
                                });
                            }
                        }">
                </canvas>
            </div>
            <div class="mt-4 text-center text-sm text-gray-500 dark:text-gray-400">
                Total: Rp {{ number_format(array_sum($chartData['values']), 0, ',', '.') }}
            </div>
        </div>

        <!-- Rekap per Kelas -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Rekap per Kelas - {{ \Carbon\Carbon::create()->month($bulan)->translatedFormat('F') }}</h3>
            <div class="space-y-3 max-h-64 overflow-y-auto">
                @forelse($rekapKelas as $rekap)
                <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <div>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $rekap['nama_kelas'] }}</span>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $rekap['jumlah_siswa'] }} siswa</p>
                    </div>
                    <span class="text-sm font-bold text-green-600 dark:text-green-400">
                        Rp {{ number_format($rekap['total'], 0, ',', '.') }}
                    </span>
                </div>
                @empty
                <div class="text-center py-4">
                    <i class="fas fa-receipt text-gray-400 text-2xl mb-2"></i>
                    <p class="text-gray-500 dark:text-gray-400">Tidak ada data rekap</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Tabel Detail Pembayaran -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Detail Pembayaran</h3>
            <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-600 dark:text-gray-400">Show:</span>
                <select wire:model.live="perPage" class="text-sm border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Siswa
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Kelas
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Tanggal Bayar
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Jumlah
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Metode
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Status
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                    @forelse($pembayaranList as $pembayaran)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-4 py-4">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $pembayaran->siswa->nama ?? 'N/A' }}
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                NIS: {{ $pembayaran->siswa->nis ?? 'N/A' }}
                            </div>
                        </td>
                        <td class="px-4 py-4 text-sm text-gray-900 dark:text-white">
                            @if($pembayaran->siswa->kelas)
                                {{ $pembayaran->siswa->kelas->nama_lengkap ?? ($pembayaran->siswa->kelas->nama_kelas . ' - ' . $pembayaran->siswa->kelas->jurusan) }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td class="px-4 py-4 text-sm text-gray-900 dark:text-white">
                            {{ $pembayaran->tanggal_bayar?->translatedFormat('d M Y') }}
                        </td>
                        <td class="px-4 py-4 text-sm font-medium text-gray-900 dark:text-white">
                            Rp {{ number_format($pembayaran->jumlah_bayar, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-4 text-sm text-gray-900 dark:text-white capitalize">
                            {{ $pembayaran->metode_bayar }}
                        </td>
                        <td class="px-4 py-4">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                @if($pembayaran->status_verifikasi === 'diterima') bg-green-100 text-green-800
                                @elseif($pembayaran->status_verifikasi === 'ditolak') bg-red-100 text-red-800
                                @else bg-yellow-100 text-yellow-800 @endif">
                                {{ $pembayaran->status_text }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-receipt text-2xl sm:text-3xl mb-2"></i>
                                <p class="text-sm sm:text-base">Tidak ada data pembayaran</p>
                                <p class="text-xs sm:text-sm mt-1">Data akan muncul ketika ada transaksi pembayaran</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($pembayaranList->hasPages())
        <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
            {{ $pembayaranList->links() }}
        </div>
        @endif
    </div>

    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
             class="fixed top-4 right-4 bg-green-500 text-white px-4 py-3 rounded-lg shadow-lg z-50 max-w-sm">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <span class="text-sm">{{ session('message') }}</span>
            </div>
        </div>
    @endif
</div>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>