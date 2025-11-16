<div class="p-4 sm:p-6">
    <!-- Header dan Tombol -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">Manajemen Agenda Sekolah</h1>
        <button wire:click="create" class="w-full sm:w-auto bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition text-sm sm:text-base">
            <i class="fas fa-plus mr-2"></i>Tambah Agenda
        </button>
    </div>

    <!-- Statistik -->
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-3 sm:gap-6 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm sm:text-lg font-semibold text-gray-900 dark:text-white">Terjadwal</h3>
                    <p class="text-xl sm:text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $terjadwal }}</p>
                </div>
                <i class="fas fa-calendar-alt text-blue-600 dark:text-blue-400 text-lg sm:text-2xl"></i>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm sm:text-lg font-semibold text-gray-900 dark:text-white">Berlangsung</h3>
                    <p class="text-xl sm:text-2xl font-bold text-green-600 dark:text-green-400">{{ $berlangsung }}</p>
                </div>
                <i class="fas fa-play text-green-600 dark:text-green-400 text-lg sm:text-2xl"></i>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm sm:text-lg font-semibold text-gray-900 dark:text-white">Selesai</h3>
                    <p class="text-xl sm:text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $selesai }}</p>
                </div>
                <i class="fas fa-check text-purple-600 dark:text-purple-400 text-lg sm:text-2xl"></i>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm sm:text-lg font-semibold text-gray-900 dark:text-white">Dibatalkan</h3>
                    <p class="text-xl sm:text-2xl font-bold text-red-600 dark:text-red-400">{{ $dibatalkan }}</p>
                </div>
                <i class="fas fa-times text-red-600 dark:text-red-400 text-lg sm:text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Search dan Filter -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex flex-col gap-3">
                <div class="flex-1">
                    <input 
                        type="text" 
                        wire:model.live="search"
                        placeholder="Cari agenda, tempat, atau deskripsi..." 
                        class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                </div>
                <div class="w-full sm:w-48">
                    <select 
                        wire:model.live="perPage"
                        class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                        <option value="5">5 per halaman</option>
                        <option value="10">10 per halaman</option>
                        <option value="25">25 per halaman</option>
                        <option value="50">50 per halaman</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Agenda - Desktop View -->
    <div class="hidden sm:block bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Judul Agenda</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tempat</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Sasaran</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                    @forelse($agenda as $item)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-4 py-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $item->judul_agenda }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $item->jenis_agenda_formatted }}</div>
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-900 dark:text-white">
                                <div>{{ $item->tanggal_mulai->translatedFormat('d M Y') }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    @if($item->waktu_mulai && $item->waktu_selesai)
                                        {{ $item->waktu_mulai }} - {{ $item->waktu_selesai }}
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-900 dark:text-white">{{ $item->tempat }}</td>
                            <td class="px-4 py-4 text-sm text-gray-900 dark:text-white">{{ $item->sasaran_formatted }}</td>
                            <td class="px-4 py-4">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    @if($item->status === 'terjadwal') bg-blue-100 text-blue-800
                                    @elseif($item->status === 'berlangsung') bg-green-100 text-green-800
                                    @elseif($item->status === 'selesai') bg-purple-100 text-purple-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ $statusOptions[$item->status] }}
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex space-x-2">
                                    <button wire:click="showDetail({{ $item->id }})" class="text-blue-600 hover:text-blue-900 text-sm">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button wire:click="edit({{ $item->id }})" class="text-green-600 hover:text-green-900 text-sm">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button wire:click="updateStatus({{ $item->id }}, '{{ $item->status === 'berlangsung' ? 'selesai' : ($item->status === 'terjadwal' ? 'berlangsung' : 'terjadwal') }}')" class="text-orange-600 hover:text-orange-900 text-sm">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                    <button wire:click="delete({{ $item->id }})" onclick="return confirm('Apakah Anda yakin ingin menghapus agenda ini?')" class="text-red-600 hover:text-red-900 text-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-calendar-times text-3xl mb-2"></i>
                                    <p>Tidak ada data agenda</p>
                                    <p class="text-sm mt-1">Klik tombol "Tambah Agenda" untuk menambahkan agenda baru</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($agenda->hasPages())
            <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                {{ $agenda->links() }}
            </div>
        @endif
    </div>

    <!-- Mobile Card View -->
    <div class="sm:hidden space-y-4">
        @forelse($agenda as $item)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <!-- Header Card -->
                <div class="flex justify-between items-start mb-3">
                    <div class="flex-1">
                        <h3 class="text-base font-semibold text-gray-900 dark:text-white">{{ $item->judul_agenda }}</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $item->jenis_agenda_formatted }}</p>
                    </div>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full 
                        @if($item->status === 'terjadwal') bg-blue-100 text-blue-800
                        @elseif($item->status === 'berlangsung') bg-green-100 text-green-800
                        @elseif($item->status === 'selesai') bg-purple-100 text-purple-800
                        @else bg-red-100 text-red-800 @endif">
                        {{ $statusOptions[$item->status] }}
                    </span>
                </div>

                <!-- Detail Card -->
                <div class="grid grid-cols-2 gap-3 mb-3 text-sm">
                    <div>
                        <span class="text-gray-500 dark:text-gray-400">Tanggal:</span>
                        <div class="font-medium text-gray-900 dark:text-white">
                            {{ $item->tanggal_mulai->translatedFormat('d M Y') }}
                            @if($item->tanggal_mulai != $item->tanggal_selesai)
                                - {{ $item->tanggal_selesai->translatedFormat('d M Y') }}
                            @endif
                        </div>
                    </div>
                    <div>
                        <span class="text-gray-500 dark:text-gray-400">Waktu:</span>
                        <div class="font-medium text-gray-900 dark:text-white">
                            @if($item->waktu_mulai && $item->waktu_selesai)
                                {{ $item->waktu_mulai }} - {{ $item->waktu_selesai }}
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <span class="text-gray-500 dark:text-gray-400">Tempat:</span>
                        <div class="font-medium text-gray-900 dark:text-white">{{ $item->tempat }}</div>
                    </div>
                    <div>
                        <span class="text-gray-500 dark:text-gray-400">Sasaran:</span>
                        <div class="font-medium text-gray-900 dark:text-white">{{ $item->sasaran_formatted }}</div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-end space-x-2 pt-3 border-t border-gray-200 dark:border-gray-600">
                    <button wire:click="showDetail({{ $item->id }})" class="px-3 py-1 bg-blue-50 text-blue-600 rounded text-sm">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button wire:click="edit({{ $item->id }})" class="px-3 py-1 bg-green-50 text-green-600 rounded text-sm">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button wire:click="updateStatus({{ $item->id }}, '{{ $item->status === 'berlangsung' ? 'selesai' : ($item->status === 'terjadwal' ? 'berlangsung' : 'terjadwal') }}')" class="px-3 py-1 bg-orange-50 text-orange-600 rounded text-sm">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                    <button wire:click="delete({{ $item->id }})" onclick="return confirm('Apakah Anda yakin ingin menghapus agenda ini?')" class="px-3 py-1 bg-red-50 text-red-600 rounded text-sm">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        @empty
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 text-center">
                <i class="fas fa-calendar-times text-3xl mb-2 text-gray-400 dark:text-gray-500"></i>
                <p class="text-gray-500 dark:text-gray-400">Tidak ada data agenda</p>
                <p class="text-sm mt-1 text-gray-400 dark:text-gray-500">Klik tombol "Tambah Agenda" untuk menambahkan agenda baru</p>
            </div>
        @endforelse

        <!-- Pagination for Mobile -->
        @if($agenda->hasPages())
            <div class="mt-4">
                {{ $agenda->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Form -->
    @if($showModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-4xl max-h-[90vh] overflow-y-auto">
                <div class="p-4 sm:p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ $modalTitle }}</h3>
                    
                    <form wire:submit="save">
                        <div class="grid grid-cols-1 gap-4">
                            <!-- Judul Agenda -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Judul Agenda *</label>
                                <input type="text" wire:model="judul_agenda" placeholder="Judul agenda..." class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('judul_agenda') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Deskripsi -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deskripsi *</label>
                                <textarea wire:model="deskripsi" rows="3" placeholder="Deskripsi lengkap agenda..." class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                                @error('deskripsi') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Tanggal dan Waktu -->
                            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Mulai *</label>
                                    <input type="date" wire:model="tanggal_mulai" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    @error('tanggal_mulai') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Selesai *</label>
                                    <input type="date" wire:model="tanggal_selesai" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    @error('tanggal_selesai') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Waktu Mulai</label>
                                    <input type="time" wire:model="waktu_mulai" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Waktu Selesai</label>
                                    <input type="time" wire:model="waktu_selesai" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                            </div>

                            <!-- Tempat dan Penanggung Jawab -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tempat *</label>
                                    <input type="text" wire:model="tempat" placeholder="Tempat pelaksanaan..." class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    @error('tempat') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Penanggung Jawab *</label>
                                    <input type="text" wire:model="penanggung_jawab" placeholder="Nama penanggung jawab..." class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    @error('penanggung_jawab') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Sasaran dan Jenis Agenda -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sasaran *</label>
                                    <select wire:model="sasaran" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="">Pilih Sasaran</option>
                                        @foreach($sasaranOptions as $value => $label)
                                            <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('sasaran') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jenis Agenda *</label>
                                    <select wire:model="jenis_agenda" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="">Pilih Jenis</option>
                                        @foreach($jenisAgendaOptions as $value => $label)
                                            <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('jenis_agenda') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Status dan Dokumentasi -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status *</label>
                                    <select wire:model="status" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        @foreach($statusOptions as $value => $label)
                                            <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Dokumentasi</label>
                                    <input type="file" wire:model="dokumentasi" accept="image/*" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    @error('dokumentasi') <span class="text-red-500 text-xs">{{ $message }}</span> @endif
                                    @if($dokumentasi)
                                        <p class="text-xs text-green-600 mt-1">File terpilih: {{ $dokumentasi->getClientOriginalName() }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-6 flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-3">
                            <button type="button" wire:click="$set('showModal', false)" class="w-full sm:w-auto px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">Batal</button>
                            <button type="submit" class="w-full sm:w-auto px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Detail -->
    @if($showDetailModal && $selectedAgenda)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                <div class="p-4 sm:p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Detail Agenda</h3>
                        <button wire:click="$set('showDetailModal', false)" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <div class="space-y-4">
                        <!-- Header -->
                        <div class="p-4 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg text-white">
                            <h2 class="text-xl font-bold">{{ $selectedAgenda->judul_agenda }}</h2>
                            <p class="text-blue-100">{{ $selectedAgenda->jenis_agenda_formatted }}</p>
                        </div>

                        <!-- Info Utama -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">Status</div>
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($selectedAgenda->status === 'terjadwal') bg-blue-100 text-blue-800
                                    @elseif($selectedAgenda->status === 'berlangsung') bg-green-100 text-green-800
                                    @elseif($selectedAgenda->status === 'selesai') bg-purple-100 text-purple-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ $statusOptions[$selectedAgenda->status] }}
                                </span>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">Sasaran</div>
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $selectedAgenda->sasaran_formatted }}
                                </div>
                            </div>
                        </div>

                        <!-- Waktu dan Tempat -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">Tanggal</div>
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $selectedAgenda->tanggal_mulai->translatedFormat('d M Y') }} 
                                    @if($selectedAgenda->tanggal_mulai != $selectedAgenda->tanggal_selesai)
                                        - {{ $selectedAgenda->tanggal_selesai->translatedFormat('d M Y') }}
                                    @endif
                                </div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">Waktu</div>
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    @if($selectedAgenda->waktu_mulai && $selectedAgenda->waktu_selesai)
                                        {{ $selectedAgenda->waktu_mulai }} - {{ $selectedAgenda->waktu_selesai }}
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">Tempat</div>
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $selectedAgenda->tempat }}
                                </div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">Penanggung Jawab</div>
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $selectedAgenda->penanggung_jawab }}
                                </div>
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div>
                            <div class="text-sm text-gray-500 dark:text-gray-400 mb-2">Deskripsi</div>
                            <div class="text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                                {{ $selectedAgenda->deskripsi }}
                            </div>
                        </div>

                        <!-- Dokumentasi -->
                        @if($selectedAgenda->dokumentasi)
                            <div>
                                <div class="text-sm text-gray-500 dark:text-gray-400 mb-2">Dokumentasi</div>
                                <div class="text-sm text-gray-900 dark:text-white">
                                    <img src="{{ asset('storage/' . $selectedAgenda->dokumentasi) }}" alt="Dokumentasi" class="w-full max-w-xs rounded-lg shadow">
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="mt-6 flex justify-end space-x-3">
                        <button wire:click="$set('showDetailModal', false)" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                            Tutup
                        </button>
                        <button wire:click="edit({{ $selectedAgenda->id }})" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">
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
             class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif
</div>