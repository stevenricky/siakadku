<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Riwayat Konseling</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Catatan lengkap sesi konseling yang telah diselesaikan</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a 
                    href="{{ route('siswa.jadwal-konseling.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm"
                >
                    <i class="fas fa-calendar-day mr-2"></i>Lihat Jadwal
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards - Fokus pada hasil -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Selesai</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalSelesai }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                        <i class="fas fa-clock text-blue-600 dark:text-blue-400 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Rata-rata Durasi</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $rataRataDurasi }} menit</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center">
                        <i class="fas fa-calendar-check text-purple-600 dark:text-purple-400 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Bulan Ini</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $konselingBulanIni }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900 rounded-lg flex items-center justify-center">
                        <i class="fas fa-chart-bar text-orange-600 dark:text-orange-400 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Layanan Terbanyak</p>
                    <p class="text-lg font-bold text-gray-900 dark:text-white truncate">
                        {{ $layananTerbanyak->nama_layanan ?? 'Tidak ada data' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters - Sederhana dengan Filter Tahun Real-time -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <!-- Search - Fokus pada hasil -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cari Hasil Konseling</label>
                <div class="relative">
                    <input 
                        type="text" 
                        wire:model.live="search"
                        placeholder="Cari permasalahan, tindakan, hasil konseling..." 
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                    >
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                </div>
            </div>

            <!-- Tahun Filter - Real-time -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tahun</label>
                <select 
                    wire:model.live="filterTahun"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                >
                    <option value="">Semua Tahun</option>
                    @foreach($tahunOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Layanan Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jenis Layanan</label>
                <select 
                    wire:model.live="filterLayanan"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                >
                    <option value="">Semua Layanan</option>
                    @foreach($layananOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Bulan Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Bulan</label>
                <select 
                    wire:model.live="filterBulan"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                >
                    <option value="">Semua Bulan</option>
                    @foreach($bulanOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Content - Fokus pada hasil konseling -->
    @if($konseling->count() > 0)
        <div class="space-y-4 mb-6">
            @foreach($konseling as $item)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 md:p-6 hover:shadow-md transition-shadow">
                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between">
                        <!-- Informasi Utama -->
                        <div class="flex-1">
                            <div class="flex flex-col md:flex-row md:items-start md:justify-between mb-3">
                                <div class="mb-2 md:mb-0">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">
                                        Sesi Konseling - {{ $item->layananBk->nama_layanan ?? 'Layanan BK' }}
                                    </h3>
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-4 text-sm text-gray-500 dark:text-gray-400">
                                        <span class="flex items-center mb-1 sm:mb-0">
                                            <i class="fas fa-calendar mr-1"></i>
                                            {{ $item->tanggal_konseling->format('d F Y') }}
                                        </span>
                                        <span class="flex items-center mb-1 sm:mb-0">
                                            <i class="fas fa-clock mr-1"></i>
                                            {{ \Carbon\Carbon::parse($item->waktu_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($item->waktu_selesai)->format('H:i') }}
                                        </span>
                                        <span class="flex items-center">
                                            <i class="fas fa-user-tie mr-1"></i>
                                            {{ $item->guruBk->nama_lengkap ?? '-' }}
                                        </span>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 self-start md:self-auto">
                                    <i class="fas fa-check mr-1"></i>Selesai
                                </span>
                            </div>

                            <!-- Permasalahan -->
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Permasalahan</h4>
                                <p class="text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                                    {{ $item->permasalahan }}
                                </p>
                            </div>

                            <!-- Tindakan dan Hasil -->
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                @if($item->tindakan)
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tindakan</h4>
                                        <p class="text-gray-900 dark:text-white bg-blue-50 dark:bg-blue-900/20 p-3 rounded-lg border border-blue-200 dark:border-blue-800">
                                            {{ $item->tindakan }}
                                        </p>
                                    </div>
                                @endif

                                @if($item->hasil)
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Hasil Konseling</h4>
                                        <p class="text-gray-900 dark:text-white bg-green-50 dark:bg-green-900/20 p-3 rounded-lg border border-green-200 dark:border-green-800">
                                            {{ $item->hasil }}
                                        </p>
                                    </div>
                                @endif
                            </div>

                            <!-- Catatan -->
                            @if($item->catatan)
                                <div class="mt-4">
                                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Catatan Tambahan</h4>
                                    <p class="text-gray-900 dark:text-white bg-yellow-50 dark:bg-yellow-900/20 p-3 rounded-lg border border-yellow-200 dark:border-yellow-800">
                                        {{ $item->catatan }}
                                    </p>
                                </div>
                            @endif
                        </div>

                        <!-- Action Button -->
                        <div class="lg:ml-6 lg:mt-0 mt-4">
                            <button 
                                wire:click="showDetail({{ $item->id }})"
                                class="w-full lg:w-auto inline-flex items-center justify-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors"
                            >
                                <i class="fas fa-expand mr-2"></i>Detail Lengkap
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm text-gray-700 dark:text-gray-300 mb-2 sm:mb-0">
                Menampilkan {{ $konseling->firstItem() }} hingga {{ $konseling->lastItem() }} dari {{ $konseling->total() }} riwayat
            </p>
            <div>
                {{ $konseling->links() }}
            </div>
        </div>
    @else
        <!-- Empty State -->
        <div class="text-center py-12">
            <div class="mx-auto w-24 h-24 mb-4 text-gray-400">
                <i class="fas fa-clipboard-check text-6xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Belum ada riwayat konseling</h3>
            <p class="text-gray-500 dark:text-gray-400 mb-4">Riwayat konseling akan muncul di sini setelah sesi konseling selesai.</p>
            <div class="flex flex-col sm:flex-row justify-center space-y-2 sm:space-y-0 sm:space-x-2">
                <a 
                    href="{{ route('siswa.jadwal-konseling.index') }}"
                    class="inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors"
                >
                    <i class="fas fa-calendar-plus mr-2"></i>Jadwalkan Konseling
                </a>
                @if($search || $filterLayanan || $filterBulan || $filterTahun)
                    <button 
                        wire:click="$set(['search' => '', 'filterLayanan' => '', 'filterBulan' => '', 'filterTahun' => ''])"
                        class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors"
                    >
                        Tampilkan Semua
                    </button>
                @endif
            </div>
        </div>
    @endif

    <!-- Detail Modal - Responsif -->
    @if($showDetailModal && $selectedKonseling)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeDetail"></div>

                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">
                                    Detail Riwayat Konseling
                                </h3>
                                
                                <div class="space-y-4">
                                    <!-- Informasi Dasar -->
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Tanggal</p>
                                            <p class="font-medium text-gray-900 dark:text-white">
                                                {{ $selectedKonseling->tanggal_konseling->format('d F Y') }}
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Waktu</p>
                                            <p class="font-medium text-gray-900 dark:text-white">
                                                {{ \Carbon\Carbon::parse($selectedKonseling->waktu_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($selectedKonseling->waktu_selesai)->format('H:i') }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Layanan</p>
                                            <p class="font-medium text-gray-900 dark:text-white">
                                                {{ $selectedKonseling->layananBk->nama_layanan ?? '-' }}
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Guru BK</p>
                                            <p class="font-medium text-gray-900 dark:text-white">
                                                {{ $selectedKonseling->guruBk->nama_lengkap ?? '-' }}
                                            </p>
                                        </div>
                                    </div>

                                    <div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Tempat</p>
                                        <p class="font-medium text-gray-900 dark:text-white">{{ $selectedKonseling->tempat }}</p>
                                    </div>

                                    <div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Status</p>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                            Selesai
                                        </span>
                                    </div>

                                    <!-- Permasalahan -->
                                    <div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Permasalahan</p>
                                        <p class="font-medium text-gray-900 dark:text-white mt-1 bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                                            {{ $selectedKonseling->permasalahan }}
                                        </p>
                                    </div>

                                    <!-- Tindakan (jika ada) -->
                                    @if($selectedKonseling->tindakan)
                                        <div>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Tindakan yang Dilakukan</p>
                                            <p class="font-medium text-gray-900 dark:text-white mt-1 bg-blue-50 dark:bg-blue-900/20 p-3 rounded-lg border border-blue-200 dark:border-blue-800">
                                                {{ $selectedKonseling->tindakan }}
                                            </p>
                                        </div>
                                    @endif

                                    <!-- Hasil (jika selesai) -->
                                    @if($selectedKonseling->hasil)
                                        <div>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Hasil Konseling</p>
                                            <p class="font-medium text-gray-900 dark:text-white mt-1 bg-green-50 dark:bg-green-900/20 p-3 rounded-lg border border-green-200 dark:border-green-800">
                                                {{ $selectedKonseling->hasil }}
                                            </p>
                                        </div>
                                    @endif

                                    <!-- Catatan -->
                                    @if($selectedKonseling->catatan)
                                        <div>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Catatan Tambahan</p>
                                            <p class="font-medium text-gray-900 dark:text-white mt-1 bg-yellow-50 dark:bg-yellow-900/20 p-3 rounded-lg border border-yellow-200 dark:border-yellow-800">
                                                {{ $selectedKonseling->catatan }}
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button 
                            wire:click="closeDetail" 
                            type="button" 
                            class="w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>