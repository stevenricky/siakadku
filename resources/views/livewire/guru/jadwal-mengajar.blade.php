<div>
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Jadwal Mengajar</h1>
        <p class="text-gray-600 dark:text-gray-400">Jadwal mengajar Anda</p>
    </div>

    <!-- Hari Filter -->
    <div class="mb-6 bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex flex-wrap gap-2">
            @foreach($hariList as $hari)
                <button
                    wire:click="$set('hariFilter', '{{ $hari }}')"
                    class="px-4 py-2 rounded-lg transition-colors {{ $hariFilter === $hari 
                        ? 'bg-primary-600 text-white' 
                        : 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600' }}"
                >
                    {{ $hari }}
                </button>
            @endforeach
        </div>
    </div>

    <!-- Jadwal Harian -->
    <div class="mb-8">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
            Jadwal {{ $hariFilter }}
        </h2>
        
        @if($jadwal->count() > 0)
            <div class="grid gap-4">
                @foreach($jadwal as $item)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-primary-500">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ $item->mapel->nama_mapel ?? 'Mata Pelajaran' }}
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    {{ $item->kelas->nama_kelas ?? 'Kelas' }} • {{ $item->ruangan }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ $item->jam_mulai }} - {{ $item->jam_selesai }}
                                </p>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    {{ $item->jam_pelajaran }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-8 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">Tidak ada jadwal</h3>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    Tidak ada jadwal mengajar untuk hari {{ $hariFilter }}.
                </p>
            </div>
        @endif
    </div>

    <!-- Jadwal Mingguan -->
    <div>
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
            Jadwal Mingguan
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($hariList as $hari)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                    <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="font-semibold text-gray-900 dark:text-white">{{ $hari }}</h3>
                    </div>
                    <div class="p-4">
                        @if(isset($jadwalMingguan[$hari]) && $jadwalMingguan[$hari]->count() > 0)
                            <div class="space-y-3">
                                @foreach($jadwalMingguan[$hari] as $item)
                                    <div class="border-l-4 border-primary-500 pl-3 py-2">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $item->mapel->nama_mapel ?? 'Mapel' }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $item->kelas->nama_kelas ?? 'Kelas' }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $item->jam_mulai }} - {{ $item->jam_selesai }} • {{ $item->ruangan }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">
                                Tidak ada jadwal
                            </p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>