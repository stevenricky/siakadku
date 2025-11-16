<div class="p-4 sm:p-6">
    <!-- Header dan Tombol -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">Data Alumni</h1>
        <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
            <button class="w-full sm:w-auto bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition text-sm sm:text-base">
                <i class="fas fa-download mr-2"></i>Export
            </button>
            <button wire:click="create" class="w-full sm:w-auto bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition text-sm sm:text-base">
                <i class="fas fa-plus mr-2"></i>Tambah Alumni
            </button>
        </div>
    </div>

    <!-- Statistik -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 sm:p-4">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                    <i class="fas fa-user-graduate text-blue-600 dark:text-blue-400 text-sm sm:text-base"></i>
                </div>
                <div class="ml-3">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400">Total Alumni</p>
                    <p class="text-lg sm:text-2xl font-bold text-gray-900 dark:text-white">{{ $totalAlumni }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 sm:p-4">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 dark:bg-green-900 rounded-lg">
                    <i class="fas fa-university text-green-600 dark:text-green-400 text-sm sm:text-base"></i>
                </div>
                <div class="ml-3">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400">Kuliah</p>
                    <p class="text-lg sm:text-2xl font-bold text-gray-900 dark:text-white">{{ $kuliah }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 sm:p-4">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 dark:bg-purple-900 rounded-lg">
                    <i class="fas fa-briefcase text-purple-600 dark:text-purple-400 text-sm sm:text-base"></i>
                </div>
                <div class="ml-3">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400">Bekerja</p>
                    <p class="text-lg sm:text-2xl font-bold text-gray-900 dark:text-white">{{ $bekerja }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 sm:p-4">
            <div class="flex items-center">
                <div class="p-2 bg-orange-100 dark:bg-orange-900 rounded-lg">
                    <i class="fas fa-chart-line text-orange-600 dark:text-orange-400 text-sm sm:text-base"></i>
                </div>
                <div class="ml-3">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400">Wirausaha</p>
                    <p class="text-lg sm:text-2xl font-bold text-gray-900 dark:text-white">{{ $wirausaha }}</p>
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
                        placeholder="Cari alumni, no ijazah, instansi, atau email..." 
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
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Daftar Alumni</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Alumni</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tahun Lulus</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status Saat Ini</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Instansi</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kontak</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($alumni as $item)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-10 w-10 flex-shrink-0 bg-gray-200 dark:bg-gray-600 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-gray-500 dark:text-gray-400"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $item->siswa->nama }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $item->siswa->kelas->nama_kelas ?? '-' }} - {{ $item->tahun_lulus }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            {{ $item->tahun_lulus }}
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            @php
                                $statusColors = [
                                    'kuliah' => 'bg-green-100 text-green-800',
                                    'kerja' => 'bg-purple-100 text-purple-800',
                                    'wirausaha' => 'bg-orange-100 text-orange-800',
                                    'lainnya' => 'bg-gray-100 text-gray-800'
                                ];
                            @endphp
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$item->status_setelah_lulus] }}">
                                {{ $statusOptions[$item->status_setelah_lulus] }}
                            </span>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            {{ $item->instansi ?? '-' }}
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            <div>{{ $item->email ?? '-' }}</div>
                            <div class="text-xs text-gray-500">{{ $item->no_telepon ?? '-' }}</div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <button wire:click="showDetail({{ $item->id }})" class="text-blue-600 hover:text-blue-900" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button wire:click="edit({{ $item->id }})" class="text-green-600 hover:text-green-900" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button wire:click="updateStatus({{ $item->id }})" class="text-orange-600 hover:text-orange-900" title="{{ $item->status_aktif ? 'Nonaktifkan' : 'Aktifkan' }}">
                                    <i class="fas {{ $item->status_aktif ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                                </button>
                                <button wire:click="delete({{ $item->id }})" onclick="return confirm('Apakah Anda yakin ingin menghapus data alumni ini?')" class="text-red-600 hover:text-red-900" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                            Tidak ada data alumni
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($alumni->hasPages())
        <div class="p-4 border-t border-gray-200 dark:border-gray-700">
            {{ $alumni->links() }}
        </div>
        @endif
    </div>

    <!-- Tabel Data - Mobile -->
    <div class="sm:hidden space-y-4">
        @forelse($alumni as $item)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="space-y-3">
                <!-- Header -->
                <div class="flex justify-between items-start">
                    <div class="flex items-center">
                        <div class="h-10 w-10 flex-shrink-0 bg-gray-200 dark:bg-gray-600 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-gray-500 dark:text-gray-400"></i>
                        </div>
                        <div class="ml-3">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $item->siswa->nama }}
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                Lulus: {{ $item->tahun_lulus }}
                            </div>
                        </div>
                    </div>
                    @php
                        $statusColors = [
                            'kuliah' => 'bg-green-100 text-green-800',
                            'kerja' => 'bg-purple-100 text-purple-800',
                            'wirausaha' => 'bg-orange-100 text-orange-800',
                            'lainnya' => 'bg-gray-100 text-gray-800'
                        ];
                    @endphp
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$item->status_setelah_lulus] }}">
                        {{ $statusOptions[$item->status_setelah_lulus] }}
                    </span>
                </div>

                <!-- Detail Info -->
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div>
                        <div class="text-gray-500 dark:text-gray-400 text-xs">Instansi</div>
                        <div class="text-gray-900 dark:text-white">
                            {{ $item->instansi ?? '-' }}
                        </div>
                    </div>
                    <div>
                        <div class="text-gray-500 dark:text-gray-400 text-xs">Kontak</div>
                        <div class="text-gray-900 dark:text-white">
                            {{ $item->email ?? $item->no_telepon ?? '-' }}
                        </div>
                    </div>
                    @if($item->jurusan_kuliah)
                    <div class="col-span-2">
                        <div class="text-gray-500 dark:text-gray-400 text-xs">Jurusan</div>
                        <div class="text-gray-900 dark:text-white">
                            {{ $item->jurusan_kuliah }}
                        </div>
                    </div>
                    @endif
                    @if($item->jabatan_pekerjaan)
                    <div class="col-span-2">
                        <div class="text-gray-500 dark:text-gray-400 text-xs">Jabatan</div>
                        <div class="text-gray-900 dark:text-white">
                            {{ $item->jabatan_pekerjaan }}
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Actions -->
                <div class="flex justify-between pt-2 border-t border-gray-200 dark:border-gray-700">
                    <button wire:click="showDetail({{ $item->id }})" class="text-blue-600 hover:text-blue-900 text-sm">
                        <i class="fas fa-eye mr-1"></i>Detail
                    </button>
                    <button wire:click="edit({{ $item->id }})" class="text-green-600 hover:text-green-900 text-sm">
                        <i class="fas fa-edit mr-1"></i>Edit
                    </button>
                    <button wire:click="updateStatus({{ $item->id }})" class="text-orange-600 hover:text-orange-900 text-sm">
                        <i class="fas {{ $item->status_aktif ? 'fa-toggle-on' : 'fa-toggle-off' }} mr-1"></i>
                        {{ $item->status_aktif ? 'Nonaktif' : 'Aktif' }}
                    </button>
                    <button wire:click="delete({{ $item->id }})" onclick="return confirm('Apakah Anda yakin ingin menghapus data alumni ini?')" class="text-red-600 hover:text-red-900 text-sm">
                        <i class="fas fa-trash mr-1"></i>Hapus
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 text-center">
            <div class="text-gray-500 dark:text-gray-400">
                <i class="fas fa-user-graduate text-3xl mb-3"></i>
                <p>Tidak ada data alumni</p>
            </div>
        </div>
        @endforelse

        <!-- Pagination Mobile -->
        @if($alumni->hasPages())
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            {{ $alumni->links() }}
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

                        <!-- Tahun Lulus dan No Ijazah -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tahun Lulus *</label>
                                <input type="number" wire:model="tahun_lulus" min="2000" max="{{ date('Y') + 1 }}" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('tahun_lulus') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">No Ijazah</label>
                                <input type="text" wire:model="no_ijazah" placeholder="Nomor ijazah..." class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('no_ijazah') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Status Setelah Lulus -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status Setelah Lulus *</label>
                            <select wire:model="status_setelah_lulus" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @foreach($statusOptions as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('status_setelah_lulus') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Instansi dan Alamat -->
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Instansi</label>
                                <input type="text" wire:model="instansi" placeholder="Nama kampus/perusahaan..." class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('instansi') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Alamat Instansi</label>
                                <input type="text" wire:model="alamat_instansi" placeholder="Alamat instansi..." class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('alamat_instansi') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Jurusan dan Jabatan -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jurusan Kuliah</label>
                                <input type="text" wire:model="jurusan_kuliah" placeholder="Jurusan kuliah..." class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('jurusan_kuliah') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jabatan Pekerjaan</label>
                                <input type="text" wire:model="jabatan_pekerjaan" placeholder="Jabatan pekerjaan..." class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('jabatan_pekerjaan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Kontak -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">No Telepon</label>
                                <input type="text" wire:model="no_telepon" placeholder="Nomor telepon..." class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('no_telepon') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                                <input type="email" wire:model="email" placeholder="Alamat email..." class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Prestasi -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Prestasi Setelah Lulus</label>
                            <textarea wire:model="prestasi_setelah_lulus" rows="3" placeholder="Prestasi yang dicapai setelah lulus..." class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                            @error('prestasi_setelah_lulus') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Status Aktif -->
                        <div class="flex items-center">
                            <input type="checkbox" wire:model="status_aktif" id="status_aktif" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <label for="status_aktif" class="ml-2 text-sm text-gray-700 dark:text-gray-300">Status Aktif</label>
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
    @if($showDetailModal && $selectedAlumni)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="p-4 sm:p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Detail Alumni</h3>
                    <button wire:click="$set('showDetailModal', false)" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="space-y-4">
                    <!-- Info Siswa -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Nama Alumni</div>
                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $selectedAlumni->siswa->nama }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $selectedAlumni->siswa->kelas->nama_kelas ?? '-' }} - {{ $selectedAlumni->tahun_lulus }}
                            </div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">No Ijazah</div>
                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $selectedAlumni->no_ijazah ?? '-' }}</div>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Status</div>
                            @php
                                $statusColors = [
                                    'kuliah' => 'bg-green-100 text-green-800',
                                    'kerja' => 'bg-purple-100 text-purple-800',
                                    'wirausaha' => 'bg-orange-100 text-orange-800',
                                    'lainnya' => 'bg-gray-100 text-gray-800'
                                ];
                            @endphp
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$selectedAlumni->status_setelah_lulus] }}">
                                {{ $statusOptions[$selectedAlumni->status_setelah_lulus] }}
                            </span>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Tahun Lulus</div>
                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $selectedAlumni->tahun_lulus }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Status Aktif</div>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $selectedAlumni->status_aktif ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $selectedAlumni->status_aktif ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>
                    </div>

                    <!-- Instansi -->
                    @if($selectedAlumni->instansi)
                    <div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mb-2">Instansi</div>
                        <div class="text-sm text-gray-900 dark:text-white">{{ $selectedAlumni->instansi }}</div>
                        @if($selectedAlumni->alamat_instansi)
                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $selectedAlumni->alamat_instansi }}</div>
                        @endif
                    </div>
                    @endif

                    <!-- Detail Berdasarkan Status -->
                    @if($selectedAlumni->status_setelah_lulus == 'kuliah' && $selectedAlumni->jurusan_kuliah)
                    <div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mb-2">Jurusan Kuliah</div>
                        <div class="text-sm text-gray-900 dark:text-white">{{ $selectedAlumni->jurusan_kuliah }}</div>
                    </div>
                    @endif

                    @if($selectedAlumni->status_setelah_lulus == 'kerja' && $selectedAlumni->jabatan_pekerjaan)
                    <div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mb-2">Jabatan</div>
                        <div class="text-sm text-gray-900 dark:text-white">{{ $selectedAlumni->jabatan_pekerjaan }}</div>
                    </div>
                    @endif

                    <!-- Kontak -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @if($selectedAlumni->email)
                        <div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Email</div>
                            <div class="text-sm text-gray-900 dark:text-white">{{ $selectedAlumni->email }}</div>
                        </div>
                        @endif
                        @if($selectedAlumni->no_telepon)
                        <div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">No Telepon</div>
                            <div class="text-sm text-gray-900 dark:text-white">{{ $selectedAlumni->no_telepon }}</div>
                        </div>
                        @endif
                    </div>

                    <!-- Prestasi -->
                    @if($selectedAlumni->prestasi_setelah_lulus)
                    <div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mb-2">Prestasi Setelah Lulus</div>
                        <div class="text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                            {{ $selectedAlumni->prestasi_setelah_lulus }}
                        </div>
                    </div>
                    @endif
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