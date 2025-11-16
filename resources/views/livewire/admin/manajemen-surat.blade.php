<div class="p-4 sm:p-6">
    <!-- Header dan Tombol -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">Manajemen Surat</h1>
        <button wire:click="create" class="w-full sm:w-auto bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition text-sm sm:text-base flex items-center justify-center">
            <i class="fas fa-plus mr-2"></i>Tambah Surat
        </button>
    </div>

    <!-- Statistik -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 sm:p-4">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400 truncate">Surat Masuk</p>
                    <p class="text-lg sm:text-xl font-bold text-blue-600 dark:text-blue-400">{{ $suratMasuk }}</p>
                </div>
                <div class="flex-shrink-0 ml-2">
                    <i class="fas fa-envelope text-blue-600 dark:text-blue-400 text-sm sm:text-base"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 sm:p-4">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400 truncate">Surat Keluar</p>
                    <p class="text-lg sm:text-xl font-bold text-green-600 dark:text-green-400">{{ $suratKeluar }}</p>
                </div>
                <div class="flex-shrink-0 ml-2">
                    <i class="fas fa-paper-plane text-green-600 dark:text-green-400 text-sm sm:text-base"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 sm:p-4">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400 truncate">Perlu Tindakan</p>
                    <p class="text-lg sm:text-xl font-bold text-yellow-600 dark:text-yellow-400">{{ $perluTindakan }}</p>
                </div>
                <div class="flex-shrink-0 ml-2">
                    <i class="fas fa-clock text-yellow-600 dark:text-yellow-400 text-sm sm:text-base"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 sm:p-4">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400 truncate">Diarsipkan</p>
                    <p class="text-lg sm:text-xl font-bold text-purple-600 dark:text-purple-400">{{ $diarsipkan }}</p>
                </div>
                <div class="flex-shrink-0 ml-2">
                    <i class="fas fa-archive text-purple-600 dark:text-purple-400 text-sm sm:text-base"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Search dan Filter -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
        <div class="p-3 sm:p-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1">
                    <input 
                        type="text" 
                        wire:model.live="search"
                        placeholder="Cari nomor surat, perihal, pengirim..." 
                        class="w-full px-3 py-2 text-xs sm:text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                </div>
                <div class="w-full sm:w-32">
                    <select 
                        wire:model.live="perPage"
                        class="w-full px-3 py-2 text-xs sm:text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                        <option value="5">5 data</option>
                        <option value="10">10 data</option>
                        <option value="25">25 data</option>
                        <option value="50">50 data</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Surat - Desktop -->
    <div class="hidden lg:block bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full min-w-full">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nomor Surat</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Perihal</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jenis</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                    @forelse($surat as $item)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <td class="px-4 py-4">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $item->nomor_surat }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $item->jenis_surat_formatted }}
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <div class="text-sm font-medium text-gray-900 dark:text-white line-clamp-1">{{ $item->perihal }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 line-clamp-1">
                                @if($item->jenis_surat == 'masuk')
                                    Dari: {{ $item->pengirim }}
                                @else
                                    Kepada: {{ $item->penerima }}
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                @if($item->jenis_surat == 'masuk') bg-blue-100 text-blue-800
                                @else bg-green-100 text-green-800 @endif">
                                {{ $item->jenis_surat_formatted }}
                            </span>
                        </td>
                        <td class="px-4 py-4">
                            <div class="text-sm text-gray-900 dark:text-white">{{ $item->tanggal_surat->translatedFormat('d/m/Y') }}</div>
                            @if($item->tanggal_terima)
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    Terima: {{ $item->tanggal_terima->translatedFormat('d/m/Y') }}
                                </div>
                            @endif
                        </td>
                        <td class="px-4 py-4">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                @if($item->status === 'baru') bg-yellow-100 text-yellow-800
                                @elseif($item->status === 'diproses') bg-blue-100 text-blue-800
                                @elseif($item->status === 'selesai') bg-green-100 text-green-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ $item->status_formatted }}
                            </span>
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex space-x-1">
                                <button wire:click="showDetail({{ $item->id }})" class="p-1 text-blue-600 hover:text-blue-900 transition-colors" title="Detail">
                                    <i class="fas fa-eye text-xs"></i>
                                </button>
                                <button wire:click="edit({{ $item->id }})" class="p-1 text-green-600 hover:text-green-900 transition-colors" title="Edit">
                                    <i class="fas fa-edit text-xs"></i>
                                </button>
                                <button wire:click="updateStatus({{ $item->id }}, '{{ $item->status === 'selesai' ? 'arsip' : ($item->status === 'diproses' ? 'selesai' : 'diproses') }}')" class="p-1 text-orange-600 hover:text-orange-900 transition-colors" title="Update Status">
                                    <i class="fas fa-sync-alt text-xs"></i>
                                </button>
                                <button wire:click="delete({{ $item->id }})" onclick="return confirm('Hapus surat ini?')" class="p-1 text-red-600 hover:text-red-900 transition-colors" title="Hapus">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-envelope-open text-2xl sm:text-3xl mb-2"></i>
                                <p class="text-sm sm:text-base">Tidak ada data surat</p>
                                <p class="text-xs sm:text-sm mt-1">Klik tombol "Tambah Surat" untuk menambahkan surat baru</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Desktop -->
        @if($surat->hasPages())
        <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
            {{ $surat->links() }}
        </div>
        @endif
    </div>

    <!-- Card Surat - Mobile -->
    <div class="lg:hidden space-y-3">
        @forelse($surat as $item)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="space-y-3">
                <!-- Header -->
                <div class="flex justify-between items-start">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-xs font-medium text-gray-900 dark:text-white truncate">
                                {{ $item->nomor_surat }}
                            </span>
                            <span class="flex-shrink-0 px-2 py-0.5 text-xs font-semibold rounded-full 
                                @if($item->jenis_surat == 'masuk') bg-blue-100 text-blue-800
                                @else bg-green-100 text-green-800 @endif">
                                {{ $item->jenis_surat == 'masuk' ? 'Masuk' : 'Keluar' }}
                            </span>
                        </div>
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white line-clamp-2">
                            {{ $item->perihal }}
                        </h3>
                    </div>
                </div>

                <!-- Info -->
                <div class="grid grid-cols-2 gap-2 text-xs">
                    <div>
                        <div class="text-gray-500 dark:text-gray-400">Pengirim/Penerima</div>
                        <div class="text-gray-900 dark:text-white font-medium truncate">
                            @if($item->jenis_surat == 'masuk')
                                {{ $item->pengirim }}
                            @else
                                {{ $item->penerima }}
                            @endif
                        </div>
                    </div>
                    <div>
                        <div class="text-gray-500 dark:text-gray-400">Tanggal</div>
                        <div class="text-gray-900 dark:text-white font-medium">
                            {{ $item->tanggal_surat->translatedFormat('d/m/Y') }}
                        </div>
                    </div>
                </div>

                <!-- Status dan Aksi -->
                <div class="flex justify-between items-center pt-2 border-t border-gray-200 dark:border-gray-700">
                    <span class="px-2 py-1 text-xs font-semibold rounded-full 
                        @if($item->status === 'baru') bg-yellow-100 text-yellow-800
                        @elseif($item->status === 'diproses') bg-blue-100 text-blue-800
                        @elseif($item->status === 'selesai') bg-green-100 text-green-800
                        @else bg-gray-100 text-gray-800 @endif">
                        {{ $item->status_formatted }}
                    </span>
                    
                    <div class="flex space-x-2">
                        <button wire:click="showDetail({{ $item->id }})" class="p-1 text-blue-600 hover:text-blue-900" title="Detail">
                            <i class="fas fa-eye text-xs"></i>
                        </button>
                        <button wire:click="edit({{ $item->id }})" class="p-1 text-green-600 hover:text-green-900" title="Edit">
                            <i class="fas fa-edit text-xs"></i>
                        </button>
                        <button wire:click="updateStatus({{ $item->id }}, '{{ $item->status === 'selesai' ? 'arsip' : ($item->status === 'diproses' ? 'selesai' : 'diproses') }}')" class="p-1 text-orange-600 hover:text-orange-900" title="Update Status">
                            <i class="fas fa-sync-alt text-xs"></i>
                        </button>
                        <button wire:click="delete({{ $item->id }})" onclick="return confirm('Hapus surat ini?')" class="p-1 text-red-600 hover:text-red-900" title="Hapus">
                            <i class="fas fa-trash text-xs"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 text-center">
            <div class="text-gray-500 dark:text-gray-400">
                <i class="fas fa-envelope-open text-3xl mb-3"></i>
                <p class="text-sm">Tidak ada data surat</p>
                <p class="text-xs mt-1">Klik tombol "Tambah Surat" untuk menambah</p>
            </div>
        </div>
        @endforelse

        <!-- Pagination Mobile -->
        @if($surat->hasPages())
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            {{ $surat->links() }}
        </div>
        @endif
    </div>

    <!-- Modal Form -->
    @if($showModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-2 sm:p-4 z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-2xl sm:max-w-4xl max-h-[95vh] overflow-y-auto">
            <div class="p-4 sm:p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $modalTitle }}</h3>
                    <button wire:click="$set('showModal', false)" class="text-gray-400 hover:text-gray-600 p-1">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>
                
                <form wire:submit="save">
                    <div class="grid grid-cols-1 gap-3 sm:gap-4">
                        <!-- Jenis Surat dan Nomor Surat -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jenis Surat *</label>
                                <select wire:model="jenis_surat" wire:change="generateNomorSurat" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Pilih Jenis</option>
                                    @foreach($jenisSuratOptions as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('jenis_surat') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nomor Surat *</label>
                                <input type="text" wire:model="nomor_surat" placeholder="Nomor surat..." class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('nomor_surat') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Perihal -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Perihal *</label>
                            <input type="text" wire:model="perihal" placeholder="Perihal surat..." class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('perihal') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Isi Singkat -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Isi Singkat *</label>
                            <textarea wire:model="isi_singkat" rows="2" placeholder="Isi singkat surat..." class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                            @error('isi_singkat') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Pengirim dan Penerima -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    @if($jenis_surat == 'masuk') Pengirim *
                                    @else Penerima
                                    @endif
                                </label>
                                <input type="text" wire:model="{{ $jenis_surat == 'masuk' ? 'pengirim' : 'penerima' }}" 
                                    placeholder="{{ $jenis_surat == 'masuk' ? 'Nama pengirim...' : 'Nama penerima...' }}" 
                                    class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('pengirim') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    @if($jenis_surat == 'masuk') Penerima
                                    @else Pengirim *
                                    @endif
                                </label>
                                <input type="text" wire:model="{{ $jenis_surat == 'masuk' ? 'penerima' : 'pengirim' }}" 
                                    placeholder="{{ $jenis_surat == 'masuk' ? 'Nama penerima...' : 'Nama pengirim...' }}" 
                                    class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('penerima') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Tanggal -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Surat *</label>
                                <input type="date" wire:model="tanggal_surat" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('tanggal_surat') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Terima</label>
                                <input type="date" wire:model="tanggal_terima" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                        </div>

                        <!-- Disposisi dan Status -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Disposisi Ke</label>
                                <select wire:model="disposisi_ke" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Pilih Disposisi</option>
                                    @foreach($disposisiOptions as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status *</label>
                                <select wire:model="status" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    @foreach($statusOptions as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Catatan Disposisi -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Catatan Disposisi</label>
                            <textarea wire:model="catatan_disposisi" rows="2" placeholder="Catatan untuk disposisi..." class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                        </div>

                        <!-- File Surat -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">File Surat</label>
                            <input type="file" wire:model="file_surat" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('file_surat') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            @if($file_surat)
                                <p class="text-xs text-green-600 mt-1">File: {{ $file_surat->getClientOriginalName() }}</p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="mt-6 flex flex-col sm:flex-row gap-2 sm:gap-3">
                        <button type="button" wire:click="$set('showModal', false)" class="flex-1 sm:flex-none px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">Batal</button>
                        <button type="submit" class="flex-1 sm:flex-none px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal Detail -->
    @if($showDetailModal && $selectedSurat)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-2 sm:p-4 z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-sm sm:max-w-2xl max-h-[95vh] overflow-y-auto">
            <div class="p-4 sm:p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Detail Surat</h3>
                    <button wire:click="$set('showDetailModal', false)" class="text-gray-400 hover:text-gray-600 p-1">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>

                <div class="space-y-4">
                    <!-- Header -->
                    <div class="p-3 sm:p-4 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg text-white">
                        <h2 class="text-base sm:text-xl font-bold line-clamp-2">{{ $selectedSurat->perihal }}</h2>
                        <p class="text-blue-100 text-xs sm:text-sm mt-1">{{ $selectedSurat->nomor_surat }}</p>
                    </div>

                    <!-- Info Utama -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                        <div>
                            <div class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">Jenis Surat</div>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                @if($selectedSurat->jenis_surat == 'masuk') bg-blue-100 text-blue-800
                                @else bg-green-100 text-green-800 @endif">
                                {{ $selectedSurat->jenis_surat_formatted }}
                            </span>
                        </div>
                        <div>
                            <div class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">Status</div>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                @if($selectedSurat->status === 'baru') bg-yellow-100 text-yellow-800
                                @elseif($selectedSurat->status === 'diproses') bg-blue-100 text-blue-800
                                @elseif($selectedSurat->status === 'selesai') bg-green-100 text-green-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ $selectedSurat->status_formatted }}
                            </span>
                        </div>
                    </div>

                    <!-- Pengirim dan Penerima -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                        <div>
                            <div class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">
                                @if($selectedSurat->jenis_surat == 'masuk') Pengirim
                                @else Penerima
                                @endif
                            </div>
                            <div class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                {{ $selectedSurat->jenis_surat == 'masuk' ? $selectedSurat->pengirim : $selectedSurat->penerima }}
                            </div>
                        </div>
                        <div>
                            <div class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">
                                @if($selectedSurat->jenis_surat == 'masuk') Penerima
                                @else Pengirim
                                @endif
                            </div>
                            <div class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                {{ $selectedSurat->jenis_surat == 'masuk' ? ($selectedSurat->penerima ?? '-') : $selectedSurat->pengirim }}
                            </div>
                        </div>
                    </div>

                    <!-- Tanggal -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                        <div>
                            <div class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">Tanggal Surat</div>
                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $selectedSurat->tanggal_surat->translatedFormat('d F Y') }}
                            </div>
                        </div>
                        @if($selectedSurat->tanggal_terima)
                        <div>
                            <div class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">Tanggal Terima</div>
                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $selectedSurat->tanggal_terima->translatedFormat('d F Y') }}
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Isi Singkat -->
                    <div>
                        <div class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mb-2">Isi Singkat</div>
                        <div class="text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                            {{ $selectedSurat->isi_singkat }}
                        </div>
                    </div>

                    <!-- Disposisi -->
                    @if($selectedSurat->disposisi_ke || $selectedSurat->catatan_disposisi)
                    <div>
                        <div class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mb-2">Disposisi</div>
                        <div class="text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                            @if($selectedSurat->disposisi_ke)
                                <div class="font-medium">Disposisi ke: {{ $selectedSurat->disposisi_formatted }}</div>
                            @endif
                            @if($selectedSurat->catatan_disposisi)
                                <div class="mt-2 text-xs sm:text-sm">{{ $selectedSurat->catatan_disposisi }}</div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- File Surat -->
                    @if($selectedSurat->has_file)
                    <div>
                        <div class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mb-2">File Surat</div>
                        <div class="text-sm text-gray-900 dark:text-white">
                            <a href="{{ $selectedSurat->file_url }}" target="_blank" class="text-blue-600 hover:text-blue-800 flex items-center text-xs sm:text-sm">
                                <i class="fas fa-file-download mr-2"></i>
                                Download File
                            </a>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="mt-6 flex flex-col sm:flex-row gap-2 sm:gap-3">
                    <button wire:click="$set('showDetailModal', false)" class="flex-1 sm:flex-none px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                        Tutup
                    </button>
                    <button wire:click="edit({{ $selectedSurat->id }})" class="flex-1 sm:flex-none px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-edit mr-1"></i>Edit
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
             class="fixed top-4 right-4 bg-green-500 text-white px-4 py-3 rounded-lg shadow-lg z-50 max-w-sm">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <span class="text-sm">{{ session('success') }}</span>
            </div>
        </div>
    @endif
</div>

