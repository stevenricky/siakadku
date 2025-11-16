<div class="p-4 sm:p-6">
    <!-- Header dan Tombol Tambah -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">Jadwal Konseling</h1>
        <button wire:click="create" class="w-full sm:w-auto bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition text-sm sm:text-base">
            <i class="fas fa-plus mr-2"></i>Jadwalkan Konseling
        </button>
    </div>

    <!-- Statistik -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 sm:p-4">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                    <i class="fas fa-calendar-alt text-blue-600 dark:text-blue-400 text-sm sm:text-base"></i>
                </div>
                <div class="ml-3">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400">Terjadwal</p>
                    <p class="text-lg sm:text-2xl font-bold text-gray-900 dark:text-white">{{ $terjadwal }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 sm:p-4">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 dark:bg-green-900 rounded-lg">
                    <i class="fas fa-check text-green-600 dark:text-green-400 text-sm sm:text-base"></i>
                </div>
                <div class="ml-3">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400">Selesai</p>
                    <p class="text-lg sm:text-2xl font-bold text-gray-900 dark:text-white">{{ $selesai }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 sm:p-4">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 dark:bg-yellow-900 rounded-lg">
                    <i class="fas fa-clock text-yellow-600 dark:text-yellow-400 text-sm sm:text-base"></i>
                </div>
                <div class="ml-3">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400">Berlangsung</p>
                    <p class="text-lg sm:text-2xl font-bold text-gray-900 dark:text-white">{{ $berlangsung }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 sm:p-4">
            <div class="flex items-center">
                <div class="p-2 bg-red-100 dark:bg-red-900 rounded-lg">
                    <i class="fas fa-times text-red-600 dark:text-red-400 text-sm sm:text-base"></i>
                </div>
                <div class="ml-3">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400">Dibatalkan</p>
                    <p class="text-lg sm:text-2xl font-bold text-gray-900 dark:text-white">{{ $dibatalkan }}</p>
                </div>
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
                        placeholder="Cari siswa, guru BK, atau tempat..." 
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

    <!-- Tabel Data - Desktop -->
    <div class="hidden sm:block bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal & Waktu</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Siswa</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Guru BK</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Layanan</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($konseling as $item)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $item->tanggal_konseling->translatedFormat('d M Y') }}
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $item->waktu_mulai }} - {{ $item->waktu_selesai }}
                            </div>
                            <div class="text-xs text-gray-400">{{ $item->tempat }}</div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-8 w-8 flex-shrink-0 bg-gray-200 dark:bg-gray-600 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-gray-500 dark:text-gray-400 text-sm"></i>
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $item->siswa->nama }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $item->siswa->kelas->nama_kelas ?? '-' }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
    {{ $item->guruBk->nama ?? 'Tidak ditemukan' }}
