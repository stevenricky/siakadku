<div class="p-4 sm:p-6">
    <!-- Header dan Tombol Tambah -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">Catatan Bimbingan Konseling</h1>
        <button wire:click="create" class="w-full sm:w-auto bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition text-sm sm:text-base">
            <i class="fas fa-plus mr-2"></i>Tambah Catatan
        </button>
    </div>

    <!-- Statistik -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-6 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm sm:text-lg font-semibold text-gray-900 dark:text-white">Kasus Ringan</h3>
                    <p class="text-xl sm:text-2xl font-bold text-green-600 dark:text-green-400">{{ $kasusRingan }}</p>
                </div>
                <i class="fas fa-circle text-green-600 dark:text-green-400 text-lg sm:text-2xl"></i>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm sm:text-lg font-semibold text-gray-900 dark:text-white">Kasus Sedang</h3>
                    <p class="text-xl sm:text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $kasusSedang }}</p>
                </div>
                <i class="fas fa-circle text-yellow-600 dark:text-yellow-400 text-lg sm:text-2xl"></i>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm sm:text-lg font-semibold text-gray-900 dark:text-white">Kasus Berat</h3>
                    <p class="text-xl sm:text-2xl font-bold text-red-600 dark:text-red-400">{{ $kasusBerat }}</p>
                </div>
                <i class="fas fa-circle text-red-600 dark:text-red-400 text-lg sm:text-2xl"></i>
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
                        placeholder="Cari siswa, jenis catatan, atau deskripsi..." 
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
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Daftar Catatan BK</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Siswa</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jenis Catatan</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tingkat</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($catatanBk as $catatan)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            {{ $catatan->tanggal_catatan->translatedFormat('d M Y') }}
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-8 w-8 flex-shrink-0 bg-gray-200 dark:bg-gray-600 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-gray-500 dark:text-gray-400 text-sm"></i>
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $catatan->siswa->nama }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $catatan->siswa->kelas->nama_kelas ?? '-' }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            {{ $jenisCatatanOptions[$catatan->jenis_catatan] ?? $catatan->jenis_catatan }}
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            @php
                                $tingkatColors = [
                                    'ringan' => 'bg-green-100 text-green-800',
                                    'sedang' => 'bg-yellow-100 text-yellow-800',
                                    'berat' => 'bg-red-100 text-red-800'
                                ];
                            @endphp
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $tingkatColors[$catatan->tingkat_keparahan] }}">
                                {{ $tingkatKeparahanOptions[$catatan->tingkat_keparahan] }}
                            </span>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $catatan->status_selesai ? 'bg-gray-100 text-gray-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ $catatan->status_selesai ? 'Selesai' : 'Aktif' }}
                            </span>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <button wire:click="showDetail({{ $catatan->id }})" class="text-blue-600 hover:text-blue-900" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button wire:click="edit({{ $catatan->id }})" class="text-green-600 hover:text-green-900" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button wire:click="updateStatus({{ $catatan->id }})" class="text-orange-600 hover:text-orange-900" title="{{ $catatan->status_selesai ? 'Tandai Aktif' : 'Tandai Selesai' }}">
                                    <i class="fas {{ $catatan->status_selesai ? 'fa-undo' : 'fa-check' }}"></i>
                                </button>
                                <button wire:click="delete({{ $catatan->id }})" onclick="return confirm('Apakah Anda yakin ingin menghapus catatan ini?')" class="text-red-600 hover:text-red-900" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                            Tidak ada data catatan BK
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($catatanBk->hasPages())
        <div class="p-4 border-t border-gray-200 dark:border-gray-700">
            {{ $catatanBk->links() }}
        </div>
        @endif
    </div>

    <!-- Tabel Data - Mobile -->
    <div class="sm:hidden space-y-4">
        @forelse($catatanBk as $catatan)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="space-y-3">
                <!-- Header -->
                <div class="flex justify-between items-start">
                    <div>
                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $catatan->tanggal_catatan->translatedFormat('d M Y') }}
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            {{ $jenisCatatanOptions[$catatan->jenis_catatan] ?? $catatan->jenis_catatan }}
                        </div>
                    </div>
                    <div class="flex flex-col items-end space-y-1">
                        @php
                            $tingkatColors = [
                                'ringan' => 'bg-green-100 text-green-800',
                                'sedang' => 'bg-yellow-100 text-yellow-800',
                                'berat' => 'bg-red-100 text-red-800'
                            ];
                        @endphp
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $tingkatColors[$catatan->tingkat_keparahan] }}">
                            {{ $tingkatKeparahanOptions[$catatan->tingkat_keparahan] }}
                        </span>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $catatan->status_selesai ? 'bg-gray-100 text-gray-800' : 'bg-blue-100 text-blue-800' }}">
                            {{ $catatan->status_selesai ? 'Selesai' : 'Aktif' }}
                        </span>
                    </div>
                </div>

                <!-- Siswa Info -->
                <div class="flex items-center">
                    <div class="h-8 w-8 flex-shrink-0 bg-gray-200 dark:bg-gray-600 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-gray-500 dark:text-gray-400 text-sm"></i>
                    </div>
                    <div class="ml-3">
                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $catatan->siswa->nama }}
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">
                            {{ $catatan->siswa->kelas->nama_kelas ?? '-' }}
                        </div>
                    </div>
                </div>

                <!-- Deskripsi -->
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    {{ Str::limit($catatan->deskripsi, 80) }}
                </div>

                <!-- Actions -->
                <div class="flex justify-between pt-2 border-t border-gray-200 dark:border-gray-700">
                    <button wire:click="showDetail({{ $catatan->id }})" class="text-blue-600 hover:text-blue-900 text-sm">
                        <i class="fas fa-eye mr-1"></i>Detail
                    </button>
                    <button wire:click="edit({{ $catatan->id }})" class="text-green-600 hover:text-green-900 text-sm">
                        <i class="fas fa-edit mr-1"></i>Edit
                    </button>
                    <button wire:click="updateStatus({{ $catatan->id }})" class="text-orange-600 hover:text-orange-900 text-sm">
                        <i class="fas {{ $catatan->status_selesai ? 'fa-undo' : 'fa-check' }} mr-1"></i>
                        {{ $catatan->status_selesai ? 'Aktifkan' : 'Selesaikan' }}
                    </button>
                    <button wire:click="delete({{ $catatan->id }})" onclick="return confirm('Apakah Anda yakin ingin menghapus catatan ini?')" class="text-red-600 hover:text-red-900 text-sm">
                        <i class="fas fa-trash mr-1"></i>Hapus
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 text-center">
            <div class="text-gray-500 dark:text-gray-400">
                <i class="fas fa-clipboard-list text-3xl mb-3"></i>
                <p>Tidak ada data catatan BK</p>
            </div>
        </div>
        @endforelse

        <!-- Pagination Mobile -->
        @if($catatanBk->hasPages())
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            {{ $catatanBk->links() }}
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
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Layanan BK</label>
                            <select wire:model="layanan_bk_id" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Pilih Layanan (Opsional)</option>
                                @foreach($layananBk as $layanan)
                                <option value="{{ $layanan->id }}">{{ $layanan->nama_layanan }}</option>
                                @endforeach
                            </select>
                            @error('layanan_bk_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Tanggal dan Jenis -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal *</label>
                                <input type="date" wire:model="tanggal_catatan" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('tanggal_catatan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jenis Catatan *</label>
                                <select wire:model="jenis_catatan" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Pilih Jenis</option>
                                    @foreach($jenisCatatanOptions as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('jenis_catatan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deskripsi *</label>
                            <textarea wire:model="deskripsi" rows="4" placeholder="Deskripsi lengkap mengenai catatan BK..." class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                            @error('deskripsi') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Tingkat Keparahan dan Status -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tingkat Keparahan *</label>
                                <select wire:model="tingkat_keparahan" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    @foreach($tingkatKeparahanOptions as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('tingkat_keparahan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" wire:model="status_selesai" id="status_selesai" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <label for="status_selesai" class="ml-2 text-sm text-gray-700 dark:text-gray-300">Tandai Selesai</label>
                            </div>
                        </div>

                        <!-- Tindak Lanjut -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tindak Lanjut</label>
                            <textarea wire:model="tindak_lanjut" rows="3" placeholder="Rencana tindak lanjut yang akan dilakukan..." class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                            @error('tindak_lanjut') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
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
    @if($showDetailModal && $selectedCatatan)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="p-4 sm:p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Detail Catatan BK</h3>
                    <button wire:click="$set('showDetailModal', false)" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="space-y-4">
                    <!-- Info Siswa -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Siswa</div>
                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $selectedCatatan->siswa->nama }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $selectedCatatan->siswa->kelas->nama_kelas ?? '-' }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Guru BK</div>
                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $selectedCatatan->guruBk->nama ?? '-' }}</div>
                        </div>
                    </div>

                    <!-- Info Catatan -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Tanggal</div>
                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $selectedCatatan->tanggal_catatan->translatedFormat('d M Y') }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Jenis</div>
                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $jenisCatatanOptions[$selectedCatatan->jenis_catatan] ?? $selectedCatatan->jenis_catatan }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Tingkat</div>
                            @php
                                $tingkatColors = [
                                    'ringan' => 'bg-green-100 text-green-800',
                                    'sedang' => 'bg-yellow-100 text-yellow-800',
                                    'berat' => 'bg-red-100 text-red-800'
                                ];
                            @endphp
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $tingkatColors[$selectedCatatan->tingkat_keparahan] }}">
                                {{ $tingkatKeparahanOptions[$selectedCatatan->tingkat_keparahan] }}
                            </span>
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mb-2">Deskripsi</div>
                        <div class="text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                            {{ $selectedCatatan->deskripsi }}
                        </div>
                    </div>

                    <!-- Tindak Lanjut -->
                    @if($selectedCatatan->tindak_lanjut)
                    <div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mb-2">Tindak Lanjut</div>
                        <div class="text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                            {{ $selectedCatatan->tindak_lanjut }}
                        </div>
                    </div>
                    @endif

                    <!-- Status -->
                    <div class="flex justify-between items-center pt-4 border-t border-gray-200 dark:border-gray-700">
                        <div>
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full {{ $selectedCatatan->status_selesai ? 'bg-gray-100 text-gray-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ $selectedCatatan->status_selesai ? 'Selesai' : 'Aktif' }}
                            </span>
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">
                            Dibuat: {{ $selectedCatatan->created_at->translatedFormat('d M Y H:i') }}
                        </div>
                    </div>
                </div>
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