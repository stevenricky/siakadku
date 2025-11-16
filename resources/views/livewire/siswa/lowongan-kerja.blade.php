<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Lowongan Kerja</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Temukan peluang karir yang sesuai dengan minat Anda</p>
            </div>
            <div class="mt-4 md:mt-0">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                    <i class="fas fa-briefcase mr-1"></i>
                    {{ $totalAktif }} Lowongan Aktif
                </span>
            </div>
        </div>
    </div>

    <!-- Filters Sederhana -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Search -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cari Lowongan</label>
                <div class="relative">
                    <input 
                        type="text" 
                        wire:model.live="search"
                        placeholder="Cari perusahaan, posisi, lokasi..." 
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                    >
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                </div>
            </div>

            <!-- Lokasi Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Lokasi</label>
                <select 
                    wire:model.live="filterLokasi"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                >
                    <option value="">Semua Lokasi</option>
                    @foreach($lokasiOptions as $lokasi)
                        <option value="{{ $lokasi }}">{{ $lokasi }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Items Per Page -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Items per Page</label>
                <select 
                    wire:model.live="perPage"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                >
                    <option value="6">6 Items</option>
                    <option value="9">9 Items</option>
                    <option value="12">12 Items</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Content -->
    @if($lowongan->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
            @foreach($lowongan as $item)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-md transition-shadow">
                    <div class="p-4">
                        <!-- Header -->
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center">
                                @if($item->logo_perusahaan)
                                    <img src="{{ asset('storage/' . $item->logo_perusahaan) }}" 
                                         alt="{{ $item->nama_perusahaan }}"
                                         class="w-10 h-10 rounded-lg object-cover mr-3">
                                @else
                                    <div class="w-10 h-10 bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-building text-gray-400"></i>
                                    </div>
                                @endif
                                <div>
                                    <h3 class="font-semibold text-gray-900 dark:text-white">{{ $item->posisi }}</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $item->nama_perusahaan }}</p>
                                </div>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                Buka
                            </span>
                        </div>
                        
                       <!-- Tambahkan di bagian info, setelah lokasi -->
<div class="space-y-2 mb-3">
    <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
        <i class="fas fa-map-marker-alt mr-2 w-4"></i>
        {{ $item->lokasi }}
    </div>
    <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
        <i class="fas fa-money-bill-wave mr-2 w-4"></i>
        {{ $item->gaji_formatted }}
    </div>
    <!-- TAMBAHKAN LINK WEBSITE JIKA ADA -->
    @if($item->website)
    <div class="flex items-center text-sm text-blue-600 dark:text-blue-400">
        <i class="fas fa-globe mr-2 w-4"></i>
        <a href="{{ $item->website }}" target="_blank" class="hover:underline">
            Kunjungi Website
        </a>
    </div>
    @endif
    <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
        <i class="fas fa-calendar-alt mr-2 w-4"></i>
        Tutup: {{ $item->tanggal_tutup->format('d M Y') }}
    </div>
</div>

                        <!-- Deskripsi Singkat -->
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 line-clamp-2">
                            {{ Str::limit($item->deskripsi_pekerjaan, 100) }}
                        </p>

                        <!-- Ganti bagian tampilan hari tersisa -->
<div class="flex items-center justify-between">
    @if($item->hari_tersisa > 0)
        <span class="text-xs text-gray-500 dark:text-gray-400">
            {{ (int)$item->hari_tersisa }} hari lagi
        </span>
    @else
        <span class="text-xs text-red-500 dark:text-red-400">
            Segera tutup
        </span>
    @endif
    <button 
        wire:click="showDetail({{ $item->id }})"
        class="inline-flex items-center px-3 py-1.5 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors"
    >
        <i class="fas fa-eye mr-1"></i> Detail
    </button>
