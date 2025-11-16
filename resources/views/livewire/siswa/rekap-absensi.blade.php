<div>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Rekap Absensi</h2>
                <p class="text-gray-600 dark:text-gray-400">Monitor kehadiran Anda</p>
            </div>

            <!-- Filter Section -->
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Bulan Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Bulan
                        </label>
                        <select wire:model.live="bulan" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2.5 text-sm focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                            @foreach(range(1, 12) as $month)
                                <option value="{{ $month }}">{{ DateTime::createFromFormat('!m', $month)->format('F') }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tahun Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Tahun
                        </label>
                        <select wire:model.live="tahun" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2.5 text-sm focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                            @foreach(range(now()->year, now()->year - 2) as $year)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Export Buttons -->
                    <div class="flex flex-col sm:flex-row gap-2 sm:items-end">
                        <button wire:click="exportExcel" class="flex-1 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg px-3 py-2.5 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors font-medium">
                            Export Excel
                        </button>
                        <button wire:click="exportPDF" class="flex-1 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg px-3 py-2.5 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors font-medium">
                            Export PDF
                        </button>
                    </div>
                </div>
            </div>

            <!-- Statistik Keseluruhan -->
            <div class="grid grid-cols-2 lg:grid-cols-5 gap-3 mb-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 text-center">
                    <div class="text-xl lg:text-2xl font-bold text-green-600 dark:text-green-400">{{ $statistik['hadir'] }}</div>
                    <div class="text-xs lg:text-sm text-gray-600 dark:text-gray-400 mt-1">Hadir</div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 text-center">
                    <div class="text-xl lg:text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $statistik['izin'] }}</div>
                    <div class="text-xs lg:text-sm text-gray-600 dark:text-gray-400 mt-1">Izin</div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 text-center">
                    <div class="text-xl lg:text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $statistik['sakit'] }}</div>
                    <div class="text-xs lg:text-sm text-gray-600 dark:text-gray-400 mt-1">Sakit</div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 text-center">
                    <div class="text-xl lg:text-2xl font-bold text-red-600 dark:text-red-400">{{ $statistik['alpha'] }}</div>
                    <div class="text-xs lg:text-sm text-gray-600 dark:text-gray-400 mt-1">Alpha</div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 text-center col-span-2 lg:col-span-1">
                    <div class="text-xl lg:text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $statistik['persentase_kehadiran'] }}%</div>
                    <div class="text-xs lg:text-sm text-gray-600 dark:text-gray-400 mt-1">Kehadiran</div>
                </div>
            </div>

            <!-- Rekap Per Mapel -->
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Rekap Per Mata Pelajaran</h3>
                
                @if($rekapPerMapel->count() > 0)
                    <!-- Desktop Table -->
                    <div class="hidden md:block overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead>
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">Mapel</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">Hadir</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">Izin</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">Sakit</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">Alpha</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">Total</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">%</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($rekapPerMapel as $rekap)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                        <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-white">{{ $rekap['mapel'] }}</td>
                                        <td class="px-4 py-3 text-sm text-center text-green-600 dark:text-green-400 font-semibold">{{ $rekap['hadir'] }}</td>
                                        <td class="px-4 py-3 text-sm text-center text-blue-600 dark:text-blue-400 font-semibold">{{ $rekap['izin'] }}</td>
                                        <td class="px-4 py-3 text-sm text-center text-yellow-600 dark:text-yellow-400 font-semibold">{{ $rekap['sakit'] }}</td>
                                        <td class="px-4 py-3 text-sm text-center text-red-600 dark:text-red-400 font-semibold">{{ $rekap['alpha'] }}</td>
                                        <td class="px-4 py-3 text-sm text-center text-gray-900 dark:text-white font-semibold">{{ $rekap['total'] }}</td>
                                        <td class="px-4 py-3 text-sm text-center">
                                            <span class="font-semibold 
                                                @if($rekap['persentase_hadir'] >= 80) text-green-600 dark:text-green-400
                                                @elseif($rekap['persentase_hadir'] >= 60) text-yellow-600 dark:text-yellow-400
                                                @else text-red-600 dark:text-red-400 @endif">
                                                {{ $rekap['persentase_hadir'] }}%
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Cards -->
                    <div class="md:hidden space-y-3">
                        @foreach($rekapPerMapel as $rekap)
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                                <div class="flex justify-between items-start mb-3">
                                    <h4 class="text-sm font-semibold text-gray-900 dark:text-white">{{ $rekap['mapel'] }}</h4>
                                    <span class="text-xs font-medium 
                                        @if($rekap['persentase_hadir'] >= 80) text-green-600 dark:text-green-400
                                        @elseif($rekap['persentase_hadir'] >= 60) text-yellow-600 dark:text-yellow-400
                                        @else text-red-600 dark:text-red-400 @endif">
                                        {{ $rekap['persentase_hadir'] }}%
                                    </span>
                                </div>
                                <div class="grid grid-cols-4 gap-2 text-center">
                                    <div>
                                        <div class="text-lg font-bold text-green-600 dark:text-green-400">{{ $rekap['hadir'] }}</div>
                                        <div class="text-xs text-gray-600 dark:text-gray-400">Hadir</div>
                                    </div>
                                    <div>
                                        <div class="text-lg font-bold text-blue-600 dark:text-blue-400">{{ $rekap['izin'] }}</div>
                                        <div class="text-xs text-gray-600 dark:text-gray-400">Izin</div>
                                    </div>
                                    <div>
                                        <div class="text-lg font-bold text-yellow-600 dark:text-yellow-400">{{ $rekap['sakit'] }}</div>
                                        <div class="text-xs text-gray-600 dark:text-gray-400">Sakit</div>
                                    </div>
                                    <div>
                                        <div class="text-lg font-bold text-red-600 dark:text-red-400">{{ $rekap['alpha'] }}</div>
                                        <div class="text-xs text-gray-600 dark:text-gray-400">Alpha</div>
                                    </div>
                                </div>
                                <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-600 text-center">
                                    <span class="text-xs text-gray-600 dark:text-gray-400">Total: </span>
                                    <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $rekap['total'] }} pertemuan</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                        <p>Tidak ada data absensi untuk periode yang dipilih.</p>
                    </div>
                @endif
            </div>

            <!-- Detail Absensi Harian -->
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Detail Absensi Harian</h3>
                
                @if($absensiList->count() > 0)
                    <!-- Desktop Table -->
                    <div class="hidden md:block overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead>
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">Tanggal</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">Mapel</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($absensiList as $absensi)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                                            {{ $absensi->tanggal->format('d/m/Y') }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                                            {{ $absensi->jadwal->mapel->nama_mapel ?? 'N/A' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-center">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($absensi->status == 'hadir') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                @elseif($absensi->status == 'izin') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                                @elseif($absensi->status == 'sakit') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                                @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @endif">
                                                {{ ucfirst($absensi->status) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">
                                            {{ $absensi->keterangan ?? '-' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Cards -->
                    <div class="md:hidden space-y-3">
                        @foreach($absensiList as $absensi)
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                                <div class="flex justify-between items-start mb-2">
                                    <span class="text-sm font-semibold text-gray-900 dark:text-white">
                                        {{ $absensi->tanggal->format('d/m/Y') }}
                                    </span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($absensi->status == 'hadir') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                        @elseif($absensi->status == 'izin') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                        @elseif($absensi->status == 'sakit') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                        @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @endif">
                                        {{ ucfirst($absensi->status) }}
                                    </span>
                                </div>
                                <div class="text-sm text-gray-900 dark:text-white mb-2">
                                    {{ $absensi->jadwal->mapel->nama_mapel ?? 'N/A' }}
                                </div>
                                @if($absensi->keterangan)
                                    <div class="text-xs text-gray-600 dark:text-gray-400">
                                        {{ $absensi->keterangan }}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                        <p>Tidak ada data absensi untuk periode yang dipilih.</p>
                    </div>
                @endif
            </div>

            <!-- Flash Message -->
            @if(session()->has('success'))
                <div class="mt-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-300 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif
        </div>
    </div>

    <!-- Loading Indicator -->
    <div wire:loading class="fixed inset-0 bg-black/30 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 shadow-lg">
            <div class="flex items-center space-x-3">
                <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-blue-600"></div>
                <span class="text-gray-900 dark:text-white text-sm">Memuat data...</span>
            </div>
        </div>
    </div>
</div>