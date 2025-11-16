<div>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-4">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-6">
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mb-2">
                    Cetak Rapor
                </h1>
                <p class="text-gray-600 dark:text-gray-400 text-sm sm:text-base">
                    Cetak rapor semesteran siswa
                </p>
            </div>

            <!-- Filter Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                    <!-- Semester -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Semester
                        </label>
                        <select 
                            wire:model.live="semesterFilter"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors"
                        >
                            <option value="ganjil">Semester Ganjil</option>
                            <option value="genap">Semester Genap</option>
                        </select>
                    </div>

                    <!-- Tahun Ajaran -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Tahun Ajaran
                        </label>
                        <select 
                            wire:model.live="tahunAjaranFilter"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors"
                        >
                            @foreach($tahunAjaranList as $tahun)
                                <option value="{{ $tahun->id }}">{{ $tahun->tahun_ajaran }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tombol Cetak -->
                    <div>
                        <button 
                            wire:click="cetakRapor"
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-70 cursor-not-allowed"
                            class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-3 px-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center justify-center"
                        >
                            <i class="fas fa-print mr-2"></i>
                            <span wire:loading.remove>Cetak Rapor PDF</span>
                            <span wire:loading>
                                <i class="fas fa-spinner fa-spin mr-2"></i>
                                Memproses...
                            </span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Student Info -->
            @if($siswa)
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900 dark:to-indigo-900 border border-blue-200 dark:border-blue-700 rounded-xl p-4 sm:p-6 mb-6">
                <div class="flex flex-col sm:flex-row items-center sm:items-start text-center sm:text-left gap-4">
                    <div class="flex-shrink-0">
                        <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center text-white text-xl font-bold">
                            {{ substr($siswa->nama_lengkap, 0, 1) }}
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-1">
                            {{ $siswa->nama_lengkap }}
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 text-sm text-gray-600 dark:text-gray-300">
                            <div class="flex items-center justify-center sm:justify-start">
                                <i class="fas fa-id-card mr-2 text-blue-500"></i>
                                <span>NIS: {{ $siswa->nis }}</span>
                            </div>
                            <div class="flex items-center justify-center sm:justify-start">
                                <i class="fas fa-graduation-cap mr-2 text-green-500"></i>
                                <span>Kelas: {{ $siswa->kelas->nama_kelas ?? '-' }}</span>
                            </div>
                            <div class="flex items-center justify-center sm:justify-start">
                                <i class="fas fa-user-tie mr-2 text-purple-500"></i>
                                <span>Wali: {{ \Illuminate\Support\Str::limit($siswa->kelas->waliKelas->nama_lengkap ?? '-', 20) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Statistics -->
            @if($nilai->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 text-center">
                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400 mb-1">
                        {{ number_format($statistics['average'], 1) }}
                    </div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Rata-rata</div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 text-center">
                    <div class="text-2xl font-bold text-green-600 dark:text-green-400 mb-1">
                        {{ $statistics['totalMapel'] }}
                    </div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Total Mapel</div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 text-center">
                    <div class="text-2xl font-bold text-emerald-600 dark:text-emerald-400 mb-1">
                        {{ $statistics['lulus'] }}
                    </div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Lulus</div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 text-center">
                    <div class="text-2xl font-bold text-purple-600 dark:text-purple-400 mb-1">
                        {{ $statistics['totalMapel'] > 0 ? number_format(($statistics['lulus'] / $statistics['totalMapel']) * 100, 0) : 0 }}%
                    </div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">% Lulus</div>
                </div>
            </div>
            @endif

            <!-- Nilai Table -->
            @if($nilai->count() > 0)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-4 sm:px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Preview Nilai Semester {{ ucfirst($semesterFilter) }}
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        Menampilkan {{ $nilai->count() }} mata pelajaran
                    </p>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Mapel
                                </th>
                                <th class="px-3 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider hidden sm:table-cell">
                                    Guru
                                </th>
                                <th class="px-2 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Nilai
                                </th>
                                <th class="px-2 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Predikat
                                </th>
                                <th class="px-2 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Status
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($nilai as $item)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <td class="px-4 py-3">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $item->mapel->nama_mapel ?? 'N/A' }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 sm:hidden mt-1">
                                            {{ $item->guru->nama_lengkap ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td class="px-3 py-3 text-sm text-gray-900 dark:text-white hidden sm:table-cell">
                                        {{ \Illuminate\Support\Str::limit($item->guru->nama_lengkap ?? 'N/A', 25) }}
                                    </td>
                                    <td class="px-2 py-3 text-center">
                                        <span class="text-sm font-bold text-gray-900 dark:text-white">
                                            {{ $item->nilai_akhir ? number_format($item->nilai_akhir, 1) : '-' }}
                                        </span>
                                    </td>
                                    <td class="px-2 py-3 text-center">
                                        @php
                                            $predikatClass = match($item->predikat) {
                                                'A' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                                'B' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                                'C' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                                default => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200',
                                            };
                                        @endphp
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $predikatClass }}">
                                            {{ $item->predikat ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-2 py-3 text-center">
                                        @php
                                            $statusClass = $item->nilai_akhir >= ($item->mapel->kkm ?? 75) 
                                                ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'
                                                : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200';
                                        @endphp
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusClass }}">
                                            {{ $item->nilai_akhir >= ($item->mapel->kkm ?? 75) ? 'Lulus' : 'TL' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-12 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <i class="fas fa-clipboard-list text-gray-400 text-4xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
                        Belum ada nilai
                    </h3>
                    <p class="text-gray-500 dark:text-gray-400 max-w-md mx-auto">
                        Tidak ada data nilai untuk semester {{ $semesterFilter }} tahun ajaran yang dipilih.
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>