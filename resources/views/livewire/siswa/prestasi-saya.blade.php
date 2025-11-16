<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Prestasi Saya</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Daftar prestasi yang telah dicapai</p>
            </div>
            <div class="mt-4 md:mt-0 flex space-x-3">
                <!-- View Mode Toggle -->
                <div class="flex bg-gray-100 dark:bg-gray-700 rounded-lg p-1">
                    <button 
                        @click="setViewMode('grid')" 
                        :class="viewMode === 'grid' ? 'bg-white dark:bg-gray-600 shadow-sm' : 'text-gray-500 dark:text-gray-400'"
                        class="px-3 py-2 rounded-md text-sm font-medium transition-colors"
                    >
                        <i class="fas fa-th-large"></i>
                    </button>
                    <button 
                        @click="setViewMode('list')" 
                        :class="viewMode === 'list' ? 'bg-white dark:bg-gray-600 shadow-sm' : 'text-gray-500 dark:text-gray-400'"
                        class="px-3 py-2 rounded-md text-sm font-medium transition-colors"
                    >
                        <i class="fas fa-list"></i>
                    </button>
                </div>
                
                <button onclick="window.print()" class="btn btn-outline-primary">
                    <i class="fas fa-print mr-2"></i>Print
                </button>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                        <i class="fas fa-trophy text-blue-600 dark:text-blue-400 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Prestasi</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalPrestasi }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                        <i class="fas fa-graduation-cap text-green-600 dark:text-green-400 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Akademik</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $prestasiAkademik }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center">
                        <i class="fas fa-star text-purple-600 dark:text-purple-400 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Non Akademik</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $prestasiNonAkademik }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900 rounded-lg flex items-center justify-center">
                        <i class="fas fa-calendar text-orange-600 dark:text-orange-400 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Tahun Ini</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $prestasiTahunIni }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <!-- Search -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cari Prestasi</label>
                <div class="relative">
                    <input 
                        type="text" 
                        wire:model.live="search"
                        placeholder="Cari nama prestasi..." 
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                    >
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                </div>
            </div>

            <!-- Tahun Filter -->
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

            <!-- Jenis Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jenis</label>
                <select 
                    wire:model.live="filterJenis"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                >
                    <option value="">Semua Jenis</option>
                    @foreach($jenisOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Tingkat Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tingkat</label>
                <select 
                    wire:model.live="filterTingkat"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                >
                    <option value="">Semua Tingkat</option>
                    @foreach($tingkatOptions as $value => $label)
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
    @if($prestasi->count() > 0)
        <!-- Grid View -->
        @if($viewMode === 'grid')
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                @foreach($prestasi as $item)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-md transition-shadow">
                        <div class="p-4">
                            <div class="flex items-start justify-between mb-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    @if($item->jenis_prestasi == 'Akademik') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                    @elseif($item->jenis_prestasi == 'Non Akademik') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200
                                    @elseif($item->jenis_prestasi == 'Olahraga') bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200
                                    @elseif($item->jenis_prestasi == 'Seni') bg-pink-100 text-pink-800 dark:bg-pink-900 dark:text-pink-200
                                    @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 @endif">
                                    {{ $item->jenis_prestasi }}
                                </span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    {{ $item->tingkat }}
                                </span>
                            </div>
                            
                            <h3 class="font-semibold text-gray-900 dark:text-white mb-2 line-clamp-2">{{ $item->nama_prestasi }}</h3>
                            
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3 line-clamp-2">
                                {{ $item->deskripsi ?: 'Tidak ada deskripsi' }}
                            </p>
                            
                            <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400 mb-3">
                                <div class="flex items-center">
                                    <i class="fas fa-calendar mr-1"></i>
                                    {{ $item->tanggal_prestasi->format('d M Y') }}
                                </div>
                                @if($item->peringkat)
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                        Peringkat {{ $item->peringkat }}
                                    </span>
                                @endif
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $item->penyelenggara }}
                                </span>
                                <div class="flex space-x-2">
                                    <button 
                                        wire:click="showDetail({{ $item->id }})"
                                        class="inline-flex items-center px-3 py-1.5 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors"
                                    >
                                        <i class="fas fa-eye mr-1"></i> Detail
                                    </button>
                                    @if($item->sertifikat)
                                        <button 
                                            wire:click="downloadSertifikat({{ $item->id }})"
                                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 transition-colors"
                                        >
                                            <i class="fas fa-download mr-1"></i>
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
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Prestasi</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jenis & Tingkat</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Penyelenggara</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                            @foreach($prestasi as $item)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-4 py-4">
                                        <div>
                                            <div class="font-medium text-gray-900 dark:text-white">{{ $item->nama_prestasi }}</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400 mt-1 line-clamp-2">
                                                {{ $item->deskripsi ?: 'Tidak ada deskripsi' }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="space-y-1">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                @if($item->jenis_prestasi == 'Akademik') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                @elseif($item->jenis_prestasi == 'Non Akademik') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200
                                                @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 @endif">
                                                {{ $item->jenis_prestasi }}
                                            </span>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                {{ $item->tingkat }}
                                            </span>
                                            @if($item->peringkat)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                    Peringkat {{ $item->peringkat }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $item->tanggal_prestasi->format('d M Y') }}
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $item->penyelenggara }}
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="flex space-x-2">
                                            <button 
                                                wire:click="showDetail({{ $item->id }})"
                                                class="inline-flex items-center px-3 py-1.5 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors"
                                            >
                                                <i class="fas fa-eye mr-1"></i> Detail
                                            </button>
                                            @if($item->sertifikat)
                                                <button 
                                                    wire:click="downloadSertifikat({{ $item->id }})"
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
                Menampilkan {{ $prestasi->firstItem() }} hingga {{ $prestasi->lastItem() }} dari {{ $prestasi->total() }} prestasi
            </p>
            <div>
                {{ $prestasi->links() }}
            </div>
        </div>
    @else
        <!-- Empty State -->
        <div class="text-center py-12">
            <div class="mx-auto w-24 h-24 mb-4 text-gray-400">
                <i class="fas fa-trophy text-6xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Belum ada prestasi</h3>
            <p class="text-gray-500 dark:text-gray-400 mb-4">Prestasi yang Anda raih akan ditampilkan di sini.</p>
            @if($search || $filterTahun || $filterJenis || $filterTingkat)
                <button 
                    wire:click="$set(['search' => '', 'filterTahun' => '', 'filterJenis' => '', 'filterTingkat' => ''])"
                    class="btn btn-primary"
                >
                    Tampilkan Semua Prestasi
                </button>
            @endif
        </div>
    @endif

    <!-- Detail Modal -->
    @if($showDetailModal && $selectedPrestasi)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeDetail"></div>

                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">
                                    Detail Prestasi
                                </h3>
                                
                                <div class="space-y-4">
                                    <div>
                                        <h4 class="font-semibold text-gray-900 dark:text-white text-lg">{{ $selectedPrestasi->nama_prestasi }}</h4>
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Jenis</p>
                                            <p class="font-medium text-gray-900 dark:text-white">{{ $selectedPrestasi->jenis_prestasi }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Tingkat</p>
                                            <p class="font-medium text-gray-900 dark:text-white">{{ $selectedPrestasi->tingkat }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Tanggal</p>
                                            <p class="font-medium text-gray-900 dark:text-white">{{ $selectedPrestasi->tanggal_prestasi->format('d F Y') }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Peringkat</p>
                                            <p class="font-medium text-gray-900 dark:text-white">{{ $selectedPrestasi->peringkat ?: '-' }}</p>
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Penyelenggara</p>
                                        <p class="font-medium text-gray-900 dark:text-white">{{ $selectedPrestasi->penyelenggara }}</p>
                                    </div>
                                    
                                    @if($selectedPrestasi->deskripsi)
                                        <div>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Deskripsi</p>
                                            <p class="font-medium text-gray-900 dark:text-white">{{ $selectedPrestasi->deskripsi }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        @if($selectedPrestasi->sertifikat)
                            <button 
                                wire:click="downloadSertifikat({{ $selectedPrestasi->id }})"
                                type="button" 
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm"
                            >
                                <i class="fas fa-download mr-2"></i>Download Sertifikat
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