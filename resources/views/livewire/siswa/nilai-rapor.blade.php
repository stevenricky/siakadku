<div class="p-3 sm:p-4 md:p-6">
    <!-- Header -->
    <div class="mb-4 sm:mb-6">
        <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">Nilai & Rapor</h1>
        <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400 mt-1">Nilai dan hasil belajar Anda</p>
    </div>

    <!-- Filters -->
    <div class="mb-4 sm:mb-6 bg-white dark:bg-gray-800 rounded-lg shadow p-3 sm:p-4 md:p-6">
        <div class="space-y-3 sm:space-y-4">
            <!-- Tahun Ajaran & Semester Filters -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                <!-- Tahun Ajaran Filter -->
                <div>
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 sm:mb-2">
                        Tahun Ajaran
                    </label>
                    <select 
                        wire:model.live="tahunAjaranFilter"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                    >
                        <option value="">Pilih Tahun Ajaran</option>
                        @foreach($tahunAjaranList as $tahun)
                            <option value="{{ $tahun->id }}">{{ $tahun->tahun_ajaran }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Semester Filter -->
                <div>
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 sm:mb-2">
                        Semester
                    </label>
                    <select 
                        wire:model.live="semesterFilter"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                    >
                        <option value="ganjil">Ganjil</option>
                        <option value="genap">Genap</option>
                    </select>
                </div>
            </div>

            <!-- Statistics -->
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3 sm:p-4 flex items-center justify-center">
                <div class="text-center">
                    <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">Rata-rata Nilai</p>
                    <p class="text-xl sm:text-2xl md:text-3xl font-semibold text-gray-900 dark:text-white">
                        {{ number_format($statistics['average'], 1) }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4 md:gap-6 mb-4 sm:mb-6">
        <!-- Total Mapel -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 sm:p-4 md:p-6">
            <div class="flex items-center">
                <div class="p-2 sm:p-3 rounded-full bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400 mr-3 sm:mr-4">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400">Total Mapel</p>
                    <p class="text-lg sm:text-xl md:text-2xl font-semibold text-gray-900 dark:text-white">{{ $statistics['totalMapel'] }}</p>
                </div>
            </div>
        </div>

        <!-- Mapel Lulus -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 sm:p-4 md:p-6">
            <div class="flex items-center">
                <div class="p-2 sm:p-3 rounded-full bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400 mr-3 sm:mr-4">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400">Mapel Lulus</p>
                    <p class="text-lg sm:text-xl md:text-2xl font-semibold text-gray-900 dark:text-white">{{ $statistics['lulus'] }}</p>
                </div>
            </div>
        </div>

        <!-- Persentase Kelulusan -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 sm:p-4 md:p-6">
            <div class="flex items-center">
                <div class="p-2 sm:p-3 rounded-full bg-purple-100 dark:bg-purple-900 text-purple-600 dark:text-purple-400 mr-3 sm:mr-4">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400">Persentase Lulus</p>
                    <p class="text-lg sm:text-xl md:text-2xl font-semibold text-gray-900 dark:text-white">
                        {{ $statistics['totalMapel'] > 0 ? round(($statistics['lulus'] / $statistics['totalMapel']) * 100, 1) : 0 }}%
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    @if($nilai->count() > 0)
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <!-- Desktop Table View -->
        <div class="hidden sm:block overflow-x-auto">
            <table class="w-full min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Mata Pelajaran
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Guru
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Nilai
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Predikat
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Status
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($nilai as $item)
                        @php
                            $isLulus = $item->nilai_akhir >= ($item->mapel->kkm ?? 75);
                            $statusColor = $isLulus 
                                ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300'
                                : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300';
                            
                            $predikatColors = [
                                'A' => 'bg-green-100 text-green-800',
                                'B' => 'bg-blue-100 text-blue-800',
                                'C' => 'bg-yellow-100 text-yellow-800', 
                                'D' => 'bg-red-100 text-red-800'
                            ];
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $item->mapel->nama_mapel ?? 'Mata Pelajaran' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                {{ $item->guru->nama_lengkap ?? 'Guru' }}
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                                {{ number_format($item->nilai_akhir, 1) }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $predikatColors[$item->predikat] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $item->predikat }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusColor }}">
                                    {{ $isLulus ? 'Lulus' : 'Tidak' }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="sm:hidden divide-y divide-gray-200 dark:divide-gray-700">
            @foreach($nilai as $item)
                @php
                    $isLulus = $item->nilai_akhir >= ($item->mapel->kkm ?? 75);
                    $statusColor = $isLulus 
                        ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300'
                        : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300';
                    
                    $predikatColors = [
                        'A' => 'bg-green-100 text-green-800',
                        'B' => 'bg-blue-100 text-blue-800',
                        'C' => 'bg-yellow-100 text-yellow-800', 
                        'D' => 'bg-red-100 text-red-800'
                    ];
                @endphp
                <div class="p-4">
                    <div class="flex justify-between items-start mb-2">
                        <div class="flex-1">
                            <h3 class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $item->mapel->nama_mapel ?? 'Mata Pelajaran' }}
                            </h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                {{ $item->guru->nama_lengkap ?? 'Guru' }}
                            </p>
                        </div>
                        <div class="ml-3 text-right">
                            <div class="text-lg font-semibold text-gray-900 dark:text-white">
                                {{ number_format($item->nilai_akhir, 1) }}
                            </div>
                            <span class="inline-flex px-2 py-0.5 text-xs font-semibold rounded-full {{ $predikatColors[$item->predikat] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $item->predikat }}
                            </span>
                        </div>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-gray-500 dark:text-gray-400">
                            KKM: {{ $item->mapel->kkm ?? 75 }}
                        </span>
                        <span class="inline-flex px-2 py-0.5 text-xs font-semibold rounded-full {{ $statusColor }}">
                            {{ $isLulus ? 'Lulus' : 'Tidak Lulus' }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @else
    <!-- Empty State -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 sm:p-8 md:p-12 text-center">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">Tidak ada data nilai</h3>
        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
            Silakan pilih tahun ajaran dan semester untuk melihat nilai Anda.
        </p>
    </div>
    @endif
</div>