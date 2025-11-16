<div>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="md:flex md:items-center md:justify-between mb-6">
                <div class="flex-1 min-w-0">
                    <h2 class="text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:text-3xl sm:truncate">
                        Manajemen Ekstrakurikuler
                    </h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Kelola data kegiatan ekstrakurikuler sekolah
                    </p>
                </div>
                <div class="mt-4 flex md:mt-0 md:ml-4">
                    <button wire:click="create" 
                            class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Tambah Ekstrakurikuler
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
                               wire:model.debounce.300ms="search"
                               placeholder="Cari ekstrakurikuler..."
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    </div>
                    <div class="sm:w-48">
                        <select wire:model="perPage" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            <option value="10">10 per halaman</option>
                            <option value="25">25 per halaman</option>
                            <option value="50">50 per halaman</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Card Layout for Mobile -->
            <div class="md:hidden bg-white dark:bg-gray-800 shadow overflow-hidden rounded-lg mb-6">
                <div class="p-4">
                    @forelse($ekstrakurikulers as $ekstra)
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-4 last:border-0 last:pb-0 last:mb-0">
                            <!-- Header with Nama and Status -->
                            <div class="flex justify-between items-start mb-3">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ $ekstra->nama_ekstra }}</h3>
                                @if($ekstra->status)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">
                                        Nonaktif
                                    </span>
                                @endif
                            </div>
                            
                            <!-- Description -->
                            @if($ekstra->deskripsi)
                                <div class="mb-3 text-sm text-gray-600 dark:text-gray-400">
                                    {{ \Illuminate\Support\Str::limit($ekstra->deskripsi, 100) }}
                                </div>
                            @endif
                            
                            <!-- Details Grid -->
                            <div class="grid grid-cols-2 gap-2 mb-3 text-sm">
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Pembina</p>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $ekstra->pembina }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Tempat</p>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $ekstra->tempat }}</p>
                                </div>
                                <div class="col-span-2">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Jadwal</p>
                                    <p class="font-medium text-gray-900 dark:text-white">
                                        {{ $ekstra->hari }}, {{ optional($ekstra->jam_mulai)->format('H:i') ?? 'N/A' }} - {{ optional($ekstra->jam_selesai)->format('H:i') ?? 'N/A' }}
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Actions -->
                            <div class="flex gap-2">
                                <button wire:click="edit({{ $ekstra->id }})" 
                                        class="flex-1 bg-blue-50 text-blue-600 hover:bg-blue-100 dark:bg-blue-900 dark:text-blue-400 dark:hover:bg-blue-800 px-3 py-2 rounded-md text-sm font-medium text-center transition-colors">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Edit
                                </button>
                                <button wire:click="delete({{ $ekstra->id }})" 
                                        wire:confirm="Apakah Anda yakin ingin menghapus ekstrakurikuler ini?"
                                        class="flex-1 bg-red-50 text-red-600 hover:bg-red-100 dark:bg-red-900 dark:text-red-400 dark:hover:bg-red-800 px-3 py-2 rounded-md text-sm font-medium text-center transition-colors">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Hapus
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332-.477 4.5-1.253M13 12.253v13m0-13C13 5.477 14.168 5 15.5 5c1.332 0 2.5.477 2.5 1.253v13M16 12.253v13m0-13C16 5.477 17.168 5 18.5 5c1.332 0 2.5.477 2.5 1.253v13"></path>
                            </svg>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Tidak ada data ekstrakurikuler</p>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination for Mobile -->
                <div class="bg-white dark:bg-gray-800 px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                    {{ $ekstrakurikulers->links() }}
                </div>
            </div>

            <!-- Table Layout for Desktop -->
            <div class="hidden md:block bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Nama Ekstrakurikuler
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Pembina
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Jadwal
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Tempat
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($ekstrakurikulers as $ekstra)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $ekstra->nama_ekstra }}
                                    </div>
                                    @if($ekstra->deskripsi)
                                    <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                        {{ \Illuminate\Support\Str::limit($ekstra->deskripsi, 50) }}
                                    </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ $ekstra->pembina }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    <div class="font-medium">{{ $ekstra->hari }}</div>
                                    <div class="text-xs text-gray-400">
                                        {{ optional($ekstra->jam_mulai)->format('H:i') ?? 'N/A' }} - 
                                        {{ optional($ekstra->jam_selesai)->format('H:i') ?? 'N/A' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ $ekstra->tempat }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    @if($ekstra->status)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Aktif
                                    </span>
                                    @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        Nonaktif
                                    </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button wire:click="edit({{ $ekstra->id }})" 
                                            class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 mr-3">
                                        Edit
                                    </button>
                                    <button wire:click="delete({{ $ekstra->id }})" 
                                            wire:confirm="Apakah Anda yakin ingin menghapus ekstrakurikuler ini?"
                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                    Tidak ada data ekstrakurikuler
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination for Desktop -->
                <div class="bg-white dark:bg-gray-800 px-4 py-3 border-t border-gray-200 dark:border-gray-700 sm:px-6">
                    {{ $ekstrakurikulers->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Form -->
    @if($showModal)
    <div class="fixed inset-0 overflow-y-auto z-50">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">
                        {{ $modalTitle }}
                    </h3>
                    
                    <form wire:submit.prevent="save">
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label for="nama_ekstra" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Nama Ekstrakurikuler
                                </label>
                                <input type="text" 
                                       wire:model="nama_ekstra"
                                       id="nama_ekstra"
                                       class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm"
                                       placeholder="Contoh: Pramuka, Basket, Musik">
                                @error('nama_ekstra') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="deskripsi" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Deskripsi
                                </label>
                                <textarea wire:model="deskripsi"
                                          id="deskripsi"
                                          rows="3"
                                          class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm"
                                          placeholder="Deskripsi kegiatan ekstrakurikuler..."></textarea>
                                @error('deskripsi') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="pembina" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Pembina
                                </label>
                                <input type="text" 
                                       wire:model="pembina"
                                       id="pembina"
                                       class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm"
                                       placeholder="Nama pembina ekstrakurikuler">
                                @error('pembina') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label for="hari" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Hari
                                    </label>
                                    <select wire:model="hari"
                                            id="hari"
                                            class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm">
                                        <option value="">Pilih Hari</option>
                                        <option value="Senin">Senin</option>
                                        <option value="Selasa">Selasa</option>
                                        <option value="Rabu">Rabu</option>
                                        <option value="Kamis">Kamis</option>
                                        <option value="Jumat">Jumat</option>
                                        <option value="Sabtu">Sabtu</option>
                                        <option value="Minggu">Minggu</option>
                                    </select>
                                    @error('hari') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label for="jam_mulai" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Jam Mulai
                                    </label>
                                    <input type="time" 
                                           wire:model="jam_mulai"
                                           id="jam_mulai"
                                           class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm">
                                    @error('jam_mulai') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label for="jam_selesai" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Jam Selesai
                                    </label>
                                    <input type="time" 
                                           wire:model="jam_selesai"
                                           id="jam_selesai"
                                           class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm">
                                    @error('jam_selesai') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div>
                                <label for="tempat" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Tempat
                                </label>
                                <input type="text" 
                                       wire:model="tempat"
                                       id="tempat"
                                       class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm"
                                       placeholder="Contoh: Lapangan Basket, Lab Musik">
                                @error('tempat') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="flex items-center">
                                <input wire:model="status" 
                                       id="status"
                                       type="checkbox" 
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="status" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                                    Ekstrakurikuler aktif
                                </label>
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
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>