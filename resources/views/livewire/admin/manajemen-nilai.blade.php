<div>
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Manajemen Nilai</h1>
        <p class="text-gray-600 dark:text-gray-400">Pantau nilai siswa secara keseluruhan</p>
    </div>

    <!-- Quick Stats -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <!-- Total Siswa Card -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                    </svg>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Siswa Aktif</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalSiswa }}</p>
            </div>
        </div>
    </div>

    <!-- Rata-rata Kelas Card -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Rata-rata Kelas</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($rataRataKelas, 2) }}</p>
            </div>
        </div>
    </div>

    <!-- Nilai Tertinggi Card -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Nilai Tertinggi</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($nilaiTertinggi, 2) }}</p>
            </div>
        </div>
    </div>

    <!-- Persentase Lulus Card -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Persentase Lulus</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $persentaseLulus }}%</p>
            </div>
        </div>
    </div>
</div>

    <!-- Filters -->
    <div class="mb-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Filter Data</h3>
            
            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-2 w-full sm:w-auto">
</button>

<button 
    wire:click="exportCSV"
    wire:loading.attr="disabled"
    class="flex items-center gap-2 px-3 py-2 text-sm bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors"
>
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
    </svg>
    
    <!-- Loading spinner -->
        <svg wire:loading wire:target="exportCSV" class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
        </svg>
        
        <span>
            <span wire:loading.remove wire:target="exportCSV">Export CSV</span>
            <span wire:loading wire:target="exportCSV">Mengekspor...</span>
        </span>
    </button>
                
                <button 
                    wire:click="resetFilters"
                    class="flex items-center gap-2 px-3 py-2 text-sm bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    <span class="hidden sm:inline">Reset Filter</span>
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Search -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cari Siswa/Mapel</label>
                <div class="relative">
                    <input 
                        type="text" 
                        wire:model.live.debounce.300ms="search"
                        placeholder="Cari siswa atau mapel..."
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg pl-10 pr-3 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                    >
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
            </div>

           <!-- Tahun Ajaran Filter - Di bagian filters -->
<div>
    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tahun Ajaran</label>
    <select 
        wire:model.live="tahunAjaranFilter"
        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
    >
        <option value="">Semua Tahun Ajaran</option>
        @foreach($tahunAjaranList as $tahun)
            <option value="{{ $tahun['id'] }}">{{ $tahun['label'] }}</option>
        @endforeach
    </select>
