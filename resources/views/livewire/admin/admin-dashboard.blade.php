<div class="space-y-6">
    <!-- Header dengan Refresh Button -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard Admin</h1>
            <p class="text-gray-600 dark:text-gray-400">{{ $subtitle }}</p>
        </div>
        <div class="flex items-center space-x-3">
            <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                <div class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></div>
                <span>Live</span>
            </div>
            
            <button wire:click="refreshData" 
                    wire:loading.attr="disabled"
                    class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg transition-colors duration-200 disabled:opacity-50 text-sm">
                <svg wire:loading.remove wire:target="refreshData" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                <svg wire:loading wire:target="refreshData" class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Refresh Data
            </button>
        </div>
    </div>

    <!-- Flash Message -->
    @if (session()->has('message'))
        <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" 
             class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center justify-between">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ session('message') }}</span>
            </div>
            <button @click="show = false" class="text-green-700 hover:text-green-900">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    @endif

   <!-- Real-time Stats Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
    <!-- Total Siswa -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 sm:p-6 border-l-4 border-blue-500">
        <!-- Header -->
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Siswa</h3>
            @if($realtimeStats['new_registrations'] > 0)
            <div class="text-green-500 flex items-center text-sm">
                <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
                <span class="text-xs sm:text-sm ml-1">+{{ $realtimeStats['new_registrations'] }}</span>
            </div>
            @endif
        </div>
        
        <!-- Content -->
        <div class="flex items-center gap-3">
            <div class="p-2 sm:p-3 bg-blue-100 dark:bg-blue-900 rounded-lg">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-xl sm:text-2xl font-semibold text-gray-900 dark:text-white">{{ $totalSiswa }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $siswaAktif }} aktif</p>
            </div>
        </div>
    </div>


        <!-- Kehadiran Hari Ini - Stacked Layout -->
<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 sm:p-6 border-l-4 border-green-500">
    <!-- Header -->
    <div class="flex items-center justify-between mb-3">
        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Kehadiran Hari Ini</h3>
        @php
            $attendanceRate = $realtimeStats['today_attendance'] > 0 ? 
                round(($realtimeStats['today_present'] / $realtimeStats['today_attendance']) * 100, 1) : 0;
        @endphp
        <div class="text-sm font-semibold text-gray-700 dark:text-gray-300">
            {{ $attendanceRate }}%
        </div>
    </div>
    
    <!-- Content -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-green-100 dark:bg-green-900 rounded-lg">
                <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-xl font-semibold text-gray-900 dark:text-white">
                {{ $realtimeStats['today_present'] }}/{{ $realtimeStats['today_attendance'] }}
            </p>
        </div>
    </div>
    
    <!-- Progress Bar -->
    <div class="mt-3 w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
        <div class="bg-green-500 h-2 rounded-full transition-all duration-500 ease-out" 
             style="width: {{ min($attendanceRate, 100) }}%"></div>
    </div>
