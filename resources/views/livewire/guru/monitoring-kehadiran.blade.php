<div>
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Monitoring Kehadiran</h1>
        <p class="text-gray-600 dark:text-gray-400">Pantau dan kelola kehadiran siswa di kelas yang Anda ajar</p>
    </div>

    <!-- Statistik Hari Ini -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="bi bi-check-circle text-2xl text-green-500"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Hadir Hari Ini</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $statistikHariIni['hadir'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="bi bi-envelope text-2xl text-blue-500"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Izin Hari Ini</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $statistikHariIni['izin'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-yellow-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="bi bi-heart-pulse text-2xl text-yellow-500"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Sakit Hari Ini</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $statistikHariIni['sakit'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-red-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="bi bi-x-circle text-2xl text-red-500"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Alpha Hari Ini</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $statistikHariIni['alpha'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-purple-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="bi bi-calendar-check text-2xl text-purple-500"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Hari Ini</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $statistikHariIni['total'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Pencarian</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="bi bi-search text-gray-400"></i>
                    </div>
                    <input 
                        type="text" 
                        wire:model.live="search"
                        placeholder="Cari nama siswa, NIS, kelas, atau mapel..."
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    >
                    @if($search)
                        <button 
                            wire:click="$set('search', '')"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600"
                        >
                            <i class="bi bi-x-lg text-sm"></i>
                        </button>
                    @endif
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Kelas</label>
                <select 
                    wire:model.live="kelasId"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                >
                    <option value="">Semua Kelas</option>
                    @foreach($kelasList as $kelas)
                        <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tanggal</label>
                <input 
                    type="date" 
                    wire:model.live="tanggal"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                >
            </div>
            <div class="flex items-end space-x-2">
                <button 
                    wire:click="$set('tanggal', '{{ now()->format('Y-m-d') }}')"
                    class="flex-1 px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-colors"
                >
                    Hari Ini
                </button>
                <button 
                    wire:click="resetFilters"
                    class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700"
                >
                    Reset
                </button>
            </div>
        </div>
    </div>

    <!-- Daftar Kehadiran -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Daftar Kehadiran</h2>
            <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-500 dark:text-gray-400">
                    {{ $absensiList->total() }} data ditemukan
                </span>
                @if($search)
                    <span class="text-xs bg-primary-100 text-primary-800 px-2 py-1 rounded-full">
                        Pencarian: "{{ $search }}"
                    </span>
                @endif
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Siswa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kelas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Mata Pelajaran</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($absensiList as $absensi)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 w-10 h-10 bg-primary-100 dark:bg-primary-900 rounded-full flex items-center justify-center">
                                        <span class="text-primary-600 dark:text-primary-300 font-medium">
                                            {{ substr($absensi->siswa->nama_lengkap, 0, 1) }}
                                        </span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $absensi->siswa->nama_lengkap }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $absensi->siswa->nis }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    {{ $absensi->jadwal->kelas->nama_kelas ?? '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ $absensi->tanggal->translatedFormat('d F Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusColors = [
                                        'hadir' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                        'izin' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                        'sakit' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                        'alpha' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
                                    ];
                                    $statusLabels = [
                                        'hadir' => 'Hadir',
                                        'izin' => 'Izin',
                                        'sakit' => 'Sakit',
                                        'alpha' => 'Alpha'
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$absensi->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $statusLabels[$absensi->status] ?? $absensi->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ $absensi->jadwal->mapel->nama_mapel ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <!-- Dropdown untuk ubah status -->
                                    <div class="relative" x-data="{ open: false }">
                                        <button 
                                            @click="open = !open"
                                            class="text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300"
                                        >
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        
                                        <div 
                                            x-show="open" 
                                            @click.away="open = false"
                                            class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg z-10 border border-gray-200 dark:border-gray-700"
                                        >
                                            <div class="py-1">
                                                <button 
                                                    wire:click="updateStatus({{ $absensi->id }}, 'hadir')"
                                                    class="block w-full text-left px-4 py-2 text-sm text-green-600 hover:bg-green-50 dark:hover:bg-green-900"
                                                >
                                                    <i class="bi bi-check-circle mr-2"></i>Hadir
                                                </button>
                                                <button 
                                                    wire:click="updateStatus({{ $absensi->id }}, 'izin')"
                                                    class="block w-full text-left px-4 py-2 text-sm text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900"
                                                >
                                                    <i class="bi bi-envelope mr-2"></i>Izin
                                                </button>
                                                <button 
                                                    wire:click="updateStatus({{ $absensi->id }}, 'sakit')"
                                                    class="block w-full text-left px-4 py-2 text-sm text-yellow-600 hover:bg-yellow-50 dark:hover:bg-yellow-900"
                                                >
                                                    <i class="bi bi-heart-pulse mr-2"></i>Sakit
                                                </button>
                                                <button 
                                                    wire:click="updateStatus({{ $absensi->id }}, 'alpha')"
                                                    class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900"
                                                >
                                                    <i class="bi bi-x-circle mr-2"></i>Alpha
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center">
                                <div class="text-gray-500 dark:text-gray-400">
                                    <i class="bi bi-clipboard-x text-4xl mb-3 block"></i>
                                    <p>Tidak ada data kehadiran ditemukan</p>
                                    @if($search || $kelasId || $tanggal != now()->format('Y-m-d'))
                                        <p class="text-sm mt-2">Coba ubah filter pencarian Anda</p>
                                        <button 
                                            wire:click="resetFilters"
                                            class="mt-2 px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700"
                                        >
                                            Reset Filter
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $absensiList->links() }}
        </div>
    </div>

    <!-- Statistik Keseluruhan -->
    <div class="mt-6 bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Statistik Keseluruhan</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="text-center p-4 bg-green-50 dark:bg-green-900 rounded-lg">
                <div class="text-2xl font-bold text-green-600 dark:text-green-300">{{ $statistikKeseluruhan['hadir'] }}</div>
                <div class="text-sm text-green-600 dark:text-green-300">Total Hadir</div>
            </div>
            <div class="text-center p-4 bg-blue-50 dark:bg-blue-900 rounded-lg">
                <div class="text-2xl font-bold text-blue-600 dark:text-blue-300">{{ $statistikKeseluruhan['izin'] }}</div>
                <div class="text-sm text-blue-600 dark:text-blue-300">Total Izin</div>
            </div>
            <div class="text-center p-4 bg-yellow-50 dark:bg-yellow-900 rounded-lg">
                <div class="text-2xl font-bold text-yellow-600 dark:text-yellow-300">{{ $statistikKeseluruhan['sakit'] }}</div>
                <div class="text-sm text-yellow-600 dark:text-yellow-300">Total Sakit</div>
            </div>
            <div class="text-center p-4 bg-red-50 dark:bg-red-900 rounded-lg">
                <div class="text-2xl font-bold text-red-600 dark:text-red-300">{{ $statistikKeseluruhan['alpha'] }}</div>
                <div class="text-sm text-red-600 dark:text-red-300">Total Alpha</div>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="fixed top-4 right-4 z-50">
            <div class="bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-2">
                <i class="bi bi-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif
</div>