</div>

            <!-- Semester -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Semester</label>
                <select 
                    wire:model.live="semesterFilter"
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                >
                    <option value="">Semua Semester</option>
                    <option value="ganjil">Ganjil</option>
                    <option value="genap">Genap</option>
                </select>
            </div>

            <!-- Mata Pelajaran -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mata Pelajaran</label>
                <select 
                    wire:model.live="mapelFilter"
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                >
                    <option value="">Semua Mapel</option>
                    @foreach($mapelList as $mapel)
                        <option value="{{ $mapel->id }}">{{ $mapel->nama_mapel }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Additional Filters -->
        <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Kelas Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kelas</label>
                <select 
                    wire:model.live="kelasFilter"
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                >
                    <option value="">Semua Kelas</option>
                    @foreach($kelasList as $kelas)
                        <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Predikat Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Predikat</label>
                <select 
                    wire:model.live="predikatFilter"
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                >
                    <option value="">Semua Predikat</option>
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                    <option value="D">D</option>
                </select>
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status Kelulusan</label>
                <select 
                    wire:model.live="statusFilter"
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                >
                    <option value="">Semua Status</option>
                    <option value="Lulus">Lulus</option>
                    <option value="Tidak Lulus">Tidak Lulus</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Table Container -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <!-- Mobile Card View -->
        <div class="sm:hidden space-y-3 p-4">
            @forelse($nilais as $nilai)
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                    <div class="space-y-3">
                        <!-- Header -->
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900 dark:text-white text-sm">
    {{ $nilai->siswa?->nama_lengkap ?? 'Siswa tidak ditemukan' }}
</h3>
<p class="text-xs text-gray-600 dark:text-gray-400">
    {{ $nilai->siswa?->nis ?? 'N/A' }} • {{ $nilai->mapel?->nama_mapel ?? 'Mapel tidak ditemukan' }}
</p>
                            </div>
                            <div class="text-right">
                                <div class="text-lg font-bold text-gray-900 dark:text-white">
                                    {{ number_format($nilai->nilai_akhir, 1) }}
                                </div>
                                <span class="inline-flex px-2 py-0.5 text-xs font-semibold rounded-full {{ $this->getStatusColor($nilai->status) }}">
                                    {{ $nilai->status }}
                                </span>
                            </div>
                        </div>

                       <!-- Di bagian Details mobile view -->
<div class="grid grid-cols-2 gap-2 text-xs">
    <div class="flex items-center gap-1">
        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span>Predikat: </span>
        <span class="font-semibold {{ $this->getPredikatColor($nilai->predikat) }}">
            {{ $nilai->predikat }}
        </span>
    </div>
    <div class="flex items-center gap-1">
        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
        </svg>
        <span class="truncate">{{ $nilai->guru?->nama_lengkap ?? 'Guru tidak ditemukan' }}</span>
    </div>
</div>

<!-- Additional Info mobile view -->
<div class="flex justify-between items-center text-xs text-gray-500 dark:text-gray-400">
    <span>Tahun: {{ $nilai->tahunAjaran?->label ?? '-' }}</span>
    <span>Semester: {{ ucfirst($nilai->semester) }}</span>
</div>

                        <!-- Action Button -->
                        <div class="pt-2 border-t border-gray-200 dark:border-gray-600">
                            <button 
                                wire:click="showDetail({{ $nilai->id }})"
                                class="w-full bg-primary-600 hover:bg-primary-700 text-white py-2 px-4 rounded-lg text-sm font-medium transition-colors flex items-center justify-center gap-2"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Lihat Detail
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-8">
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="text-lg font-medium text-gray-900 dark:text-white mb-1">Tidak ada data nilai</p>
                    <p class="text-gray-600 dark:text-gray-400">Data tidak ditemukan dengan filter yang dipilih</p>
                </div>
            @endforelse
        </div>

        <!-- Desktop Table View -->
        <div class="hidden sm:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Siswa
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Mata Pelajaran
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('nilai_akhir')">
                            <div class="flex items-center gap-1">
                                Nilai Akhir
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                                </svg>
                            </div>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('predikat')">
                            <div class="flex items-center gap-1">
                                Predikat
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                                </svg>
                            </div>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Guru
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
    @forelse($nilais as $nilai)
        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
            <td class="px-4 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900 dark:text-white">
                    {{ $nilai->siswa?->nama_lengkap ?? 'Siswa tidak ditemukan' }}
                </div>
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    {{ $nilai->siswa?->nis ?? 'N/A' }}
                </div>
                <div class="text-xs text-gray-400">
                    {{ $nilai->siswa?->kelas?->nama_kelas ?? '-' }}
                </div>
            </td>
            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                {{ $nilai->mapel?->nama_mapel ?? 'Mapel tidak ditemukan' }}
            </td>
            <td class="px-4 py-4 whitespace-nowrap">
                <div class="text-sm font-bold text-gray-900 dark:text-white">{{ number_format($nilai->nilai_akhir, 2) }}</div>
                <div class="text-xs text-gray-500 dark:text-gray-400">
                    {{ $nilai->tahunAjaran?->label ?? '-' }} - {{ ucfirst($nilai->semester) }}
                </div>
            </td>
            <td class="px-4 py-4 whitespace-nowrap">
                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $this->getPredikatColor($nilai->predikat) }}">
                    {{ $nilai->predikat }}
                </span>
            </td>
            <td class="px-4 py-4 whitespace-nowrap">
                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $this->getStatusColor($nilai->status) }}">
                    {{ $nilai->status }}
                </span>
            </td>
            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                {{ $nilai->guru?->nama_lengkap ?? 'Guru tidak ditemukan' }}
            </td>
            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                <button 
                    wire:click="showDetail({{ $nilai->id }})"
                    class="text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300 transition-colors p-1 rounded hover:bg-primary-50 dark:hover:bg-primary-900/20"
                    title="Detail Nilai"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </button>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="7" class="px-6 py-8 text-center">
                <div class="flex flex-col items-center justify-center text-gray-500 dark:text-gray-400">
                    <svg class="w-12 h-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="text-lg font-medium mb-1">Tidak ada data nilai</p>
                    <p class="text-sm">Data tidak ditemukan dengan filter yang dipilih</p>
                </div>
            </td>
        </tr>
    @endforelse
</tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($nilais->hasPages())
            <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-3">
                    <div class="text-sm text-gray-700 dark:text-gray-300">
                        Menampilkan {{ $nilais->firstItem() }} - {{ $nilais->lastItem() }} dari {{ $nilais->total() }} hasil
                    </div>
                    <div>
                        {{ $nilais->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Detail Modal -->
    @if($showDetailModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Detail Nilai</h3>
                        <button wire:click="closeDetail" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Form Modal untuk Tambah/Edit Nilai -->
@if($showFormModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-4xl max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        {{ $formType === 'create' ? 'Tambah Nilai' : 'Edit Nilai' }}
                    </h3>
                    <button wire:click="closeForm" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="saveNilai">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <!-- Siswa -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Siswa *</label>
                            <select wire:model="siswa_id" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white" required>
                                <option value="">Pilih Siswa</option>
                                @foreach($siswaList as $siswa)
                                    <option value="{{ $siswa->id }}">{{ $siswa->nama_lengkap }} - {{ $siswa->nis }}</option>
                                @endforeach
                            </select>
                            @error('siswa_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Mata Pelajaran -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mata Pelajaran *</label>
                            <select wire:model="mapel_id" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white" required>
                                <option value="">Pilih Mapel</option>
                                @foreach($mapelList as $mapel)
                                    <option value="{{ $mapel->id }}">{{ $mapel->nama_mapel }}</option>
                                @endforeach
                            </select>
                            @error('mapel_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Guru -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Guru *</label>
                            <select wire:model="guru_id" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white" required>
                                <option value="">Pilih Guru</option>
                                @foreach($guruList as $guru)
                                    <option value="{{ $guru->id }}">{{ $guru->nama_lengkap }}</option>
                                @endforeach
                            </select>
                            @error('guru_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Tahun Ajaran & Semester -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tahun Ajaran *</label>
                                <select wire:model="tahun_ajaran_id" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white" required>
                                    <option value="">Pilih Tahun</option>
                                    @foreach($tahunAjaranList as $tahun)
                                        <option value="{{ $tahun->id }}">{{ $tahun->tahun_ajaran }}</option>
                                    @endforeach
                                </select>
                                @error('tahun_ajaran_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Semester *</label>
                                <select wire:model="semester" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white" required>
                                    <option value="Ganjil">Ganjil</option>
                                    <option value="Genap">Genap</option>
                                </select>
                                @error('semester') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Nilai Components -->
                    <div class="mb-6">
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Komponen Nilai</h4>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">UH 1</label>
                                <input type="number" wire:model.live="nilai_uh1" min="0" max="100" step="0.1" 
                                       class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white">
                                @error('nilai_uh1') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">UH 2</label>
                                <input type="number" wire:model.live="nilai_uh2" min="0" max="100" step="0.1"
                                       class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white">
                                @error('nilai_uh2') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">UTS</label>
                                <input type="number" wire:model.live="nilai_uts" min="0" max="100" step="0.1"
                                       class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white">
                                @error('nilai_uts') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">UAS</label>
                                <input type="number" wire:model.live="nilai_uas" min="0" max="100" step="0.1"
                                       class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white">
                                @error('nilai_uas') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Hasil Perhitungan -->
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-6">
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Hasil Perhitungan</h4>
                        <div class="grid grid-cols-3 gap-4 text-sm">
                            <div class="text-center">
                                <div class="text-gray-500 dark:text-gray-400">Nilai Akhir</div>
                                <div class="text-2xl font-bold text-primary-600 dark:text-primary-400">
                                    {{ number_format($nilai_akhir, 2) }}
                                </div>
                            </div>
                            <div class="text-center">
                                <div class="text-gray-500 dark:text-gray-400">Predikat</div>
                                <div class="text-2xl font-bold {{ $getPredikatColor($predikat) }}">
                                    {{ $predikat }}
                                </div>
                            </div>
                            <div class="text-center">
                                <div class="text-gray-500 dark:text-gray-400">Status</div>
                                <div class="text-2xl font-bold {{ $getStatusColor($nilai_akhir >= 75 ? 'Lulus' : 'Tidak Lulus') }}">
                                    {{ $nilai_akhir >= 75 ? 'Lulus' : 'Tidak Lulus' }}
                                </div>
                            </div>
                        </div>
                        @if($isCalculating)
                            <div class="mt-2 text-xs text-blue-600 dark:text-blue-400 text-center">
                                🔄 Menghitung ulang...
                            </div>
                        @endif
                    </div>

                    <!-- Deskripsi -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deskripsi</label>
                        <textarea wire:model="deskripsi" rows="3" 
                                  class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                                  placeholder="Deskripsi pencapaian siswa..."></textarea>
                        @error('deskripsi') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="button" wire:click="closeForm" 
                                class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                            Batal
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 text-sm font-medium text-white bg-primary-600 border border-transparent rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                            {{ $formType === 'create' ? 'Simpan Nilai' : 'Update Nilai' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

                    @if($selectedNilai)
    <div class="space-y-4">
        <!-- Student Info -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Informasi Siswa</h4>
                <div class="space-y-1 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-400">Nama:</span>
                        <span class="font-medium text-gray-900 dark:text-white">
                            {{ $selectedNilai->siswa?->nama_lengkap ?? 'Siswa tidak ditemukan' }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-400">NIS:</span>
                        <span class="font-medium text-gray-900 dark:text-white">
                            {{ $selectedNilai->siswa?->nis ?? 'N/A' }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-400">Kelas:</span>
                        <span class="font-medium text-gray-900 dark:text-white">
                            {{ $selectedNilai->siswa?->kelas?->nama_kelas ?? '-' }}
                        </span>
                    </div>
                </div>
            </div>
            
            <div>
                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Informasi Nilai</h4>
                <div class="space-y-1 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-400">Mata Pelajaran:</span>
                        <span class="font-medium text-gray-900 dark:text-white">
                            {{ $selectedNilai->mapel?->nama_mapel ?? 'Mapel tidak ditemukan' }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-400">Guru:</span>
                        <span class="font-medium text-gray-900 dark:text-white">
                            {{ $selectedNilai->guru?->nama_lengkap ?? 'Guru tidak ditemukan' }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-400">Tahun Ajaran:</span>
                        <span class="font-medium text-gray-900 dark:text-white">
                            {{ $selectedNilai->tahunAjaran?->label ?? '-' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

                            <!-- Score Breakdown -->
                            <div>
                                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Rincian Nilai</h4>
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Nilai UH 1:</span>
                                        <span class="font-medium text-gray-900 dark:text-white">{{ number_format($selectedNilai->nilai_uh1, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Nilai UH 2:</span>
                                        <span class="font-medium text-gray-900 dark:text-white">{{ number_format($selectedNilai->nilai_uh2, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Nilai UTS:</span>
                                        <span class="font-medium text-gray-900 dark:text-white">{{ number_format($selectedNilai->nilai_uts, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Nilai UAS:</span>
                                        <span class="font-medium text-gray-900 dark:text-white">{{ number_format($selectedNilai->nilai_uas, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between border-t border-gray-200 dark:border-gray-600 pt-2">
                                        <span class="text-gray-900 dark:text-white font-semibold">Nilai Akhir:</span>
                                        <span class="font-bold text-lg text-primary-600 dark:text-primary-400">{{ number_format($selectedNilai->nilai_akhir, 2) }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Summary -->
                            <div class="grid grid-cols-2 gap-4">
                                <div class="text-center">
                                    <div class="text-sm text-gray-500 dark:text-gray-400">Predikat</div>
                                    <div class="text-lg font-bold {{ $this->getPredikatColor($selectedNilai->predikat) }}">{{ $selectedNilai->predikat }}</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-sm text-gray-500 dark:text-gray-400">Status</div>
                                    <div class="text-lg font-bold {{ $this->getStatusColor($selectedNilai->status) }}">{{ $selectedNilai->status }}</div>
                                </div>
                            </div>

                            <!-- Deskripsi -->
                            @if($selectedNilai->deskripsi)
                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Deskripsi</h4>
                                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 text-sm text-gray-600 dark:text-gray-400">
                                        {{ $selectedNilai->deskripsi }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>