</div>

        <!-- Pengguna Aktif -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 sm:p-6 border-l-4 border-purple-500">
            <div class="flex items-center">
                <div class="p-2 sm:p-3 bg-purple-100 dark:bg-purple-900 rounded-lg">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <div class="ml-3 sm:ml-4">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Pengguna Aktif</h3>
                    <p class="text-xl sm:text-2xl font-semibold text-gray-900 dark:text-white">{{ $realtimeStats['online_users'] }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $realtimeStats['active_sessions'] }} sesi</p>
                </div>
            </div>
        </div>

        <!-- Total Guru -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 sm:p-6 border-l-4 border-yellow-500">
            <div class="flex items-center">
                <div class="p-2 sm:p-3 bg-yellow-100 dark:bg-yellow-900 rounded-lg">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div class="ml-3 sm:ml-4">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Guru</h3>
                    <p class="text-xl sm:text-2xl font-semibold text-gray-900 dark:text-white">{{ $totalGuru }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $guruAktif }} aktif</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
        <!-- Chart Distribusi Siswa per Kelas -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 sm:p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Distribusi Siswa per Kelas</h3>
            <div class="h-48 sm:h-64">
                <canvas id="classDistributionChart" 
                        wire:ignore
                        x-data="{
                            init() {
                                const ctx = this.$el.getContext('2d');
                                new Chart(ctx, {
                                    type: 'bar',
                                    data: @js($chartData),
                                    options: {
                                        responsive: true,
                                        maintainAspectRatio: false,
                                        plugins: {
                                            legend: {
                                                position: 'top',
                                            },
                                            tooltip: {
                                                mode: 'index',
                                                intersect: false
                                            }
                                        },
                                        scales: {
                                            y: {
                                                beginAtZero: true,
                                                ticks: {
                                                    stepSize: 1
                                                }
                                            }
                                        }
                                    }
                                });
                            }
                        }">
                </canvas>
            </div>
        </div>

        <!-- Chart Trend Kehadiran 7 Hari -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 sm:p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Trend Kehadiran 7 Hari Terakhir</h3>
            <div class="h-48 sm:h-64">
                <canvas id="attendanceTrendChart"
                        wire:ignore
                        x-data="{
                            init() {
                                const ctx = this.$el.getContext('2d');
                                new Chart(ctx, {
                                    type: 'line',
                                    data: @js($attendanceData),
                                    options: {
                                        responsive: true,
                                        maintainAspectRatio: false,
                                        plugins: {
                                            legend: {
                                                position: 'top',
                                            }
                                        },
                                        scales: {
                                            y: {
                                                beginAtZero: true,
                                                max: 100,
                                                ticks: {
                                                    callback: function(value) {
                                                        return value + '%';
                                                    }
                                                }
                                            }
                                        }
                                    }
                                });
                            }
                        }">
                </canvas>
            </div>
        </div>

        <!-- Chart Distribusi Nilai -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 sm:p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Distribusi Nilai Siswa</h3>
            <div class="h-48 sm:h-64">
                <canvas id="performanceChart"
                        wire:ignore
                        x-data="{
                            init() {
                                const ctx = this.$el.getContext('2d');
                                new Chart(ctx, {
                                    type: 'doughnut',
                                    data: @js($performanceData),
                                    options: {
                                        responsive: true,
                                        maintainAspectRatio: false,
                                        plugins: {
                                            legend: {
                                                position: 'bottom',
                                            }
                                        }
                                    }
                                });
                            }
                        }">
                </canvas>
            </div>
        </div>

        <!-- Chart Aktivitas Pengguna - DIPERBAIKI -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 sm:p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Aktivitas Pengguna 7 Hari</h3>
            <div class="h-48 sm:h-64">
                <canvas id="userActivityChart"
                        wire:ignore
                        x-data="{
                            init() {
                                const ctx = this.$el.getContext('2d');
                                const chartData = @js($userActivityData);
                                
                                // Tambahkan warna untuk setiap dataset
                                if (chartData && chartData.datasets) {
                                    chartData.datasets.forEach((dataset, index) => {
                                        const colors = [
                                            'rgba(59, 130, 246, 0.8)',  // Blue
                                            'rgba(16, 185, 129, 0.8)',  // Green  
                                            'rgba(245, 158, 11, 0.8)',  // Yellow
                                            'rgba(139, 92, 246, 0.8)',  // Purple
                                            'rgba(239, 68, 68, 0.8)',   // Red
                                            'rgba(14, 165, 233, 0.8)',  // Sky
                                            'rgba(20, 184, 166, 0.8)'   // Teal
                                        ];
                                        dataset.backgroundColor = colors[index] || colors[0];
                                        dataset.borderColor = dataset.backgroundColor.replace('0.8', '1');
                                        dataset.borderWidth = 2;
                                    });
                                }

                                new Chart(ctx, {
                                    type: 'bar',
                                    data: chartData,
                                    options: {
                                        responsive: true,
                                        maintainAspectRatio: false,
                                        plugins: {
                                            legend: {
                                                position: 'top',
                                            }
                                        },
                                        scales: {
                                            y: {
                                                beginAtZero: true,
                                                ticks: {
                                                    stepSize: 1
                                                }
                                            }
                                        }
                                    }
                                });
                            }
                        }">
                </canvas>
            </div>
        </div>
    </div>

    <!-- Additional Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        <!-- Quick Stats -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 sm:p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Statistik Cepat</h3>
            <div class="grid grid-cols-2 gap-3 sm:gap-4">
                <div class="text-center p-3 sm:p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                    <div class="text-xl sm:text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $totalMapel }}</div>
                    <div class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">Mata Pelajaran</div>
                </div>
                <div class="text-center p-3 sm:p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                    <div class="text-xl sm:text-2xl font-bold text-green-600 dark:text-green-400">{{ $siswaAktif }}</div>
                    <div class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">Siswa Aktif</div>
                </div>
                <div class="text-center p-3 sm:p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                    <div class="text-xl sm:text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $mapelIpa }}</div>
                    <div class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">Mapel IPA</div>
                </div>
                <div class="text-center p-3 sm:p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                    <div class="text-xl sm:text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $mapelIps }}</div>
                    <div class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">Mapel IPS</div>
                </div>
            </div>
        </div>

        <!-- Kelas Distribution - DIPERBAIKI -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 sm:p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Distribusi Kelas</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Kelas X</span>
                    <div class="flex items-center space-x-2">
                        <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $kelasX }}</span>
                        <div class="w-16 sm:w-20 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            <div class="bg-blue-500 h-2 rounded-full" 
                                 style="width: {{ $totalSiswa > 0 ? ($kelasX / $totalSiswa) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Kelas XI</span>
                    <div class="flex items-center space-x-2">
                        <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $kelasXI }}</span>
                        <div class="w-16 sm:w-20 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            <div class="bg-green-500 h-2 rounded-full" 
                                 style="width: {{ $totalSiswa > 0 ? ($kelasXI / $totalSiswa) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Kelas XII</span>
                    <div class="flex items-center space-x-2">
                        <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $kelasXII }}</span>
                        <div class="w-16 sm:w-20 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            <div class="bg-purple-500 h-2 rounded-full" 
                                 style="width: {{ $totalSiswa > 0 ? ($kelasXII / $totalSiswa) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Weekly Summary -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 sm:p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Ringkasan Minggu Ini</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Pendaftaran Baru</span>
                    <span class="text-sm font-semibold text-green-600 dark:text-green-400">{{ $realtimeStats['weekly_registrations'] }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Sesi Aktif</span>
                    <span class="text-sm font-semibold text-blue-600 dark:text-blue-400">{{ $realtimeStats['active_sessions'] }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Backup Terakhir</span>
                    <span class="text-xs font-semibold text-gray-600 dark:text-gray-400">{{ $lastBackup }}</span>
                </div>
            </div>
        </div>

        <!-- System Status -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 sm:p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Status Sistem</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Database</span>
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                        <div class="w-2 h-2 bg-green-500 rounded-full mr-1"></div>
                        Online
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Cache</span>
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                        Active
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Data Source</span>
                    <span class="text-xs font-medium text-blue-600 dark:text-blue-400">{{ $realtimeStats['data_source'] }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Students -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="px-4 sm:px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Siswa Baru Terdaftar</h3>
            <div class="flex items-center space-x-2">
                <span class="text-xs text-gray-500 dark:text-gray-400">
                    Total: {{ $totalSiswa }} siswa
                </span>
                @if(Route::has('admin.siswa.index'))
                    <a href="{{ route('admin.siswa.index') }}" class="text-primary-600 dark:text-primary-400 text-sm hover:underline">Lihat Semua</a>
                @else
                    <a href="/admin/siswa" class="text-primary-600 dark:text-primary-400 text-sm hover:underline">Lihat Semua</a>
                @endif
            </div>
        </div>
        <div class="p-4 sm:p-6">
            @if($recentSiswa->count() > 0)
                <div class="space-y-3">
                    @foreach($recentSiswa as $siswa)
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                        <div class="flex items-center">
                            <div class="h-8 w-8 sm:h-10 sm:w-10 bg-primary-100 dark:bg-primary-900 rounded-full flex items-center justify-center">
                                <span class="text-xs sm:text-sm font-medium text-primary-600 dark:text-primary-400">
                                    {{ strtoupper(substr($siswa->nama_lengkap ?? $siswa->nama, 0, 1)) }}
                                </span>
                            </div>
                            <div class="ml-3">
                                <h4 class="text-sm font-medium text-gray-900 dark:text-white">{{ $siswa->nama_lengkap ?? $siswa->nama }}</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $siswa->kelas->nama ?? 'Belum ada kelas' }} • {{ $siswa->nis }}
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ $siswa->created_at->format('d M Y') }}</span>
                            <div class="text-xs text-green-500 dark:text-green-400 mt-1">
                                {{ $siswa->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-6 sm:py-8">
                    <svg class="w-10 h-10 sm:w-12 sm:h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                    </svg>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Belum ada siswa terdaftar</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Auto-refresh data setiap 30 detik (tanpa notifikasi)
setInterval(() => {
    @this.refreshData();
}, 30000);
</script>