</div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="flex items-center justify-between">
            <p class="text-sm text-gray-700 dark:text-gray-300">
                Menampilkan {{ $lowongan->firstItem() }} hingga {{ $lowongan->lastItem() }} dari {{ $lowongan->total() }} lowongan
            </p>
            <div>
                {{ $lowongan->links() }}
            </div>
        </div>
    @else
        <!-- Empty State -->
        <div class="text-center py-12">
            <div class="mx-auto w-24 h-24 mb-4 text-gray-400">
                <i class="fas fa-briefcase text-6xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Tidak ada lowongan aktif</h3>
            <p class="text-gray-500 dark:text-gray-400 mb-4">Silakan coba lagi nanti atau ubah filter pencarian Anda.</p>
            @if($search || $filterLokasi)
                <button 
                    wire:click="$set(['search' => '', 'filterLokasi' => ''])"
                    class="btn btn-primary"
                >
                    Tampilkan Semua Lowongan
                </button>
            @endif
        </div>
    @endif

    <!-- Detail Modal -->
    @if($showDetailModal && $selectedLowongan)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeDetail"></div>

                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">
                                    Detail Lowongan Kerja
                                </h3>
                                
                                <div class="space-y-4">
                                    <!-- Header -->
                                    <div class="flex items-start justify-between">
                                        <div class="flex items-center">
                                            @if($selectedLowongan->logo_perusahaan)
                                                <img src="{{ asset('storage/' . $selectedLowongan->logo_perusahaan) }}" 
                                                     alt="{{ $selectedLowongan->nama_perusahaan }}"
                                                     class="w-12 h-12 rounded-lg object-cover mr-4">
                                            @else
                                                <div class="w-12 h-12 bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center mr-4">
                                                    <i class="fas fa-building text-gray-400"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <h4 class="font-semibold text-gray-900 dark:text-white text-lg">{{ $selectedLowongan->posisi }}</h4>
                                                <p class="text-gray-600 dark:text-gray-400">{{ $selectedLowongan->nama_perusahaan }}</p>
                                            </div>
                                        </div>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                            Buka
                                        </span>
                                    </div>

                                    <!-- Informasi Utama -->
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Lokasi</p>
                                            <p class="font-medium text-gray-900 dark:text-white">{{ $selectedLowongan->lokasi }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Gaji</p>
                                            <p class="font-medium text-gray-900 dark:text-white">{{ $selectedLowongan->gaji_formatted }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Tanggal Buka</p>
                                            <p class="font-medium text-gray-900 dark:text-white">{{ $selectedLowongan->tanggal_buka->format('d F Y') }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Tanggal Tutup</p>
                                            <p class="font-medium text-gray-900 dark:text-white">{{ $selectedLowongan->tanggal_tutup->format('d F Y') }}</p>
                                        </div>
                                    </div>

                                    <!-- Deskripsi Pekerjaan -->
                                    <div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Deskripsi Pekerjaan</p>
                                        <p class="text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                                            {{ $selectedLowongan->deskripsi_pekerjaan }}
                                        </p>
                                    </div>

                                    <!-- Kualifikasi -->
                                    <div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Kualifikasi</p>
                                        <p class="text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                                            {{ $selectedLowongan->kualifikasi }}
                                        </p>
                                    </div>

                                    <!-- Di bagian kontak, ganti website menjadi link -->
<div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
    <h5 class="text-sm font-medium text-blue-800 dark:text-blue-300 mb-2">Informasi Kontak</h5>
    <div class="space-y-1 text-sm">
        <p><strong>Kontak Person:</strong> {{ $selectedLowongan->kontak_person }}</p>
        <p><strong>Email:</strong> {{ $selectedLowongan->email }}</p>
        <p><strong>Telepon:</strong> {{ $selectedLowongan->no_telepon }}</p>
        @if($selectedLowongan->website)
            <p><strong>Website:</strong> 
                <a href="{{ $selectedLowongan->website }}" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline">
                    {{ $selectedLowongan->website }}
                </a>
            </p>
        @endif
    </div>
</div>

                                    @if($selectedLowongan->hari_tersisa > 0)
                                        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-3">
                                            <div class="flex items-center">
                                                <i class="fas fa-clock text-green-500 mr-2"></i>
                                                <p class="text-sm text-green-700 dark:text-green-300">
                                                    Lowongan tutup dalam {{ $selectedLowongan->hari_tersisa }} hari
                                                </p>
                                            </div>
                                        </div>
                                    @else
                                        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-3">
                                            <div class="flex items-center">
                                                <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                                                <p class="text-sm text-red-700 dark:text-red-300">
                                                    Lowongan segera tutup
                                                </p>
                                            </div>
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