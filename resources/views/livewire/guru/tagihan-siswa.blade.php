<div>
    <div class="py-4 sm:py-6">
        <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-6">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 sm:mb-6 gap-3">
                <div class="flex-1 min-w-0">
                    <h2 class="text-xl sm:text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:truncate">
                         Tagihan SPP Siswa
                    </h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Monitor dan kelola tagihan SPP siswa
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
                                <i class="fas fa-file-invoice text-blue-600 dark:text-blue-400 text-sm"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Tagihan</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $totalTagihan }}</p>
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
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Lunas</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $lunasCount }}</p>
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
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Belum Bayar</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $belumBayarCount }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-red-100 dark:bg-red-900 rounded-lg flex items-center justify-center">
                                <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400 text-sm"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Tertunggak</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $tertunggakCount }}</p>
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

                            <!-- Bulan Filter -->
                            <select wire:model="bulanFilter" 
                                    class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                @foreach($bulanList as $index => $bulan)
                                <option value="{{ $index + 1 }}">{{ $bulan }}</option>
                                @endforeach
                            </select>

                            <!-- Tahun Filter -->
                            <select wire:model="tahunFilter" 
                                    class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                @foreach($tahunList as $tahun)
                                <option value="{{ $tahun }}">{{ $tahun }}</option>
                                @endforeach
                            </select>

                            <!-- Status Filter -->
                            <select wire:model="statusFilter" 
                                    class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                <option value="">Semua Status</option>
                                <option value="lunas">Lunas</option>
                                <option value="belum_bayar">Belum Bayar</option>
                                <option value="tertunggak">Tertunggak</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile Card View -->
            <div class="sm:hidden space-y-4">
                @if($tagihanList->isNotEmpty())
                    @foreach($tagihanList as $tagihan)
                    @php
                        // Null safety untuk data tagihan
                        $siswa = $tagihan->siswa;
                        $kelas = $siswa->kelas ?? null;
                        $statusColor = $tagihan->status_color ?? 'gray';
                        $statusText = $tagihan->status_lengkap ?? 'Unknown';
                        $isTelat = $tagihan->isOverdue() ?? false;
                        $totalPembayaran = $tagihan->total_pembayaran ?? 0;
                        $jatuhTempo = $tagihan->tanggal_jatuh_tempo ?? now();
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
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $statusColor }}-100 text-{{ $statusColor }}-800 dark:bg-{{ $statusColor }}-900 dark:text-{{ $statusColor }}-200">
                                @if($isTelat)
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                @elseif($tagihan->status === 'lunas')
                                <i class="fas fa-check mr-1"></i>
                                @else
                                <i class="fas fa-clock mr-1"></i>
                                @endif
                                {{ $statusText }}
                            </span>
                        </div>
                        
                        <div class="space-y-2 mb-3">
                            <div class="flex justify-between">
                                <span class="text-xs text-gray-500 dark:text-gray-400">Periode</span>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $tagihan->bulan }} {{ $tagihan->tahun }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-xs text-gray-500 dark:text-gray-400">Jumlah</span>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">Rp {{ number_format($tagihan->jumlah_tagihan ?? 0, 0, ',', '.') }}</span>
                            </div>
                            @if($jatuhTempo)
                            <div class="flex justify-between">
                                <span class="text-xs text-gray-500 dark:text-gray-400">Jatuh Tempo</span>
                                <span class="text-sm font-medium text-{{ $isTelat ? 'red' : 'gray' }}-600 dark:text-{{ $isTelat ? 'red' : 'gray' }}-400">
                                    {{ $jatuhTempo->format('d/m/Y') }}
                                </span>
                            </div>
                            @endif
                        </div>
                        
                        <div class="flex justify-between items-center pt-3 border-t border-gray-200 dark:border-gray-700">
                            <div class="flex space-x-2">
                                <button wire:click="lihatDetail({{ $tagihan->id }})" 
                                        class="inline-flex items-center text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 text-xs px-2 py-1 rounded hover:bg-blue-50 dark:hover:bg-blue-900/20">
                                    <i class="fas fa-eye mr-1"></i> Detail
                                </button>
                                
                                @if($tagihan->status !== 'lunas')
                                <button wire:click="ingatkanSiswa({{ $tagihan->id }})" 
                                        class="inline-flex items-center text-orange-600 hover:text-orange-900 dark:text-orange-400 dark:hover:text-orange-300 text-xs px-2 py-1 rounded hover:bg-orange-50 dark:hover:bg-orange-900/20">
                                    <i class="fas fa-bell mr-1"></i> Ingatkan
                                </button>
                                @endif
                            </div>
                            
                            <!-- Disabled Delete Button -->
                            <button disabled
                                    class="inline-flex items-center text-gray-400 cursor-not-allowed text-xs px-2 py-1 rounded">
                                <i class="fas fa-trash mr-1"></i> Hapus
                            </button>
                        </div>
                    </div>
                    @endforeach
                    
                    <!-- Pagination for Mobile -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                        <div class="flex flex-col items-center gap-3">
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                Menampilkan {{ $tagihanList->firstItem() }} - {{ $tagihanList->lastItem() }} dari {{ $tagihanList->total() }} tagihan
                            </div>
                            {{ $tagihanList->links() }}
                        </div>
                    </div>
                @else
                <!-- Empty State -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 text-center">
                    <div class="mx-auto w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-file-invoice text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Tidak ada tagihan</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-4">
                        Tidak ditemukan tagihan SPP yang sesuai dengan filter yang dipilih.
                    </p>
                    <button wire:click="$set(['kelasFilter' => '', 'statusFilter' => '', 'search' => ''])" 
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-refresh mr-2"></i>
                        Reset Filter
                    </button>
                </div>
                @endif
            </div>

            <!-- Desktop Table View -->
            <div class="hidden sm:block bg-white dark:bg-gray-800 rounded-lg shadow">
                @if($tagihanList->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Siswa
                                </th>
                                <th class="hidden sm:table-cell px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Periode
                                </th>
                                <th class="hidden md:table-cell px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Jumlah
                                </th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="hidden lg:table-cell px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Jatuh Tempo
                                </th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($tagihanList as $tagihan)
                            @php
                                // Null safety untuk data tagihan
                                $siswa = $tagihan->siswa;
                                $kelas = $siswa->kelas ?? null;
                                $statusColor = $tagihan->status_color ?? 'gray';
                                $statusText = $tagihan->status_lengkap ?? 'Unknown';
                                $isTelat = $tagihan->isOverdue() ?? false;
                                $totalPembayaran = $tagihan->total_pembayaran ?? 0;
                                $jatuhTempo = $tagihan->tanggal_jatuh_tempo ?? now();
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                <td class="px-3 py-4">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 flex-shrink-0 bg-gray-200 dark:bg-gray-600 rounded-full flex items-center justify-center">
                                            <i class="fas fa-user text-gray-500 dark:text-gray-400"></i>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $siswa->nama_lengkap ?? 'N/A' }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                NIS: {{ $siswa->nis ?? 'N/A' }} • {{ $kelas->nama_kelas ?? 'Kelas' }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="sm:hidden text-xs text-gray-500 dark:text-gray-400 mt-2">
                                        {{ $tagihan->bulan }} {{ $tagihan->tahun }} • 
                                        Rp {{ number_format($totalPembayaran, 0, ',', '.') }}
                                    </div>
                                </td>
                                <td class="hidden sm:table-cell px-3 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    {{ $tagihan->bulan }} {{ $tagihan->tahun }}
                                </td>
                                <td class="hidden md:table-cell px-3 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    <div class="flex flex-col">
                                        <span>Rp {{ number_format($tagihan->jumlah_tagihan ?? 0, 0, ',', '.') }}</span>
                                        @if(($tagihan->denda ?? 0) > 0)
                                        <span class="text-xs text-red-600 dark:text-red-400">
                                            + Denda: Rp {{ number_format($tagihan->denda ?? 0, 0, ',', '.') }}
                                        </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $statusColor }}-100 text-{{ $statusColor }}-800 dark:bg-{{ $statusColor }}-900 dark:text-{{ $statusColor }}-200">
                                        @if($isTelat)
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        @elseif($tagihan->status === 'lunas')
                                        <i class="fas fa-check mr-1"></i>
                                        @else
                                        <i class="fas fa-clock mr-1"></i>
                                        @endif
                                        {{ $statusText }}
                                    </span>
                                    @if($isTelat && $jatuhTempo)
                                    <div class="text-xs text-red-600 dark:text-red-400 mt-1">
                                        Telat {{ now()->diffInDays($jatuhTempo) }} hari
                                    </div>
                                    @endif
                                </td>
                                <td class="hidden lg:table-cell px-3 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    @if($jatuhTempo)
                                    <div>{{ $jatuhTempo->format('d/m/Y') }}</div>
                                    <div class="text-xs text-{{ $isTelat ? 'red' : 'gray' }}-600 dark:text-{{ $isTelat ? 'red' : 'gray' }}-400">
                                        {{ $jatuhTempo->diffForHumans() }}
                                    </div>
                                    @else
                                    <div class="text-gray-400">-</div>
                                    @endif
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-1">
                                        <button wire:click="lihatDetail({{ $tagihan->id }})" 
                                                class="inline-flex items-center text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 text-xs px-2 py-1 rounded hover:bg-blue-50 dark:hover:bg-blue-900/20">
                                            <i class="fas fa-eye mr-1"></i> Detail
                                        </button>
                                        
                                        @if($tagihan->status !== 'lunas')
                                        <button wire:click="ingatkanSiswa({{ $tagihan->id }})" 
                                                class="inline-flex items-center text-orange-600 hover:text-orange-900 dark:text-orange-400 dark:hover:text-orange-300 text-xs px-2 py-1 rounded hover:bg-orange-50 dark:hover:bg-orange-900/20">
                                            <i class="fas fa-bell mr-1"></i> Ingatkan
                                        </button>
                                        @endif
                                        
                                        <!-- Disabled Delete Button -->
                                        <button disabled
                                                class="inline-flex items-center text-gray-400 cursor-not-allowed text-xs px-2 py-1 rounded">
                                            <i class="fas fa-trash mr-1"></i> Hapus
                                        </button>
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
                            Menampilkan {{ $tagihanList->firstItem() }} - {{ $tagihanList->lastItem() }} dari {{ $tagihanList->total() }} tagihan
                        </div>
                        <div class="flex-1 sm:flex-none">
                            {{ $tagihanList->links() }}
                        </div>
                    </div>
                </div>
                @else
                <!-- Empty State -->
                <div class="text-center py-12">
                    <div class="mx-auto w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-file-invoice text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Tidak ada tagihan</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-4 max-w-md mx-auto">
                        Tidak ditemukan tagihan SPP yang sesuai dengan filter yang dipilih.
                    </p>
                    <button wire:click="$set(['kelasFilter' => '', 'statusFilter' => '', 'search' => ''])" 
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-refresh mr-2"></i>
                        Reset Filter
                    </button>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal Detail Tagihan -->
    @if($showDetailModal && $selectedTagihan)
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
                            Detail Tagihan SPP
                        </h3>
                        <button wire:click="$set('showDetailModal', false)" 
                                class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                            <i class="fas fa-times text-lg"></i>
                        </button>
                    </div>
                    
                    <div class="space-y-4">
                        <!-- Header Info -->
                        <div class="flex items-start space-x-3 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="h-10 w-10 flex-shrink-0 bg-gray-200 dark:bg-gray-600 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-gray-500 dark:text-gray-400"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ $selectedTagihan->siswa->nama_lengkap ?? 'N/A' }}
                                </h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    NIS: {{ $selectedTagihan->siswa->nis ?? 'N/A' }} • 
                                    {{ $selectedTagihan->siswa->kelas->nama_kelas ?? 'Kelas' }} • 
                                    {{ $selectedTagihan->bulan }} {{ $selectedTagihan->tahun }}
                                </p>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $selectedTagihan->status_color ?? 'gray' }}-100 text-{{ $selectedTagihan->status_color ?? 'gray' }}-800 dark:bg-{{ $selectedTagihan->status_color ?? 'gray' }}-900 dark:text-{{ $selectedTagihan->status_color ?? 'gray' }}-200">
                                @if($selectedTagihan->isOverdue() ?? false)
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                @elseif($selectedTagihan->status === 'lunas')
                                <i class="fas fa-check mr-1"></i>
                                @else
                                <i class="fas fa-clock mr-1"></i>
                                @endif
                                {{ $selectedTagihan->status_lengkap ?? 'Unknown' }}
                            </span>
                        </div>

                        <!-- Rincian Tagihan -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Rincian Tagihan
                            </label>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">Jumlah Tagihan</span>
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">Rp {{ number_format($selectedTagihan->jumlah_tagihan ?? 0, 0, ',', '.') }}</span>
                                    </div>
                                    @if(($selectedTagihan->denda ?? 0) > 0)
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">Denda</span>
                                        <span class="text-sm font-medium text-red-600 dark:text-red-400">Rp {{ number_format($selectedTagihan->denda ?? 0, 0, ',', '.') }}</span>
                                    </div>
                                    @endif
                                    <div class="border-t border-gray-200 dark:border-gray-600 pt-2 mt-2">
                                        <div class="flex justify-between">
                                            <span class="text-sm font-medium text-gray-900 dark:text-white">Total</span>
                                            <span class="text-sm font-bold text-gray-900 dark:text-white">Rp {{ number_format(($selectedTagihan->jumlah_tagihan ?? 0) + ($selectedTagihan->denda ?? 0), 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Jatuh Tempo -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Jatuh Tempo
                            </label>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $selectedTagihan->tanggal_jatuh_tempo ? $selectedTagihan->tanggal_jatuh_tempo->format('d/m/Y') : '-' }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            {{ $selectedTagihan->tanggal_jatuh_tempo ? $selectedTagihan->tanggal_jatuh_tempo->diffForHumans() : '-' }}
                                        </p>
                                    </div>
                                    @if($selectedTagihan->isOverdue() ?? false)
                                    <div class="text-sm text-red-600 dark:text-red-400">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        Telat {{ now()->diffInDays($selectedTagihan->tanggal_jatuh_tempo) }} hari
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Riwayat Pembayaran -->
                        @if($selectedTagihan->pembayaran && $selectedTagihan->pembayaran->count() > 0)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Riwayat Pembayaran
                            </label>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <div class="space-y-3">
                                    @foreach($selectedTagihan->pembayaran as $pembayaran)
                                    <div class="flex items-center justify-between pb-3 border-b border-gray-200 dark:border-gray-600 last:border-0">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                Rp {{ number_format($pembayaran->jumlah ?? 0, 0, ',', '.') }}
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $pembayaran->tanggal_pembayaran ? $pembayaran->tanggal_pembayaran->format('d/m/Y H:i') : '-' }}
                                            </p>
                                        </div>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                            <i class="fas fa-check mr-1"></i>
                                            Lunas
                                        </span>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="mt-5 sm:mt-6 flex flex-col sm:flex-row sm:justify-end gap-3">
                    @if($selectedTagihan->status !== 'lunas')
                    <button wire:click="ingatkanSiswa({{ $selectedTagihan->id }})" 
                            type="button"
                            class="w-full sm:w-auto inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-orange-600 text-base font-medium text-white hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 sm:text-sm">
                        <i class="fas fa-bell mr-2"></i>
                        Ingatkan Siswa
                    </button>
                    @endif
                    <button wire:click="$set('showDetailModal', false)" 
                            type="button"
                            class="w-full sm:w-auto inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm">
                        Tutup
                    </button>
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

    @if (session()->has('info'))
        <div class="fixed bottom-4 right-4 z-50">
            <div class="bg-blue-500 text-white px-4 sm:px-6 py-3 rounded-lg shadow-lg flex items-center max-w-xs sm:max-w-md">
                <i class="fas fa-info-circle mr-2"></i>
                <span class="text-sm">{{ session('info') }}</span>
            </div>
        </div>
    @endif
</div>