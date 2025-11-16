<div>
    <div class="py-4 md:py-6">
        <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-8">
            <!-- Header -->
            <div class="mb-4 md:mb-8">
                <h2 class="text-xl md:text-2xl font-bold text-gray-900 dark:text-white">Tagihan SPP</h2>
                <p class="mt-1 md:mt-2 text-sm md:text-base text-gray-600 dark:text-gray-400">Kelola pembayaran SPP Anda</p>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-6 mb-4 md:mb-8">
                <!-- Total Tagihan -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 md:p-6">
                    <div class="flex items-center">
                        <div class="p-2 md:p-3 rounded-full bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400">
                            <i class="fas fa-file-invoice text-lg md:text-xl"></i>
                        </div>
                        <div class="ml-3 md:ml-4">
                            <p class="text-xs md:text-sm font-medium text-gray-600 dark:text-gray-400">Total Tagihan</p>
                            <p class="text-lg md:text-2xl font-bold text-gray-900 dark:text-white">{{ $totalTagihan }}</p>
                        </div>
                    </div>
                </div>

                <!-- Lunas -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 md:p-6">
                    <div class="flex items-center">
                        <div class="p-2 md:p-3 rounded-full bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400">
                            <i class="fas fa-check-circle text-lg md:text-xl"></i>
                        </div>
                        <div class="ml-3 md:ml-4">
                            <p class="text-xs md:text-sm font-medium text-gray-600 dark:text-gray-400">Lunas</p>
                            <p class="text-lg md:text-2xl font-bold text-gray-900 dark:text-white">{{ $totalLunas }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                Rp {{ number_format($totalNominalLunas, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Belum Bayar -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 md:p-6">
                    <div class="flex items-center">
                        <div class="p-2 md:p-3 rounded-full bg-yellow-100 dark:bg-yellow-900 text-yellow-600 dark:text-yellow-400">
                            <i class="fas fa-clock text-lg md:text-xl"></i>
                        </div>
                        <div class="ml-3 md:ml-4">
                            <p class="text-xs md:text-sm font-medium text-gray-600 dark:text-gray-400">Belum Bayar</p>
                            <p class="text-lg md:text-2xl font-bold text-gray-900 dark:text-white">{{ $totalBelumBayar }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                Rp {{ number_format($totalNominalBelumBayar, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Tertunggak -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 md:p-6">
                    <div class="flex items-center">
                        <div class="p-2 md:p-3 rounded-full bg-red-100 dark:bg-red-900 text-red-600 dark:text-red-400">
                            <i class="fas fa-exclamation-triangle text-lg md:text-xl"></i>
                        </div>
                        <div class="ml-3 md:ml-4">
                            <p class="text-xs md:text-sm font-medium text-gray-600 dark:text-gray-400">Tertunggak</p>
                            <p class="text-lg md:text-2xl font-bold text-gray-900 dark:text-white">{{ $totalTertunggak }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                Rp {{ number_format($totalNominalTertunggak, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-3 md:p-6 mb-4 md:mb-6">
                <div class="flex flex-col md:flex-row gap-3 md:gap-4">
                    <!-- Search -->
                    <div class="flex-1 min-w-0">
                        <input 
                            type="text" 
                            wire:model.live="search"
                            placeholder="Cari berdasarkan bulan atau keterangan..."
                            class="w-full px-3 md:px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white text-sm md:text-base"
                        >
                    </div>

                    <!-- Filter Status -->
                    <div class="min-w-0">
                        <select 
                            wire:model.live="filterStatus"
                            class="w-full md:w-auto px-3 md:px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white text-sm md:text-base"
                        >
                            <option value="semua">Semua Status</option>
                            <option value="belum_bayar">Belum Bayar</option>
                            <option value="lunas">Lunas</option>
                            <option value="tertunggak">Tertunggak</option>
                        </select>
                    </div>

                    <!-- Filter Bulan -->
                    <div class="min-w-0">
                        <select 
                            wire:model.live="filterBulan"
                            class="w-full md:w-auto px-3 md:px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white text-sm md:text-base"
                        >
                            <option value="">Semua Bulan</option>
                            <option value="Januari">Januari</option>
                            <option value="Februari">Februari</option>
                            <option value="Maret">Maret</option>
                            <option value="April">April</option>
                            <option value="Mei">Mei</option>
                            <option value="Juni">Juni</option>
                            <option value="Juli">Juli</option>
                            <option value="Agustus">Agustus</option>
                            <option value="September">September</option>
                            <option value="Oktober">Oktober</option>
                            <option value="November">November</option>
                            <option value="Desember">Desember</option>
                        </select>
                    </div>

                    <!-- Filter Tahun -->
                    <div class="min-w-0">
                        <select 
                            wire:model.live="filterTahun"
                            class="w-full md:w-auto px-3 md:px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white text-sm md:text-base"
                        >
                            <option value="2024">2024</option>
                            <option value="2023">2023</option>
                            <option value="2022">2022</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Tagihan List -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                @if($tagihanList->count() > 0)
                    <!-- Desktop Table View -->
                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full min-w-full">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">
                                        Periode
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">
                                        Jatuh Tempo
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">
                                        Jumlah
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">
                                        Denda
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($tagihanList as $tagihan)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $tagihan->bulan }} {{ $tagihan->tahun }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 dark:text-white">
                                                {{ \Carbon\Carbon::parse($tagihan->tanggal_jatuh_tempo)->format('d M Y') }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $tagihan->jatuh_tempo_relative }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $tagihan->jumlah_tagihan_formatted }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 dark:text-white">
                                                {{ $tagihan->denda_formatted }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $statusColors = [
                                                    'belum_bayar' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                                    'lunas' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                                    'tertunggak' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
                                                ];
                                                $status = $tagihan->status ?? 'belum_bayar';
                                            @endphp
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$status] }}">
                                                {{ $tagihan->status_lengkap }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex gap-2">
                                                <button 
                                                    wire:click="lihatDetail({{ $tagihan->id }})"
                                                    class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 text-sm px-2 py-1 rounded border border-blue-200 dark:border-blue-800 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors"
                                                >
                                                    <i class="fas fa-eye mr-1"></i>
                                                    Detail
                                                </button>
                                                
                                                @if($tagihan->status === 'belum_bayar' || $tagihan->status === 'tertunggak')
                                                    <button 
                                                        wire:click="bayarTagihan({{ $tagihan->id }})"
                                                        wire:confirm="Anda yakin ingin melakukan pembayaran?"
                                                        class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 text-sm px-2 py-1 rounded border border-green-200 dark:border-green-800 hover:bg-green-50 dark:hover:bg-green-900/20 transition-colors"
                                                    >
                                                        <i class="fas fa-credit-card mr-1"></i>
                                                        Bayar
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Card View -->
                    <div class="md:hidden">
                        @foreach($tagihanList as $tagihan)
                            @php
                                $statusColors = [
                                    'belum_bayar' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                    'lunas' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                    'tertunggak' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
                                ];
                                $status = $tagihan->status ?? 'belum_bayar';
                            @endphp
                            <div class="border-b border-gray-200 dark:border-gray-700 p-4">
                                <!-- Header Card -->
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <h3 class="text-base font-semibold text-gray-900 dark:text-white">
                                            {{ $tagihan->bulan }} {{ $tagihan->tahun }}
                                        </h3>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            Jatuh Tempo: {{ \Carbon\Carbon::parse($tagihan->tanggal_jatuh_tempo)->format('d M Y') }}
                                        </p>
                                    </div>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$status] }}">
                                        {{ $tagihan->status_lengkap }}
                                    </span>
                                </div>

                                <!-- Detail Info -->
                                <div class="space-y-2 mb-3">
                                    <div class="flex justify-between items-center">
                                        <span class="text-xs text-gray-500 dark:text-gray-400">Jumlah Tagihan</span>
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $tagihan->jumlah_tagihan_formatted }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-xs text-gray-500 dark:text-gray-400">Denda</span>
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $tagihan->denda_formatted }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between items-center pt-2 border-t border-gray-200 dark:border-gray-700">
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Total Pembayaran</span>
                                        <span class="text-base font-bold text-green-600 dark:text-green-400">
                                            {{ $tagihan->total_pembayaran_formatted ?? 'Rp ' . number_format($tagihan->jumlah_tagihan + ($tagihan->denda ?? 0), 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex gap-2">
                                    <button 
                                        wire:click="lihatDetail({{ $tagihan->id }})"
                                        class="flex-1 text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 text-sm px-3 py-2 rounded border border-blue-200 dark:border-blue-800 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors flex items-center justify-center"
                                    >
                                        <i class="fas fa-eye mr-1"></i>
                                        Detail
                                    </button>
                                    
                                    @if($tagihan->status === 'belum_bayar' || $tagihan->status === 'tertunggak')
                                        <button 
                                            wire:click="bayarTagihan({{ $tagihan->id }})"
                                            wire:confirm="Anda yakin ingin melakukan pembayaran?"
                                            class="flex-1 text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 text-sm px-3 py-2 rounded border border-green-200 dark:border-green-800 hover:bg-green-50 dark:hover:bg-green-900/20 transition-colors flex items-center justify-center"
                                        >
                                            <i class="fas fa-credit-card mr-1"></i>
                                            Bayar
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="bg-white dark:bg-gray-800 px-3 md:px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                        {{ $tagihanList->links() }}
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="text-center py-8 md:py-12">
                        <div class="text-gray-400 dark:text-gray-500">
                            <i class="fas fa-file-invoice text-4xl md:text-6xl mb-3 md:mb-4"></i>
                        </div>
                        <h3 class="mt-2 text-sm md:text-base font-medium text-gray-900 dark:text-white">Tidak ada tagihan</h3>
                        <p class="mt-1 text-xs md:text-sm text-gray-500 dark:text-gray-400 max-w-sm mx-auto">
                            @if($search || $filterStatus !== 'semua' || $filterBulan || $filterTahun)
                                Coba ubah pencarian atau filter Anda.
                            @else
                                Belum ada tagihan SPP untuk akun Anda.
                            @endif
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 md:px-6 md:py-3 rounded-lg shadow-lg text-sm md:text-base max-w-xs md:max-w-sm z-50">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <span class="flex-1">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="fixed bottom-4 right-4 bg-red-500 text-white px-4 py-2 md:px-6 md:py-3 rounded-lg shadow-lg text-sm md:text-base max-w-xs md:max-w-sm z-50">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <span class="flex-1">{{ session('error') }}</span>
            </div>
        </div>
    @endif

    @if (session()->has('info'))
        <div class="fixed bottom-4 right-4 bg-blue-500 text-white px-4 py-2 md:px-6 md:py-3 rounded-lg shadow-lg text-sm md:text-base max-w-xs md:max-w-sm z-50">
            <div class="flex items-center">
                <i class="fas fa-info-circle mr-2"></i>
                <span class="flex-1">{{ session('info') }}</span>
            </div>
        </div>
    @endif
</div>

@script
<script>
    // Event listener untuk detail tagihan
    Livewire.on('show-tagihan-detail', (event) => {
        const tagihan = event.tagihan;
        
        // Create modal element
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50';
        modal.innerHTML = `
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full max-h-[90vh] overflow-y-auto">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Detail Tagihan SPP</h3>
                        <button onclick="this.closest('.fixed').remove()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Periode:</span>
                            <span class="font-medium text-gray-900 dark:text-white">${tagihan.bulan} ${tagihan.tahun}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Jumlah Tagihan:</span>
                            <span class="font-medium text-gray-900 dark:text-white">${tagihan.jumlah_tagihan_formatted}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Denda:</span>
                            <span class="font-medium text-gray-900 dark:text-white">${tagihan.denda_formatted}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Total:</span>
                            <span class="font-medium text-green-600 dark:text-green-400">${tagihan.total_pembayaran_formatted || 'Rp ' + new Intl.NumberFormat('id-ID').format(tagihan.jumlah_tagihan + (tagihan.denda || 0))}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Status:</span>
                            <span class="font-medium ${tagihan.status === 'lunas' ? 'text-green-600' : tagihan.status === 'tertunggak' ? 'text-red-600' : 'text-yellow-600'}">${tagihan.status_lengkap}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Jatuh Tempo:</span>
                            <span class="font-medium text-gray-900 dark:text-white">${new Date(tagihan.tanggal_jatuh_tempo).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })}</span>
                        </div>
                        ${tagihan.keterangan ? `
                        <div class="border-t pt-3">
                            <span class="text-gray-600 dark:text-gray-400">Keterangan:</span>
                            <p class="text-gray-900 dark:text-white mt-1">${tagihan.keterangan}</p>
                        </div>
                        ` : ''}
                    </div>
                    
                    <div class="mt-6 flex justify-end">
                        <button onclick="this.closest('.fixed').remove()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        
        // Close modal on background click
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.remove();
            }
        });
    });
</script>
@endscript