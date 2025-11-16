<div>
    <div class="py-4 sm:py-6">
        <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-6">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 sm:mb-6 gap-3">
                <div class="flex-1 min-w-0">
                    <h2 class="text-xl sm:text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:truncate">
                         Konfirmasi Pembayaran SPP
                    </h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Verifikasi dan konfirmasi pembayaran SPP siswa
                        @if($kelasWali)
                        - Wali Kelas {{ $kelasWali->nama_kelas ?? '' }}
                        @endif
                    </p>
                </div>
                <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                    <button wire:click="exportLaporan" 
                            class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 w-full sm:w-auto">
                        <i class="fas fa-download mr-2"></i>Export Laporan
                    </button>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                                <i class="fas fa-file-invoice-dollar text-blue-600 dark:text-blue-400 text-sm"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Pembayaran</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $totalPembayaran }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-yellow-100 dark:bg-yellow-900 rounded-lg flex items-center justify-center">
                                <i class="fas fa-clock text-yellow-600 dark:text-yellow-400 text-sm"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Menunggu</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $pendingCount }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                                <i class="fas fa-check text-green-600 dark:text-green-400 text-sm"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Diterima</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $diterimaCount }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-red-100 dark:bg-red-900 rounded-lg flex items-center justify-center">
                                <i class="fas fa-times text-red-600 dark:text-red-400 text-sm"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Ditolak</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $ditolakCount }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
                <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex flex-col gap-4">
                        <!-- Search -->
                        <div class="flex-1">
                            <input type="text" 
                                   wire:model.debounce.300ms="search"
                                   placeholder="Cari siswa atau NIS..."
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>

                        <!-- Filter Options -->
                        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                            <!-- Kelas Filter -->
                            @if(!$kelasWali)
                            <select wire:model="kelasFilter" 
                                    class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                <option value="">Semua Kelas</option>
                                @foreach($kelasList as $kelas)
                                <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas ?? 'Kelas' }}</option>
                                @endforeach
                            </select>
                            @endif

                            <!-- Status Filter -->
                            <select wire:model="statusFilter" 
                                    class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                <option value="">Semua Status</option>
                                <option value="pending">Menunggu</option>
                                <option value="diterima">Diterima</option>
                                <option value="ditolak">Ditolak</option>
                            </select>

                            <!-- Tanggal Filter -->
                            <input type="date" 
                                   wire:model="tanggalFilter"
                                   class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile Card View -->
            <div class="sm:hidden space-y-4">
                @if($pembayaranList->isNotEmpty())
                    @foreach($pembayaranList as $pembayaran)
                    @php
                        $siswa = $pembayaran->siswa;
                        $kelas = $siswa->kelas ?? null;
                        $tagihan = $pembayaran->tagihanSpp;
                    @endphp
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex items-center">
                                <div class="h-10 w-10 flex-shrink-0 bg-gray-200 dark:bg-gray-600 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-gray-500 dark:text-gray-400"></i>
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $siswa->nama_lengkap ?? 'N/A' }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        NIS: {{ $siswa->nis ?? 'N/A' }} • {{ $kelas->nama_kelas ?? 'Kelas' }}
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <i class="{{ $pembayaran->status_icon }} mr-1"></i>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $pembayaran->status_badge }}">
                                    {{ $pembayaran->status_text }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="space-y-2 mb-3">
                            <div class="flex justify-between">
                                <span class="text-xs text-gray-500 dark:text-gray-400">Pembayaran</span>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $pembayaran->jumlah_bayar_formatted }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-xs text-gray-500 dark:text-gray-400">Metode</span>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $pembayaran->metode_bayar_text }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-xs text-gray-500 dark:text-gray-400">Periode</span>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $tagihan->bulan ?? '' }} {{ $tagihan->tahun ?? '' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-xs text-gray-500 dark:text-gray-400">Tanggal</span>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $pembayaran->tanggal_bayar->format('d/m/Y') }}</span>
                            </div>
                        </div>
                        
                        <div class="flex justify-between items-center pt-3 border-t border-gray-200 dark:border-gray-700">
                            <div class="flex space-x-2">
                                <!-- View Detail -->
                                <button wire:click="viewDetail({{ $pembayaran->id }})" 
                                        class="inline-flex items-center text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 text-xs px-2 py-1 rounded hover:bg-blue-50 dark:hover:bg-blue-900/20">
                                    <i class="fas fa-eye mr-1"></i> Detail
                                </button>

                                <!-- Download Bukti -->
                                @if($pembayaran->bukti_bayar)
                                <button wire:click="downloadBukti({{ $pembayaran->id }})" 
                                        class="inline-flex items-center text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 text-xs px-2 py-1 rounded hover:bg-green-50 dark:hover:bg-green-900/20">
                                    <i class="fas fa-download mr-1"></i> Bukti
                                </button>
                                @endif
                            </div>
                            
                            <!-- Action Buttons for Pending -->
                            @if($pembayaran->can_verify)
                            <div class="flex space-x-2">
                                <button wire:click="verifikasiPembayaran({{ $pembayaran->id }})" 
                                        class="inline-flex items-center text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 text-xs px-2 py-1 rounded hover:bg-green-50 dark:hover:bg-green-900/20">
                                    <i class="fas fa-check mr-1"></i> Terima
                                </button>
                                <button wire:click="tolakPembayaran({{ $pembayaran->id }})" 
                                        class="inline-flex items-center text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 text-xs px-2 py-1 rounded hover:bg-red-50 dark:hover:bg-red-900/20">
                                    <i class="fas fa-times mr-1"></i> Tolak
                                </button>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                    
                    <!-- Pagination for Mobile -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                        <div class="flex flex-col items-center gap-3">
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                Menampilkan {{ $pembayaranList->firstItem() }} - {{ $pembayaranList->lastItem() }} dari {{ $pembayaranList->total() }} pembayaran
                            </div>
                            {{ $pembayaranList->links() }}
                        </div>
                    </div>
                @else
                <!-- Empty State -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 text-center">
                    <div class="mx-auto w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-file-invoice-dollar text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Tidak ada pembayaran</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-4">
                        Tidak ditemukan pembayaran SPP yang sesuai dengan filter yang dipilih.
                    </p>
                    <button wire:click="$set(['kelasFilter' => '', 'statusFilter' => '', 'search' => '', 'tanggalFilter' => ''])" 
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-refresh mr-2"></i>
                        Reset Filter
                    </button>
                </div>
                @endif
            </div>

            <!-- Desktop Table View -->
            <div class="hidden sm:block bg-white dark:bg-gray-800 rounded-lg shadow">
                @if($pembayaranList->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Siswa & Pembayaran
                                </th>
                                <th class="hidden md:table-cell px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Detail
                                </th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($pembayaranList as $pembayaran)
                            @php
                                $siswa = $pembayaran->siswa;
                                $kelas = $siswa->kelas ?? null;
                                $tagihan = $pembayaran->tagihanSpp;
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                <td class="px-3 py-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="h-10 w-10 flex-shrink-0 bg-gray-200 dark:bg-gray-600 rounded-full flex items-center justify-center">
                                            <i class="fas fa-user text-gray-500 dark:text-gray-400"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $siswa->nama_lengkap ?? 'N/A' }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                NIS: {{ $siswa->nis ?? 'N/A' }} • {{ $kelas->nama_kelas ?? 'Kelas' }}
                                            </div>
                                            <div class="text-sm text-gray-700 dark:text-gray-300 mt-1">
                                                {{ $pembayaran->jumlah_bayar_formatted }} • {{ $pembayaran->metode_bayar_text }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="md:hidden text-xs text-gray-500 dark:text-gray-400 mt-2">
                                        {{ $tagihan->bulan ?? '' }} {{ $tagihan->tahun ?? '' }} • 
                                        {{ $pembayaran->tanggal_bayar->format('d/m/Y') }}
                                    </div>
                                </td>
                                <td class="hidden md:table-cell px-3 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col">
                                        <span>{{ $tagihan->bulan ?? '' }} {{ $tagihan->tahun ?? '' }}</span>
                                        <span class="text-xs">{{ $pembayaran->tanggal_bayar->format('d F Y') }}</span>
                                    </div>
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <i class="{{ $pembayaran->status_icon }} mr-2"></i>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $pembayaran->status_badge }}">
                                            {{ $pembayaran->status_text }}
                                        </span>
                                    </div>
                                    @if($pembayaran->terverifikasi && $pembayaran->verified_at)
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        oleh {{ $pembayaran->nama_verifikator ?? 'Admin' }}
                                    </div>
                                    @endif
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-1">
                                        <!-- View Detail -->
                                        <button wire:click="viewDetail({{ $pembayaran->id }})" 
                                                class="inline-flex items-center text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 text-xs px-2 py-1 rounded hover:bg-blue-50 dark:hover:bg-blue-900/20">
                                            <i class="fas fa-eye mr-1"></i> Detail
                                        </button>

                                        <!-- Download Bukti -->
                                        @if($pembayaran->bukti_bayar)
                                        <button wire:click="downloadBukti({{ $pembayaran->id }})" 
                                                class="inline-flex items-center text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 text-xs px-2 py-1 rounded hover:bg-green-50 dark:hover:bg-green-900/20">
                                            <i class="fas fa-download mr-1"></i> Bukti
                                        </button>
                                        @endif

                                        <!-- Action Buttons for Pending -->
                                        @if($pembayaran->can_verify)
                                        <button wire:click="verifikasiPembayaran({{ $pembayaran->id }})" 
                                                class="inline-flex items-center text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 text-xs px-2 py-1 rounded hover:bg-green-50 dark:hover:bg-green-900/20">
                                            <i class="fas fa-check mr-1"></i> Terima
                                        </button>
                                        <button wire:click="tolakPembayaran({{ $pembayaran->id }})" 
                                                class="inline-flex items-center text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 text-xs px-2 py-1 rounded hover:bg-red-50 dark:hover:bg-red-900/20">
                                            <i class="fas fa-times mr-1"></i> Tolak
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="bg-white dark:bg-gray-800 px-3 sm:px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            Menampilkan {{ $pembayaranList->firstItem() }} - {{ $pembayaranList->lastItem() }} dari {{ $pembayaranList->total() }} pembayaran
                        </div>
                        <div class="flex-1 sm:flex-none">
                            {{ $pembayaranList->links() }}
                        </div>
                    </div>
                </div>
                @else
                <!-- Empty State -->
                <div class="text-center py-12">
                    <div class="mx-auto w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-file-invoice-dollar text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Tidak ada pembayaran</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-4 max-w-md mx-auto">
                        Tidak ditemukan pembayaran SPP yang sesuai dengan filter yang dipilih.
                    </p>
                    <button wire:click="$set(['kelasFilter' => '', 'statusFilter' => '', 'search' => '', 'tanggalFilter' => ''])" 
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-refresh mr-2"></i>
                        Reset Filter
                    </button>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal Detail Pembayaran -->
    @if($showDetailModal && $selectedPembayaran)
    <div class="fixed inset-0 overflow-y-auto z-50">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full sm:p-6 w-full">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                            Detail Pembayaran SPP
                        </h3>
                        <button wire:click="$set('showDetailModal', false)" 
                                class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                            <i class="fas fa-times text-lg"></i>
                        </button>
                    </div>
                    
                    <div class="space-y-4">
                        <!-- Info Siswa -->
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Informasi Siswa</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Nama Siswa</p>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $selectedPembayaran->siswa->nama_lengkap ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">NIS</p>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $selectedPembayaran->siswa->nis ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Kelas</p>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $selectedPembayaran->siswa->kelas->nama_kelas ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Periode</p>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $selectedPembayaran->tagihanSpp->bulan ?? '' }} {{ $selectedPembayaran->tagihanSpp->tahun ?? '' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Info Pembayaran -->
                        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Informasi Pembayaran</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Jumlah Bayar</p>
                                    <p class="text-lg font-semibold text-blue-600 dark:text-blue-400">{{ $selectedPembayaran->jumlah_bayar_formatted }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Metode Bayar</p>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $selectedPembayaran->metode_bayar_text }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Tanggal Bayar</p>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $selectedPembayaran->tanggal_bayar->format('d F Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Status</p>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $selectedPembayaran->status_badge }}">
                                        <i class="{{ $selectedPembayaran->status_icon }} mr-1"></i>
                                        {{ $selectedPembayaran->status_text }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Bukti Bayar -->
                        @if($selectedPembayaran->bukti_bayar)
                        <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4">
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Bukti Pembayaran</h4>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <i class="fas fa-file-pdf text-red-500 text-xl"></i>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">File Bukti Bayar</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Format: PDF/Image</p>
                                    </div>
                                </div>
                                <button wire:click="downloadBukti({{ $selectedPembayaran->id }})" 
                                        class="inline-flex items-center px-3 py-1 border border-green-300 dark:border-green-600 rounded-md text-sm font-medium text-green-700 dark:text-green-300 bg-green-50 dark:bg-green-900/30 hover:bg-green-100 dark:hover:bg-green-900/40 transition-colors">
                                    <i class="fas fa-download mr-1"></i> Download
                                </button>
                            </div>
                        </div>
                        @endif

                        <!-- Catatan Verifikasi -->
                        @if($selectedPembayaran->catatan)
                        <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg p-4">
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Catatan Verifikasi</h4>
                            <p class="text-sm text-gray-700 dark:text-gray-300">{{ $selectedPembayaran->catatan }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="mt-5 sm:mt-6 flex flex-col sm:flex-row sm:justify-end gap-3">
                    <button wire:click="$set('showDetailModal', false)" 
                            type="button"
                            class="w-full sm:w-auto inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm">
                        Tutup
                    </button>
                    @if($selectedPembayaran->can_verify)
                    <button wire:click="verifikasiPembayaran({{ $selectedPembayaran->id }})" 
                            type="button"
                            class="w-full sm:w-auto inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:text-sm">
                        <i class="fas fa-check mr-2"></i>
                        Terima Pembayaran
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal Verifikasi -->
    @if($showModal && $selectedPembayaran)
    <div class="fixed inset-0 overflow-y-auto z-50">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6 w-full">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                            @if($statusVerifikasi === 'diterima')
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>Terima Pembayaran
                            @else
                            <i class="fas fa-times-circle text-red-500 mr-2"></i>Tolak Pembayaran
                            @endif
                        </h3>
                        <button wire:click="resetModal" 
                                class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                            <i class="fas fa-times text-lg"></i>
                        </button>
                    </div>
                    
                    <div class="mb-4">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            @if($statusVerifikasi === 'diterima')
                            Anda akan menerima pembayaran SPP dari:
                            @else
                            Anda akan menolak pembayaran SPP dari:
                            @endif
                        </p>
                        <div class="mt-2 bg-gray-50 dark:bg-gray-700 rounded-lg p-3">
                            <p class="font-medium text-gray-900 dark:text-white">{{ $selectedPembayaran->siswa->nama_lengkap ?? 'N/A' }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ $selectedPembayaran->siswa->kelas->nama_kelas ?? 'Kelas' }} • 
                                {{ $selectedPembayaran->tagihanSpp->bulan ?? '' }} {{ $selectedPembayaran->tagihanSpp->tahun ?? '' }}
                            </p>
                            <p class="text-sm font-semibold text-blue-600 dark:text-blue-400 mt-1">
                                {{ $selectedPembayaran->jumlah_bayar_formatted }}
                            </p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="catatanVerifikasi" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Catatan (Opsional)
                        </label>
                        <textarea wire:model="catatanVerifikasi" 
                                  id="catatanVerifikasi"
                                  rows="3"
                                  placeholder="Berikan catatan verifikasi..."
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"></textarea>
                        @if($statusVerifikasi === 'ditolak')
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            Disarankan memberikan alasan penolakan untuk informasi siswa.
                        </p>
                        @endif
                    </div>

                    @if($statusVerifikasi === 'diterima')
                    <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-3 mb-4">
                        <div class="flex items-center">
                            <i class="fas fa-info-circle text-green-500 mr-2"></i>
                            <p class="text-sm text-green-700 dark:text-green-300">
                                Status tagihan SPP akan diubah menjadi <span class="font-semibold">LUNAS</span> setelah verifikasi.
                            </p>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="mt-5 sm:mt-6">
                    <div class="flex flex-col sm:flex-row sm:justify-end gap-3">
                        <button type="button" 
                                wire:click="resetModal" 
                                class="w-full sm:w-auto px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Batal
                        </button>
                        <button type="button" 
                                wire:click="prosesVerifikasi" 
                                class="w-full sm:w-auto px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white 
                                    @if($statusVerifikasi === 'diterima') bg-green-600 hover:bg-green-700 focus:ring-green-500
                                    @else bg-red-600 hover:bg-red-700 focus:ring-red-500 @endif
                                    focus:outline-none focus:ring-2 focus:ring-offset-2">
                            @if($statusVerifikasi === 'diterima')
                            <i class="fas fa-check mr-1"></i> Terima Pembayaran
                            @else
                            <i class="fas fa-times mr-1"></i> Tolak Pembayaran
                            @endif
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Flash Messages -->
    @if (session()->has('success'))
    <div class="fixed bottom-4 right-4 z-50">
        <div class="bg-green-500 text-white px-4 sm:px-6 py-3 rounded-lg shadow-lg flex items-center max-w-xs sm:max-w-md">
            <i class="fas fa-check-circle mr-2"></i>
            <span class="text-sm">{{ session('success') }}</span>
        </div>
    </div>
    @endif

    @if (session()->has('error'))
    <div class="fixed bottom-4 right-4 z-50">
        <div class="bg-red-500 text-white px-4 sm:px-6 py-3 rounded-lg shadow-lg flex items-center max-w-xs sm:max-w-md">
            <i class="fas fa-exclamation-circle mr-2"></i>
            <span class="text-sm">{{ session('error') }}</span>
        </div>
    </div>
    @endif
</div>