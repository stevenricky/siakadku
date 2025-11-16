<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Informasi Karir</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Eksplorasi peluang karir dan pengembangan diri</p>
            </div>
            <div class="mt-4 md:mt-0">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                    <i class="fas fa-chart-line mr-1"></i>
                    {{ $totalArtikel }} Artikel • {{ $totalLowonganAktif }} Lowongan • {{ $totalBeasiswaAktif }} Beasiswa
                </span>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="mb-6">
        <div class="border-b border-gray-200 dark:border-gray-700">
            <nav class="-mb-px flex space-x-8">
                <button
                    wire:click="setActiveTab('artikel')"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors {{ $activeTab === 'artikel' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}"
                >
                    <i class="fas fa-newspaper mr-2"></i>Artikel Karir
                </button>
                <button
                    wire:click="setActiveTab('lowongan')"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors {{ $activeTab === 'lowongan' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}"
                >
                    <i class="fas fa-briefcase mr-2"></i>Lowongan Kerja
                </button>
                <button
                    wire:click="setActiveTab('beasiswa')"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors {{ $activeTab === 'beasiswa' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}"
                >
                    <i class="fas fa-graduation-cap mr-2"></i>Beasiswa
                </button>
            </nav>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Search -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Cari {{ $activeTab === 'artikel' ? 'Artikel' : ($activeTab === 'lowongan' ? 'Lowongan' : 'Beasiswa') }}
                </label>
                <div class="relative">
                    <input 
                        type="text" 
                        wire:model.live="search"
                        placeholder="Cari {{ $activeTab === 'artikel' ? 'artikel, kategori...' : ($activeTab === 'lowongan' ? 'perusahaan, posisi...' : 'beasiswa, penyelenggara...') }}" 
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                    >
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                </div>
            </div>

            <!-- Kategori Filter (hanya untuk artikel) -->
            @if($activeTab === 'artikel')
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kategori</label>
                <select 
                    wire:model.live="filterKategori"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                >
                    <option value="">Semua Kategori</option>
                    @foreach($kategoriOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            @endif

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

    <!-- Content berdasarkan Tab -->
    @if($activeTab === 'artikel')
        <!-- Artikel Karir -->
        @if($artikel->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                @foreach($artikel as $item)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-md transition-shadow">
                        @if($item->gambar)
                            <img src="{{ asset('storage/' . $item->gambar) }}" 
                                 alt="{{ $item->judul }}"
                                 class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                <i class="fas fa-newspaper text-gray-400 text-4xl"></i>
                            </div>
                        @endif
                        
                        <div class="p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    {{ $item->kategori }}
                                </span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $item->waktu_baca }}
                                </span>
                            </div>
                            
                            <h3 class="font-semibold text-gray-900 dark:text-white mb-2 text-lg line-clamp-2">
                                {{ $item->judul }}
                            </h3>
                            
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3 line-clamp-3">
                                {{ $item->konten_singkat }}
                            </p>
                            
                            <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                                <div class="flex items-center">
                                    <i class="fas fa-user-edit mr-1"></i>
                                    {{ $item->penulis }}
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-calendar mr-1"></i>
                                    {{ $item->tanggal_format }}
                                </div>
                            </div>
                            
                            <div class="mt-4 flex items-center justify-between">
                                <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-eye mr-1"></i>
                                    {{ $item->views }} views
                                </div>
                                <button 
                                    wire:click="showDetail({{ $item->id }})"
                                    class="inline-flex items-center px-3 py-1.5 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors"
                                >
                                    <i class="fas fa-book-open mr-1"></i> Baca
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <div class="mx-auto w-24 h-24 mb-4 text-gray-400">
                    <i class="fas fa-newspaper text-6xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Tidak ada artikel ditemukan</h3>
                <p class="text-gray-500 dark:text-gray-400">Coba ubah filter pencarian Anda.</p>
            </div>
        @endif

    @elseif($activeTab === 'lowongan')
        <!-- Lowongan Kerja -->
        @if($lowongan->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                @foreach($lowongan as $item)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-md transition-shadow">
                        <div class="p-4">
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
                            
                            <div class="space-y-2 mb-3">
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                    <i class="fas fa-map-marker-alt mr-2 w-4"></i>
                                    {{ $item->lokasi }}
                                </div>
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                    <i class="fas fa-money-bill-wave mr-2 w-4"></i>
                                    {{ $item->gaji_formatted }}
                                </div>
                            </div>

                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 line-clamp-2">
                                {{ Str::limit($item->deskripsi_pekerjaan, 100) }}
                            </p>

                            <div class="flex items-center justify-between">
                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $item->hari_tersisa }} hari lagi
                                </span>
                                <a 
                                    href="{{ route('siswa.lowongan-kerja.index') }}"
                                    class="inline-flex items-center px-3 py-1.5 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors"
                                >
                                    <i class="fas fa-eye mr-1"></i> Detail
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <div class="mx-auto w-24 h-24 mb-4 text-gray-400">
                    <i class="fas fa-briefcase text-6xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Tidak ada lowongan aktif</h3>
                <p class="text-gray-500 dark:text-gray-400">Silakan coba lagi nanti.</p>
            </div>
        @endif

    @elseif($activeTab === 'beasiswa')
        <!-- Beasiswa -->
        @if($beasiswa->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                @foreach($beasiswa as $item)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-md transition-shadow">
                        <div class="p-4">
                            <div class="flex items-start justify-between mb-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    {{ $item->penyelenggara }}
                                </span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    Buka
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
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                    <i class="fas fa-calendar-alt mr-2 w-4"></i>
                                    Tutup: {{ $item->tanggal_tutup->format('d M Y') }}
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $item->hari_tersisa }} hari lagi
                                </span>
                                <a 
                                    href="{{ route('siswa.beasiswa.index') }}"
                                    class="inline-flex items-center px-3 py-1.5 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors"
                                >
                                    <i class="fas fa-eye mr-1"></i> Detail
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <div class="mx-auto w-24 h-24 mb-4 text-gray-400">
                    <i class="fas fa-graduation-cap text-6xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Tidak ada beasiswa aktif</h3>
                <p class="text-gray-500 dark:text-gray-400">Silakan coba lagi nanti.</p>
            </div>
        @endif
    @endif

    <!-- Pagination -->
    @if(($activeTab === 'artikel' && $artikel->count() > 0) || 
        ($activeTab === 'lowongan' && $lowongan->count() > 0) || 
        ($activeTab === 'beasiswa' && $beasiswa->count() > 0))
        <div class="flex items-center justify-between">
            <p class="text-sm text-gray-700 dark:text-gray-300">
                @if($activeTab === 'artikel')
                    Menampilkan {{ $artikel->firstItem() }} hingga {{ $artikel->lastItem() }} dari {{ $artikel->total() }} artikel
                @elseif($activeTab === 'lowongan')
                    Menampilkan {{ $lowongan->firstItem() }} hingga {{ $lowongan->lastItem() }} dari {{ $lowongan->total() }} lowongan
                @else
                    Menampilkan {{ $beasiswa->firstItem() }} hingga {{ $beasiswa->lastItem() }} dari {{ $beasiswa->total() }} beasiswa
                @endif
            </p>
            <div>
                @if($activeTab === 'artikel')
                    {{ $artikel->links() }}
                @elseif($activeTab === 'lowongan')
                    {{ $lowongan->links() }}
                @else
                    {{ $beasiswa->links() }}
                @endif
            </div>
        </div>
    @endif

    <!-- Detail Modal untuk Artikel -->
    @if($showDetailModal && $selectedArtikel)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeDetail"></div>

                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                    <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <!-- Header -->
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                                        {{ $selectedArtikel->judul }}
                                    </h3>
                                    <button wire:click="closeDetail" class="text-gray-400 hover:text-gray-600">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>

                                <!-- Meta Info -->
                                <div class="flex items-center space-x-4 text-sm text-gray-500 dark:text-gray-400 mb-4">
                                    <span class="inline-flex items-center">
                                        <i class="fas fa-user-edit mr-1"></i>
                                        {{ $selectedArtikel->penulis }}
                                    </span>
                                    <span class="inline-flex items-center">
                                        <i class="fas fa-calendar mr-1"></i>
                                        {{ $selectedArtikel->tanggal_format }}
                                    </span>
                                    <span class="inline-flex items-center">
                                        <i class="fas fa-clock mr-1"></i>
                                        {{ $selectedArtikel->waktu_baca }}
                                    </span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                        {{ $selectedArtikel->kategori }}
                                    </span>
                                </div>

                                <!-- Gambar -->
                                @if($selectedArtikel->gambar)
                                    <img src="{{ asset('storage/' . $selectedArtikel->gambar) }}" 
                                         alt="{{ $selectedArtikel->judul }}"
                                         class="w-full h-64 object-cover rounded-lg mb-4">
                                @endif

                                <!-- Konten -->
                                <div class="prose dark:prose-invert max-w-none">
                                    {!! nl2br(e($selectedArtikel->konten)) !!}
                                </div>

                                <!-- Tags -->
                                @if($selectedArtikel->tags)
                                    <div class="mt-4">
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($selectedArtikel->tags as $tag)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                                    #{{ $tag }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- Sumber -->
                                @if($selectedArtikel->sumber)
                                    <div class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                                        Sumber: {{ $selectedArtikel->sumber }}
                                    </div>
                                @endif
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