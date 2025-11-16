<div>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="md:flex md:items-center md:justify-between mb-6">
                <div class="flex-1 min-w-0">
                    <h2 class="text-xl font-bold leading-7 text-gray-900 dark:text-white sm:text-2xl md:text-3xl sm:truncate">
                        Manajemen Pengumuman
                    </h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Kelola pengumuman untuk seluruh komunitas sekolah
                    </p>
                </div>
                <div class="mt-4 flex md:mt-0 md:ml-4">
                    <button wire:click="create" 
                            type="button"
                            class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 w-full sm:w-auto justify-center">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Buat Pengumuman
                    </button>
                </div>
            </div>

            <!-- Flash Messages -->
            @if(session()->has('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-sm text-green-800 rounded-lg p-4 dark:bg-green-800/10 dark:border-green-900 dark:text-green-500" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            @if(session()->has('error'))
                <div class="mb-4 bg-red-50 border border-red-200 text-sm text-red-800 rounded-lg p-4 dark:bg-red-800/10 dark:border-red-900 dark:text-red-500" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Search and Filter -->
            <div class="mb-6 bg-white dark:bg-gray-800 shadow rounded-lg p-4">
                <div class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-1">
                        <input type="text" 
                               wire:model.live.debounce.300ms="search"
                               placeholder="Cari pengumuman..."
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    </div>
                    <div class="sm:w-48">
                        <select wire:model.live="perPage" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            <option value="10">10 per halaman</option>
                            <option value="25">25 per halaman</option>
                            <option value="50">50 per halaman</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Table - Mobile View -->
            <div class="sm:hidden bg-white dark:bg-gray-800 shadow overflow-hidden rounded-lg">
                @forelse($pengumumen as $pengumuman)
                    <div class="border-b border-gray-200 dark:border-gray-700 p-4">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $pengumuman->judul }}
                                @if($pengumuman->is_urgent)
                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-exclamation-triangle mr-1"></i> Mendesak
                                    </span>
                                @endif
                            </h3>
                            <div class="flex gap-2">
                                <button wire:click="edit({{ $pengumuman->id }})" 
                                        type="button"
                                        class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button wire:click="delete({{ $pengumuman->id }})" 
                                        wire:confirm="Apakah Anda yakin ingin menghapus pengumuman ini?"
                                        type="button"
                                        class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">
                            {{ \Illuminate\Support\Str::limit(strip_tags($pengumuman->isi), 100) }}
                        </p>
                        <div class="flex flex-wrap gap-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $pengumuman->target === 'semua' ? 'bg-purple-100 text-purple-800' : 
                                   ($pengumuman->target === 'guru' ? 'bg-blue-100 text-blue-800' : 
                                   ($pengumuman->target === 'siswa' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800')) }}">
                                {{ ucfirst($pengumuman->target) }}
                            </span>
                            @if($pengumuman->status === 'mendesak')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Mendesak
                                </span>
                            @elseif($pengumuman->status === 'aktif')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    Kadaluarsa
                                </span>
                            @endif
                        </div>
                        <div class="mt-3 flex justify-between text-xs text-gray-500 dark:text-gray-400">
                            <span>Oleh: {{ $pengumuman->user->name ?? 'N/A' }}</span>
                            <span>Berlaku: {{ $pengumuman->tanggal_berlaku ? $pengumuman->tanggal_berlaku->format('d/m/Y') : 'Selamanya' }}</span>
                        </div>
                    </div>
                @empty
                    <div class="p-4 text-center text-sm text-gray-500 dark:text-gray-400">
                        Tidak ada data pengumuman
                    </div>
                @endforelse
            </div>

            <!-- Table - Desktop View -->
            <div class="hidden sm:block bg-white dark:bg-gray-800 shadow overflow-hidden rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Judul Pengumuman
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Target
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Dibuat Oleh
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Berlaku Sampai
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($pengumumen as $pengumuman)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $pengumuman->judul }}
                                            @if($pengumuman->is_urgent)
                                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                                    <i class="fas fa-exclamation-triangle mr-1"></i> Mendesak
                                                </span>
                                            @endif
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                            {{ \Illuminate\Support\Str::limit(strip_tags($pengumuman->isi), 80) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            {{ $pengumuman->target === 'semua' ? 'bg-purple-100 text-purple-800' : 
                                               ($pengumuman->target === 'guru' ? 'bg-blue-100 text-blue-800' : 
                                               ($pengumuman->target === 'siswa' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800')) }}">
                                            {{ ucfirst($pengumuman->target) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        @if($pengumuman->status === 'mendesak')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Mendesak
                                            </span>
                                        @elseif($pengumuman->status === 'aktif')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Aktif
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                Kadaluarsa
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ $pengumuman->user->name ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ $pengumuman->tanggal_berlaku ? $pengumuman->tanggal_berlaku->format('d/m/Y') : 'Selamanya' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button wire:click="edit({{ $pengumuman->id }})" 
                                                type="button"
                                                class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 mr-3">
                                            Edit
                                        </button>
                                        <button wire:click="delete({{ $pengumuman->id }})" 
                                                wire:confirm="Apakah Anda yakin ingin menghapus pengumuman ini?"
                                                type="button"
                                                class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                            Hapus
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                        Tidak ada data pengumuman
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="bg-white dark:bg-gray-800 px-4 py-3 border-t border-gray-200 dark:border-gray-700 sm:px-6">
                    {{ $pengumumen->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Form -->
    @if($showModal)
        <div class="fixed inset-0 overflow-y-auto z-50">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
                     wire:click="$set('showModal', false)"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full sm:p-6">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">
                            {{ $modalTitle }}
                        </h3>
                        
                        <form wire:submit.prevent="save">
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label for="judul" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Judul Pengumuman *
                                    </label>
                                    <input type="text" 
                                           wire:model="judul"
                                           id="judul"
                                           class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm"
                                           placeholder="Masukkan judul pengumuman">
                                    @error('judul') 
                                        <span class="text-red-500 text-xs">{{ $message }}</span> 
                                    @enderror
                                </div>

                                <div>
                                    <label for="isi" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Isi Pengumuman *
                                    </label>
                                    <textarea wire:model="isi"
                                              id="isi"
                                              rows="5"
                                              class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm"
                                              placeholder="Tulis isi pengumuman di sini..."></textarea>
                                    @error('isi') 
                                        <span class="text-red-500 text-xs">{{ $message }}</span> 
                                    @enderror
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="target" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Target Pengumuman *
                                        </label>
                                        <select wire:model="target"
                                                id="target"
                                                class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm">
                                            <option value="semua">Semua User</option>
                                            <option value="admin">Admin Saja</option>
                                            <option value="guru">Guru Saja</option>
                                            <option value="siswa">Siswa Saja</option>
                                        </select>
                                        @error('target') 
                                            <span class="text-red-500 text-xs">{{ $message }}</span> 
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="tanggal_berlaku" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Berlaku Sampai
                                        </label>
                                        <input type="date" 
                                               wire:model="tanggal_berlaku"
                                               id="tanggal_berlaku"
                                               min="{{ date('Y-m-d') }}"
                                               class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm">
                                        @error('tanggal_berlaku') 
                                            <span class="text-red-500 text-xs">{{ $message }}</span> 
                                        @enderror
                                        <p class="text-xs text-gray-500 mt-1">Kosongkan jika berlaku selamanya</p>
                                    </div>
                                </div>

                                <div class="flex items-center">
                                    <input wire:model="is_urgent" 
                                           id="is_urgent"
                                           type="checkbox" 
                                           class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                    <label for="is_urgent" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                                        Tandai sebagai pengumuman mendesak
                                    </label>
                                </div>

                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 dark:bg-yellow-800/10 dark:border-yellow-900">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-400">
                                                Informasi Penting
                                            </h3>
                                            <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                                                <p>• Pengumuman mendesak akan ditampilkan dengan warna merah</p>
                                                <p>• Target pengumuman menentukan siapa yang dapat melihat</p>
                                                <p>• Tanggal berlaku kosong = pengumuman aktif selamanya</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                        <button wire:click="$set('showModal', false)" 
                                type="button"
                                class="w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:col-start-1 sm:text-sm">
                            Batal
                        </button>
                        <button wire:click="save"
                                type="button"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:col-start-2 sm:text-sm">
                            Simpan Pengumuman
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>