@if($activeTab === 'mengajar' && auth()->user()->isGuru())
<!-- Data Mengajar -->
<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden"
     x-data="mengajarData()"
     x-show="activeTab === 'mengajar'"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 transform scale-95"
     x-transition:enter-end="opacity-100 transform scale-100">
    <div class="bg-gradient-to-r from-purple-600 to-pink-600 px-6 py-4">
        <h3 class="text-lg font-semibold text-white flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
            Data Mengajar
        </h3>
    </div>
    
    <div class="p-6">
        <!-- Statistik Mengajar -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-slate-50 dark:bg-slate-700 rounded-xl p-4 text-center">
                <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">
                    {{ $statistikGuru['total_mapel'] ?? 0 }}
                </div>
                <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">Mata Pelajaran</div>
            </div>
            <div class="bg-slate-50 dark:bg-slate-700 rounded-xl p-4 text-center">
                <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                    {{ $statistikGuru['total_jadwal'] ?? 0 }}
                </div>
                <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">Jadwal Mengajar</div>
            </div>
            <div class="bg-slate-50 dark:bg-slate-700 rounded-xl p-4 text-center">
                <div class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">
                    {{ $statistikGuru['total_rpp'] ?? 0 }}
                </div>
                <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">RPP Dibuat</div>
            </div>
            <div class="bg-slate-50 dark:bg-slate-700 rounded-xl p-4 text-center">
                <div class="text-2xl font-bold text-amber-600 dark:text-amber-400">
                    {{ $statistikGuru['total_kelas_wali'] ?? 0 }}
                </div>
                <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">Kelas Wali</div>
            </div>
        </div>

        <!-- Mata Pelajaran yang Diampu -->
        <div class="mb-8">
            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                Mata Pelajaran yang Diampu
            </h4>
            
            @if($roleData && $roleData->mapel->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($roleData->mapel as $mapel)
                <div class="bg-slate-50 dark:bg-slate-700 rounded-xl p-4 border border-slate-200 dark:border-slate-600">
                    <div class="flex items-center justify-between">
                        <div>
                            <h5 class="font-semibold text-gray-900 dark:text-white">{{ $mapel->nama_mapel }}</h5>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Kode: {{ $mapel->kode_mapel }}</p>
                        </div>
                        <span class="px-2 py-1 bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200 text-xs rounded-full">
                            Aktif
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8">
                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                <p class="text-gray-500 dark:text-gray-400">Belum ada mata pelajaran yang diampu</p>
            </div>
            @endif
        </div>

        <!-- Kelas Wali (jika ada) -->
        @if($roleData && $roleData->kelasWali)
        <div class="mb-8">
            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                Kelas Wali
            </h4>
            
            <div class="bg-slate-50 dark:bg-slate-700 rounded-xl p-6 border border-slate-200 dark:border-slate-600">
                <div class="flex items-center justify-between">
                    <div>
                        <h5 class="text-xl font-bold text-gray-900 dark:text-white">{{ $roleData->kelasWali->nama_kelas }}</h5>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">
                            Tingkat: {{ $roleData->kelasWali->tingkat }} | 
                            Jurusan: {{ $roleData->kelasWali->jurusan ?? 'Umum' }}
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                            Jumlah Siswa: {{ $roleData->kelasWali->siswas->count() }} siswa
                        </p>
                    </div>
                    <div class="text-right">
                        <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-sm rounded-full font-medium">
                            Wali Kelas
                        </span>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Jadwal Mengajar Terdekat -->
        <div>
            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Jadwal Mengajar Hari Ini
            </h4>
            
            @if($roleData && $roleData->jadwal->count() > 0)
            <div class="space-y-3">
                @php
                    $today = \Carbon\Carbon::today()->englishDayOfWeek;
                    $todaySchedules = $roleData->jadwal->where('hari', strtolower($today));
                @endphp
                
                @if($todaySchedules->count() > 0)
                    @foreach($todaySchedules as $jadwal)
                    <div class="bg-slate-50 dark:bg-slate-700 rounded-xl p-4 border border-slate-200 dark:border-slate-600">
                        <div class="flex items-center justify-between">
                            <div>
                                <h5 class="font-semibold text-gray-900 dark:text-white">{{ $jadwal->mapel->nama_mapel }}</h5>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ $jadwal->kelas->nama_kelas }} | 
                                    {{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}
                                </p>
                            </div>
                            <span class="px-2 py-1 bg-emerald-100 dark:bg-emerald-900 text-emerald-800 dark:text-emerald-200 text-xs rounded-full">
                                Hari Ini
                            </span>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-4">
                        <p class="text-gray-500 dark:text-gray-400">Tidak ada jadwal mengajar hari ini</p>
                    </div>
                @endif
            </div>
            @else
            <div class="text-center py-4">
                <p class="text-gray-500 dark:text-gray-400">Belum ada jadwal mengajar</p>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
function mengajarData() {
    return {
        activeTab: @entangle('activeTab'),
        
        init() {
            // Auto-refresh data mengajar setiap 1 menit
            setInterval(() => {
                Livewire.dispatch('refreshProfile');
            }, 60000);
        }
    }
}
</script>
@endif