<div>
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Monitoring Performa</h1>
        <p class="text-gray-600 dark:text-gray-400">Pantau dan analisis performa akademik siswa</p>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Pencarian Siswa</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="bi bi-search text-gray-400"></i>
                    </div>
                    <input 
                        type="text" 
                        wire:model.live="search"
                        placeholder="Cari nama siswa atau NIS..."
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
                    <option value="">Pilih Kelas</option>
                    @foreach($kelasList as $kelas)
                        <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Mata Pelajaran</label>
                <select 
                    wire:model.live="mapelId"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                >
                    <option value="">Semua Mapel</option>
                    @foreach($mapelList as $mapel)
                        <option value="{{ $mapel->id }}">{{ $mapel->nama_mapel }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Semester</label>
                <select 
                    wire:model.live="semester"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                >
                    <option value="">Semua Semester</option>
                    <option value="1">Semester 1</option>
                    <option value="2">Semester 2</option>
                </select>
            </div>
            <div class="flex items-end">
                <button 
                    wire:click="resetFilters"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700"
                >
                    Reset Filter
                </button>
            </div>
        </div>
        
        <!-- Filter Tahun Ajaran -->
        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tahun Ajaran</label>
                <select 
                    wire:model.live="tahunAjaranId"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                >
                    @foreach($tahunAjaranList as $tahun)
                        <option value="{{ $tahun->id }}" {{ $tahun->status === 'aktif' ? 'selected' : '' }}>
                            {{ $tahun->tahun_ajaran }} {{ $tahun->status === 'aktif' ? '(Aktif)' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Statistik -->
    @if($kelasId && $performaData->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-6 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 border-l-4 border-blue-500">
            <div class="text-center">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Siswa</p>
                <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $statistik['total_siswa'] }}</p>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 border-l-4 border-green-500">
            <div class="text-center">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Rata-rata Kelas</p>
                <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $statistik['rata_rata_kelas'] }}</p>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 border-l-4 border-yellow-500">
            <div class="text-center">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Nilai Tertinggi</p>
                <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $statistik['tertinggi'] }}</p>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 border-l-4 border-red-500">
            <div class="text-center">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Nilai Terendah</p>
                <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $statistik['terendah'] }}</p>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 border-l-4 border-green-500">
            <div class="text-center">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Lulus</p>
                <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $statistik['total_lulus'] }}</p>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 border-l-4 border-red-500">
            <div class="text-center">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Tidak Lulus</p>
                <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $statistik['total_tidak_lulus'] }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Daftar Performa -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Performa Siswa</h2>
            @if($kelasId)
                <span class="text-sm text-gray-500 dark:text-gray-400">
                    {{ $performaData->count() }} siswa ditemukan
                </span>
            @endif
        </div>

        <div class="p-6">
            @if(!$kelasId)
                <!-- Placeholder ketika belum memilih kelas -->
                <div class="text-center text-gray-500 dark:text-gray-400 py-12">
                    <i class="bi bi-graph-up-arrow text-4xl mb-4 block"></i>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Pilih Kelas</h3>
                    <p>Pilih kelas terlebih dahulu untuk melihat performa siswa</p>
                </div>
            @elseif($performaData->count() > 0)
                <!-- Ranking Performa -->
                <div class="space-y-4">
                    @foreach($performaData as $index => $performa)
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <!-- Ranking -->
                                    <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center 
                                        {{ $index < 3 ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200' }}">
                                        <span class="font-bold">#{{ $index + 1 }}</span>
                                    </div>
                                    
                                    <!-- Foto/Inisial Siswa -->
                                    <div class="flex-shrink-0 w-12 h-12 bg-primary-100 dark:bg-primary-900 rounded-full flex items-center justify-center">
                                        <span class="text-primary-600 dark:text-primary-300 font-medium text-lg">
                                            {{ substr($performa['siswa']->nama_lengkap, 0, 1) }}
                                        </span>
                                    </div>
                                    
                                    <!-- Info Siswa -->
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                            {{ $performa['siswa']->nama_lengkap }}
                                        </h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            NIS: {{ $performa['siswa']->nis }} • 
                                            {{ $performa['siswa']->kelas->nama_kelas ?? 'Kelas' }}
                                        </p>
                                        <p class="text-xs text-gray-400 dark:text-gray-500">
                                            {{ $performa['jumlah_mapel'] }} mapel • 
                                            {{ $performa['lulus'] }} lulus • 
                                            {{ $performa['tidak_lulus'] }} tidak lulus
                                        </p>
                                    </div>
                                </div>
                                
                                <!-- Nilai Rata-rata -->
                                <div class="text-right">
                                    <div class="text-2xl font-bold text-gray-900 dark:text-white">
                                        {{ $performa['rata_rata'] }}
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ $performa['warna'] == 'green' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : '' }}
                                        {{ $performa['warna'] == 'blue' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : '' }}
                                        {{ $performa['warna'] == 'yellow' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : '' }}
                                        {{ $performa['warna'] == 'red' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : '' }}">
                                        {{ $performa['kategori'] }}
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Detail Nilai -->
                            <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Detail Nilai:</span>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        Tertinggi: <span class="font-semibold text-green-600">{{ $performa['nilai_tertinggi'] }}</span> • 
                                        Terendah: <span class="font-semibold text-red-600">{{ $performa['nilai_terendah'] }}</span>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2">
                                    @foreach($performa['detail_nilai'] as $detail)
                                        <div class="flex justify-between items-center text-sm bg-gray-50 dark:bg-gray-700 px-3 py-2 rounded">
                                            <div>
                                                <span class="text-gray-700 dark:text-gray-300">{{ $detail['mapel'] }}</span>
                                                <span class="text-xs text-gray-500 ml-2">{{ $detail['predikat'] }}</span>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <span class="font-semibold {{ $detail['nilai_akhir'] >= 75 ? 'text-green-600' : ($detail['nilai_akhir'] >= 65 ? 'text-yellow-600' : 'text-red-600') }}">
                                                    {{ $detail['nilai_akhir'] }}
                                                </span>
                                                <span class="text-xs {{ $detail['status'] === 'Lulus' ? 'text-green-500' : 'text-red-500' }}">
                                                    {{ $detail['status'] === 'Lulus' ? '✓' : '✗' }}
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Tidak ada data -->
                <div class="text-center text-gray-500 dark:text-gray-400 py-12">
                    <i class="bi bi-clipboard-data text-4xl mb-4 block"></i>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Tidak Ada Data</h3>
                    <p>Tidak ditemukan data performa untuk kriteria yang dipilih</p>
                    @if($search)
                        <button 
                            wire:click="$set('search', '')"
                            class="mt-2 text-primary-600 hover:text-primary-700"
                        >
                            Tampilkan semua siswa
                        </button>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>