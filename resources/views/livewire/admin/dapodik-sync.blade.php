<div>
    <div class="p-4 sm:p-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">Sinkronisasi Dapodik</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Sinkronisasi data dengan sistem Dapodik - SMA NPSN: {{ $dapodikConfig['npsn'] }}</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="bg-white dark:bg-gray-800 rounded-lg p-3 shadow">
                    <div class="text-2xl font-bold text-blue-600">{{ $syncData['siswa'] + $syncData['guru'] + $syncData['kelas'] }}</div>
                    <div class="text-sm text-gray-500">Total Data</div>
                </div>
            </div>
        </div>

        <!-- Flash Messages -->
        @if (session()->has('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                <i class="fas fa-exclamation-triangle mr-2"></i>{{ session('error') }}
            </div>
        @endif

        @if (session()->has('warning'))
            <div class="mb-4 p-4 bg-yellow-100 border border-yellow-400 text-yellow-700 rounded-lg">
                <i class="fas fa-exclamation-circle mr-2"></i>{{ session('warning') }}
            </div>
        @endif

        @if (session()->has('info'))
            <div class="mb-4 p-4 bg-blue-100 border border-blue-400 text-blue-700 rounded-lg">
                <i class="fas fa-info-circle mr-2"></i>{{ session('info') }}
            </div>
        @endif

        <!-- Sync Status Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Status Sinkronisasi</h3>
                    
                    <div class="flex items-center gap-3 mb-4">
                        <div class="flex items-center gap-2">
                            @if($syncStatus === 'idle')
                                <div class="w-3 h-3 bg-gray-400 rounded-full"></div>
                                <span class="text-sm text-gray-600 dark:text-gray-400">Siap</span>
                            @elseif($syncStatus === 'testing')
                                <div class="w-3 h-3 bg-blue-400 rounded-full animate-pulse"></div>
                                <span class="text-sm text-blue-600 dark:text-blue-400">Testing Koneksi</span>
                            @elseif($syncStatus === 'syncing')
                                <div class="w-3 h-3 bg-yellow-400 rounded-full animate-pulse"></div>
                                <span class="text-sm text-yellow-600 dark:text-yellow-400">Sedang Sync</span>
                            @elseif($syncStatus === 'success')
                                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                <span class="text-sm text-green-600 dark:text-green-400">Berhasil</span>
                            @elseif($syncStatus === 'error')
                                <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                                <span class="text-sm text-red-600 dark:text-red-400">Error</span>
                            @elseif($syncStatus === 'warning')
                                <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                                <span class="text-sm text-yellow-600 dark:text-yellow-400">Warning</span>
                            @endif
                        </div>
                        
                        @if($lastSync)
                            <span class="text-sm text-gray-500">• Terakhir sync: {{ \Carbon\Carbon::parse($lastSync)->translatedFormat('d M Y H:i') }}</span>
                        @endif
                    </div>

                    <!-- Progress Bar -->
                    @if($syncStatus === 'syncing')
                    <div class="w-full bg-gray-200 rounded-full h-3 dark:bg-gray-700 mb-4">
                        <div 
                            class="bg-blue-600 h-3 rounded-full transition-all duration-300"
                            style="width: {{ $syncProgress }}%">
                        </div>
                    </div>
                    @if($currentStep)
                    <p class="text-sm text-blue-600 dark:text-blue-400 mb-2">
                        <i class="fas fa-spinner fa-spin mr-2"></i>{{ $currentStep }}
                    </p>
                    @endif
                    @endif

                    <!-- Sync Results -->
                    @if(!empty($syncResults) && $syncStatus !== 'syncing')
                    <div class="mt-4 space-y-2">
                        @foreach($syncResults as $type => $result)
                        <div class="flex items-center justify-between text-sm">
                            <span class="capitalize">{{ $type }}:</span>
                            @if($result['success'])
                                <span class="text-green-600 flex items-center">
                                    <i class="fas fa-check mr-1"></i> {{ $result['count'] }} data
                                </span>
                            @else
                                <span class="text-red-600 flex items-center">
                                    <i class="fas fa-times mr-1"></i> Gagal
                                </span>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>

                <div class="flex flex-col sm:flex-row gap-3">
                    <button 
                        wire:click="testConnection"
                        wire:loading.attr="disabled"
                        wire:target="testConnection"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg flex items-center transition-colors disabled:opacity-50">
                        <i class="fas fa-plug mr-2"></i>
                        <span wire:loading.remove wire:target="testConnection">Test Koneksi</span>
                        <span wire:loading wire:target="testConnection">Testing...</span>
                    </button>
                    
                    <button 
                        wire:click="startSync"
                        wire:loading.attr="disabled"
                        wire:target="startSync"
                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg flex items-center transition-colors disabled:opacity-50">
                        <i class="fas fa-sync-alt mr-2"></i>
                        <span wire:loading.remove wire:target="startSync">Mulai Sync</span>
                        <span wire:loading wire:target="startSync">Memproses...</span>
                    </button>

                    @if($syncStatus === 'syncing')
                    <button 
                        wire:click="resetSync"
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg flex items-center transition-colors">
                        <i class="fas fa-stop mr-2"></i>Stop
                    </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Sync Buttons -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Sinkronisasi Parsial</h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <button 
                    wire:click="syncSiswaOnly"
                    wire:loading.attr="disabled"
                    wire:target="syncSiswaOnly"
                    class="px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg flex items-center justify-center transition-colors disabled:opacity-50">
                    <i class="fas fa-users mr-2"></i>
                    <span wire:loading.remove wire:target="syncSiswaOnly">Sync Siswa</span>
                    <span wire:loading wire:target="syncSiswaOnly">Loading...</span>
                </button>
                
                <button 
                    wire:click="syncGuruOnly"
                    wire:loading.attr="disabled"
                    wire:target="syncGuruOnly"
                    class="px-4 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg flex items-center justify-center transition-colors disabled:opacity-50">
                    <i class="fas fa-chalkboard-teacher mr-2"></i>
                    <span wire:loading.remove wire:target="syncGuruOnly">Sync Guru</span>
                    <span wire:loading wire:target="syncGuruOnly">Loading...</span>
                </button>
                
                <button 
                    wire:click="syncKelasOnly"
                    wire:loading.attr="disabled"
                    wire:target="syncKelasOnly"
                    class="px-4 py-3 bg-purple-600 hover:bg-purple-700 text-white rounded-lg flex items-center justify-center transition-colors disabled:opacity-50">
                    <i class="fas fa-door-open mr-2"></i>
                    <span wire:loading.remove wire:target="syncKelasOnly">Sync Kelas</span>
                    <span wire:loading wire:target="syncKelasOnly">Loading...</span>
                </button>
            </div>
        </div>

        <!-- Data Statistics -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <!-- Siswa Card -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Data Siswa</h3>
                <p class="text-3xl font-bold text-blue-600 mt-2">{{ $syncData['siswa'] }}</p>
                <p class="text-sm text-gray-500 mt-1">Siswa aktif tersinkronisasi</p>
            </div>
            <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-lg">
                <i class="fas fa-users text-blue-600 dark:text-blue-400 text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Guru Card -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Data Guru</h3>
                <p class="text-3xl font-bold text-green-600 mt-2">{{ $syncData['guru'] }}</p>
                <p class="text-sm text-gray-500 mt-1">Guru aktif tersinkronisasi</p>
            </div>
            <div class="p-3 bg-green-100 dark:bg-green-900 rounded-lg">
                <i class="fas fa-chalkboard-teacher text-green-600 dark:text-green-400 text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Kelas Card -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Data Kelas</h3>
                <p class="text-3xl font-bold text-purple-600 mt-2">{{ $syncData['kelas'] }}</p>
                <p class="text-sm text-gray-500 mt-1">Kelas reguler tersinkronisasi</p>
            </div>
            <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-lg">
                <i class="fas fa-door-open text-purple-600 dark:text-purple-400 text-xl"></i>
            </div>
        </div>
    </div>
</div>



        <!-- Sync Logs & Configuration -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Configuration Card -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Konfigurasi Dapodik</h3>
                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">NPSN: {{ $dapodikConfig['npsn'] }}</span>
                </div>
                
                <form wire:submit.prevent="saveConfig">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                URL API Dapodik
                            </label>
                            <input 
                                type="url" 
                                wire:model="dapodikConfig.base_url"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('dapodikConfig.base_url') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Username/Email
                            </label>
                            <input 
                                type="email" 
                                wire:model="dapodikConfig.username"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Email Dapodik">
                            @error('dapodikConfig.username') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Password
                            </label>
                            <input 
                                type="password" 
                                wire:model="dapodikConfig.password"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Password Dapodik">
                            @error('dapodikConfig.password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                NPSN Sekolah
                            </label>
                            <input 
                                type="text" 
                                wire:model="dapodikConfig.npsn"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="8 digit NPSN"
                                maxlength="8">
                            @error('dapodikConfig.npsn') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex gap-3">
                            <button 
                                type="submit"
                                wire:loading.attr="disabled"
                                class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg flex items-center justify-center transition-colors disabled:opacity-50">
                                <i class="fas fa-save mr-2"></i>
                                <span wire:loading.remove>Simpan Konfigurasi</span>
                                <span wire:loading>Menyimpan...</span>
                            </button>
                            
                            <button 
                                type="button"
                                wire:click="clearLogs"
                                wire:confirm="Apakah Anda yakin ingin menghapus semua riwayat sinkronisasi?"
                                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg flex items-center transition-colors">
                                <i class="fas fa-trash mr-2"></i>Bersihkan Log
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Sync History Card -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Riwayat Sinkronisasi</h3>
                
                <div class="space-y-3 max-h-96 overflow-y-auto">
                    @forelse($syncHistory as $log)
                    <div class="flex items-center justify-between p-3 rounded-lg 
                        @if($log->status === 'success') bg-green-50 dark:bg-green-900/20
                        @elseif($log->status === 'error') bg-red-50 dark:bg-red-900/20
                        @elseif($log->status === 'warning') bg-yellow-50 dark:bg-yellow-900/20
                        @else bg-gray-50 dark:bg-gray-700 @endif">
                        <div class="flex items-center gap-3">
                            @if($log->status === 'success')
                                <i class="fas fa-check-circle text-green-500"></i>
                            @elseif($log->status === 'error')
                                <i class="fas fa-exclamation-triangle text-red-500"></i>
                            @elseif($log->status === 'warning')
                                <i class="fas fa-exclamation-circle text-yellow-500"></i>
                            @else
                                <i class="fas fa-info-circle text-blue-500"></i>
                            @endif
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white capitalize">
                                    {{ $log->sync_type }} - {{ $log->status }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ $log->sync_date->translatedFormat('d M Y H:i') }}
                                </p>
                                @if($log->data_count > 0)
                                <p class="text-xs text-gray-600">{{ $log->data_count }} data</p>
                                @endif
                            </div>
                        </div>
                        <span class="text-sm 
                            @if($log->status === 'success') text-green-600
                            @elseif($log->status === 'error') text-red-600
                            @elseif($log->status === 'warning') text-yellow-600
                            @else text-gray-600 @endif">
                            {{ $log->data_count }} data
                        </span>
                    </div>
                    @empty
                    <div class="text-center py-4 text-gray-500">
                        <i class="fas fa-history text-2xl mb-2"></i>
                        <p>Belum ada riwayat sinkronisasi</p>
                    </div>
                    @endforelse
                </div>

                <button class="w-full mt-4 px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors flex items-center justify-center opacity-50 cursor-not-allowed" disabled>
                    <i class="fas fa-history mr-2"></i>Lihat Semua Riwayat (Coming Soon)
                </button>
            </div>
        </div>
    </div>
</div>