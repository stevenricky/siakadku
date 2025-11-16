<div>
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Laporan Nilai</h1>
        <p class="text-gray-600 dark:text-gray-400">Laporan nilai siswa per kelas dan mata pelajaran</p>
    </div>

    <!-- Filter Section -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Filter Laporan</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Kelas</label>
                <select 
                    wire:model.live="kelasFilter"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                >
                    <option value="">Pilih Kelas</option>
                    @foreach($kelasList as $kelas)
                        <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Mata Pelajaran</label>
                <select 
                    wire:model.live="mapelFilter"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                >
                    <option value="">Pilih Mata Pelajaran</option>
                    @foreach($mapelList as $mapel)
                        <option value="{{ $mapel->id }}">{{ $mapel->nama_mapel }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Semester</label>
                <select 
                    wire:model.live="semesterFilter"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                >
                    <option value="ganjil">Ganjil</option>
                    <option value="genap">Genap</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    @if($kelasFilter && $mapelFilter && !empty($statistics))
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="bi bi-people text-2xl text-blue-500"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Siswa</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $statistics['totalSiswa'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="bi bi-graph-up text-2xl text-green-500"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Rata-rata Nilai</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $statistics['average'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-purple-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="bi bi-arrow-up text-2xl text-purple-500"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Nilai Tertinggi</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $statistics['highest'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-orange-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="bi bi-arrow-down text-2xl text-orange-500"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Nilai Terendah</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $statistics['lowest'] }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Action Buttons -->
    @if($kelasFilter && $mapelFilter)
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
        <div class="flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Data Nilai Tersedia</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    {{ $nilaiData->count() }} data nilai ditemukan
                </p>
            </div>
            <div class="flex space-x-3">
                <button 
                    wire:click="exportPDF"
                    class="flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors"
                >
                    <i class="bi bi-file-pdf mr-2"></i>
                    Export PDF
                </button>
                <button 
                    wire:click="exportExcel"
                    class="flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors"
                >
                    <i class="bi bi-file-excel mr-2"></i>
                    Export Excel
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Data Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Data Nilai Siswa</h2>
        </div>

        <div class="p-6">
            @if(!$kelasFilter || !$mapelFilter)
                <div class="text-center py-8">
                    <i class="bi bi-funnel text-4xl text-gray-300 mb-3"></i>
                    <p class="text-gray-500 dark:text-gray-400">Silakan pilih kelas dan mata pelajaran untuk melihat laporan nilai</p>
                </div>
            @elseif($nilaiData->isEmpty())
                <div class="text-center py-8">
                    <i class="bi bi-exclamation-circle text-4xl text-yellow-300 mb-3"></i>
                    <p class="text-gray-500 dark:text-gray-400">Tidak ada data nilai untuk filter yang dipilih</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">NIS</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nama Siswa</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nilai Tugas</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nilai UTS</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nilai UAS</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nilai Akhir</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Grade</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
    @foreach($nilaiData as $index => $nilai)
    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
            {{ $index + 1 }}
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
            {{ $nilai->siswa->nis ?? '-' }}
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
            {{ $nilai->siswa->nama_lengkap ?? '-' }}
        </td>
        <!-- PERBAIKAN: Gunakan field yang sesuai dengan database -->
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white text-center">
            {{ $nilai->nilai_uh1 ?? 0 }}
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white text-center">
            {{ $nilai->nilai_uh2 ?? 0 }}
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white text-center">
            {{ $nilai->nilai_uts ?? 0 }}
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white text-center">
            {{ $nilai->nilai_uas ?? 0 }}
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-center">
    <span class="text-sm font-semibold {{ $nilai->nilai_akhir >= 75 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
        {{ $nilai->nilai_akhir ?? 0 }}
    </span>
</td>

                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @php
                                        $nilaiAkhir = $nilai->nilai_akhir ?? 0;
                                        if ($nilaiAkhir >= 85) {
                                            $grade = 'A';
                                            $color = 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200';
                                        } elseif ($nilaiAkhir >= 75) {
                                            $grade = 'B';
                                            $color = 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200';
                                        } elseif ($nilaiAkhir >= 65) {
                                            $grade = 'C';
                                            $color = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200';
                                        } elseif ($nilaiAkhir >= 55) {
                                            $grade = 'D';
                                            $color = 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200';
                                        } else {
                                            $grade = 'E';
                                            $color = 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200';
                                        }
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $color }}">
                                        {{ $grade }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($nilai->nilai_akhir >= 75)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                            Lulus
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                            Tidak Lulus
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Summary Section -->
                @if(!empty($statistics))
                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Ringkasan Kelulusan</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Lulus:</span>
                                    {{ $statistics['passed'] }} siswa
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Tidak Lulus:</span>
                                <span class="text-sm font-semibold text-red-600 dark:text-red-400">
                                    {{ $statistics['failed'] }} siswa
                                </span>
                            </div>
                            <div class="pt-2">
                                @php
                                    $total = $statistics['totalSiswa'];
                                    $passedPercentage = $total > 0 ? ($statistics['passed'] / $total) * 100 : 0;
                                @endphp
                                <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400 mb-1">
                                    <span>Persentase Kelulusan</span>
                                    <span>{{ number_format($passedPercentage, 1) }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                    <div class="bg-green-600 h-2 rounded-full" style="width: {{ $passedPercentage }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Distribusi Nilai</h3>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">Rata-rata Kelas:</span>
                                <span class="font-semibold text-gray-900 dark:text-white">{{ $statistics['average'] }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">Rentang Nilai:</span>
                                <span class="font-semibold text-gray-900 dark:text-white">
                                    {{ $statistics['lowest'] }} - {{ $statistics['highest'] }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            @endif
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

    @if (session()->has('error'))
        <div class="fixed top-4 right-4 z-50">
            <div class="bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-2">
                <i class="bi bi-exclamation-circle"></i>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif
</div>