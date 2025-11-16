<div class="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4 px-4 sm:px-0">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Manajemen SPP</h1>
        <div class="flex space-x-3 w-full sm:w-auto">
            <button 
                wire:click="openCreateForm"
                class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center justify-center space-x-2 transition-colors duration-300 w-full sm:w-auto"
            >
                <i class="fas fa-plus"></i>
                <span>Tambah SPP</span>
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 mb-6 px-4 sm:px-0">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 sm:p-6 transition-colors duration-300">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400">
                    <i class="fas fa-money-bill-wave text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Penerimaan</p>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($totalNominal, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 sm:p-6 transition-colors duration-300">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 dark:bg-red-900 text-red-600 dark:text-red-400">
                    <i class="fas fa-exclamation-triangle text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Tunggakan</p>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($totalTunggakan, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 sm:p-6 transition-colors duration-300">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Data</p>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">{{ $sppList->total() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6 transition-colors duration-300 px-4 sm:px-0">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Filter Data</h2>
        </div>
        <div class="p-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Search -->
                <div class="sm:col-span-2 lg:col-span-1">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pencarian</label>
                    <input 
                        type="text" 
                        wire:model.live="search"
                        placeholder="Cari NIS/Nama Siswa..."
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors duration-300"
                    >
                </div>

                <!-- Tahun Ajaran Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tahun Ajaran</label>
                    <select 
                        wire:model.live="tahunAjaranFilter"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors duration-300"
                    >
                        <option value="">Semua Tahun Ajaran</option>
                        @foreach($tahunAjarans as $tahun)
                            <option value="{{ $tahun->id }}">{{ $tahun->tahun_awal }}/{{ $tahun->tahun_akhir }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                    <select 
                        wire:model.live="statusFilter"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors duration-300"
                    >
                        <option value="">Semua Status</option>
                        @foreach($statusList as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Per Page -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Data per Halaman</label>
                    <select 
                        wire:model.live="perPage"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors duration-300"
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

    <!-- Card Layout for Mobile -->
    <div class="md:hidden px-4 mb-6">
        @forelse($sppList as $spp)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-4 overflow-hidden transition-colors duration-300">
                <div class="p-4">
                    <!-- Header with Siswa info and Status -->
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex-1">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ $spp->siswa->nama }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">NIS: {{ $spp->siswa->nis }}</p>
                        </div>
                        <span @class([
                            'px-2 py-1 text-xs font-semibold rounded-full',
                            'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' => $spp->status === 'belum_bayar',
                            'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' => $spp->status === 'lunas',
                            'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200' => $spp->status === 'tertunggak'
                        ])>
                            {{ $statusList[$spp->status] }}
                        </span>
                    </div>
                    
                    <!-- Details Grid -->
                    <div class="grid grid-cols-2 gap-2 mb-3 text-sm">
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Tahun Ajaran</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $spp->tahunAjaran->tahun_awal }}/{{ $spp->tahunAjaran->tahun_akhir }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Bulan/Tahun</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $spp->bulan }} {{ $spp->tahun }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Nominal</p>
                            <p class="font-medium text-gray-900 dark:text-white">Rp {{ number_format($spp->nominal, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Tanggal Bayar</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $spp->tanggal_bayar ? $spp->tanggal_bayar->format('d/m/Y') : '-' }}</p>
                        </div>
                    </div>
                    
                    <!-- Actions -->
                    <div class="flex gap-2">
                        <button 
                            wire:click="openEditForm({{ $spp->id }})"
                            class="flex-1 bg-blue-50 text-blue-600 hover:bg-blue-100 dark:bg-blue-900 dark:text-blue-400 dark:hover:bg-blue-800 px-3 py-2 rounded-md text-sm font-medium text-center transition-colors duration-300"
                        >
                            <i class="fas fa-edit mr-1"></i> Edit
                        </button>
                        @if($spp->status !== 'lunas')
                            <button 
                                wire:click="markAsPaid({{ $spp->id }})"
                                onclick="return confirm('Tandai sebagai lunas?')"
                                class="flex-1 bg-green-50 text-green-600 hover:bg-green-100 dark:bg-green-900 dark:text-green-400 dark:hover:bg-green-800 px-3 py-2 rounded-md text-sm font-medium text-center transition-colors duration-300"
                            >
                                <i class="fas fa-check mr-1"></i> Lunas
                            </button>
                        @endif
                        <button 
                            wire:click="deleteSpp({{ $spp->id }})"
                            onclick="return confirm('Hapus data SPP?')"
                            class="flex-1 bg-red-50 text-red-600 hover:bg-red-100 dark:bg-red-900 dark:text-red-400 dark:hover:bg-red-800 px-3 py-2 rounded-md text-sm font-medium text-center transition-colors duration-300"
                        >
                            <i class="fas fa-trash mr-1"></i> Hapus
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-8 text-center">
                <i class="fas fa-inbox text-4xl text-gray-400 mb-3"></i>
                <p class="text-sm text-gray-500 dark:text-gray-400">Tidak ada data SPP</p>
            </div>
        @endforelse

        <!-- Pagination for Mobile -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow px-4 py-3">
            {{ $sppList->links() }}
        </div>
    </div>

    <!-- Table Layout for Desktop -->
    <div class="hidden md:block bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden transition-colors duration-300">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Siswa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tahun Ajaran</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Bulan/Tahun</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nominal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal Bayar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($sppList as $spp)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-300">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $spp->siswa->nama }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">NIS: {{ $spp->siswa->nis }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ $spp->tahunAjaran->tahun_awal }}/{{ $spp->tahunAjaran->tahun_akhir }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ $spp->bulan }} {{ $spp->tahun }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                Rp {{ number_format($spp->nominal, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span @class([
                                    'px-2 py-1 text-xs font-semibold rounded-full',
                                    'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' => $spp->status === 'belum_bayar',
                                    'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' => $spp->status === 'lunas',
                                    'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200' => $spp->status === 'tertunggak'
                                ])>
                                    {{ $statusList[$spp->status] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ $spp->tanggal_bayar ? $spp->tanggal_bayar->format('d/m/Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <button 
                                    wire:click="openEditForm({{ $spp->id }})"
                                    class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 transition-colors duration-300"
                                    title="Edit"
                                >
                                    <i class="fas fa-edit"></i>
                                </button>
                                @if($spp->status !== 'lunas')
                                    <button 
                                        wire:click="markAsPaid({{ $spp->id }})"
                                        onclick="return confirm('Tandai sebagai lunas?')"
                                        class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 transition-colors duration-300"
                                        title="Tandai Lunas"
                                    >
                                        <i class="fas fa-check"></i>
                                    </button>
                                @endif
                                <button 
                                    wire:click="deleteSpp({{ $spp->id }})"
                                    onclick="return confirm('Hapus data SPP?')"
                                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition-colors duration-300"
                                    title="Hapus"
                                >
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                <i class="fas fa-inbox mr-2"></i>Tidak ada data SPP
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination for Desktop -->
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $sppList->links() }}
        </div>
    </div>

    <!-- Form Modal -->
    @if($showForm)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 dark:bg-gray-900 dark:bg-opacity-75 overflow-y-auto h-full w-full z-50 transition-opacity duration-300">
            <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-lg bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 transition-colors duration-300">
                <div class="flex justify-between items-center pb-3 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        {{ $formType === 'create' ? 'Tambah Data SPP' : 'Edit Data SPP' }}
                    </h3>
                    <button wire:click="closeForm" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-300">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <form wire:submit="saveSpp">
                    <div class="space-y-4 mt-4">
                        <!-- Siswa -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Siswa *</label>
                            <select 
                                wire:model="siswa_id"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors duration-300"
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

                        <!-- Tahun Ajaran -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tahun Ajaran *</label>
                            <select 
                                wire:model="tahun_ajaran_id"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors duration-300"
                                required
                            >
                                <option value="">Pilih Tahun Ajaran</option>
                                @foreach($tahunAjarans as $tahun)
                                    <option value="{{ $tahun->id }}">
                                        {{ $tahun->tahun_awal }}/{{ $tahun->tahun_akhir }} - Semester {{ $tahun->semester }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tahun_ajaran_id') <span class="text-red-500 text-xs dark:text-red-400">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Bulan -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Bulan *</label>
                                <select 
                                    wire:model="bulan"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors duration-300"
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
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors duration-300"
                                    required
                                >
                                @error('tahun') <span class="text-red-500 text-xs dark:text-red-400">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Nominal -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nominal *</label>
                            <input 
                                type="number" 
                                wire:model="nominal"
                                min="0"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors duration-300"
                                required
                            >
                            @error('nominal') <span class="text-red-500 text-xs dark:text-red-400">{{ $message }}</span> @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status *</label>
                            <select 
                                wire:model="status"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors duration-300"
                                required
                            >
                                @foreach($statusList as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                            @error('status') <span class="text-red-500 text-xs dark:text-red-400">{{ $message }}</span> @enderror
                        </div>

                        <!-- Tanggal Bayar (muncul hanya jika status lunas) -->
                        @if($status === 'lunas')
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Bayar</label>
                                <input 
                                    type="date" 
                                    wire:model="tanggal_bayar"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors duration-300"
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
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors duration-300"
                                placeholder="Keterangan tambahan..."
                            ></textarea>
                            @error('keterangan') <span class="text-red-500 text-xs dark:text-red-400">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button 
                            type="button"
                            wire:click="closeForm"
                            class="px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-300"
                        >
                            Batal
                        </button>
                        <button 
                            type="submit"
                            class="px-4 py-2 text-sm text-white bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-600 rounded-lg transition-colors duration-300"
                        >
                            {{ $formType === 'create' ? 'Simpan' : 'Update' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="fixed top-4 right-4 z-50">
            <div class="bg-green-500 dark:bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg transition-colors duration-300">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="fixed top-4 right-4 z-50">
            <div class="bg-red-500 dark:bg-red-600 text-white px-6 py-3 rounded-lg shadow-lg transition-colors duration-300">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        </div>
    @endif
</div>