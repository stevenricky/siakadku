<div class="p-4 sm:p-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">Konseling Siswa</h1>
            <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400 mt-1">
                Kelola jadwal dan hasil konseling siswa
                @if($guru->kelasWali)
                    <span class="text-blue-600 dark:text-blue-400 font-medium">
                        (Kelas: {{ $guru->kelasWali->nama_kelas }})
                    </span>
                @else
                    <span class="text-green-600 dark:text-green-400 font-medium">
                        (Semua Kelas)
                    </span>
                @endif
            </p>
        </div>
        <div class="flex flex-wrap gap-2 w-full sm:w-auto">
            <button 
                wire:click="openTambahModal"
                class="bg-blue-600 text-white px-3 py-2 sm:px-4 sm:py-2 rounded-lg hover:bg-blue-700 transition flex items-center justify-center flex-1 sm:flex-none"
            >
                <i class="fas fa-plus mr-2 text-xs sm:text-sm"></i>
                <span class="text-xs sm:text-sm">Jadwal Baru</span>
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 sm:gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 sm:p-4">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <i class="fas fa-calendar-check text-blue-600 text-sm sm:text-base"></i>
                </div>
                <div class="ml-3">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400">Terjadwal</p>
                    <p class="text-lg sm:text-2xl font-bold text-gray-900 dark:text-white">{{ $totalTerjadwal }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 sm:p-4">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <i class="fas fa-check-circle text-green-600 text-sm sm:text-base"></i>
                </div>
                <div class="ml-3">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400">Selesai</p>
                    <p class="text-lg sm:text-2xl font-bold text-gray-900 dark:text-white">{{ $totalSelesai }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 sm:p-4">
            <div class="flex items-center">
                <div class="p-2 bg-red-100 rounded-lg">
                    <i class="fas fa-times-circle text-red-600 text-sm sm:text-base"></i>
                </div>
                <div class="ml-3">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400">Dibatalkan</p>
                    <p class="text-lg sm:text-2xl font-bold text-gray-900 dark:text-white">{{ $totalDibatalkan }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
        <div class="p-3 sm:p-4 border-b border-gray-200 dark:border-gray-700">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                <input 
                    type="text" 
                    wire:model.live="search"
                    placeholder="Cari nama siswa/tempat/permasalahan..." 
                    class="px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white w-full"
                >
                <select 
                    wire:model.live="statusFilter"
                    class="px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white w-full"
                >
                    <option value="">Semua Status</option>
                    <option value="terjadwal">Terjadwal</option>
                    <option value="selesai">Selesai</option>
                    <option value="dibatalkan">Dibatalkan</option>
                </select>
                <input 
                    type="date" 
                    wire:model.live="tanggalFilter"
                    class="px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white w-full"
                >
                <button 
                    wire:click="resetFilters"
                    class="bg-gray-500 text-white px-3 py-2 text-sm rounded-lg hover:bg-gray-600 transition w-full sm:w-auto"
                >
                    Reset Filter
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Card View -->
    <div class="sm:hidden space-y-4">
        @forelse($konseling as $item)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <div class="flex justify-between items-start mb-3">
                    <div class="flex-1">
                        <div class="flex items-center">
                            <div class="h-8 w-8 flex-shrink-0 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-blue-600 text-xs"></i>
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $item->siswa->nama_lengkap }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $item->siswa->kelas->nama_kelas ?? '-' }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                        @php
                            $statusBadge = match($item->status) {
                                'terjadwal' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                'selesai' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                'dibatalkan' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                default => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200'
                            };
                        @endphp
                        {{ $statusBadge }}">
                        @php
                            $statusText = match($item->status) {
                                'terjadwal' => 'Terjadwal',
                                'selesai' => 'Selesai',
                                'dibatalkan' => 'Dibatalkan',
                                default => $item->status
                            };
                        @endphp
                        {{ $statusText }}
                    </span>
                </div>
                
                <div class="space-y-2 mb-3">
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500 dark:text-gray-400">Layanan</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $item->layananBk->nama_layanan }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500 dark:text-gray-400">Jadwal</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ \Carbon\Carbon::parse($item->tanggal_konseling)->translatedFormat('d M Y') }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500 dark:text-gray-400">Waktu</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $item->waktu_mulai }} - {{ $item->waktu_selesai }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500 dark:text-gray-400">Tempat</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $item->tempat }}</span>
                    </div>
                </div>
                
                <div class="flex justify-between items-center pt-3 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex space-x-2">
                        <button 
                            wire:click="showDetail({{ $item->id }})"
                            class="text-blue-600 hover:text-blue-900 text-xs px-2 py-1 bg-blue-50 rounded"
                            title="Detail"
                        >
                            <i class="fas fa-eye"></i>
                        </button>
                        @if($item->status === 'terjadwal')
                            <button 
                                wire:click="openEditModal({{ $item->id }})"
                                class="text-green-600 hover:text-green-900 text-xs px-2 py-1 bg-green-50 rounded"
                                title="Input Hasil"
                            >
                                <i class="fas fa-edit"></i>
                            </button>
                            <button 
                                wire:click="batalkanKonseling({{ $item->id }})"
                                class="text-red-600 hover:text-red-900 text-xs px-2 py-1 bg-red-50 rounded"
                                title="Batalkan"
                                onclick="return confirm('Yakin ingin membatalkan konseling ini?')"
                            >
                                <i class="fas fa-times"></i>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 text-center">
                <div class="text-gray-500 dark:text-gray-400">
                    <i class="fas fa-comments text-4xl mb-4"></i>
                    <p class="text-lg font-medium">Tidak ada data konseling</p>
                    <p class="text-sm mt-2">Mulai dengan membuat jadwal konseling baru</p>
                </div>
            </div>
        @endforelse
        
        <!-- Pagination for Mobile -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            {{ $konseling->links() }}
        </div>
    </div>

    <!-- Desktop Table View -->
    <div class="hidden sm:block bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-3 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Siswa</th>
                        <th class="px-3 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Layanan</th>
                        <th class="px-3 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jadwal</th>
                        <th class="px-3 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-3 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($konseling as $item)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-3 py-2 sm:px-6 sm:py-4">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 flex-shrink-0 bg-blue-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-blue-600 text-xs"></i>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $item->siswa->nama_lengkap }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $item->siswa->kelas->nama_kelas ?? '-' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-3 py-2 sm:px-6 sm:py-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $item->layananBk->nama_layanan }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $item->tempat }}
                                </div>
                            </td>
                            <td class="px-3 py-2 sm:px-6 sm:py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">
                                    {{ \Carbon\Carbon::parse($item->tanggal_konseling)->translatedFormat('d M Y') }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $item->waktu_mulai }} - {{ $item->waktu_selesai }}
                                </div>
                            </td>
                            <td class="px-3 py-2 sm:px-6 sm:py-4 whitespace-nowrap">
                                @php
                                    $statusBadge = match($item->status) {
                                        'terjadwal' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                        'selesai' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                        'dibatalkan' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                        default => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200'
                                    };
                                    $statusText = match($item->status) {
                                        'terjadwal' => 'Terjadwal',
                                        'selesai' => 'Selesai',
                                        'dibatalkan' => 'Dibatalkan',
                                        default => $item->status
                                    };
                                @endphp
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusBadge }}">
                                    {{ $statusText }}
                                </span>
                            </td>
                            <td class="px-3 py-2 sm:px-6 sm:py-4 whitespace-nowrap">
                                <div class="flex flex-wrap gap-1">
                                    <button 
                                        wire:click="showDetail({{ $item->id }})"
                                        class="text-blue-600 hover:text-blue-900 text-xs px-2 py-1 bg-blue-50 rounded"
                                        title="Detail"
                                    >
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @if($item->status === 'terjadwal')
                                        <button 
                                            wire:click="openEditModal({{ $item->id }})"
                                            class="text-green-600 hover:text-green-900 text-xs px-2 py-1 bg-green-50 rounded"
                                            title="Input Hasil"
                                        >
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button 
                                            wire:click="batalkanKonseling({{ $item->id }})"
                                            class="text-red-600 hover:text-red-900 text-xs px-2 py-1 bg-red-50 rounded"
                                            title="Batalkan"
                                            onclick="return confirm('Yakin ingin membatalkan konseling ini?')"
                                        >
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center">
                                <div class="text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-comments text-4xl mb-4"></i>
                                    <p class="text-lg font-medium">Tidak ada data konseling</p>
                                    <p class="text-sm mt-2">Mulai dengan membuat jadwal konseling baru</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-3 sm:px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $konseling->links() }}
        </div>
    </div>

    <!-- Modal Tambah Konseling -->
    @if($showTambahModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-4 sm:p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Jadwal Konseling Baru</h3>
                    <button wire:click="closeTambahModal" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form wire:submit="simpanKonseling">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Siswa *</label>
                            <select 
                                wire:model="siswaId" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                            >
                                <option value="">Pilih Siswa</option>
                                @foreach($siswaList as $siswa)
                                    <option value="{{ $siswa->id }}">
                                        {{ $siswa->nama_lengkap }} - {{ $siswa->kelas->nama_kelas ?? '-' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('siswaId') 
                                <span class="text-red-500 text-xs">{{ $message }}</span> 
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Layanan BK *</label>
                            <select 
                                wire:model="layananBkId" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                            >
                                <option value="">Pilih Layanan</option>
                                @foreach($layananBkList as $layanan)
                                    <option value="{{ $layanan->id }}">
                                        {{ $layanan->nama_layanan }}
                                    </option>
                                @endforeach
                            </select>
                            @error('layananBkId') 
                                <span class="text-red-500 text-xs">{{ $message }}</span> 
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal *</label>
                                <input 
                                    type="date" 
                                    wire:model="tanggalKonseling" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                                >
                                @error('tanggalKonseling') 
                                    <span class="text-red-500 text-xs">{{ $message }}</span> 
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Waktu Mulai *</label>
                                <input 
                                    type="time" 
                                    wire:model="waktuMulai" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                                >
                                @error('waktuMulai') 
                                    <span class="text-red-500 text-xs">{{ $message }}</span> 
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Waktu Selesai *</label>
                                <input 
                                    type="time" 
                                    wire:model="waktuSelesai" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                                >
                                @error('waktuSelesai') 
                                    <span class="text-red-500 text-xs">{{ $message }}</span> 
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tempat *</label>
                            <input 
                                type="text" 
                                wire:model="tempat" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                                placeholder="Contoh: Ruang BK, Ruang Guru, dll."
                            >
                            @error('tempat') 
                                <span class="text-red-500 text-xs">{{ $message }}</span> 
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Permasalahan *</label>
                            <textarea 
                                wire:model="permasalahan" 
                                rows="3" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white" 
                                placeholder="Jelaskan permasalahan yang dihadapi siswa..."
                            ></textarea>
                            @error('permasalahan') 
                                <span class="text-red-500 text-xs">{{ $message }}</span> 
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Catatan</label>
                            <textarea 
                                wire:model="catatan" 
                                rows="2" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white" 
                                placeholder="Catatan tambahan..."
                            ></textarea>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:justify-end gap-3 mt-6">
                        <button 
                            type="button" 
                            wire:click="closeTambahModal" 
                            class="w-full sm:w-auto px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200"
                        >
                            Batal
                        </button>
                        <button 
                            type="submit" 
                            class="w-full sm:w-auto px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                        >
                            Simpan Jadwal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal Detail -->
    @if($showDetailModal && $selectedKonseling)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-4 sm:p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Detail Konseling</h3>
                    <button wire:click="closeDetail" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Siswa</p>
                            <p class="text-gray-900 dark:text-white">{{ $selectedKonseling->siswa->nama_lengkap }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $selectedKonseling->siswa->kelas->nama_kelas ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Layanan BK</p>
                            <p class="text-gray-900 dark:text-white">{{ $selectedKonseling->layananBk->nama_layanan }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal</p>
                            <p class="text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($selectedKonseling->tanggal_konseling)->translatedFormat('d F Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Waktu</p>
                            <p class="text-gray-900 dark:text-white">{{ $selectedKonseling->waktu_mulai }} - {{ $selectedKonseling->waktu_selesai }}</p>
                        </div>
                    </div>

                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Tempat</p>
                        <p class="text-gray-900 dark:text-white">{{ $selectedKonseling->tempat }}</p>
                    </div>

                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Permasalahan</p>
                        <p class="text-gray-900 dark:text-white">{{ $selectedKonseling->permasalahan }}</p>
                    </div>

                    @if($selectedKonseling->tindakan)
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Tindakan</p>
                        <p class="text-gray-900 dark:text-white">{{ $selectedKonseling->tindakan }}</p>
                    </div>
                    @endif

                    @if($selectedKonseling->hasil)
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Hasil</p>
                        <p class="text-gray-900 dark:text-white">{{ $selectedKonseling->hasil }}</p>
                    </div>
                    @endif

                    @if($selectedKonseling->catatan)
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Catatan</p>
                        <p class="text-gray-900 dark:text-white">{{ $selectedKonseling->catatan }}</p>
                    </div>
                    @endif

                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</p>
                        @php
                            $statusBadge = match($selectedKonseling->status) {
                                'terjadwal' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                'selesai' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                'dibatalkan' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                default => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200'
                            };
                            $statusText = match($selectedKonseling->status) {
                                'terjadwal' => 'Terjadwal',
                                'selesai' => 'Selesai',
                                'dibatalkan' => 'Dibatalkan',
                                default => $selectedKonseling->status
                            };
                        @endphp
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusBadge }}">
                            {{ $statusText }}
                        </span>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row sm:justify-end gap-3 mt-6">
                    <button 
                        type="button" 
                        wire:click="closeDetail" 
                        class="w-full sm:w-auto px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200"
                    >
                        Tutup
                    </button>
                    @if($selectedKonseling->status === 'terjadwal')
                        <button 
                            wire:click="openEditModal({{ $selectedKonseling->id }})"
                            type="button"
                            class="w-full sm:w-auto px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700"
                        >
                            <i class="fas fa-edit mr-2"></i>
                            Input Hasil
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal Edit/Input Hasil -->
    @if($showEditModal && $selectedKonseling)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-4 sm:p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Input Hasil Konseling</h3>
                    <button wire:click="closeEditModal" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form wire:submit="updateKonseling">
                    <div class="space-y-4">
                        <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg">
                            <div class="text-sm text-blue-800 dark:text-blue-300">
                                <p class="font-medium">Detail Konseling</p>
                                <p class="mt-1">{{ $selectedKonseling->siswa->nama_lengkap }}</p>
                                <p class="text-xs mt-1">{{ $selectedKonseling->layananBk->nama_layanan }}</p>
                                <p class="text-xs mt-1">{{ \Carbon\Carbon::parse($selectedKonseling->tanggal_konseling)->translatedFormat('d F Y') }} - {{ $selectedKonseling->waktu_mulai }}</p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tindakan yang Dilakukan *</label>
                            <textarea 
                                wire:model="tindakan" 
                                rows="3" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white" 
                                placeholder="Jelaskan tindakan yang dilakukan selama konseling..."
                            ></textarea>
                            @error('tindakan') 
                                <span class="text-red-500 text-xs">{{ $message }}</span> 
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Hasil Konseling *</label>
                            <textarea 
                                wire:model="hasil" 
                                rows="3" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white" 
                                placeholder="Jelaskan hasil yang dicapai dari konseling..."
                            ></textarea>
                            @error('hasil') 
                                <span class="text-red-500 text-xs">{{ $message }}</span> 
                            @enderror
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:justify-end gap-3 mt-6">
                        <button 
                            type="button" 
                            wire:click="closeEditModal" 
                            class="w-full sm:w-auto px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200"
                        >
                            Batal
                        </button>
                        <button 
                            type="submit" 
                            class="w-full sm:w-auto px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700"
                        >
                            <i class="fas fa-check mr-2"></i>
                            Simpan Hasil
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Success/Error Messages -->
    @if (session()->has('success'))
    <div class="fixed top-4 right-4 z-50 animate-fade-in">
        <div class="bg-green-500 text-white px-4 sm:px-6 py-3 rounded-lg shadow-lg flex items-center max-w-xs sm:max-w-md">
            <i class="fas fa-check-circle mr-2"></i>
            <span class="text-sm">{{ session('success') }}</span>
        </div>
    </div>
    @endif

    @if (session()->has('error'))
    <div class="fixed top-4 right-4 z-50 animate-fade-in">
        <div class="bg-red-500 text-white px-4 sm:px-6 py-3 rounded-lg shadow-lg flex items-center max-w-xs sm:max-w-md">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            <span class="text-sm">{{ session('error') }}</span>
        </div>
    </div>
    @endif

    <!-- Loading Indicator -->
    <div wire:loading class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 flex items-center">
            <i class="fas fa-spinner fa-spin text-blue-600 mr-3"></i>
            <span class="text-gray-700 dark:text-gray-300">Memproses...</span>
        </div>
    </div>
</div>