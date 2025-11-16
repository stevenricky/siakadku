<div class="p-4 sm:p-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div class="flex-1 min-w-0">
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white truncate">Laporan Peminjaman</h1>
            <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400 mt-1 truncate">
                Analisis dan statistik peminjaman buku perpustakaan
            </p>
        </div>
        <div class="flex flex-wrap gap-2 w-full sm:w-auto">
            <button 
                wire:click="exportPDF"
                class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 sm:px-4 sm:py-2 rounded-lg transition flex items-center justify-center flex-1 sm:flex-none min-w-[120px]"
            >
                <i class="fas fa-download mr-2 text-xs sm:text-sm"></i>
                <span class="text-xs sm:text-sm">Export PDF</span>
            </button>
            <button 
                wire:click="exportCSV"
                class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 sm:px-4 sm:py-2 rounded-lg transition flex items-center justify-center flex-1 sm:flex-none min-w-[120px]"
            >
                <i class="fas fa-file-csv mr-2 text-xs sm:text-sm"></i>
                <span class="text-xs sm:text-sm">Export CSV</span>
            </button>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-3">
                <select 
                    wire:model.live="periode"
                    class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
                    <option value="bulan_ini">Bulan Ini</option>
                    <option value="semester_ini">Semester Ini</option>
                    <option value="tahun_ini">Tahun Ini</option>
                    <option value="custom">Custom</option>
                </select>

                @if($periode === 'custom')
                <input 
                    type="month" 
                    wire:model.live="bulanTahun"
                    class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
                @else
                <div class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white flex items-center sm:col-span-1 lg:col-span-2">
                    <span class="truncate">
                        {{ \Carbon\Carbon::parse($tanggalAwal)->translatedFormat('d M Y') }} - {{ \Carbon\Carbon::parse($tanggalAkhir)->translatedFormat('d M Y') }}
                    </span>
                </div>
                @endif

                <select 
                    wire:model.live="kategoriFilter"
                    class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
                    <option value="">Semua Kategori</option>
                    @foreach($kategoriList as $kategori)
                        <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                    @endforeach
                </select>

                <button 
                    wire:click="generateLaporan"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 text-sm rounded-lg transition flex items-center justify-center focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                >
                    <i class="fas fa-sync-alt mr-2"></i>
                    <span>Generate</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Total Peminjaman -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 sm:p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0 p-3 bg-blue-100 dark:bg-blue-900 rounded-lg">
                    <i class="fas fa-exchange-alt text-blue-600 dark:text-blue-400 text-lg sm:text-xl"></i>
                </div>
                <div class="ml-4 flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400 truncate">Total Peminjaman</p>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white truncate">{{ $totalPeminjaman }}</p>
                </div>
            </div>
        </div>

        <!-- Rata-rata per Hari -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 sm:p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0 p-3 bg-green-100 dark:bg-green-900 rounded-lg">
                    <i class="fas fa-chart-line text-green-600 dark:text-green-400 text-lg sm:text-xl"></i>
                </div>
                <div class="ml-4 flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400 truncate">Rata-rata per Hari</p>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white truncate">{{ $rataRataPerHari }}</p>
                </div>
            </div>
        </div>

        <!-- Tertinggi -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 sm:p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0 p-3 bg-yellow-100 dark:bg-yellow-900 rounded-lg">
                    <i class="fas fa-trophy text-yellow-600 dark:text-yellow-400 text-lg sm:text-xl"></i>
                </div>
                <div class="ml-4 flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400 truncate">Tertinggi</p>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white truncate">{{ $peminjamanTertinggi }}/hari</p>
                </div>
            </div>
        </div>

        <!-- Status Aktif -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 sm:p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0 p-3 bg-purple-100 dark:bg-purple-900 rounded-lg">
                    <i class="fas fa-clock text-purple-600 dark:text-purple-400 text-lg sm:text-xl"></i>
                </div>
                <div class="ml-4 flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400 truncate">Sedang Dipinjam</p>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white truncate">
                        {{ $statusDistribution['dipinjam']['count'] ?? 0 }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-6">
        <!-- Distribusi Status -->
        <div class="xl:col-span-1 bg-white dark:bg-gray-800 rounded-lg shadow p-4 sm:p-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white flex items-center">
                <i class="fas fa-chart-pie mr-2 text-blue-600"></i>
                Distribusi Status
            </h3>
            <div class="space-y-4">
                @php
                    $statusLabels = [
                        'dipinjam' => 'Sedang Dipinjam',
                        'dikembalikan' => 'Sudah Dikembalikan', 
                        'terlambat' => 'Terlambat',
                        'hilang' => 'Hilang'
                    ];
                    
                    $statusColors = [
                        'dipinjam' => 'bg-blue-600',
                        'dikembalikan' => 'bg-green-600', 
                        'terlambat' => 'bg-red-600',
                        'hilang' => 'bg-gray-600'
                    ];
                @endphp
                
                @foreach($statusDistribution as $status => $data)
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center">
                            <span class="w-3 h-3 {{ $statusColors[$status] ?? 'bg-gray-400' }} rounded-full mr-2"></span>
                            {{ $statusLabels[$status] ?? $status }}
                        </span>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white">
                            {{ $data['percentage'] }}%
                        </span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                        <div class="h-2 rounded-full {{ $statusColors[$status] ?? 'bg-gray-400' }}" 
                             style="width: {{ $data['percentage'] }}%">
                        </div>
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 text-right">
                        {{ $data['count'] }} peminjaman
                    </div>
                </div>
                @endforeach
                
                @if(empty($statusDistribution))
                <div class="text-center text-gray-500 dark:text-gray-400 py-8">
                    <i class="fas fa-chart-pie text-3xl mb-3"></i>
                    <p class="text-sm">Tidak ada data status</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Buku Terpopuler -->
        <div class="xl:col-span-2 bg-white dark:bg-gray-800 rounded-lg shadow p-4 sm:p-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white flex items-center">
                <i class="fas fa-crown mr-2 text-yellow-600"></i>
                Buku Terpopuler
            </h3>
            <div class="space-y-3">
                @forelse($bukuTerpopuler as $buku)
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                    <div class="flex items-center min-w-0 flex-1">
                        <div class="h-10 w-10 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-book text-blue-600 dark:text-blue-400"></i>
                        </div>
                        <div class="ml-3 min-w-0 flex-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                {{ $buku->buku->judul ?? 'Buku tidak ditemukan' }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                {{ $buku->buku->penulis ?? '-' }} • {{ $buku->buku->kategori->nama_kategori ?? '-' }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2 ml-3">
                        <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 rounded-full font-semibold">
                            {{ $buku->total_peminjaman }}x
                        </span>
                        <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 rounded-full font-semibold">
                            #{{ $loop->iteration }}
                        </span>
                    </div>
                </div>
                @empty
                <div class="text-center text-gray-500 dark:text-gray-400 py-8">
                    <i class="fas fa-book-open text-3xl mb-3"></i>
                    <p class="text-sm">Belum ada data buku terpopuler</p>
                    <p class="text-xs mt-1">Data akan muncul ketika ada peminjaman buku</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Detail Peminjaman -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                <i class="fas fa-list-ul mr-2 text-green-600"></i>
                Detail Peminjaman
            </h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                Menampilkan {{ $detailPeminjaman->count() }} data peminjaman
            </p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Peminjam</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Buku</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Durasi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($detailPeminjaman as $peminjaman)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                <div class="flex flex-col">
                                    <span class="font-medium">{{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->translatedFormat('d M Y') }}</span>
                                    <span class="text-xs text-gray-500">{{ $peminjaman->kode_peminjaman }}</span>
                                </div>
                            </td>
                            <td class="px-3 py-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $peminjaman->siswa->nama }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $peminjaman->siswa->kelas->nama_kelas ?? '-' }}
                                </div>
                            </td>
                            <td class="px-3 py-4">
                                <div class="text-sm text-gray-900 dark:text-white">
                                    {{ \Illuminate\Support\Str::limit($peminjaman->buku->judul, 30) }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ \Illuminate\Support\Str::limit($peminjaman->buku->penulis, 25) }}
                                </div>
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap">
                                <div class="flex flex-col space-y-1">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $peminjaman->status_badge }} w-fit">
                                        {{ $peminjaman->status_text }}
                                    </span>
                                    @if($peminjaman->denda > 0)
                                        <div class="text-xs text-red-600 dark:text-red-400">
                                            Denda: Rp {{ number_format($peminjaman->denda, 0, ',', '.') }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                <span class="font-medium">{{ $peminjaman->durasi }}</span> hari
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center">
                                <div class="text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-inbox text-4xl mb-4"></i>
                                    <p class="text-lg font-medium">Tidak ada data peminjaman</p>
                                    <p class="text-sm mt-2">Tidak ada data peminjaman pada periode yang dipilih</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if (session()->has('info'))
    <div class="fixed top-4 right-4 z-50 animate-fade-in">
        <div class="bg-blue-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center max-w-sm">
            <i class="fas fa-info-circle mr-2"></i>
            <span class="flex-1">{{ session('info') }}</span>
        </div>
    </div>
    @endif

    <!-- Loading Indicator -->
    <div wire:loading class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 flex items-center shadow-xl">
            <i class="fas fa-spinner fa-spin text-blue-600 mr-3 text-xl"></i>
            <span class="text-gray-700 dark:text-gray-300 font-medium">Memproses laporan...</span>
        </div>
    </div>
</div>