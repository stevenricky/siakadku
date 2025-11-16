<div class="py-4 md:py-6">
    <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-8">
        <!-- Header -->
        <div class="mb-4 md:mb-6">
            <h2 class="text-xl md:text-2xl font-bold text-gray-900 dark:text-white">Materi Pembelajaran</h2>
            <p class="mt-1 md:mt-2 text-sm md:text-base text-gray-600 dark:text-gray-400">Akses materi pembelajaran dari guru</p>
        </div>

        <!-- Filters & Search -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 md:p-4 mb-4 md:mb-6">
            <div class="flex flex-col md:flex-row gap-3 md:gap-4">
                <!-- Search -->
                <div class="flex-1 min-w-0">
                    <input type="text" 
                           wire:model.live="search"
                           placeholder="Cari materi atau mata pelajaran..."
                           class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 md:px-4 py-2 text-sm md:text-base focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                </div>
                
                <!-- Mapel Filter -->
                <div class="flex gap-2 min-w-0">
                    <select wire:model.live="filterMapel" 
                            class="w-full md:w-48 border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm md:text-base focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white truncate">
                        <option value="">Semua Mapel</option>
                        @foreach($mapels as $mapel)
                            <option value="{{ $mapel->id }}" class="truncate">{{ $mapel->nama_mapel }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Rest of the content remains the same -->
        <!-- Materi List -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
            @if($materiList->count() > 0)
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($materiList as $materi)
                        <div class="p-4 md:p-6 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <div class="flex flex-col gap-4">
                                <!-- Left Content -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start gap-3">
                                        <!-- File Icon -->
                                        <div class="flex-shrink-0">
                                            @if($materi->file)
                                                <div class="w-8 h-8 md:w-10 md:h-10 rounded-full flex items-center justify-center 
                                                    @if($materi->file_type === 'pdf') bg-red-100 text-red-600 dark:bg-red-900 dark:text-red-400
                                                    @elseif($materi->file_type === 'word') bg-blue-100 text-blue-600 dark:bg-blue-900 dark:text-blue-400
                                                    @elseif($materi->file_type === 'powerpoint') bg-orange-100 text-orange-600 dark:bg-orange-900 dark:text-orange-400
                                                    @elseif($materi->file_type === 'excel') bg-green-100 text-green-600 dark:bg-green-900 dark:text-green-400
                                                    @else bg-gray-100 text-gray-600 dark:bg-gray-900 dark:text-gray-400 @endif">
                                                    <!-- Icons remain the same -->
                                                    @if($materi->file_type === 'pdf')
                                                        <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                        </svg>
                                                    @elseif($materi->file_type === 'word')
                                                        <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                        </svg>
                                                    @else
                                                        <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                        </svg>
                                                    @endif
                                                </div>
                                            @elseif($materi->link)
                                                <div class="w-8 h-8 md:w-10 md:h-10 rounded-full flex items-center justify-center bg-purple-100 text-purple-600 dark:bg-purple-900 dark:text-purple-400">
                                                    <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                                    </svg>
                                                </div>
                                            @else
                                                <div class="w-8 h-8 md:w-10 md:h-10 rounded-full flex items-center justify-center bg-gray-100 text-gray-600 dark:bg-gray-900 dark:text-gray-400">
                                                    <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Materi Info -->
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-base md:text-lg font-semibold text-gray-900 dark:text-white line-clamp-2">
                                                {{ $materi->judul }}
                                            </h3>
                                            
                                            <p class="text-xs md:text-sm text-gray-600 dark:text-gray-400 mb-1 md:mb-2">
                                                <span class="font-medium">{{ $materi->mapel->nama_mapel }}</span> • {{ $materi->guru->nama_lengkap }}
                                            </p>
                                            
                                            @if($materi->deskripsi)
                                                <p class="text-xs md:text-sm text-gray-600 dark:text-gray-400 mb-1 md:mb-2 line-clamp-2">
                                                    {{ \Illuminate\Support\Str::limit($materi->deskripsi, 120) }}
                                                </p>
                                            @endif
                                            
                                            <p class="text-xs text-gray-500 dark:text-gray-500">
                                                Dibagikan {{ $materi->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Right Actions -->
                                <div class="flex flex-col xs:flex-row gap-2 justify-end">
                                    @if($materi->file)
                                        @if($materi->can_preview)
                                            <button wire:click="previewMateri({{ $materi->id }})"
                                                    class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs md:text-sm font-medium transition-colors whitespace-nowrap">
                                                <span class="hidden xs:inline">Preview</span>
                                                <span class="xs:hidden">Lihat</span>
                                            </button>
                                        @endif
                                        
                                        <button wire:click="downloadMateri({{ $materi->id }})"
                                                class="px-3 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-xs md:text-sm font-medium transition-colors whitespace-nowrap">
                                            <span class="hidden xs:inline">Download</span>
                                            <span class="xs:hidden">Unduh</span>
                                        </button>
                                    @endif
                                    
                                    @if($materi->link)
                                        <button wire:click="bukaLink({{ $materi->id }})"
                                                class="px-3 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-xs md:text-sm font-medium transition-colors whitespace-nowrap">
                                            <span class="hidden xs:inline">Buka Link</span>
                                            <span class="xs:hidden">Buka</span>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="px-4 md:px-6 py-3 md:py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $materiList->links() }}
                </div>
            @else
                <div class="text-center py-8 md:py-12">
                    <svg class="w-12 h-12 md:w-16 md:h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="mt-3 text-base md:text-lg font-medium text-gray-900 dark:text-white">Tidak ada materi</h3>
                    <p class="mt-1 text-xs md:text-sm text-gray-500 dark:text-gray-400 max-w-sm mx-auto">
                        @if($search || $filterMapel)
                            Coba ubah pencarian atau filter Anda.
                        @else
                            Belum ada materi yang dibagikan untuk kelas Anda.
                        @endif
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>