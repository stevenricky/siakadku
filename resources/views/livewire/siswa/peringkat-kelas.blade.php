<div>
    <div class="py-6">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Peringkat Kelas</h1>
                <p class="text-gray-600 dark:text-gray-400">Lihat peringkat akademik di kelas Anda</p>
            </div>

            <!-- Filter Section -->
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Semester Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Semester
                        </label>
                        <select wire:model.live="semester" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2.5 text-sm focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                            <option value="ganjil">Semester Ganjil</option>
                            <option value="genap">Semester Genap</option>
                        </select>
                    </div>

                    <!-- Tahun Ajaran Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Tahun Ajaran
                        </label>
                        <select wire:model.live="tahunAjaranId" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2.5 text-sm focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                            @foreach($tahunAjarans as $tahun)
                                <option value="{{ $tahun->id }}">{{ $tahun->tahun_ajaran }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Info Kelas -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Kelas
                        </label>
                        <div class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-lg px-3 py-2.5 text-sm font-medium">
                            {{ $siswaAuth->kelas->nama_kelas ?? '-' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Peringkat Saya -->
            @if($peringkatSaya)
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-xl border border-blue-200 dark:border-blue-800 p-6 mb-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="text-center md:text-left">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">Peringkat Anda</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Posisi Anda di antara {{ $totalSiswa }} siswa</p>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                        <div>
                            <div class="text-2xl font-bold text-blue-700 dark:text-blue-300">{{ $peringkatSaya['peringkat'] }}</div>
                            <div class="text-xs text-gray-600 dark:text-gray-400">Peringkat</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-blue-700 dark:text-blue-300">{{ $peringkatSaya['rata_rata'] }}</div>
                            <div class="text-xs text-gray-600 dark:text-gray-400">Rata-rata</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-blue-700 dark:text-blue-300">{{ $peringkatSaya['jumlah_mapel'] }}</div>
                            <div class="text-xs text-gray-600 dark:text-gray-400">Mapel</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-blue-700 dark:text-blue-300">{{ $totalSiswa }}</div>
                            <div class="text-xs text-gray-600 dark:text-gray-400">Total</div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Daftar Peringkat -->
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <!-- Header Tabel -->
                <div class="bg-gray-50 dark:bg-gray-700 px-4 sm:px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Daftar Peringkat</h3>
                        <span class="bg-white dark:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium px-3 py-1 rounded-lg border border-gray-300 dark:border-gray-500">
                            {{ count($peringkat) }} Siswa
                        </span>
                    </div>
                </div>

                @if(count($peringkat) > 0)
                    <!-- Desktop Table -->
                    <div class="hidden md:block overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Peringkat</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Nama Siswa</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Rata-rata</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Mapel</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($peringkat as $data)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150 
                                        {{ $data['siswa']->id === $siswaAuth->id ? 'bg-blue-50 dark:bg-blue-900/20' : '' }}">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                @if($data['peringkat'] <= 3)
                                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center 
                                                        @if($data['peringkat'] == 1) bg-yellow-100 border border-yellow-200 text-yellow-800
                                                        @elseif($data['peringkat'] == 2) bg-gray-100 border border-gray-200 text-gray-800
                                                        @else bg-amber-100 border border-amber-200 text-amber-800 @endif">
                                                        <span class="font-bold text-sm">
                                                            {{ $data['peringkat'] }}
                                                        </span>
                                                    </div>
                                                @else
                                                    <div class="w-8 h-8 bg-gray-100 dark:bg-gray-600 rounded-lg flex items-center justify-center">
                                                        <span class="text-gray-700 dark:text-gray-300 font-semibold text-sm">
                                                            {{ $data['peringkat'] }}
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center space-x-3">
                                                <div class="flex-shrink-0 w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                                                    <span class="text-blue-700 dark:text-blue-300 font-semibold text-sm">
                                                        {{ substr($data['siswa']->nama_lengkap, 0, 1) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                        {{ $data['siswa']->nama_lengkap }}
                                                    </div>
                                                    @if($data['siswa']->id === $siswaAuth->id)
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300">
                                                            Anda
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                                {{ $data['rata_rata'] }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <div class="text-sm text-gray-900 dark:text-white">
                                                {{ $data['jumlah_mapel'] }}
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Cards -->
                    <div class="md:hidden space-y-3 p-4">
                        @foreach($peringkat as $data)
                            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4 
                                {{ $data['siswa']->id === $siswaAuth->id ? 'ring-2 ring-blue-500' : '' }}">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center space-x-3">
                                        @if($data['peringkat'] <= 3)
                                            <div class="w-8 h-8 rounded-lg flex items-center justify-center 
                                                @if($data['peringkat'] == 1) bg-yellow-100 border border-yellow-200 text-yellow-800
                                                @elseif($data['peringkat'] == 2) bg-gray-100 border border-gray-200 text-gray-800
                                                @else bg-amber-100 border border-amber-200 text-amber-800 @endif">
                                                <span class="font-bold text-sm">
                                                    {{ $data['peringkat'] }}
                                                </span>
                                            </div>
                                        @else
                                            <div class="w-8 h-8 bg-gray-100 dark:bg-gray-600 rounded-lg flex items-center justify-center">
                                                <span class="text-gray-700 dark:text-gray-300 font-semibold text-sm">
                                                    {{ $data['peringkat'] }}
                                                </span>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $data['siswa']->nama_lengkap }}
                                            </div>
                                            @if($data['siswa']->id === $siswaAuth->id)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300">
                                                    Anda
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4 text-center">
                                    <div>
                                        <div class="text-xs text-gray-600 dark:text-gray-400">Rata-rata</div>
                                        <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ $data['rata_rata'] }}</div>
                                    </div>
                                    <div>
                                        <div class="text-xs text-gray-600 dark:text-gray-400">Mapel</div>
                                        <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ $data['jumlah_mapel'] }}</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12 px-4">
                        <div class="max-w-md mx-auto">
                            <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 dark:bg-gray-700 rounded-xl flex items-center justify-center">
                                <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Belum Ada Data Peringkat</h3>
                            <p class="text-gray-600 dark:text-gray-400 text-sm">
                                Data peringkat akan muncul ketika nilai untuk periode ini tersedia
                            </p>
                        </div>
                    </div>
                @endif
            </div>
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