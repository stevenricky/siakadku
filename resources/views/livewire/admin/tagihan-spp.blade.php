<div class="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 px-4 sm:px-0">
        <h1 class="text-xl sm:text-2xl font-bold text-gray-800 dark:text-white">Tagihan SPP</h1>
        <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
            <button 
                wire:click="generateTagihan"
                class="bg-green-600 hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-600 text-white px-4 py-2 rounded-lg flex items-center justify-center space-x-2 transition-colors duration-300 w-full sm:w-auto"
            >
                <i class="fas fa-file-invoice"></i>
                <span>Generate Tagihan</span>
            </button>
            <button 
                wire:click="openCreateForm"
                class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center justify-center space-x-2 transition-colors duration-300 w-full sm:w-auto"
            >
                <i class="fas fa-plus"></i>
                <span>Tambah Tagihan</span>
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4 mb-6 px-4 sm:px-0">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 sm:p-4 transition-colors duration-300">
            <div class="flex items-center">
                <div class="p-2 rounded-full bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400">
                    <i class="fas fa-file-invoice text-sm sm:text-lg"></i>
                </div>
                <div class="ml-3">
                    <p class="text-xs font-medium text-gray-600 dark:text-gray-400">Total Tagihan</p>
                    <p class="text-base sm:text-lg sm:text-xl font-bold text-gray-900 dark:text-white">{{ $totalTagihan }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 sm:p-4 transition-colors duration-300">
            <div class="flex items-center">
                <div class="p-2 rounded-full bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400">
                    <i class="fas fa-check text-sm sm:text-lg"></i>
                </div>
                <div class="ml-3">
                    <p class="text-xs font-medium text-gray-600 dark:text-gray-400">Lunas</p>
                    <p class="text-base sm:text-lg sm:text-xl font-bold text-gray-900 dark:text-white">{{ $totalLunas }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 sm:p-4 transition-colors duration-300">
            <div class="flex items-center">
                <div class="p-2 rounded-full bg-yellow-100 dark:bg-yellow-900 text-yellow-600 dark:text-yellow-400">
                    <i class="fas fa-clock text-sm sm:text-lg"></i>
                </div>
                <div class="ml-3">
                    <p class="text-xs font-medium text-gray-600 dark:text-gray-400">Belum Bayar</p>
                    <p class="text-base sm:text-lg sm:text-xl font-bold text-gray-900 dark:text-white">{{ $totalBelumBayar }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 sm:p-4 transition-colors duration-300">
            <div class="flex items-center">
                <div class="p-2 rounded-full bg-red-100 dark:bg-red-900 text-red-600 dark:text-red-400">
                    <i class="fas fa-exclamation-triangle text-sm sm:text-lg"></i>
                </div>
                <div class="ml-3">
                    <p class="text-xs font-medium text-gray-600 dark:text-gray-400">Tertunggak</p>
                    <p class="text-base sm:text-lg sm:text-xl font-bold text-gray-900 dark:text-white">{{ $totalTertunggak }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 sm:p-4 transition-colors duration-300">
            <div class="flex items-center">
                <div class="p-2 rounded-full bg-purple-100 dark:bg-purple-900 text-purple-600 dark:text-purple-400">
                    <i class="fas fa-money-bill-wave text-sm sm:text-lg"></i>
                </div>
                <div class="ml-3">
                    <p class="text-xs font-medium text-gray-600 dark:text-gray-400">Total Pendapatan</p>
                    <p class="text-xs font-bold text-gray-900 dark:text-white break-words">
                        Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6 transition-colors duration-300 mx-4 sm:mx-0">
        <div class="p-3 sm:p-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-base sm:text-lg font-semibold text-gray-800 dark:text-white">Filter Data</h2>
        </div>
        <div class="p-3 sm:p-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 sm:gap-4">
                <!-- Search -->
                <div class="sm:col-span-2 lg:col-span-1">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pencarian</label>
                    <input 
                        type="text" 
                        wire:model.live="search"
                        placeholder="Cari NIS/Nama Siswa..."
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors duration-300 text-sm"
                    >
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                    <select 
                        wire:model.live="statusFilter"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors duration-300 text-sm"
                    >
                        <option value="">Semua Status</option>
                        @foreach($statusList as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Bulan Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Bulan</label>
                    <select 
                        wire:model.live="bulanFilter"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors duration-300 text-sm"
                    >
                        <option value="">Semua Bulan</option>
                        @foreach($bulanList as $bulan)
                            <option value="{{ $bulan }}">{{ $bulan }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Tahun Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tahun</label>
                    <input 
                        type="number" 
                        wire:model.live="tahunFilter"
                        min="2020"
                        max="2030"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors duration-300 text-sm"
                    >
                </div>

                <!-- Per Page -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Data per Halaman</label>
                    <select 
                        wire:model.live="perPage"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors duration-300 text-sm"
                    >
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
            </div>

            <!-- Reset Filters -->
            <div class="mt-4 flex justify-end">
                <button 
                    wire:click="resetFilters"
                    class="px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-300"
                >
                    Reset Filter
                </button>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden transition-colors duration-300 mx-4 sm:mx-0">
        <!-- Mobile Card View -->
        <div class="sm:hidden">
            @forelse($tagihanList as $tagihan)
                <div class="border-b border-gray-200 dark:border-gray-700 p-4">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <div class="font-medium text-gray-900 dark:text-white">{{ $tagihan->siswa->nama }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">NIS: {{ $tagihan->siswa->nis }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $tagihan->siswa->kelas->nama_kelas ?? '-' }}</div>
                        </div>
                        <span @class([
                            'px-2 py-1 text-xs font-semibold rounded-full',
                            'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' => $tagihan->status === 'belum_bayar',
                            'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' => $tagihan->status === 'lunas',
                            'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' => $tagihan->status === 'tertunggak'
                        ])>
                            {{ $statusList[$tagihan->status] }}
                        </span>
                    </div>
                    <div class="grid grid-cols-2 gap-2 text-xs mb-2">
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">Bulan/Tahun:</span>
                            <div class="font-medium text-gray-900 dark:text-white">{{ $tagihan->bulan }}/{{ $tagihan->tahun }}</div>
                        </div>
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">Jumlah:</span>
                            <div class="font-medium text-gray-900 dark:text-white">Rp {{ number_format($tagihan->jumlah_tagihan, 0, ',', '.') }}</div>
                        </div>
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">Denda:</span>
                            <div class="font-medium text-gray-900 dark:text-white">Rp {{ number_format($tagihan->denda, 0, ',', '.') }}</div>
                        </div>
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">Jatuh Tempo:</span>
                            <div class="font-medium text-gray-900 dark:text-white">
                                {{ $tagihan->tanggal_jatuh_tempo->format('d/m/Y') }}
                                @if($tagihan->isOverdue())
                                    <span class="text-red-500 ml-1">
                                        <i class="fas fa-exclamation-circle"></i> Telat
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button 
                            wire:click="openEditForm({{ $tagihan->id }})"
                            class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 transition-colors duration-300 p-1"
                            title="Edit"
                        >
                            <i class="fas fa-edit text-sm"></i>
                        </button>
                        @if($tagihan->status !== 'lunas')
                            <button 
                                wire:click="markAsPaid({{ $tagihan->id }})"
                                class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 transition-colors duration-300 p-1"
                                onclick="return confirm('Tandai sebagai lunas?')"
                                title="Tandai Lunas"
                            >
                                <i class="fas fa-check text-sm"></i>
                            </button>
                        @endif
                        <button 
                            wire:click="deleteTagihan({{ $tagihan->id }})"
                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition-colors duration-300 p-1"
                            onclick="return confirm('Hapus data tagihan?')"
                            title="Hapus"
                        >
                            <i class="fas fa-trash text-sm"></i>
                        </button>
                    </div>
                </div>
            @empty
                <div class="p-4 text-center text-sm text-gray-500 dark:text-gray-400">
                    <i class="fas fa-inbox mr-2"></i>Tidak ada data tagihan
                </div>
            @endforelse
        </div>

        <!-- Desktop Table View -->
        <div class="hidden sm:block overflow-x-auto">
            <table class="w-full min-w-full">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Siswa</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider hidden sm:table-cell">Kelas</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Bulan/Tahun</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider hidden lg:table-cell">Jumlah</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider hidden xl:table-cell">Denda</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider hidden md:table-cell">Jatuh Tempo</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($tagihanList as $tagihan)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-300">
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $tagihan->siswa->nama }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">NIS: {{ $tagihan->siswa->nis }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 sm:hidden">
                                        {{ $tagihan->siswa->kelas->nama_kelas ?? '-' }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 lg:hidden">
                                        Rp {{ number_format($tagihan->jumlah_tagihan, 0, ',', '.') }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white hidden sm:table-cell">
                                {{ $tagihan->siswa->kelas->nama_kelas ?? '-' }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                <div class="text-xs">{{ $tagihan->bulan }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $tagihan->tahun }}</div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white hidden lg:table-cell">
                                Rp {{ number_format($tagihan->jumlah_tagihan, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white hidden xl:table-cell">
                                Rp {{ number_format($tagihan->denda, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span @class([
                                    'px-2 py-1 text-xs font-semibold rounded-full',
                                    'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' => $tagihan->status === 'belum_bayar',
                                    'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' => $tagihan->status === 'lunas',
                                    'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' => $tagihan->status === 'tertunggak'
                                ])>
                                    {{ $statusList[$tagihan->status] }}
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white hidden md:table-cell">
                                <div class="text-xs">{{ $tagihan->tanggal_jatuh_tempo->format('d/m/Y') }}</div>
                                @if($tagihan->isOverdue())
                                    <div class="text-xs text-red-500" title="Jatuh tempo">
                                        <i class="fas fa-exclamation-circle"></i> Telat
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium space-x-1">
                                <button 
                                    wire:click="openEditForm({{ $tagihan->id }})"
                                    class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 transition-colors duration-300 p-1"
                                    title="Edit"
                                >
                                    <i class="fas fa-edit text-sm"></i>
                                </button>
                                @if($tagihan->status !== 'lunas')
                                    <button 
                                        wire:click="markAsPaid({{ $tagihan->id }})"
                                        class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 transition-colors duration-300 p-1"
                                        onclick="return confirm('Tandai sebagai lunas?')"
                                        title="Tandai Lunas"
                                    >
                                        <i class="fas fa-check text-sm"></i>
                                    </button>
                                @endif
                                <button 
                                    wire:click="deleteTagihan({{ $tagihan->id }})"
                                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition-colors duration-300 p-1"
                                    onclick="return confirm('Hapus data tagihan?')"
                                    title="Hapus"
                                >
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                <i class="fas fa-inbox mr-2"></i>Tidak ada data tagihan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-4 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $tagihanList->links() }}
        </div>
    </div>

    <!-- Form Modal -->
    @if($showForm)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 dark:bg-gray-900 dark:bg-opacity-75 overflow-y-auto h-full w-full z-50 transition-opacity duration-300">
            <div class="relative top-4 sm:top-20 mx-auto p-4 border w-full max-w-2xl shadow-lg rounded-lg bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 transition-colors duration-300 max-h-[90vh] overflow-y-auto">
                <div class="flex justify-between items-center pb-3 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg sm:text-xl font-semibold text-gray-900 dark:text-white">
                        {{ $formType === 'create' ? 'Tambah Tagihan SPP' : 'Edit Tagihan SPP' }}
                    </h3>
                    <button wire:click="closeForm" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-300">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <form wire:submit="saveTagihan">
                    <div class="space-y-4 mt-4">
                        <!-- Siswa -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Siswa *</label>
                            <select 
                                wire:model="siswa_id"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors duration-300 text-sm"
                                required
                            >
                                <option value="">Pilih Siswa</option>
                                @foreach($siswas as $siswa)
                                    <option value="{{ $siswa->id }}">
                                        {{ $siswa->nis }} - {{ $siswa->nama }} - {{ $siswa->kelas->nama_kelas ?? '-' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('siswa_id') <span class="text-red-500 text-xs dark:text-red-400">{{ $message }}</span> @enderror
                        </div>

                        <!-- Biaya SPP -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Biaya SPP *</label>
                            <select 
                                wire:model="biaya_spp_id"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors duration-300 text-sm"
                                required
                            >
                                <option value="">Pilih Biaya SPP</option>
                                @foreach($biayaSppList as $biaya)
                                    <option value="{{ $biaya->id }}">
                                        {{ $biaya->kategoriBiaya->nama_kategori ?? 'SPP' }} - Rp {{ number_format($biaya->jumlah, 0, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                            @error('biaya_spp_id') <span class="text-red-500 text-xs dark:text-red-400">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Bulan -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Bulan *</label>
                                <select 
                                    wire:model="bulan"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors duration-300 text-sm"
                                    required
                                >
                                    <option value="">Pilih Bulan</option>
                                    @foreach($bulanList as $bulanItem)
                                        <option value="{{ $bulanItem }}">{{ $bulanItem }}</option>
                                    @endforeach
                                </select>
                                @error('bulan') <span class="text-red-500 text-xs dark:text-red-400">{{ $message }}</span> @enderror
                            </div>

                            <!-- Tahun -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tahun *</label>
                                <input 
                                    type="number" 
                                    wire:model="tahun"
                                    min="2020"
                                    max="2030"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors duration-300 text-sm"
                                    required
                                >
                                @error('tahun') <span class="text-red-500 text-xs dark:text-red-400">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Jumlah Tagihan -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jumlah Tagihan *</label>
                                <input 
                                    type="number" 
                                    wire:model="jumlah_tagihan"
                                    min="0"
                                    step="0.01"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors duration-300 text-sm"
                                    required
                                >
                                @error('jumlah_tagihan') <span class="text-red-500 text-xs dark:text-red-400">{{ $message }}</span> @enderror
                            </div>

                            <!-- Denda -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Denda</label>
                                <input 
                                    type="number" 
                                    wire:model="denda"
                                    min="0"
                                    step="0.01"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors duration-300 text-sm"
                                >
                                @error('denda') <span class="text-red-500 text-xs dark:text-red-400">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Status -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status *</label>
                                <select 
                                    wire:model="status"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors duration-300 text-sm"
                                    required
                                >
                                    @foreach($statusList as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                                @error('status') <span class="text-red-500 text-xs dark:text-red-400">{{ $message }}</span> @enderror
                            </div>

                            <!-- Tanggal Jatuh Tempo -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Jatuh Tempo *</label>
                                <input 
                                    type="date" 
                                    wire:model="tanggal_jatuh_tempo"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors duration-300 text-sm"
                                    required
                                >
                                @error('tanggal_jatuh_tempo') <span class="text-red-500 text-xs dark:text-red-400">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Tanggal Bayar (muncul hanya jika status lunas) -->
                        @if($status === 'lunas')
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Bayar</label>
                                <input 
                                    type="date" 
                                    wire:model="tanggal_bayar"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors duration-300 text-sm"
                                >
                                @error('tanggal_bayar') <span class="text-red-500 text-xs dark:text-red-400">{{ $message }}</span> @enderror
                            </div>
                        @endif

                        <!-- Keterangan -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Keterangan</label>
                            <textarea 
                                wire:model="keterangan"
                                rows="3"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors duration-300 text-sm"
                                placeholder="Keterangan tambahan..."
                            ></textarea>
                            @error('keterangan') <span class="text-red-500 text-xs dark:text-red-400">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-3 mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button 
                            type="button"
                            wire:click="closeForm"
                            class="px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-300 w-full sm:w-auto"
                        >
                            Batal
                        </button>
                        <button 
                            type="submit"
                            class="px-4 py-2 text-sm text-white bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-600 rounded-lg transition-colors duration-300 w-full sm:w-auto"
                        >
                            {{ $formType === 'create' ? 'Simpan' : 'Update' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="fixed top-4 right-4 z-50 max-w-sm w-full">
            <div class="bg-green-500 dark:bg-green-600 text-white px-4 py-3 rounded-lg shadow-lg transition-colors duration-300">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-check-circle"></i>
                    <span class="text-sm">{{ session('success') }}</span>
                </div>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="fixed top-4 right-4 z-50 max-w-sm w-full">
            <div class="bg-red-500 dark:bg-red-600 text-white px-4 py-3 rounded-lg shadow-lg transition-colors duration-300">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-exclamation-circle"></i>
                    <span class="text-sm">{{ session('error') }}</span>
                </div>
            </div>
        </div>
    @endif
</div>