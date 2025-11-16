<div class="p-4 sm:p-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">Pembayaran SPP</h1>
            <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400 mt-1">Kelola verifikasi pembayaran SPP siswa</p>
        </div>
        <div class="flex flex-wrap gap-2 w-full sm:w-auto">
            <button 
                wire:click="openTambahModal"
                class="bg-blue-600 text-white px-3 py-2 sm:px-4 sm:py-2 rounded-lg hover:bg-blue-700 transition flex items-center justify-center flex-1 sm:flex-none"
            >
                <i class="fas fa-plus mr-2 text-xs sm:text-sm"></i>
                <span class="text-xs sm:text-sm">Tambah Manual</span>
            </button>
            <button 
                wire:click="exportCSV"
                class="bg-green-600 text-white px-3 py-2 sm:px-4 sm:py-2 rounded-lg hover:bg-green-700 transition flex items-center justify-center flex-1 sm:flex-none"
            >
                <i class="fas fa-download mr-2 text-xs sm:text-sm"></i>
                <span class="text-xs sm:text-sm">Export CSV</span>
            </button>
            <button 
                wire:click="printPDF"
                class="bg-purple-600 text-white px-3 py-2 sm:px-4 sm:py-2 rounded-lg hover:bg-purple-700 transition flex items-center justify-center flex-1 sm:flex-none"
            >
                <i class="fas fa-print mr-2 text-xs sm:text-sm"></i>
                <span class="text-xs sm:text-sm">Print PDF</span>
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 sm:p-4">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <i class="fas fa-wallet text-blue-600 text-sm sm:text-base"></i>
                </div>
                <div class="ml-3">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400">Total</p>
                    <p class="text-lg sm:text-2xl font-bold text-gray-900 dark:text-white">{{ $this->totalPembayaran }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 sm:p-4">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <i class="fas fa-clock text-yellow-600 text-sm sm:text-base"></i>
                </div>
                <div class="ml-3">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400">Pending</p>
                    <p class="text-lg sm:text-2xl font-bold text-gray-900 dark:text-white">{{ $this->totalPending }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 sm:p-4">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <i class="fas fa-check text-green-600 text-sm sm:text-base"></i>
                </div>
                <div class="ml-3">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400">Diterima</p>
                    <p class="text-lg sm:text-2xl font-bold text-gray-900 dark:text-white">{{ $this->totalDiterima }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 sm:p-4">
            <div class="flex items-center">
                <div class="p-2 bg-red-100 rounded-lg">
                    <i class="fas fa-times text-red-600 text-sm sm:text-base"></i>
                </div>
                <div class="ml-3">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400">Ditolak</p>
                    <p class="text-lg sm:text-2xl font-bold text-gray-900 dark:text-white">{{ $this->totalDitolak }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
        <div class="p-3 sm:p-4 border-b border-gray-200 dark:border-gray-700">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3">
                <input 
                    type="text" 
                    wire:model.live="search"
                    placeholder="Cari siswa/NIS..." 
                    class="px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                >
                <select 
                    wire:model.live="statusFilter"
                    class="px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                >
                    <option value="">Semua Status</option>
                    <option value="pending">Pending</option>
                    <option value="diterima">Diterima</option>
                    <option value="ditolak">Ditolak</option>
                </select>
                <select 
                    wire:model.live="siswaFilter"
                    class="px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                >
                    <option value="">Semua Siswa</option>
                    @foreach($siswaList as $siswa)
                        <option value="{{ $siswa->id }}">{{ $siswa->nama }} ({{ $siswa->nis }})</option>
                    @endforeach
                </select>
                <select 
                    wire:model.live="kelasFilter"
                    class="px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                >
                    <option value="">Semua Kelas</option>
                    @foreach($kelasList as $kelas)
                        <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
                    @endforeach
                </select>
                <input 
                    type="month" 
                    wire:model.live="bulanFilter"
                    class="px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                >
                <button 
                    wire:click="resetFilters"
                    class="bg-gray-500 text-white px-3 py-2 text-sm rounded-lg hover:bg-gray-600 transition"
                >
                    Reset Filter
                </button>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <!-- Mobile Card View -->
        <div class="sm:hidden">
            @forelse($pembayaran as $item)
                <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex items-center">
                            <div class="h-10 w-10 flex-shrink-0">
                                <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($item->siswa->nama) }}&background=random" alt="">
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $item->siswa->nama }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $item->siswa->nis }}
                                </div>
                            </div>
                        </div>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $item->status_badge }}">
                            {{ $item->status_text }}
                        </span>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-2 mb-3 text-xs">
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">Tanggal:</span>
                            <div class="font-medium text-gray-900 dark:text-white">
                                {{ $item->tanggal_bayar->format('d M Y') }}
                            </div>
                        </div>
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">Bulan:</span>
                            <div class="font-medium text-gray-900 dark:text-white">
                                {{ $item->tagihanSpp->bulan }} {{ $item->tagihanSpp->tahun }}
                            </div>
                        </div>
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">Jumlah:</span>
                            <div class="font-medium text-gray-900 dark:text-white">
                                Rp {{ number_format($item->jumlah_bayar, 0, ',', '.') }}
                            </div>
                        </div>
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">Metode:</span>
                            <div class="font-medium text-gray-900 dark:text-white">
                                {{ ucfirst($item->metode_bayar) }}
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex flex-wrap gap-1">
                        <button 
                            wire:click="showDetail({{ $item->id }})"
                            class="text-blue-600 hover:text-blue-900 text-xs px-2 py-1 bg-blue-50 rounded"
                            title="Detail"
                        >
                            <i class="fas fa-eye"></i>
                        </button>
                        <button 
                            wire:click="openEditModal({{ $item->id }})"
                            class="text-yellow-600 hover:text-yellow-900 text-xs px-2 py-1 bg-yellow-50 rounded"
                            title="Edit"
                        >
                            <i class="fas fa-edit"></i>
                        </button>
                        @if($item->status_verifikasi === 'pending')
                            <button 
                                wire:click="openVerifikasi({{ $item->id }}, 'diterima')"
                                class="text-green-600 hover:text-green-900 text-xs px-2 py-1 bg-green-50 rounded"
                                title="Terima"
                            >
                                <i class="fas fa-check"></i>
                            </button>
                            <button 
                                wire:click="openVerifikasi({{ $item->id }}, 'ditolak')"
                                class="text-red-600 hover:text-red-900 text-xs px-2 py-1 bg-red-50 rounded"
                                title="Tolak"
                            >
                                <i class="fas fa-times"></i>
                            </button>
                        @endif
                        <!-- Tampilkan tombol download jika ada bukti_upload ATAU bukti_bayar -->
                        @if($item->bukti_upload || $item->bukti_bayar)
                            <button 
                                wire:click="downloadBukti({{ $item->id }})"
                                class="text-purple-600 hover:text-purple-900 text-xs px-2 py-1 bg-purple-50 rounded"
                                title="Download Bukti"
                            >
                                <i class="fas fa-download"></i>
                            </button>
                        @endif
                        <button 
                            wire:click="hapusPembayaran({{ $item->id }})"
                            wire:confirm="Apakah Anda yakin ingin menghapus pembayaran ini?"
                            class="text-red-600 hover:text-red-900 text-xs px-2 py-1 bg-red-50 rounded"
                            title="Hapus"
                        >
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            @empty
                <div class="p-4 text-center">
                    <div class="text-gray-500 dark:text-gray-400">
                        <i class="fas fa-receipt text-4xl mb-4"></i>
                        <p class="text-lg font-medium">Tidak ada data pembayaran</p>
                        <p class="text-sm mt-2">Mulai dengan menambahkan pembayaran manual</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Desktop Table View -->
        <div class="hidden sm:block overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-3 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal</th>
                        <th class="px-3 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Siswa</th>
                        <th class="px-3 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Bulan</th>
                        <th class="px-3 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jumlah</th>
                        <th class="px-3 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-3 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($pembayaran as $item)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-3 py-2 sm:px-6 sm:py-4 whitespace-nowrap">
                                <div class="text-xs sm:text-sm text-gray-900 dark:text-white">
                                    {{ $item->tanggal_bayar->format('d M Y') }}
                                </div>
                            </td>
                            <td class="px-3 py-2 sm:px-6 sm:py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-6 w-6 sm:h-8 sm:w-8 flex-shrink-0">
                                        <img class="h-6 w-6 sm:h-8 sm:w-8 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($item->siswa->nama) }}&background=random" alt="">
                                    </div>
                                    <div class="ml-2 sm:ml-3">
                                        <div class="text-xs sm:text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $item->siswa->nama }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $item->siswa->nis }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-3 py-2 sm:px-6 sm:py-4 whitespace-nowrap">
                                <div class="text-xs sm:text-sm text-gray-900 dark:text-white">
                                    {{ $item->tagihanSpp->bulan }} {{ $item->tagihanSpp->tahun }}
                                </div>
                            </td>
                            <td class="px-3 py-2 sm:px-6 sm:py-4 whitespace-nowrap">
                                <div class="text-xs sm:text-sm text-gray-900 dark:text-white">
                                    Rp {{ number_format($item->jumlah_bayar, 0, ',', '.') }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ ucfirst($item->metode_bayar) }}
                                </div>
                            </td>
                            <td class="px-3 py-2 sm:px-6 sm:py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $item->status_badge }}">
                                    {{ $item->status_text }}
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
                                    <button 
                                        wire:click="openEditModal({{ $item->id }})"
                                        class="text-yellow-600 hover:text-yellow-900 text-xs px-2 py-1 bg-yellow-50 rounded"
                                        title="Edit"
                                    >
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    @if($item->status_verifikasi === 'pending')
                                        <button 
                                            wire:click="openVerifikasi({{ $item->id }}, 'diterima')"
                                            class="text-green-600 hover:text-green-900 text-xs px-2 py-1 bg-green-50 rounded"
                                            title="Terima"
                                        >
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button 
                                            wire:click="openVerifikasi({{ $item->id }}, 'ditolak')"
                                            class="text-red-600 hover:text-red-900 text-xs px-2 py-1 bg-red-50 rounded"
                                            title="Tolak"
                                        >
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @endif
                                    <!-- Tampilkan tombol download jika ada bukti_upload ATAU bukti_bayar -->
                                    @if($item->bukti_upload || $item->bukti_bayar)
                                        <button 
                                            wire:click="downloadBukti({{ $item->id }})"
                                            class="text-purple-600 hover:text-purple-900 text-xs px-2 py-1 bg-purple-50 rounded"
                                            title="Download Bukti"
                                        >
                                            <i class="fas fa-download"></i>
                                        </button>
                                    @endif
                                    <button 
                                        wire:click="hapusPembayaran({{ $item->id }})"
                                        wire:confirm="Apakah Anda yakin ingin menghapus pembayaran ini?"
                                        class="text-red-600 hover:text-red-900 text-xs px-2 py-1 bg-red-50 rounded"
                                        title="Hapus"
                                    >
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center">
                                <div class="text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-receipt text-4xl mb-4"></i>
                                    <p class="text-lg font-medium">Tidak ada data pembayaran</p>
                                    <p class="text-sm mt-2">Mulai dengan menambahkan pembayaran manual</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-3 sm:px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $pembayaran->links() }}
        </div>
    </div>

    <!-- Modal Tambah -->
    @if($showTambahModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg max-w-md w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Tambah Pembayaran Manual</h3>
                    <button wire:click="closeTambahModal" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form wire:submit="simpanPembayaran">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Siswa</label>
                            <select wire:model="siswaId" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                                <option value="">Pilih Siswa</option>
                                @foreach($siswaList as $siswa)
                                    <option value="{{ $siswa->id }}">{{ $siswa->nama }} ({{ $siswa->nis }}) - {{ $siswa->kelas->nama_kelas ?? '-' }}</option>
                                @endforeach
                            </select>
                            @error('siswaId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tagihan</label>
                            <select wire:model="tagihanSppId" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                                <option value="">Pilih Tagihan</option>
                                @foreach($this->tagihanList as $tagihan)
                                    <option value="{{ $tagihan->id }}">{{ $tagihan->bulan }} {{ $tagihan->tahun }} - Rp {{ number_format($tagihan->jumlah, 0, ',', '.') }}</option>
                                @endforeach
                            </select>
                            @error('tagihanSppId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jumlah Bayar</label>
                            <input type="number" wire:model="jumlahBayar" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white" placeholder="0">
                            @error('jumlahBayar') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Bayar</label>
                            <input type="date" wire:model="tanggalBayar" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                            @error('tanggalBayar') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Metode Bayar</label>
                            <select wire:model="metodeBayar" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                                <option value="tunai">Tunai</option>
                                <option value="transfer">Transfer</option>
                                <option value="qris">QRIS</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" wire:click="closeModal" class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal Edit -->
    @if($showEditModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg max-w-md w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Edit Pembayaran</h3>
                    <button wire:click="closeEditModal" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form wire:submit="updatePembayaran">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Siswa</label>
                            <select wire:model="siswaId" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                                @foreach($siswaList as $siswa)
                                    <option value="{{ $siswa->id }}">{{ $siswa->nama }} ({{ $siswa->nis }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tagihan</label>
                            <select wire:model="tagihanSppId" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                                @foreach($this->tagihanList as $tagihan)
                                    <option value="{{ $tagihan->id }}">{{ $tagihan->bulan }} {{ $tagihan->tahun }} - Rp {{ number_format($tagihan->jumlah, 0, ',', '.') }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jumlah Bayar</label>
                            <input type="number" wire:model="jumlahBayar" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Bayar</label>
                            <input type="date" wire:model="tanggalBayar" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Metode Bayar</label>
                            <select wire:model="metodeBayar" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                                <option value="tunai">Tunai</option>
                                <option value="transfer">Transfer</option>
                                <option value="qris">QRIS</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                            <select wire:model="statusVerifikasi" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                                <option value="pending">Pending</option>
                                <option value="diterima">Diterima</option>
                                <option value="ditolak">Ditolak</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" wire:click="closeModal" class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal Verifikasi -->
    @if($showVerifikasiModal && $selectedPembayaran)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg max-w-md w-full">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                    {{ $verifikasiAction === 'diterima' ? 'Terima Pembayaran' : 'Tolak Pembayaran' }}
                </h3>
                
                <p class="text-gray-600 dark:text-gray-400 mb-4">
                    Apakah Anda yakin ingin {{ $verifikasiAction === 'diterima' ? 'menerima' : 'menolak' }} pembayaran dari 
                    <strong>{{ $selectedPembayaran->siswa->nama }}</strong> sebesar 
                    <strong>Rp {{ number_format($selectedPembayaran->jumlah_bayar, 0, ',', '.') }}</strong>?
                </p>

                @if($verifikasiAction === 'ditolak')
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Alasan Penolakan</label>
                        <textarea 
                            wire:model="catatanVerifikasi"
                            rows="3"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                            placeholder="Berikan alasan penolakan..."
                            required
                        ></textarea>
                        @error('catatanVerifikasi') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                @endif

                <div class="flex justify-end space-x-3">
                    <button wire:click="closeVerifikasi" class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200">
                        Batal
                    </button>
                    <button 
                        wire:click="prosesVerifikasi"
                        class="px-4 py-2 rounded-lg text-white {{ $verifikasiAction === 'diterima' ? 'bg-green-600 hover:bg-green-700' : 'bg-red-600 hover:bg-red-700' }}"
                    >
                        {{ $verifikasiAction === 'diterima' ? 'Terima' : 'Tolak' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal Detail -->
    @if($showDetailModal && $selectedPembayaran)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Detail Pembayaran</h3>
                    <button wire:click="closeDetail" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <!-- Grid Informasi -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Informasi Siswa -->
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <h4 class="font-medium text-gray-900 dark:text-white mb-3 border-b pb-2">Informasi Siswa</h4>
                        <div class="space-y-3">
                            <div>
                                <label class="text-sm font-medium text-gray-600 dark:text-gray-400 block">Siswa</label>
                                <p class="text-gray-900 dark:text-white font-semibold">{{ $selectedPembayaran->siswa->nama }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-600 dark:text-gray-400 block">NIS</label>
                                <p class="text-gray-900 dark:text-white">{{ $selectedPembayaran->siswa->nis }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-600 dark:text-gray-400 block">Kelas</label>
                                <p class="text-gray-900 dark:text-white">{{ $selectedPembayaran->siswa->kelas->nama_kelas ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Pembayaran -->
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <h4 class="font-medium text-gray-900 dark:text-white mb-3 border-b pb-2">Informasi Pembayaran</h4>
                        <div class="space-y-3">
                            <div>
                                <label class="text-sm font-medium text-gray-600 dark:text-gray-400 block">Tanggal Bayar</label>
                                <p class="text-gray-900 dark:text-white">{{ $selectedPembayaran->tanggal_bayar->format('d M Y') }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-600 dark:text-gray-400 block">Jumlah Bayar</label>
                                <p class="text-gray-900 dark:text-white font-semibold text-lg">Rp {{ number_format($selectedPembayaran->jumlah_bayar, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-600 dark:text-gray-400 block">Metode Bayar</label>
                                <p class="text-gray-900 dark:text-white">{{ $selectedPembayaran->metode_bayar_text }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-600 dark:text-gray-400 block">Status</label>
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full {{ $selectedPembayaran->status_badge }}">
                                    <i class="{{ $selectedPembayaran->status_icon }} mr-1"></i>
                                    {{ $selectedPembayaran->status_text }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informasi Tagihan -->
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg mb-6">
                    <h4 class="font-medium text-gray-900 dark:text-white mb-3 border-b pb-2">Informasi Tagihan</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-600 dark:text-gray-400 block">Bulan Tagihan</label>
                            <p class="text-gray-900 dark:text-white">{{ $selectedPembayaran->tagihanSpp->bulan }} {{ $selectedPembayaran->tagihanSpp->tahun }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600 dark:text-gray-400 block">Jumlah Tagihan</label>
                            <p class="text-gray-900 dark:text-white">Rp {{ number_format($selectedPembayaran->tagihanSpp->jumlah_tagihan, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600 dark:text-gray-400 block">Status Tagihan</label>
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $selectedPembayaran->tagihanSpp->status === 'lunas' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $selectedPembayaran->tagihanSpp->status === 'lunas' ? 'Lunas' : 'Belum Bayar' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- BUKTI UPLOAD DARI SISWA -->
                @if($selectedPembayaran->bukti_upload)
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 p-4 rounded-lg mb-6">
                    <h4 class="font-medium text-blue-900 dark:text-blue-100 mb-3 flex items-center">
                        <i class="fas fa-upload mr-2"></i>
                        Bukti Upload dari Siswa
                    </h4>
                    
                    <div class="flex flex-col md:flex-row gap-4 items-start">
                        <!-- Preview Gambar -->
                        <div class="flex-shrink-0">
                            <img src="{{ Storage::url('bukti-upload/' . $selectedPembayaran->bukti_upload) }}" 
                                 alt="Bukti Upload dari Siswa" 
                                 class="w-64 h-64 object-cover rounded-lg border border-blue-300 dark:border-blue-600 cursor-pointer shadow-md"
                                 onclick="window.open('{{ Storage::url('bukti-upload/' . $selectedPembayaran->bukti_upload) }}', '_blank')">
                            <p class="text-xs text-blue-600 dark:text-blue-400 mt-2 text-center">Klik untuk melihat ukuran penuh</p>
                        </div>
                        
                        <!-- Informasi File -->
                        <div class="flex-1">
                            <div class="space-y-2">
                                <div>
                                    <label class="text-sm font-medium text-blue-700 dark:text-blue-300 block">Nama File</label>
                                    <p class="text-blue-900 dark:text-blue-100 text-sm">{{ $selectedPembayaran->bukti_upload }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-blue-700 dark:text-blue-300 block">Tanggal Upload</label>
                                    <p class="text-blue-900 dark:text-blue-100 text-sm">
                                        {{ $selectedPembayaran->tanggal_upload ? $selectedPembayaran->tanggal_upload->format('d M Y H:i') : 'Tidak tersedia' }}
                                    </p>
                                </div>
                                <div class="pt-2">
                                    <button 
                                        wire:click="downloadBukti({{ $selectedPembayaran->id }})"
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition flex items-center"
                                    >
                                        <i class="fas fa-download mr-2"></i>
                                        Download Bukti Upload
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- BUKTI BAYAR LAINNYA (jika ada) -->
                @if($selectedPembayaran->bukti_bayar && !$selectedPembayaran->bukti_upload)
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg mb-6">
                    <h4 class="font-medium text-gray-900 dark:text-white mb-3">Bukti Bayar</h4>
                    <div class="flex items-center gap-4">
                        <img src="{{ Storage::url($selectedPembayaran->bukti_bayar) }}" 
                             alt="Bukti Bayar" 
                             class="w-32 h-32 object-cover rounded-lg border border-gray-300 dark:border-gray-600 cursor-pointer"
                             onclick="window.open('{{ Storage::url($selectedPembayaran->bukti_bayar) }}', '_blank')">
                        <div>
                            <button 
                                wire:click="downloadBukti({{ $selectedPembayaran->id }})"
                                class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition flex items-center"
                            >
                                <i class="fas fa-download mr-2"></i>
                                Download Bukti
                            </button>
                        </div>
                    </div>
                </div>
                @endif

                <!-- CATATAN -->
                @if($selectedPembayaran->catatan)
                <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 p-4 rounded-lg mb-6">
                    <h4 class="font-medium text-yellow-800 dark:text-yellow-200 mb-2 flex items-center">
                        <i class="fas fa-sticky-note mr-2"></i>
                        Catatan
                    </h4>
                    <p class="text-yellow-700 dark:text-yellow-300 whitespace-pre-wrap">{{ $selectedPembayaran->catatan }}</p>
                </div>
                @endif

                <!-- INFORMASI VERIFIKASI -->
                @if($selectedPembayaran->verified_by)
                <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 p-4 rounded-lg">
                    <h4 class="font-medium text-green-800 dark:text-green-200 mb-3 border-b pb-2">Informasi Verifikasi</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-green-700 dark:text-green-300 block">Diverifikasi Oleh</label>
                            <p class="text-green-900 dark:text-green-100">{{ $selectedPembayaran->verifikator->name ?? 'Tidak diketahui' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-green-700 dark:text-green-300 block">Tanggal Verifikasi</label>
                            <p class="text-green-900 dark:text-green-100">{{ $selectedPembayaran->verified_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- TOMBOL AKSI -->
                @if($selectedPembayaran->status_verifikasi === 'pending')
                <div class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-3 mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <button 
                        wire:click="openVerifikasiFromDetail({{ $selectedPembayaran->id }}, 'diterima')"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition flex items-center justify-center"
                    >
                        <i class="fas fa-check mr-2"></i>
                        Terima Pembayaran
                    </button>
                    <button 
                        wire:click="openVerifikasiFromDetail({{ $selectedPembayaran->id }}, 'ditolak')"
                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition flex items-center justify-center"
                    >
                        <i class="fas fa-times mr-2"></i>
                        Tolak Pembayaran
                    </button>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Flash Message -->
    @if(session()->has('success'))
        <div class="fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50 max-w-sm">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <span class="text-sm">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session()->has('error'))
        <div class="fixed top-4 right-4 bg-red-500 text-white px-4 py-2 rounded-lg shadow-lg z-50 max-w-sm">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <span class="text-sm">{{ session('error') }}</span>
            </div>
        </div>
    @endif
</div>