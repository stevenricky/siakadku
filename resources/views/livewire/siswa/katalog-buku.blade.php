<div>
    <div class="container mx-auto px-4 py-6 max-w-7xl">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Katalog Buku Perpustakaan</h1>
            <p class="text-gray-600 dark:text-gray-400">Temukan dan pinjam buku yang Anda butuhkan</p>
        </div>

        <!-- Filters -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 mb-6">
            <div class="flex flex-col md:flex-row gap-4 justify-between items-start md:items-center">
                <!-- Search -->
                <div class="w-full md:w-auto">
                    <div class="relative">
                        <input type="text" wire:model.live="search" placeholder="Cari judul, penulis, atau penerbit..." 
                               class="w-full md:w-80 pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Category Filter -->
                <div class="flex flex-col sm:flex-row gap-4 w-full md:w-auto">
                    <select wire:model.live="kategoriFilter" 
                            class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                        <option value="">Semua Kategori</option>
                        @foreach($kategori as $kat)
                            <option value="{{ $kat->id }}">{{ $kat->nama_kategori }}</option>
                        @endforeach
                    </select>

                    <select wire:model.live="perPage" 
                            class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                        <option value="12">12 per halaman</option>
                        <option value="24">24 per halaman</option>
                        <option value="48">48 per halaman</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Books Grid -->
        @if($buku->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($buku as $item)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-md transition-shadow duration-300">
                        <!-- Book Cover -->
                        <div class="h-48 bg-gray-200 dark:bg-gray-700 flex items-center justify-center cursor-pointer" 
                             wire:click="showDetail({{ $item->id }})">
                            @if($item->cover && file_exists(public_path('storage/' . $item->cover)))
                                <img src="{{ asset('storage/' . $item->cover) }}" alt="{{ $item->judul }}" 
                                     class="h-full w-full object-cover hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="text-gray-400 dark:text-gray-500 text-center">
                                    <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                    <span class="text-xs">No Cover</span>
                                </div>
                            @endif
                        </div>

                        <!-- Book Info -->
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-900 dark:text-white mb-2 line-clamp-2 cursor-pointer hover:text-blue-600 dark:hover:text-blue-400"
                                wire:click="showDetail({{ $item->id }})">
                                {{ $item->judul }}
                            </h3>
                            
                            <div class="space-y-1 text-sm text-gray-600 dark:text-gray-400 mb-3">
                                <p class="flex items-center gap-1">
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <span class="truncate">{{ $item->penulis }}</span>
                                </p>
                                <p class="flex items-center gap-1">
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    <span class="truncate">{{ $item->penerbit }} ({{ $item->tahun_terbit }})</span>
                                </p>
                                @if($item->kategori)
                                <p class="flex items-center gap-1">
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                    <span class="truncate">{{ $item->kategori->nama_kategori }}</span>
                                </p>
                                @endif
                            </div>

                            <!-- Status & Stock -->
                            <div class="flex items-center justify-between mb-3">
                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full {{ $item->status_badge }}">
                                    {{ $item->status_text }}
                                </span>
                                <span class="text-sm text-gray-600 dark:text-gray-400">
                                    Stok: {{ $item->stok_tersedia }}/{{ $item->stok }}
                                </span>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex space-x-2">
                                @if($item->can_borrow)
                                    <button wire:click="pinjamBuku({{ $item->id }})" 
                                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors flex items-center justify-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        Pinjam
                                    </button>
                                @else
                                    <button disabled
                                            class="flex-1 bg-gray-400 text-gray-200 px-3 py-2 rounded-lg text-sm font-medium cursor-not-allowed">
                                        Tidak Tersedia
                                    </button>
                                @endif
                                
                                <button wire:click="showDetail({{ $item->id }})"
                                        class="px-3 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Detail
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $buku->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Tidak ada buku ditemukan</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    @if($search || $kategoriFilter)
                        Coba ubah filter pencarian Anda.
                    @else
                        Belum ada buku yang tersedia di perpustakaan.
                    @endif
                </p>
            </div>
        @endif
    </div>

    <!-- Detail Book Modal -->
    @if($showDetailModal && $selectedBuku)
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4"
             x-data="{ show: true }"
             x-show="show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             wire:click="closeDetail">
            
            <!-- Modal Content -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden"
                 x-on:click.stop
                 x-show="show"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95">
                
                <!-- Header -->
                <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Detail Buku</h2>
                    <button wire:click="closeDetail" 
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Content -->
                <div class="overflow-y-auto max-h-[calc(90vh-140px)]">
                    <div class="p-6">
                        <div class="flex flex-col lg:flex-row gap-6">
                            <!-- Book Cover -->
                            <div class="lg:w-1/3">
                                <div class="bg-gray-200 dark:bg-gray-700 rounded-lg h-80 flex items-center justify-center">
                                    @if($selectedBuku->cover && file_exists(public_path('storage/' . $selectedBuku->cover)))
                                        <img src="{{ asset('storage/' . $selectedBuku->cover) }}" 
                                             alt="{{ $selectedBuku->judul }}"
                                             class="h-full w-full object-cover rounded-lg">
                                    @else
                                        <div class="text-gray-400 dark:text-gray-500 text-center">
                                            <svg class="w-16 h-16 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                            </svg>
                                            <span class="text-sm">No Cover Available</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Book Details -->
                            <div class="lg:w-2/3 space-y-6">
                                <!-- Title & Basic Info -->
                                <div>
                                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                                        {{ $selectedBuku->judul }}
                                    </h1>
                                    <div class="flex flex-wrap gap-2 mb-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $selectedBuku->status_badge }}">
                                            {{ $selectedBuku->status_text }}
                                        </span>
                                        @if($selectedBuku->kategori)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                            {{ $selectedBuku->kategori->nama_kategori }}
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Book Information Grid -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="space-y-3">
                                        <div>
                                            <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Penulis</label>
                                            <p class="text-gray-900 dark:text-white">{{ $selectedBuku->penulis }}</p>
                                        </div>
                                        <div>
                                            <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Penerbit</label>
                                            <p class="text-gray-900 dark:text-white">{{ $selectedBuku->penerbit }}</p>
                                        </div>
                                        <div>
                                            <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Tahun Terbit</label>
                                            <p class="text-gray-900 dark:text-white">{{ $selectedBuku->tahun_terbit }}</p>
                                        </div>
                                    </div>
                                    <div class="space-y-3">
                                        <div>
                                            <label class="text-sm font-medium text-gray-500 dark:text-gray-400">ISBN</label>
                                            <p class="text-gray-900 dark:text-white font-mono">{{ $selectedBuku->isbn ?? '-' }}</p>
                                        </div>
                                        <div>
                                            <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Rak Buku</label>
                                            <p class="text-gray-900 dark:text-white">{{ $selectedBuku->rak_buku ?? '-' }}</p>
                                        </div>
                                        <div>
                                            <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Stok</label>
                                            <p class="text-gray-900 dark:text-white">
                                                <span class="{{ $selectedBuku->stok_tersedia > 0 ? 'text-green-600' : 'text-red-600' }} font-semibold">
                                                    {{ $selectedBuku->stok_tersedia }} tersedia
                                                </span>
                                                dari {{ $selectedBuku->stok }} total
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Description -->
                                @if($selectedBuku->deskripsi)
                                <div>
                                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2 block">Deskripsi</label>
                                    <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                                        {{ $selectedBuku->deskripsi }}
                                    </p>
                                </div>
                                @endif

                                <!-- Action Buttons -->
                                <div class="flex flex-col sm:flex-row gap-3 pt-4">
                                    @if($selectedBuku->can_borrow)
                                        <button wire:click="pinjamBuku({{ $selectedBuku->id }})" 
                                                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors flex items-center justify-center gap-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                            Pinjam Buku Ini
                                        </button>
                                    @else
                                        <button disabled
                                                class="flex-1 bg-gray-400 text-gray-200 px-6 py-3 rounded-lg font-medium cursor-not-allowed">
                                            Buku Tidak Tersedia
                                        </button>
                                    @endif
                                    <button wire:click="closeDetail"
                                            class="px-6 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                        Tutup
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Loading Indicator -->
    <div wire:loading class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 flex items-center gap-3">
            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
            <span class="text-gray-700 dark:text-gray-300">Memuat...</span>
        </div>
    </div>
</div>

@push('styles')
<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Smooth transitions for modal */
.modal-enter {
    transition: all 0.3s ease-out;
}

.modal-leave {
    transition: all 0.2s ease-in;
}

/* Custom scrollbar for modal */
.modal-content::-webkit-scrollbar {
    width: 6px;
}

.modal-content::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.modal-content::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.modal-content::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

.dark .modal-content::-webkit-scrollbar-track {
    background: #374151;
}

.dark .modal-content::-webkit-scrollbar-thumb {
    background: #6b7280;
}

.dark .modal-content::-webkit-scrollbar-thumb:hover {
    background: #9ca3af;
}
</style>
@endpush