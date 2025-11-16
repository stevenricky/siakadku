<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Beasiswa</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Temukan peluang beasiswa untuk mendukung pendidikan Anda</p>
            </div>
            <div class="mt-4 md:mt-0 flex space-x-3">
                <!-- View Mode Toggle -->
                <div class="flex bg-gray-100 dark:bg-gray-700 rounded-lg p-1">
                    <button 
                        onclick="setViewMode('grid')" 
                        class="px-3 py-2 rounded-md text-sm font-medium transition-colors {{ $viewMode === 'grid' ? 'bg-white dark:bg-gray-600 shadow-sm text-gray-900 dark:text-white' : 'text-gray-500 dark:text-gray-400' }}"
                    >
                        <i class="fas fa-th-large"></i>
                    </button>
                    <button 
                        onclick="setViewMode('list')" 
                        class="px-3 py-2 rounded-md text-sm font-medium transition-colors {{ $viewMode === 'list' ? 'bg-white dark:bg-gray-600 shadow-sm text-gray-900 dark:text-white' : 'text-gray-500 dark:text-gray-400' }}"
                    >
                        <i class="fas fa-list"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                        <i class="fas fa-graduation-cap text-blue-600 dark:text-blue-400 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Beasiswa</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalBeasiswa }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                        <i class="fas fa-door-open text-green-600 dark:text-green-400 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Sedang Buka</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $beasiswaAktif }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-red-100 dark:bg-red-900 rounded-lg flex items-center justify-center">
                        <i class="fas fa-door-closed text-red-600 dark:text-red-400 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Sudah Tutup</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $beasiswaTutup }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center">
                        <i class="fas fa-calendar-plus text-purple-600 dark:text-purple-400 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Bulan Ini</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $beasiswaBulanIni }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Search -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cari Beasiswa</label>
                <div class="relative">
                    <input 
                        type="text" 
                        wire:model.live="search"
                        placeholder="Cari nama beasiswa, penyelenggara..." 
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                    >
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                </div>
            </div>

            <!-- Penyelenggara Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Penyelenggara</label>
                <select 
                    wire:model.live="filterPenyelenggara"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                >
                    <option value="">Semua Penyelenggara</option>
                    @foreach($penyelenggaraOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                <select 
                    wire:model.live="filterStatus"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                >
                    @foreach($statusOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
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
                    <option value="15">15 Items</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Content -->
    @if($beasiswa->count() > 0)
        <!-- Grid View -->
        @if($viewMode === 'grid')
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                @foreach($beasiswa as $item)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-md transition-shadow">
                        <div class="p-4">
                            <div class="flex items-start justify-between mb-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    {{ $item->penyelenggara }}
                                </span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    @if($item->is_active) bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                    @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @endif">
                                    {{ $item->is_active ? 'Buka' : 'Tutup' }}
                                </span>
                            </div>
                            
                            <h3 class="font-semibold text-gray-900 dark:text-white mb-2 text-lg">{{ $item->nama_beasiswa }}</h3>
                            
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3 line-clamp-2">
                                {{ $item->deskripsi_singkat }}
                            </p>
                            
                            <div class="space-y-2 mb-4">
    <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
        <i class="fas fa-money-bill-wave mr-2 w-4"></i>
        {{ $item->nilai_beasiswa_formatted }}
    </div>
    <!-- TAMBAHKAN LINK WEBSITE JIKA ADA -->
    @if($item->website)
    <div class="flex items-center text-sm text-blue-600 dark:text-blue-400">
        <i class="fas fa-globe mr-2 w-4"></i>
        <a href="{{ $item->website }}" target="_blank" class="hover:underline">
            Info Selengkapnya
        </a>
    </div>
    @endif
    <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
        <i class="fas fa-calendar-alt mr-2 w-4"></i>
        Tutup: {{ $item->tanggal_tutup->format('d M Y') }}
    </div>
</div>
                            
                            <div class="flex items-center justify-between">
                                @if($item->is_active && $item->hari_tersisa > 0)
                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $item->hari_tersisa }} hari lagi
                                    </span>
                                @else
                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                        &nbsp;
                                    </span>
                                @endif
                                <div class="flex space-x-2">
                                    <button 
                                        wire:click="showDetail({{ $item->id }})"
                                        class="inline-flex items-center px-3 py-1.5 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors"
                                    >
                                        <i class="fas fa-eye mr-1"></i> Detail
                                    </button>
                                    @if($item->dokumen)
                                        <button 
                                            wire:click="downloadDokumen({{ $item->id }})"
                                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 transition-colors"
                                        >
                                            <i class="fas fa-download"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- List View -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Beasiswa</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Penyelenggara</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nilai</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tutup</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                            @foreach($beasiswa as $item)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-4 py-4">
                                        <div class="font-medium text-gray-900 dark:text-white">{{ $item->nama_beasiswa }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400 line-clamp-2">
                                            {{ $item->deskripsi_singkat }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-900 dark:text-white">
                                        {{ $item->penyelenggara }}
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-900 dark:text-white">
                                        {{ $item->nilai_beasiswa_formatted }}
                                    </td>
                                    <td class="px-4 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            @if($item->is_active) bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                            @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @endif">
                                            {{ $item->is_active ? 'Buka' : 'Tutup' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $item->tanggal_tutup->format('d M Y') }}
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="flex space-x-2">
                                            <button 
                                                wire:click="showDetail({{ $item->id }})"
                                                class="inline-flex items-center px-3 py-1.5 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors"
                                            >
                                                <i class="fas fa-eye mr-1"></i> Detail
                                            </button>
                                            @if($item->dokumen)
                                                <button 
                                                    wire:click="downloadDokumen({{ $item->id }})"
                                                    class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 transition-colors"
                                                >
                                                    <i class="fas fa-download"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Pagination -->
        <div class="flex items-center justify-between">
            <p class="text-sm text-gray-700 dark:text-gray-300">
                Menampilkan {{ $beasiswa->firstItem() }} hingga {{ $beasiswa->lastItem() }} dari {{ $beasiswa->total() }} beasiswa
            </p>
            <div>
                {{ $beasiswa->links() }}
            </div>
        </div>
    @else
        <!-- Empty State -->
        <div class="text-center py-12">
            <div class="mx-auto w-24 h-24 mb-4 text-gray-400">
                <i class="fas fa-graduation-cap text-6xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Tidak ada beasiswa ditemukan</h3>
            <p class="text-gray-500 dark:text-gray-400 mb-4">Coba ubah filter pencarian Anda atau coba lagi nanti.</p>
            @if($search || $filterPenyelenggara || $filterStatus)
                <button 
                    wire:click="$set(['search' => '', 'filterPenyelenggara' => '', 'filterStatus' => ''])"
                    class="btn btn-primary"
                >
                    Tampilkan Semua Beasiswa
                </button>
            @endif
        </div>
    @endif

    <!-- Detail Modal -->
    @if($showDetailModal && $selectedBeasiswa)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeDetail"></div>

                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">
                                    Detail Beasiswa
                                </h3>
                                
                                <div class="space-y-4">
                                    <!-- Header -->
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <h4 class="font-semibold text-gray-900 dark:text-white text-lg">{{ $selectedBeasiswa->nama_beasiswa }}</h4>
                                            <p class="text-gray-600 dark:text-gray-400">{{ $selectedBeasiswa->penyelenggara }}</p>
                                        </div>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                            @if($selectedBeasiswa->is_active) bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                            @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @endif">
                                            {{ $selectedBeasiswa->is_active ? 'Buka' : 'Tutup' }}
                                        </span>
                                    </div>

                                    <!-- Informasi Utama -->
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Nilai Beasiswa</p>
                                            <p class="font-medium text-gray-900 dark:text-white">{{ $selectedBeasiswa->nilai_beasiswa_formatted }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Tanggal Buka</p>
                                            <p class="font-medium text-gray-900 dark:text-white">{{ $selectedBeasiswa->tanggal_buka->format('d F Y') }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Tanggal Tutup</p>
                                            <p class="font-medium text-gray-900 dark:text-white">{{ $selectedBeasiswa->tanggal_tutup->format('d F Y') }}</p>
                                        </div>
                                    </div>

                                    <!-- Deskripsi -->
                                    <div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Deskripsi</p>
                                        <p class="text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                                            {{ $selectedBeasiswa->deskripsi }}
                                        </p>
                                    </div>

                                    <!-- Persyaratan -->
                                    <div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Persyaratan</p>
                                        <p class="text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                                            {{ $selectedBeasiswa->persyaratan }}
                                        </p>
                                    </div>

                                    <!-- Di bagian kontak, ganti website menjadi link -->
<div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
    <h5 class="text-sm font-medium text-blue-800 dark:text-blue-300 mb-2">Informasi Kontak</h5>
    <div class="space-y-1 text-sm">
        <p><strong>Kontak:</strong> {{ $selectedBeasiswa->kontak }}</p>
        @if($selectedBeasiswa->website)
            <p><strong>Website:</strong> 
                <a href="{{ $selectedBeasiswa->website }}" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline">
                    {{ $selectedBeasiswa->website }}
                </a>
            </p>
        @endif
    </div>
</div>

                                    @if($selectedBeasiswa->is_active && $selectedBeasiswa->hari_tersisa > 0)
                                        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-3">
                                            <div class="flex items-center">
                                                <i class="fas fa-clock text-green-500 mr-2"></i>
                                                <p class="text-sm text-green-700 dark:text-green-300">
                                                    Pendaftaran tutup dalam {{ $selectedBeasiswa->hari_tersisa }} hari
                                                </p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        @if($selectedBeasiswa->dokumen)
                            <button 
                                wire:click="downloadDokumen({{ $selectedBeasiswa->id }})"
                                type="button" 
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm"
                            >
                                <i class="fas fa-download mr-2"></i>Download Dokumen
                            </button>
                        @endif
                        <button 
                            wire:click="closeDetail" 
                            type="button" 
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
    function setViewMode(mode) {
        @this.set('viewMode', mode);
    }
</script>