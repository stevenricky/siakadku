<!-- resources/views/livewire/siswa/siswa-dashboard.blade.php -->

<div class="space-y-4 md:space-y-6">
    <!-- Mobile Header -->
    <div class="block md:hidden bg-white dark:bg-gray-800 p-4 rounded-lg shadow mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-lg font-bold text-gray-900 dark:text-white">Dashboard Siswa</h1>
                <p class="text-xs text-gray-600 dark:text-gray-400">{{ $this->subtitle }}</p>
            </div>
            <div class="flex items-center space-x-2">
                <!-- Mobile Notifications - Alpine.js Version -->
                <div class="relative" 
                     x-data="notificationPanel()" 
                     x-init="init()"
                     @notifications-updated.window="loadNotifications()">
                    
                    <!-- Notification Bell -->
                    <button @click="toggleDropdown()" 
                            :class="{'text-blue-600': open, 'text-gray-600 dark:text-gray-300': !open}"
                            class="relative p-2 hover:text-primary-500 dark:hover:text-primary-400 transition-colors rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                        <i class="fas fa-bell text-lg"></i>
                        
                        <!-- Badge -->
                        <template x-if="unreadCount > 0">
                            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center animate-pulse"
                                  x-text="unreadCount > 9 ? '9+' : unreadCount"></span>
                        </template>
                    </button>

                    <!-- Notification Dropdown -->
                    <div x-show="open" 
                         x-cloak
                         @click.away="open = false"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform scale-95"
                         x-transition:enter-end="opacity-100 transform scale-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 transform scale-100"
                         x-transition:leave-end="opacity-0 transform scale-95"
                         class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-50 max-h-96 overflow-hidden">
                        
                        <!-- Header -->
                        <div class="p-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                            <div class="flex justify-between items-center">
                                <h3 class="font-semibold text-gray-900 dark:text-white">Notifikasi</h3>
                                <template x-if="unreadCount > 0">
                                    <button @click="markAllAsRead()" 
                                            class="text-xs text-primary-600 dark:text-primary-400 hover:text-primary-700 transition-colors">
                                        Tandai semua dibaca
                                    </button>
                                </template>
                            </div>
                        </div>

                        <!-- Notifications List -->
                        <div class="overflow-y-auto max-h-64">
                            <template x-if="notifications.length === 0">
                                <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-bell-slash text-2xl mb-2"></i>
                                    <p class="text-sm">Tidak ada notifikasi</p>
                                </div>
                            </template>

                            <template x-for="notification in notifications" :key="notification.id">
                                <div class="p-4 border-b border-gray-100 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                                     :class="{'bg-blue-50 dark:bg-blue-900/20': !notification.is_read}">
                                    <div class="flex items-start space-x-3">
                                        <!-- Icon -->
                                        <div class="flex-shrink-0 mt-1">
                                            <template x-if="notification.type === 'agenda'">
                                                <i class="fas fa-calendar text-blue-500"></i>
                                            </template>
                                            <template x-if="notification.type === 'pengumuman'">
                                                <i class="fas fa-bullhorn text-green-500"></i>
                                            </template>
                                            <template x-if="notification.type === 'nilai'">
                                                <i class="fas fa-chart-bar text-yellow-500"></i>
                                            </template>
                                            <template x-if="notification.type === 'tugas'">
                                                <i class="fas fa-tasks text-purple-500"></i>
                                            </template>
                                            <template x-if="notification.type === 'ekskul'">
                                                <i class="fas fa-futbol text-red-500"></i>
                                            </template>
                                            <template x-if="!['agenda','pengumuman','nilai','tugas','ekskul'].includes(notification.type)">
                                                <i class="fas fa-bell text-gray-500"></i>
                                            </template>
                                        </div>
                                        
                                        <!-- Content -->
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white" 
                                               x-text="notification.title"></p>
                                            <p class="text-sm text-gray-600 dark:text-gray-300 mt-1" 
                                               x-text="notification.message"></p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2" 
                                               x-text="notification.created_at"></p>
                                        </div>
                                        
                                        <!-- Mark as Read Button -->
                                        <template x-if="!notification.is_read">
                                            <button @click="markAsRead(notification.id)" 
                                                    class="flex-shrink-0 text-xs text-primary-600 hover:text-primary-700 transition-colors"
                                                    title="Tandai sebagai dibaca">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Footer -->
                        <div class="p-3 border-t border-gray-200 dark:border-gray-700 text-center bg-gray-50 dark:bg-gray-900">
                            <button @click="loadNotifications()" 
                                    class="text-sm text-primary-600 dark:text-primary-400 hover:text-primary-700 transition-colors">
                                <i class="fas fa-sync-alt mr-1"></i> Refresh
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Mobile Chat Button -->
                <button wire:click="bukaChat" 
                        class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-lg flex items-center space-x-1 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    @if($unreadCount > 0)
                        <span class="bg-red-500 text-white text-xs rounded-full h-4 w-4 flex items-center justify-center text-[10px]">
                            {{ $unreadCount }}
                        </span>
                    @endif
                </button>
            </div>
        </div>
    </div>

    <!-- Desktop Header -->
    <div class="hidden md:flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard Siswa</h1>
            <p class="text-gray-600 dark:text-gray-400">
                Selamat belajar, {{ $siswa->nama_lengkap ?? ($siswa->nama ?? 'Siswa') }} - {{ $this->subtitle }}
            </p>
        </div>
        <div class="flex items-center space-x-4">
            <!-- Refresh Button -->
            <button wire:click="refreshDashboard" 
                    class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                    title="Refresh Data">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
            </button>

            <!-- Notification Panel - Alpine.js Version -->
            <div class="relative" 
                 x-data="notificationPanel()" 
                 x-init="init()"
                 @notifications-updated.window="loadNotifications()">
                
                <!-- Notification Bell -->
                <button @click="toggleDropdown()" 
                        :class="{'text-blue-600': open, 'text-gray-600 dark:text-gray-300': !open}"
                        class="relative p-2 hover:text-primary-500 dark:hover:text-primary-400 transition-colors rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                    <i class="fas fa-bell text-lg"></i>
                    
                    <!-- Badge -->
                    <template x-if="unreadCount > 0">
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center animate-pulse"
                              x-text="unreadCount > 9 ? '9+' : unreadCount"></span>
                    </template>
                </button>

                <!-- Notification Dropdown -->
                <div x-show="open" 
                     x-cloak
                     @click.away="open = false"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 transform scale-100"
                     x-transition:leave-end="opacity-0 transform scale-95"
                     class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-50 max-h-96 overflow-hidden">
                    
                    <!-- Header -->
                    <div class="p-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                        <div class="flex justify-between items-center">
                            <h3 class="font-semibold text-gray-900 dark:text-white">Notifikasi</h3>
                            <template x-if="unreadCount > 0">
                                <button @click="markAllAsRead()" 
                                        class="text-xs text-primary-600 dark:text-primary-400 hover:text-primary-700 transition-colors">
                                    Tandai semua dibaca
                                </button>
                            </template>
                        </div>
                    </div>

                    <!-- Notifications List -->
                    <div class="overflow-y-auto max-h-64">
                        <template x-if="notifications.length === 0">
                            <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                                <i class="fas fa-bell-slash text-2xl mb-2"></i>
                                <p class="text-sm">Tidak ada notifikasi</p>
                            </div>
                        </template>

                        <template x-for="notification in notifications" :key="notification.id">
                            <div class="p-4 border-b border-gray-100 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                                 :class="{'bg-blue-50 dark:bg-blue-900/20': !notification.is_read}">
                                <div class="flex items-start space-x-3">
                                    <!-- Icon -->
                                    <div class="flex-shrink-0 mt-1">
                                        <template x-if="notification.type === 'agenda'">
                                            <i class="fas fa-calendar text-blue-500"></i>
                                        </template>
                                        <template x-if="notification.type === 'pengumuman'">
                                            <i class="fas fa-bullhorn text-green-500"></i>
                                        </template>
                                        <template x-if="notification.type === 'nilai'">
                                            <i class="fas fa-chart-bar text-yellow-500"></i>
                                        </template>
                                        <template x-if="notification.type === 'tugas'">
                                            <i class="fas fa-tasks text-purple-500"></i>
                                        </template>
                                        <template x-if="notification.type === 'ekskul'">
                                            <i class="fas fa-futbol text-red-500"></i>
                                        </template>
                                        <template x-if="!['agenda','pengumuman','nilai','tugas','ekskul'].includes(notification.type)">
                                            <i class="fas fa-bell text-gray-500"></i>
                                        </template>
                                    </div>
                                    
                                    <!-- Content -->
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white" 
                                           x-text="notification.title"></p>
                                        <p class="text-sm text-gray-600 dark:text-gray-300 mt-1" 
                                           x-text="notification.message"></p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2" 
                                           x-text="notification.created_at"></p>
                                    </div>
                                    
                                    <!-- Mark as Read Button -->
                                    <template x-if="!notification.is_read">
                                        <button @click="markAsRead(notification.id)" 
                                                class="flex-shrink-0 text-xs text-primary-600 hover:text-primary-700 transition-colors"
                                                title="Tandai sebagai dibaca">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Footer -->
                    <div class="p-3 border-t border-gray-200 dark:border-gray-700 text-center bg-gray-50 dark:bg-gray-900">
                        <button @click="loadNotifications()" 
                                class="text-sm text-primary-600 dark:text-primary-400 hover:text-primary-700 transition-colors">
                            <i class="fas fa-sync-alt mr-1"></i> Refresh
                        </button>
                    </div>
                </div>
            </div>

            <!-- Chat Button -->
            <button wire:click="bukaChat" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
                <span class="hidden sm:inline">Chat</span>
                @if($unreadCount > 0)
                    <span class="bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                        {{ $unreadCount }}
                    </span>
                @endif
            </button>
        </div>
    </div>

    <!-- Flash Message -->
    @if (session()->has('message'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg dark:bg-green-900 dark:border-green-800 dark:text-green-300 text-sm">
            {{ session('message') }}
        </div>
    @endif

    <!-- Stats Grid Mobile -->
    <div class="grid grid-cols-2 gap-3 md:hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <p class="text-xs font-medium text-gray-600 dark:text-gray-400">Nilai</p>
            <p class="text-xl font-semibold text-gray-900 dark:text-white">{{ number_format($rataRataNilai, 1) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <p class="text-xs font-medium text-gray-600 dark:text-gray-400">Kehadiran</p>
            <p class="text-xl font-semibold text-gray-900 dark:text-white">{{ $statistikKehadiran['hadir'] ?? 0 }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <p class="text-xs font-medium text-gray-600 dark:text-gray-400">Jadwal</p>
            <p class="text-xl font-semibold text-gray-900 dark:text-white">{{ $jadwalHariIni->count() }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <p class="text-xs font-medium text-gray-600 dark:text-gray-400">Pesan</p>
            <p class="text-xl font-semibold text-gray-900 dark:text-white">{{ $unreadCount }}</p>
        </div>
    </div>

    <!-- Stats Grid Desktop -->
    <div class="hidden md:grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
        <!-- Rata-rata Nilai -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 md:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Rata-rata Nilai</p>
                    <p class="text-xl md:text-2xl font-semibold text-gray-900 dark:text-white">
                        {{ number_format($rataRataNilai, 1) }}
                    </p>
                </div>
                <div class="p-2 md:p-3 rounded-full bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400">
                    <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Kehadiran -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 md:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Kehadiran</p>
                    <p class="text-xl md:text-2xl font-semibold text-gray-900 dark:text-white">
                        {{ $statistikKehadiran['hadir'] ?? 0 }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        dari {{ $statistikKehadiran['total'] ?? 0 }} hari
                    </p>
                </div>
                <div class="p-2 md:p-3 rounded-full bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400">
                    <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Jadwal Hari Ini -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 md:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Jadwal Hari Ini</p>
                    <p class="text-xl md:text-2xl font-semibold text-gray-900 dark:text-white">
                        {{ $jadwalHariIni->count() }}
                    </p>
                </div>
                <div class="p-2 md:p-3 rounded-full bg-purple-100 dark:bg-purple-900 text-purple-600 dark:text-purple-400">
                    <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Pesan & Online -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 md:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Pesan Baru</p>
                    <p class="text-xl md:text-2xl font-semibold text-gray-900 dark:text-white">
                        {{ $unreadCount }}
                    </p>
                </div>
                <div class="text-right">
                    <div class="flex items-center text-xs md:text-sm text-green-600 dark:text-green-400">
                        <div class="w-2 h-2 bg-green-500 rounded-full mr-1"></div>
                        {{ $onlineUsers }} Online
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Grid Mobile -->
    <div class="block md:hidden space-y-4">
        <!-- Jadwal Hari Ini Mobile -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                <h3 class="font-semibold text-gray-900 dark:text-white">Jadwal Hari Ini</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400">{{ now()->translatedFormat('l, d F Y') }}</p>
            </div>
            <div class="p-4">
                @forelse($jadwalHariIni as $jadwal)
                <div class="flex items-center justify-between p-3 border border-gray-200 dark:border-gray-700 rounded-lg mb-2">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                            <span class="text-blue-600 dark:text-blue-400 text-xs font-medium">
                                {{ substr(($jadwal->mapel->nama_mapel ?? $jadwal->mapel_id ?? 'M'), 0, 1) }}
                            </span>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ \Illuminate\Support\Str::limit($jadwal->mapel->nama_mapel ?? ($jadwal->mapel_id ?? 'Mata Pelajaran'), 20) }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ \Illuminate\Support\Str::limit($jadwal->guru->nama_lengkap ?? ($jadwal->guru_id ?? 'Guru'), 15) }}
                            </p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $jadwal->jam_mulai ?? '00:00' }}
                        </p>
                    </div>
                </div>
                @empty
                <div class="text-center py-4">
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Tidak ada jadwal untuk hari ini</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Statistik Kehadiran Mobile -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                <h3 class="font-semibold text-gray-900 dark:text-white">Statistik Kehadiran</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400">Bulan {{ now()->translatedFormat('F Y') }}</p>
            </div>
            <div class="p-4">
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Hadir</span>
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $statistikKehadiran['hadir'] ?? 0 }}
                            </span>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                @php
                                    $total = $statistikKehadiran['total'] ?? 0;
                                    $hadir = $statistikKehadiran['hadir'] ?? 0;
                                    $persentase = $total > 0 ? round(($hadir / $total) * 100, 1) : 0;
                                @endphp
                                {{ $persentase }}%
                            </span>
                        </div>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Sakit</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $statistikKehadiran['sakit'] ?? 0 }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Izin</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $statistikKehadiran['izin'] ?? 0 }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Alpha</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $statistikKehadiran['alpha'] ?? 0 }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pesan Terbaru Mobile -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                <h3 class="font-semibold text-gray-900 dark:text-white">Pesan Terbaru</h3>
            </div>
            <div class="p-4">
                @if($pesanTerbaru && $pesanTerbaru->count() > 0)
                    <div class="space-y-3">
                        @foreach($pesanTerbaru as $pesan)
                            <div class="flex items-start space-x-2 p-2 border border-gray-200 dark:border-gray-700 rounded-lg"
                                 wire:click="bukaChat({{ $pesan->pengirim_id }})">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                                        <span class="text-blue-600 dark:text-blue-400 text-xs font-medium">
                                            {{ substr($pesan->pengirim->name, 0, 1) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                        {{ $pesan->pengirim->name }}
                                    </p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400 truncate">
                                        {{ \Illuminate\Support\Str::limit($pesan->pesan, 30) }}
                                    </p>
                                </div>
                                @if(!$pesan->dibaca)
                                    <div class="flex-shrink-0">
                                        <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <svg class="w-8 h-8 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Belum ada pesan</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Content Grid Desktop -->
    <div class="hidden md:grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6">
        <!-- Jadwal Hari Ini -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Jadwal Hari Ini</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Jadwal pelajaran untuk hari ini</p>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($jadwalHariIni as $jadwal)
                    <div class="flex items-center justify-between p-3 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                                <span class="text-sm font-medium text-blue-600 dark:text-blue-400">
                                    {{ substr(($jadwal->mapel->nama_mapel ?? $jadwal->mapel_id ?? 'M'), 0, 1) }}
                                </span>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $jadwal->mapel->nama_mapel ?? ($jadwal->mapel_id ?? 'Mata Pelajaran') }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $jadwal->guru->nama_lengkap ?? ($jadwal->guru_id ?? 'Guru') }} • {{ $jadwal->ruangan ?? 'Ruangan' }}
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $jadwal->jam_mulai ?? '00:00' }} - {{ $jadwal->jam_selesai ?? '00:00' }}
                            </p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4">
                        <p class="text-gray-500 dark:text-gray-400">Tidak ada jadwal untuk hari ini</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="space-y-4 md:space-y-6">
            <!-- Statistik Kehadiran -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Statistik Kehadiran</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Bulan {{ now()->translatedFormat('F Y') }}</p>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Hadir</span>
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $statistikKehadiran['hadir'] ?? 0 }}
                                </span>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                    @php
                                        $total = $statistikKehadiran['total'] ?? 0;
                                        $hadir = $statistikKehadiran['hadir'] ?? 0;
                                        $persentase = $total > 0 ? round(($hadir / $total) * 100, 1) : 0;
                                    @endphp
                                    {{ $persentase }}%
                                </span>
                            </div>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Sakit</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $statistikKehadiran['sakit'] ?? 0 }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Izin</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $statistikKehadiran['izin'] ?? 0 }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Alpha</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $statistikKehadiran['alpha'] ?? 0 }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pesan Terbaru -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Pesan Terbaru</h3>
                    <span class="text-sm text-blue-600 dark:text-blue-400 cursor-pointer" wire:click="bukaChat">Lihat Semua</span>
                </div>
                <div class="p-6">
                    @if($pesanTerbaru && $pesanTerbaru->count() > 0)
                        <div class="space-y-4">
                            @foreach($pesanTerbaru as $pesan)
                                <div class="flex items-start space-x-3 p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors cursor-pointer"
                                     wire:click="bukaChat({{ $pesan->pengirim_id }})">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                                            <span class="text-blue-600 dark:text-blue-400 font-medium text-sm">
                                                {{ substr($pesan->pengirim->name, 0, 1) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-medium text-gray-900 dark:text-white truncate">
                                            {{ $pesan->pengirim->name }}
                                        </p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 truncate">
                                            {{ $pesan->pesan }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                                            {{ $pesan->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                    @if(!$pesan->dibaca)
                                        <div class="flex-shrink-0">
                                            <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            <p class="mt-2 text-gray-500 dark:text-gray-400">Belum ada pesan</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Chat Modal Mobile Responsive -->
    @if($showChat)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-2 md:p-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full h-full md:max-w-4xl md:h-[600px] flex flex-col md:flex-row">
                <!-- Mobile Chat Header -->
                <div class="md:hidden p-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                    <button wire:click="tutupChat" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>
                    <h3 class="font-semibold text-gray-900 dark:text-white">Chat</h3>
                    <div class="w-5"></div> <!-- Spacer untuk balance -->
                </div>

                <!-- Sidebar Kontak - Hidden on mobile when in chat -->
                <div class="hidden md:block w-full md:w-1/3 border-r border-gray-200 dark:border-gray-700 flex flex-col">
                    <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="font-semibold text-gray-900 dark:text-white">Percakapan</h3>
                    </div>
                    <div class="flex-1 overflow-y-auto">
                        <!-- Daftar Guru -->
                        <div class="p-3">
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">Guru Anda</p>
                            @foreach($guruList as $guru)
                                <div class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer transition-colors"
                                     wire:click="bukaChat({{ $guru['id'] }})">
                                    <div class="relative">
                                        <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                                            <span class="text-blue-600 dark:text-blue-400 text-xs font-medium">
                                                {{ substr($guru['nama'], 0, 1) }}
                                            </span>
                                        </div>
                                        @if($guru['online'])
                                            <div class="absolute -bottom-1 -right-1 w-2 h-2 bg-green-500 border-2 border-white dark:border-gray-800 rounded-full"></div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                            {{ $guru['nama'] }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                            {{ $guru['mapel'] }}
                                        </p>
                                    </div>
                                    @php
                                        $unreadFromGuru = \App\Models\Pesan::unread()
                                            ->where('pengirim_id', $guru['id'])
                                            ->where('penerima_id', auth()->id())
                                            ->count();
                                    @endphp
                                    @if($unreadFromGuru > 0)
                                        <span class="bg-red-500 text-white text-xs rounded-full h-4 w-4 flex items-center justify-center text-[10px]">
                                            {{ $unreadFromGuru }}
                                        </span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Area Chat -->
                <div class="flex-1 flex flex-col">
                    @if($chatWith)
                        <!-- Header Chat -->
                        <div class="p-3 md:p-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                            <div class="flex items-center space-x-2 md:space-x-3">
                                <button class="md:hidden text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
                                        @click="showContacts = !showContacts">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                    </svg>
                                </button>
                                <div class="w-8 h-8 md:w-10 md:h-10 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                                    <span class="text-blue-600 dark:text-blue-400 text-sm md:text-base font-medium">
                                        {{ substr($chatWith->name, 0, 1) }}
                                    </span>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-white text-sm md:text-base">{{ $chatWith->name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        @if($chatWith->isOnline())
                                            <span class="text-green-500">Online</span>
                                        @else
                                            <span class="text-gray-500">Offline</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <button wire:click="tutupChat" class="hidden md:block text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <!-- Pesan -->
                        <div class="flex-1 overflow-y-auto p-3 md:p-4 space-y-3 md:space-y-4" id="chat-messages">
                            @foreach($percakapan as $pesan)
                                <div class="flex {{ $pesan->pengirim_id == auth()->id() ? 'justify-end' : 'justify-start' }}">
                                    <div class="max-w-[85%] md:max-w-xs lg:max-w-md px-3 py-2 md:px-4 md:py-2 rounded-lg {{ $pesan->pengirim_id == auth()->id() ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white' }}">
                                        <p class="text-sm break-words">{{ $pesan->pesan }}</p>
                                        <div class="flex justify-between items-center mt-1">
                                            <p class="text-xs {{ $pesan->pengirim_id == auth()->id() ? 'text-blue-200' : 'text-gray-500' }}">
                                                {{ $pesan->created_at->format('H:i') }}
                                            </p>
                                            @if($pesan->pengirim_id == auth()->id())
                                                @if($pesan->dibaca)
                                                    <span class="text-xs text-blue-200" title="Dibaca {{ $pesan->dibaca_pada?->format('H:i') }}">
                                                        ✓✓
                                                    </span>
                                                @else
                                                    <span class="text-xs text-blue-200">✓</span>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Input Pesan - FIXED untuk mobile -->
                        <div class="p-3 md:p-4 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
                            <form wire:submit.prevent="kirimPesan" class="flex space-x-2">
                                <div class="flex-1 relative">
                                    <textarea 
                                        wire:model="newMessage"
                                        placeholder="Ketik pesan..."
                                        rows="1"
                                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white resize-none min-h-[40px] max-h-[120px] text-sm"
                                        x-data="{
                                            resize() {
                                                $el.style.height = '40px';
                                                $el.style.height = $el.scrollHeight + 'px';
                                                if ($el.scrollHeight > 120) {
                                                    $el.style.overflowY = 'auto';
                                                } else {
                                                    $el.style.overflowY = 'hidden';
                                                }
                                            }
                                        }"
                                        x-init="resize()"
                                        @input="resize()"
                                        @keydown.enter.prevent="if(!$event.shiftKey) { $wire.kirimPesan(); $nextTick(() => { $el.style.height = '40px'; }); }"></textarea>
                                </div>
                                <button type="submit" 
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg transition-colors flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    @else
                        <!-- Mobile Contact List -->
                        <div class="md:hidden flex-1 overflow-y-auto">
                            <div class="p-4">
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">Guru Anda</p>
                                @foreach($guruList as $guru)
                                    <div class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer transition-colors"
                                         wire:click="bukaChat({{ $guru['id'] }})">
                                        <div class="relative">
                                            <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                                                <span class="text-blue-600 dark:text-blue-400 text-xs font-medium">
                                                    {{ substr($guru['nama'], 0, 1) }}
                                                </span>
                                            </div>
                                            @if($guru['online'])
                                                <div class="absolute -bottom-1 -right-1 w-2 h-2 bg-green-500 border-2 border-white dark:border-gray-800 rounded-full"></div>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                                {{ $guru['nama'] }}
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                                {{ $guru['mapel'] }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Script untuk auto-scroll dan real-time -->
<script>
    document.addEventListener('livewire:init', () => {
        function scrollToBottom() {
            const chatMessages = document.getElementById('chat-messages');
            if (chatMessages) {
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }
        }

        // Refresh data setiap 30 detik
        setInterval(() => {
            Livewire.dispatch('dashboardRefresh');
        }, 30000);

        Livewire.on('pesanTerkirim', () => {
            scrollToBottom();
            // Reset textarea height
            const textarea = document.querySelector('textarea[wire\\:model="newMessage"]');
            if (textarea) {
                textarea.style.height = '40px';
            }
        });

        scrollToBottom();

        // Handle mobile back button
        window.addEventListener('popstate', function(event) {
            if (document.querySelector('[wire\\:model="showChat"]') && @this.showChat) {
                @this.tutupChat();
                event.preventDefault();
            }
        });
    });

    // Prevent form submission on enter in textarea
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && e.target.tagName === 'TEXTAREA' && !e.shiftKey) {
            e.preventDefault();
        }
    });

    // Alpine.js Notification Panel Component
    function notificationPanel() {
        return {
            open: false,
            notifications: [],
            unreadCount: 0,
            showToast: false,
            toastMessage: '',
            isLoading: false,

            async init() {
                await this.loadNotifications();
                this.startPolling();
            },

            async loadNotifications() {
                if (this.isLoading) return;
                
                this.isLoading = true;
                try {
                    const response = await fetch('/api/notifications');
                    const data = await response.json();
                    
                    this.notifications = data.notifications || [];
                    this.unreadCount = data.unread_count || 0;
                } catch (error) {
                    console.error('Error loading notifications:', error);
                    this.showToastMessage('Error memuat notifikasi', 'error');
                } finally {
                    this.isLoading = false;
                }
            },

            async markAsRead(notificationId) {
                try {
                    const response = await fetch(`/api/notifications/${notificationId}/read`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });

                    if (response.ok) {
                        await this.loadNotifications();
                        this.showToastMessage('Notifikasi ditandai sebagai dibaca');
                    }
                } catch (error) {
                    console.error('Error marking notification as read:', error);
                    this.showToastMessage('Gagal menandai notifikasi', 'error');
                }
            },

            async markAllAsRead() {
                try {
                    const response = await fetch('/api/notifications/mark-all-read', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });

                    if (response.ok) {
                        await this.loadNotifications();
                        this.showToastMessage('Semua notifikasi telah dibaca');
                    }
                } catch (error) {
                    console.error('Error marking all notifications as read:', error);
                    this.showToastMessage('Gagal menandai notifikasi', 'error');
                }
            },

            toggleDropdown() {
                this.open = !this.open;
                if (this.open) {
                    this.loadNotifications();
                }
            },

            showToastMessage(message, type = 'success') {
                this.toastMessage = message;
                this.showToast = true;
                setTimeout(() => {
                    this.showToast = false;
                }, 3000);
            },

            startPolling() {
                // Poll every 30 seconds
                setInterval(() => {
                    if (!this.open) { // Only poll when dropdown is closed
                        this.loadNotifications();
                    }
                }, 30000);
            }
        }
    }
</script>