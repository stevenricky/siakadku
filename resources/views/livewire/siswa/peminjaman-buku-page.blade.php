<div>
    <div class="container mx-auto px-4 py-6 max-w-4xl">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Form Peminjaman Buku</h1>
            <p class="text-gray-600 dark:text-gray-400">Isi form berikut untuk meminjam buku</p>
        </div>

        @if($buku)
        <!-- Book Info -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informasi Buku</h2>
            
            <div class="flex flex-col md:flex-row gap-6">
                <!-- Book Cover -->
                <div class="flex-shrink-0">
                    <div class="w-48 h-64 bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                        @if($buku->cover && file_exists(public_path('storage/' . $buku->cover)))
                            <img src="{{ asset('storage/' . $buku->cover) }}" alt="{{ $buku->judul }}" class="w-full h-full object-cover rounded-lg">
                        @else
                            <div class="text-gray-400 dark:text-gray-500 text-center">
                                <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                <span class="text-xs">No Cover</span>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Book Details -->
                <div class="flex-1">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ $buku->judul }}</h3>
                    
                    <div class="space-y-2 mb-4">
                        <p class="text-gray-600 dark:text-gray-400">
                            <span class="font-medium">Penulis:</span> {{ $buku->penulis }}
                        </p>
                        <p class="text-gray-600 dark:text-gray-400">
                            <span class="font-medium">Penerbit:</span> {{ $buku->penerbit }}
                        </p>
                        <p class="text-gray-600 dark:text-gray-400">
                            <span class="font-medium">Tahun:</span> {{ $buku->tahun_terbit }}
                        </p>
                        @if($buku->kategori)
                        <p class="text-gray-600 dark:text-gray-400">
                            <span class="font-medium">Kategori:</span> 
                            <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 rounded-full">
                                {{ $buku->kategori->nama_kategori }}
                            </span>
                        </p>
                        @endif
                        <p class="text-gray-600 dark:text-gray-400">
                            <span class="font-medium">Stok Tersedia:</span> 
                            <span class="{{ $buku->can_borrow ? 'text-green-600' : 'text-red-600' }} font-medium">
                                {{ $buku->stok_tersedia }}/{{ $buku->stok }}
                            </span>
                        </p>
                    </div>
                    
                    <!-- Status -->
                    <div class="flex items-center gap-2">
                        <span class="text-xs px-2 py-1 rounded-full {{ $buku->status_badge }}">
                            {{ $buku->status_text }}
                        </span>
                        @if($buku->can_borrow)
                            <span class="text-xs px-2 py-1 bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 rounded-full">
                                Dapat Dipinjam
                            </span>
                        @else
                            <span class="text-xs px-2 py-1 bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 rounded-full">
                                Tidak Tersedia
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Peminjaman Form -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Form Peminjaman</h2>
            
            <form wire:submit="pinjamBuku">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Tanggal Pinjam -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Tanggal Pinjam *
                        </label>
                        <input type="date" wire:model="tanggal_pinjam" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                        @error('tanggal_pinjam') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    
                    <!-- Tanggal Kembali -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Tanggal Kembali *
                        </label>
                        <input type="date" wire:model="tanggal_kembali" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                        @error('tanggal_kembali') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    
                    <!-- Keterangan -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Keterangan (Opsional)
                        </label>
                        <textarea wire:model="keterangan" rows="3" placeholder="Catatan tambahan..."
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"></textarea>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <button type="button" wire:click="batal" 
                            class="px-6 py-2 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Konfirmasi Peminjaman
                    </button>
                </div>
            </form>
        </div>
        @else
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 text-center">
            <p class="text-gray-600 dark:text-gray-400">Buku tidak ditemukan.</p>
            <a href="{{ route('siswa.katalog-buku.index') }}" class="text-blue-600 hover:text-blue-700 mt-2 inline-block">
                Kembali ke Katalog
            </a>
        </div>
        @endif
    </div>
</div>