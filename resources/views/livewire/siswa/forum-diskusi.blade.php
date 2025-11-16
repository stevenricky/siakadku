<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Forum Diskusi</h1>
        <p class="text-gray-600 dark:text-gray-400">Diskusikan topik dengan teman sekelas dan guru</p>
    </div>

    <!-- Flash Message -->
    @if (session()->has('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg dark:bg-green-900 dark:border-green-700 dark:text-green-200">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg dark:bg-red-900 dark:border-red-700 dark:text-red-200">
            {{ session('error') }}
        </div>
    @endif

    @if (session()->has('info'))
        <div class="mb-6 p-4 bg-blue-100 border border-blue-400 text-blue-700 rounded-lg dark:bg-blue-900 dark:border-blue-700 dark:text-blue-200">
            {{ session('info') }}
        </div>
    @endif

    <!-- Filter dan Pencarian - Compact Version -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
            <!-- Search -->
            <div class="lg:col-span-2">
                <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Cari Diskusi
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input 
                        type="text" 
                        id="search"
                        wire:model.live="search"
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                        placeholder="Cari judul atau isi diskusi..."
                    >
                </div>
            </div>

            <!-- Filter Kategori -->
            <div>
                <label for="filterKategori" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Kategori
                </label>
                <select 
                    id="filterKategori"
                    wire:model.live="filterKategori"
                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                >
                    <option value="">Semua</option>
                    <option value="pelajaran">📚 Pelajaran</option>
                    <option value="umum">💬 Umum</option>
                    <option value="ekstrakurikuler">⚽ Ekstrakurikuler</option>
                </select>
            </div>

            <!-- Filter Sort -->
            <div>
                <label for="filterSort" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Urutkan
                </label>
                <select 
                    id="filterSort"
                    wire:model.live="filterSort"
                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                >
                    <option value="terbaru"> Terbaru</option>
                    <option value="populer"> Populer</option>
                    <option value="trending"> Trending</option>
                    <option value="paling_dikomentari"> Banyak Komentar</option>
                </select>
            </div>

            <!-- Items Per Page & Actions -->
            <div class="flex gap-2">
                <div class="flex-1">
                    <label for="perPage" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Tampil
                    </label>
                    <select 
                        id="perPage"
                        wire:model.live="perPage"
                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                    >
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="15">15</option>
                        <option value="20">20</option>
                    </select>
                </div>
                
                <div class="flex flex-col gap-2">
                    <button 
                        wire:click="resetFilters"
                        class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors duration-200 text-sm"
                        title="Reset semua filter"
                    >
                        🔄
                    </button>
                    <button 
                        wire:click="$set('showModal', true)"
                        class="px-3 py-2 bg-blue-600 border border-transparent rounded-lg text-white hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 text-sm"
                        title="Buat diskusi baru"
                    >
                        +
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Forum -->
    @if($forumList->count() > 0)
        <div class="space-y-4">
            @foreach($forumList as $forum)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-md transition-shadow duration-200">
                    <!-- Header Forum -->
                    <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-2">
                            <div class="flex items-start gap-3">
                                @if($forum->is_pinned)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                        📌 Disematkan
                                    </span>
                                @endif
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ $forum->judul }}
                                </h3>
                            </div>
                            <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    {{ $forum->kategori_formatted }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Content Forum -->
                    <div class="px-6 py-4">
                        <div class="prose prose-sm max-w-none dark:prose-invert mb-4">
                            <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                                {{ $forum->excerpt }}
                            </p>
                        </div>

<!-- Footer Forum - Compact Version -->
<div class="pt-4 border-t border-gray-200 dark:border-gray-700">
    <!-- First Row: User Info & Date -->
    <div class="flex items-center justify-between mb-3">
        <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
            <div class="flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span class="truncate max-w-[120px]">{{ $forum->user->name }}</span>
            </div>
            <span>•</span>
            <div class="flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ $forum->created_at->format('d/m') }}</span>
            </div>
        </div>
        
        <!-- Category Badge -->
        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
            {{ substr($forum->kategori_formatted, 0, 3) }}
        </span>
    </div>

    <!-- Second Row: Stats & Actions -->
    <div class="flex items-center justify-between">
        <!-- Stats -->
        <div class="flex items-center gap-3 text-sm text-gray-500 dark:text-gray-400">
            <div class="flex items-center gap-1" title="{{ $forum->like_count }} Like">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                </svg>
                <span>{{ $forum->like_count }}</span>
            </div>
            <div class="flex items-center gap-1" title="{{ $forum->komentar->count() }} Komentar">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                </svg>
                <span>{{ $forum->komentar->count() }}</span>
            </div>
            <div class="flex items-center gap-1" title="{{ $forum->view_count }} Dilihat">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                <span>{{ $forum->view_count }}</span>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center gap-2">
            <button 
                wire:click="likeDiskusi({{ $forum->id }})"
                class="p-2 {{ $forum->is_liked_by_user ? 'text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400' }} hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200 border border-gray-300 dark:border-gray-600 rounded-lg"
                title="{{ $forum->is_liked_by_user ? 'Hapus like' : 'Suka diskusi ini' }}"
            >
                <svg class="w-4 h-4" fill="{{ $forum->is_liked_by_user ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                </svg>
            </button>
            <button 
                wire:click="bukaDetail({{ $forum->id }})"
                class="px-3 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 whitespace-nowrap"
            >
                <span class="hidden sm:inline">Baca & Diskusi</span>
                <span class="sm:hidden">Buka</span>
            </button>
        </div>
    </div>
</div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $forumList->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="text-center py-12">
            <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">Belum ada diskusi</h3>
            <p class="mt-2 text-gray-500 dark:text-gray-400">
                @if($search || $filterKategori)
                    Tidak ada diskusi yang sesuai dengan filter yang dipilih.
                @else
                    Jadilah yang pertama memulai diskusi!
                @endif
            </p>
            <button 
                wire:click="$set('showModal', true)"
                class="mt-4 px-6 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-white hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
            >
                Buat Diskusi Pertama
            </button>
        </div>
    @endif

    <!-- Modal Buat Diskusi -->
    @if($showModal)
        <div class="fixed inset-0 overflow-y-auto z-50">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 bg-gray-500 dark:bg-gray-900 bg-opacity-75 transition-opacity" wire:click="closeModal"></div>

                <!-- Modal panel -->
                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full sm:p-6">
                    <!-- Header -->
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Buat Diskusi Baru
                        </h3>
                    </div>

                    <!-- Form -->
                    <form wire:submit.prevent="buatDiskusi" class="space-y-4">
                        <!-- Judul -->
                        <div>
                            <label for="judul" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Judul Diskusi *
                            </label>
                            <input 
                                type="text" 
                                id="judul"
                                wire:model="judul"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Masukkan judul diskusi"
                                required
                            >
                            @error('judul')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kategori -->
                        <div>
                            <label for="kategori" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Kategori *
                            </label>
                            <select 
                                id="kategori"
                                wire:model="kategori"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                required
                            >
                                <option value="pelajaran">Pelajaran</option>
                                <option value="umum">Umum</option>
                                <option value="ekstrakurikuler">Ekstrakurikuler</option>
                            </select>
                            @error('kategori')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Isi -->
                        <div>
                            <label for="isi" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Isi Diskusi *
                            </label>
                            <textarea 
                                id="isi"
                                wire:model="isi"
                                rows="6"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"
                                placeholder="Tulis isi diskusi Anda..."
                                required
                            ></textarea>
                            @error('isi')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Footer -->
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-4 flex justify-end gap-3">
                            <button 
                                type="button"
                                wire:click="closeModal"
                                class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200"
                            >
                                Batal
                            </button>
                            <button 
                                type="submit"
                                class="px-6 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-white hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                            >
                                Buat Diskusi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Detail Diskusi -->
    @if($showDetailModal && $selectedForum)
        <div class="fixed inset-0 overflow-y-auto z-50">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 bg-gray-500 dark:bg-gray-900 bg-opacity-75 transition-opacity" wire:click="closeModal"></div>

                <!-- Modal panel -->
                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full sm:p-6">
                    <!-- Header -->
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                    {{ $selectedForum->judul }}
                                </h3>
                                <div class="flex items-center gap-4 mt-2 text-sm text-gray-500 dark:text-gray-400">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                        {{ $selectedForum->kategori_formatted }}
                                    </span>
                                    <span>Oleh: {{ $selectedForum->user->name }}</span>
                                    <span>{{ $selectedForum->waktu_format }}</span>
                                </div>
                            </div>
                            <button 
                                wire:click="closeModal"
                                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200"
                            >
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="prose prose-sm max-w-none dark:prose-invert mb-6">
                        <div class="text-gray-600 dark:text-gray-300 leading-relaxed">
                            {!! nl2br(e($selectedForum->isi)) !!}
                        </div>
                    </div>

                    <!-- Stats -->
                    <div class="flex items-center gap-6 text-sm text-gray-500 dark:text-gray-400 mb-6">
                        <button 
                            wire:click="likeDiskusi({{ $selectedForum->id }})"
                            class="flex items-center gap-1 {{ $selectedForum->is_liked_by_user ? 'text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400' }} hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200"
                            title="{{ $selectedForum->is_liked_by_user ? 'Hapus like' : 'Suka diskusi ini' }}"
                        >
                            <svg class="w-4 h-4" fill="{{ $selectedForum->is_liked_by_user ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                            </svg>
                            <span>{{ $selectedForum->like_count }} Like</span>
                        </button>
                        <div class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                            </svg>
                            <span>{{ $selectedForum->komentar->count() }} Komentar</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <span>{{ $selectedForum->view_count }} Dilihat</span>
                        </div>
                    </div>

                    <!-- Komentar Section -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                            Komentar ({{ $selectedForum->komentar->count() }})
                        </h4>

                        <!-- Form Komentar - VERSI YANG BENAR -->
                        <div class="mb-6">
                            <div class="flex gap-3">
                                <div class="flex-1">
                                    <textarea 
                                        wire:model.live="komentarText"
                                        placeholder="Tulis komentar Anda..."
                                        rows="3"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"
                                        wire:keydown.enter.prevent="tambahKomentar"
                                    ></textarea>
                                </div>
                                
                                <!-- Tombol Kirim -->
                                <div class="flex flex-col gap-2">
                                    <button 
                                        type="button"
                                        wire:click="tambahKomentar"
                                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 flex items-center gap-2 h-[42px]"
                                        wire:loading.attr="disabled"
                                        wire:target="tambahKomentar"
                                    >
                                        <div wire:loading.remove wire:target="tambahKomentar">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                            </svg>
                                        </div>
                                        <div wire:loading wire:target="tambahKomentar">
                                            <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                        </div>
                                        <span class="hidden sm:inline">Kirim</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Error Message -->
                            @error('komentarText')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror

                            <!-- Character Info -->
                            <div class="mt-2 flex justify-between items-center text-xs text-gray-500 dark:text-gray-400">
                                <span>
                                    @if(strlen($komentarText) > 800)
                                        <span class="text-red-600">Hati-hati, komentar hampir mencapai batas</span>
                                    @else
                                        {{ strlen($komentarText) }}/1000 karakter
                                    @endif
                                </span>
                                <span>
                                    Tekan Enter untuk kirim
                                </span>
                            </div>
                        </div>

                        <!-- Loading State -->
                        <div wire:loading wire:target="tambahKomentar" class="text-center py-2">
                            <div class="inline-flex items-center gap-2 text-sm text-blue-600 dark:text-blue-400">
                                <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Mengirim komentar...
                            </div>
                        </div>

                        <!-- Daftar Komentar -->
                        <div class="space-y-4">
                            @forelse($selectedForum->komentar as $komentar)
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                    <div class="flex justify-between items-start mb-2">
                                        <div class="flex items-center gap-2">
                                            <span class="font-medium text-gray-900 dark:text-white">
                                                {{ $komentar->user->name }}
                                            </span>
                                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $komentar->created_at->format('d M Y H:i') }}
                                            </span>
                                        </div>
                                    </div>
                                    <p class="text-gray-600 dark:text-gray-300">
                                        {{ $komentar->komentar }}
                                    </p>
                                </div>
                            @empty
                                <div class="text-center py-4 text-gray-500 dark:text-gray-400">
                                    <p>Belum ada komentar. Jadilah yang pertama berkomentar!</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>