</td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-white">
                                {{ $item->layananBk->nama_layanan }}
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 capitalize">
                                {{ $item->layananBk->jenis_layanan }}
                            </div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            @php
                                $statusColors = [
                                    'terjadwal' => 'bg-blue-100 text-blue-800',
                                    'selesai' => 'bg-green-100 text-green-800', 
                                    'dibatalkan' => 'bg-red-100 text-red-800'
                                ];
                            @endphp
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$item->status] }}">
                                {{ $statusOptions[$item->status] ?? $item->status }}
                            </span>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                @if($item->status === 'terjadwal')
                                    <button wire:click="updateStatus({{ $item->id }}, 'selesai')" class="text-green-600 hover:text-green-900" title="Tandai Selesai">
                                        <i class="fas fa-check"></i>
                                    </button>
                                @endif
                                <button wire:click="edit({{ $item->id }})" class="text-blue-600 hover:text-blue-900" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                @if($item->status === 'terjadwal')
                                    <button wire:click="updateStatus({{ $item->id }}, 'dibatalkan')" class="text-red-600 hover:text-red-900" title="Batalkan">
                                        <i class="fas fa-times"></i>
                                    </button>
                                @endif
                                <button wire:click="delete({{ $item->id }})" onclick="return confirm('Apakah Anda yakin ingin menghapus jadwal konseling ini?')" class="text-red-600 hover:text-red-900" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                            Tidak ada data jadwal konseling
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($konseling->hasPages())
        <div class="p-4 border-t border-gray-200 dark:border-gray-700">
            {{ $konseling->links() }}
        </div>
        @endif
    </div>

    <!-- Tabel Data - Mobile -->
    <div class="sm:hidden space-y-4">
        @forelse($konseling as $item)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="space-y-3">
                <!-- Header -->
                <div class="flex justify-between items-start">
                    <div>
                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $item->tanggal_konseling->translatedFormat('d M Y') }}
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">
                            {{ $item->waktu_mulai }} - {{ $item->waktu_selesai }}
                        </div>
                    </div>
                    @php
                        $statusColors = [
                            'terjadwal' => 'bg-blue-100 text-blue-800',
                            'selesai' => 'bg-green-100 text-green-800', 
                            'dibatalkan' => 'bg-red-100 text-red-800'
                        ];
                    @endphp
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$item->status] }}">
                        {{ $statusOptions[$item->status] ?? $item->status }}
                    </span>
                </div>

                <!-- Siswa Info -->
                <div class="flex items-center">
                    <div class="h-8 w-8 flex-shrink-0 bg-gray-200 dark:bg-gray-600 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-gray-500 dark:text-gray-400 text-sm"></i>
                    </div>
                    <div class="ml-3">
                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $item->siswa->nama }}
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">
                            {{ $item->siswa->kelas->nama_kelas ?? '-' }}
                        </div>
                    </div>
                </div>

                <!-- Detail Info -->
                <div class="grid grid-cols-2 gap-2 text-sm">
                    <div>
                        <div class="text-gray-500 dark:text-gray-400">Guru BK</div>
                        <div class="text-gray-900 dark:text-white">{{ $item->guruBk->nama }}</div>
                    </div>
                    <div>
                        <div class="text-gray-500 dark:text-gray-400">Layanan</div>
                        <div class="text-gray-900 dark:text-white">{{ $item->layananBk->nama_layanan }}</div>
                    </div>
                    <div class="col-span-2">
                        <div class="text-gray-500 dark:text-gray-400">Tempat</div>
                        <div class="text-gray-900 dark:text-white">{{ $item->tempat }}</div>
                    </div>
                    <div class="col-span-2">
                        <div class="text-gray-500 dark:text-gray-400">Permasalahan</div>
                        <div class="text-gray-900 dark:text-white text-xs line-clamp-2">{{ Str::limit($item->permasalahan, 80) }}</div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-end space-x-3 pt-2 border-t border-gray-200 dark:border-gray-700">
                    @if($item->status === 'terjadwal')
                        <button wire:click="updateStatus({{ $item->id }}, 'selesai')" class="text-green-600 hover:text-green-900 text-sm" title="Tandai Selesai">
                            <i class="fas fa-check mr-1"></i>Selesai
                        </button>
                    @endif
                    <button wire:click="edit({{ $item->id }})" class="text-blue-600 hover:text-blue-900 text-sm" title="Edit">
                        <i class="fas fa-edit mr-1"></i>Edit
                    </button>
                    @if($item->status === 'terjadwal')
                        <button wire:click="updateStatus({{ $item->id }}, 'dibatalkan')" class="text-red-600 hover:text-red-900 text-sm" title="Batalkan">
                            <i class="fas fa-times mr-1"></i>Batal
                        </button>
                    @endif
                    <button wire:click="delete({{ $item->id }})" onclick="return confirm('Apakah Anda yakin ingin menghapus jadwal konseling ini?')" class="text-red-600 hover:text-red-900 text-sm" title="Hapus">
                        <i class="fas fa-trash mr-1"></i>Hapus
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 text-center">
            <div class="text-gray-500 dark:text-gray-400">
                <i class="fas fa-calendar-times text-3xl mb-3"></i>
                <p>Tidak ada data jadwal konseling</p>
            </div>
        </div>
        @endforelse

        <!-- Pagination Mobile -->
        @if($konseling->hasPages())
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            {{ $konseling->links() }}
        </div>
        @endif
    </div>

    <!-- Modal Form -->
    @if($showModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="p-4 sm:p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ $modalTitle }}</h3>
                
                <form wire:submit="save">
                    <div class="grid grid-cols-1 gap-4">
                        <!-- Siswa -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Siswa *</label>
                            <select wire:model="siswa_id" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Pilih Siswa</option>
                                @foreach($siswa as $s)
                                <option value="{{ $s->id }}">{{ $s->nama }} - {{ $s->kelas->nama_kelas ?? '-' }}</option>
                                @endforeach
                            </select>
                            @error('siswa_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Guru BK -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Guru BK *</label>
                            <select wire:model="guru_id" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Pilih Guru BK</option>
                                @foreach($guruBk as $guru)
                                <option value="{{ $guru->id }}">{{ $guru->nama }}</option>
                                @endforeach
                            </select>
                            @error('guru_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Layanan BK -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Layanan BK *</label>
                            <select wire:model="layanan_bk_id" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Pilih Layanan</option>
                                @foreach($layananBk as $layanan)
                                <option value="{{ $layanan->id }}">{{ $layanan->nama_layanan }}</option>
                                @endforeach
                            </select>
                            @error('layanan_bk_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Tanggal dan Waktu -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="sm:col-span-1">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal *</label>
                                <input type="date" wire:model="tanggal_konseling" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('tanggal_konseling') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="sm:col-span-1">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Waktu Mulai *</label>
                                <input type="time" wire:model="waktu_mulai" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('waktu_mulai') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="sm:col-span-1">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Waktu Selesai *</label>
                                <input type="time" wire:model="waktu_selesai" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('waktu_selesai') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Tempat -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tempat *</label>
                            <input type="text" wire:model="tempat" placeholder="Ruangan BK, Ruang Meeting, dll." class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('tempat') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Permasalahan -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Permasalahan *</label>
                            <textarea wire:model="permasalahan" rows="3" placeholder="Deskripsi permasalahan yang dialami siswa..." class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                            @error('permasalahan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Tindakan -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tindakan</label>
                            <textarea wire:model="tindakan" rows="2" placeholder="Tindakan yang akan dilakukan..." class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                            @error('tindakan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Hasil -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Hasil</label>
                            <textarea wire:model="hasil" rows="2" placeholder="Hasil konseling..." class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                            @error('hasil') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Status dan Catatan -->
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
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Catatan</label>
                                <textarea wire:model="catatan" rows="2" placeholder="Catatan tambahan..." class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                                @error('catatan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
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

    <!-- Flash Messages -->
    @if (session()->has('success'))
    <div class="fixed top-4 right-4 z-50 max-w-sm w-full sm:max-w-md">
        <div class="bg-green-500 text-white px-4 py-3 rounded-lg shadow-lg text-sm">
            {{ session('success') }}
        </div>
    </div>
    @endif

    @if (session()->has('error'))
    <div class="fixed top-4 right-4 z-50 max-w-sm w-full sm:max-w-md">
        <div class="bg-red-500 text-white px-4 py-3 rounded-lg shadow-lg text-sm">
            {{ session('error') }}
        </div>
    </div>
    @endif
</div>