<div class="space-y-4 md:space-y-6" x-data="{ showNotifications: false }">
    <!-- Mobile Header -->
    <div class="block md:hidden bg-white dark:bg-gray-800 p-4 rounded-lg shadow mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-lg font-bold text-gray-900 dark:text-white">Dashboard Guru</h1>
                <p class="text-xs text-gray-600 dark:text-gray-400">{{ $this->subtitle }}</p>
            </div>
            <div class="flex items-center space-x-2">
                <!-- Mobile Notifications dengan Alpine.js -->
                <div class="relative" x-data="notificationPanel()">
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
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard Guru</h1>
            <p class="text-gray-600 dark:text-gray-400">
                Selamat datang, {{ auth()->user()->name ?? 'Guru' }} - {{ $this->subtitle }}
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

            <!-- Desktop Notifications dengan Alpine.js -->
            <div class="relative" x-data="notificationPanel()">
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
            <p class="text-xs font-medium text-gray-600 dark:text-gray-400">Siswa</p>
            <p class="text-xl font-semibold text-gray-900 dark:text-white">{{ $totalSiswa ?? 0 }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <p class="text-xs font-medium text-gray-600 dark:text-gray-400">Kelas</p>
            <p class="text-xl font-semibold text-gray-900 dark:text-white">{{ $totalKelasDiampu ?? 0 }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <p class="text-xs font-medium text-gray-600 dark:text-gray-400">Nilai</p>
            <p class="text-xl font-semibold text-gray-900 dark:text-white">{{ $rataRataNilai ?? 0 }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <p class="text-xs font-medium text-gray-600 dark:text-gray-400">Pesan</p>
            <p class="text-xl font-semibold text-gray-900 dark:text-white">{{ $unreadCount }}</p>
        </div>
    </div>

    <!-- Stats Grid Desktop -->
    <div class="hidden md:grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
        <!-- Total Siswa -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 md:p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="p-2 md:p-3 rounded-full bg-blue-100 dark:bg-blue-900">
                        <svg class="w-5 h-5 md:w-6 md:h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3 md:ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Siswa</p>
                        <p class="text-xl md:text-2xl font-semibold text-gray-900 dark:text-white">{{ $totalSiswa ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Kelas Diampu -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 md:p-6">
            <div class="flex items-center">
                <div class="p-2 md:p-3 rounded-full bg-green-100 dark:bg-green-900">
                    <svg class="w-5 h-5 md:w-6 md:h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <div class="ml-3 md:ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Kelas Diampu</p>
                    <p class="text-xl md:text-2xl font-semibold text-gray-900 dark:text-white">{{ $totalKelasDiampu ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Rata-rata Nilai -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 md:p-6">
            <div class="flex items-center">
                <div class="p-2 md:p-3 rounded-full bg-purple-100 dark:bg-purple-900">
                    <svg class="w-5 h-5 md:w-6 md:h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div class="ml-3 md:ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Rata-rata Nilai</p>
                    <p class="text-xl md:text-2xl font-semibold text-gray-900 dark:text-white">{{ $rataRataNilai ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Online & Pesan -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 md:p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="p-2 md:p-3 rounded-full bg-orange-100 dark:bg-orange-900">
                        <svg class="w-5 h-5 md:w-6 md:h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>
                    <div class="ml-3 md:ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Pesan Baru</p>
                        <p class="text-xl md:text-2xl font-semibold text-gray-900 dark:text-white">{{ $unreadCount }}</p>
                    </div>
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
                @if($jadwalHariIni && $jadwalHariIni->count() > 0)
                    <div class="space-y-3">
                        @foreach($jadwalHariIni as $jadwal)
                            <div class="p-3 border border-gray-200 dark:border-gray-700 rounded-lg">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <p class="font-medium text-sm text-gray-900 dark:text-white">
                                            {{ $jadwal->mapel->nama_mapel ?? 'Mata Pelajaran' }}
                                        </p>
                                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                            Kelas {{ $jadwal->kelas->nama_kelas ?? 'Kelas' }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ $jadwal->jam_mulai ?? '00:00' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <svg class="w-8 h-8 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Tidak ada jadwal</p>
                    </div>
                @endif
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
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Jadwal Mengajar Hari Ini</h2>
                <span class="text-sm text-gray-500 dark:text-gray-400">{{ now()->translatedFormat('l, d F Y') }}</span>
            </div>
            <div class="p-6">
                @if($jadwalHariIni && $jadwalHariIni->count() > 0)
                    <div class="space-y-4">
                        @foreach($jadwalHariIni as $jadwal)
                            <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">
                                        {{ $jadwal->mapel->nama_mapel ?? 'Mata Pelajaran' }}
                                    </p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        Kelas {{ $jadwal->kelas->nama_kelas ?? 'Kelas' }} • {{ $jadwal->ruangan ?? 'Ruangan' }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                        {{ $jadwal->jam_mulai ?? '00:00' }} - {{ $jadwal->jam_selesai ?? '00:00' }}
                                    </p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $jadwal->hari }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="mt-2 text-gray-500 dark:text-gray-400">Tidak ada jadwal mengajar hari ini</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="space-y-4 md:space-y-6">
            <!-- Nilai Terbaru -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Nilai Terbaru</h2>
                </div>
                <div class="p-6">
                    @if($recentNilai && $recentNilai->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentNilai as $nilai)
                                <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">
                                            {{ $nilai->siswa->nama ?? 'Siswa' }}
                                        </p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            {{ $nilai->mapel->nama_mapel ?? 'Mata Pelajaran' }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                            {{ $nilai->nilai ?? 0 }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $nilai->created_at->format('d M Y') ?? '' }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="mt-2 text-gray-500 dark:text-gray-400">Belum ada nilai yang diinput</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Pesan Terbaru -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Pesan Terbaru</h2>
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
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full h-full md:max-w-4xl md:h-[80vh] flex flex-col">
                <!-- Header Chat -->
                <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between bg-white dark:bg-gray-800 sticky top-0 z-10">
                    <div class="flex items-center space-x-3">
                        <button wire:click="tutupChat" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 md:hidden">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </button>
                        @if($chatWith)
                            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                                <span class="text-blue-600 dark:text-blue-400 font-medium text-sm">
                                    {{ substr($chatWith->name, 0, 1) }}
                                </span>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 dark:text-white">{{ $chatWith->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    @if($chatWith->isOnline())
                                        <span class="text-green-500">Online</span>
                                    @else
                                        <span class="text-gray-500">Offline</span>
                                    @endif
                                </p>
                            </div>
                        @else
                            <div>
                                <p class="font-semibold text-gray-900 dark:text-white">Pilih Kontak</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Pilih siswa untuk memulai percakapan</p>
                            </div>
                        @endif
                    </div>
                    <button wire:click="tutupChat" class="hidden md:block text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="flex flex-1 overflow-hidden">
                    <!-- Sidebar Kontak - Always visible on desktop, conditional on mobile -->
                    <div class="w-full md:w-1/3 border-r border-gray-200 dark:border-gray-700 flex flex-col {{ $chatWith ? 'hidden md:flex' : 'flex' }}">
                        <div class="p-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                            <h3 class="font-semibold text-gray-900 dark:text-white">Percakapan</h3>
                        </div>
                        <div class="flex-1 overflow-y-auto">
                            <!-- Daftar Siswa -->
                            <div class="p-3">
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">Siswa Anda</p>
                                @if(count($siswaList) > 0)
                                    @foreach($siswaList as $siswa)
                                        <div class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer transition-colors mb-2"
                                             wire:click="bukaChat({{ $siswa['id'] }})">
                                            <div class="relative">
                                                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                                                    <span class="text-blue-600 dark:text-blue-400 text-sm font-medium">
                                                        {{ substr($siswa['nama'], 0, 1) }}
                                                    </span>
                                                </div>
                                                @if($siswa['online'])
                                                    <div class="absolute -bottom-1 -right-1 w-3 h-3 bg-green-500 border-2 border-white dark:border-gray-800 rounded-full"></div>
                                                @endif
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                                    {{ $siswa['nama'] }}
                                                </p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                                    {{ $siswa['kelas'] }}
                                                </p>
                                            </div>
                                            @php
                                                $unreadFromStudent = \App\Models\Pesan::unread()
                                                    ->where('pengirim_id', $siswa['id'])
                                                    ->where('penerima_id', auth()->id())
                                                    ->count();
                                            @endphp
                                            @if($unreadFromStudent > 0)
                                                <span class="bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                                    {{ $unreadFromStudent }}
                                                </span>
                                            @endif
                                        </div>
                                    @endforeach
                                @else
                                    <div class="text-center py-8">
                                        <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                        </svg>
                                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Belum ada siswa</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Area Chat -->
                    <div class="flex-1 flex flex-col {{ $chatWith ? 'flex' : 'hidden md:flex' }}">
                        @if($chatWith)
                            <!-- Pesan -->
                            <div class="flex-1 overflow-y-auto p-4 space-y-4" id="chat-messages">
                                @if($percakapan && $percakapan->count() > 0)
                                    @foreach($percakapan as $pesan)
                                        <div class="flex {{ $pesan->pengirim_id == auth()->id() ? 'justify-end' : 'justify-start' }}">
                                            <div class="max-w-[70%] md:max-w-xs lg:max-w-md px-4 py-3 rounded-lg {{ $pesan->pengirim_id == auth()->id() ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white' }}">
                                                <p class="text-sm break-words">{{ $pesan->pesan }}</p>
                                                <div class="flex justify-between items-center mt-2">
                                                    <p class="text-xs {{ $pesan->pengirim_id == auth()->id() ? 'text-blue-200' : 'text-gray-500' }}">
                                                        {{ $pesan->created_at->format('H:i') }}
                                                    </p>
                                                    @if($pesan->pengirim_id == auth()->id())
                                                        @if($pesan->dibaca)
                                                            <span class="text-xs text-blue-200 ml-2" title="Dibaca {{ $pesan->dibaca_pada?->format('H:i') }}">
                                                                ✓✓
                                                            </span>
                                                        @else
                                                            <span class="text-xs text-blue-200 ml-2">✓</span>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="text-center py-8">
                                        <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                        </svg>
                                        <p class="text-gray-500 dark:text-gray-400">Belum ada pesan</p>
                                        <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Mulai percakapan dengan {{ $chatWith->name }}</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Input Pesan -->
                            <div class="p-4 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 sticky bottom-0">
                                <form wire:submit.prevent="kirimPesan" class="flex space-x-3">
                                    <div class="flex-1 relative">
                                        <textarea 
                                            wire:model="newMessage"
                                            placeholder="Ketik pesan..."
                                            rows="1"
                                            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-3 pr-12 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white resize-none min-h-[50px] max-h-[120px] text-sm"
                                            x-data="{
                                                resize() {
                                                    $el.style.height = '50px';
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
                                            @keydown.enter.prevent="if(!$event.shiftKey) { $wire.kirimPesan(); $nextTick(() => { $el.style.height = '50px'; }); }"></textarea>
                                    </div>
                                    <button type="submit" 
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg transition-colors flex items-center justify-center flex-shrink-0 disabled:opacity-50 disabled:cursor-not-allowed"
                                            :disabled="!$wire.newMessage.trim()">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        @else
                            <!-- Placeholder ketika belum memilih chat -->
                            <div class="flex-1 flex items-center justify-center">
                                <div class="text-center">
                                    <svg class="w-20 h-20 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                    </svg>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Pilih Percakapan</h3>
                                    <p class="text-gray-500 dark:text-gray-400 max-w-sm">
                                        Pilih salah satu siswa dari daftar untuk memulai percakapan atau melihat riwayat pesan.
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Script untuk auto-scroll dan real-time -->
    <script>
        document.addEventListener('livewire:init', () => {
            function scrollToBottom() {
                const chatMessages = document.getElementById('chat-messages');
                if (chatMessages) {
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                }
            }

            // Auto scroll ketika chat dibuka atau pesan baru dikirim
            Livewire.on('pesanTerkirim', () => {
                setTimeout(scrollToBottom, 100);
                // Reset textarea height
                const textarea = document.querySelector('textarea[wire\\:model="newMessage"]');
                if (textarea) {
                    textarea.style.height = '50px';
                }
            });

            // Scroll ke bawah ketika percakapan dimuat
            Livewire.hook('component.updated', (component) => {
                if (component.name === 'guru.guru-dashboard' && component.get('showChat')) {
                    setTimeout(scrollToBottom, 200);
                }
            });

            // Handle mobile back button
            window.addEventListener('popstate', function(event) {
                if (@this.showChat) {
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

        // Notification Panel Function
        function notificationPanel() {
            return {
                open: false,
                notifications: [],
                unreadCount: 0,
                isLoading: false,

                init() {
                    this.loadNotifications();
                },

                async loadNotifications() {
                    if (this.isLoading) return;
                    
                    this.isLoading = true;
                    try {
                        // Simulasi data notifikasi
                        this.notifications = [
                            {
                                id: 1,
                                title: 'Pengingat Jadwal',
                                message: 'Anda memiliki jadwal mengajar kelas 10 IPA dalam 15 menit',
                                type: 'agenda',
                                is_read: false,
                                created_at: new Date().toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'})
                            }
                        ];
                        
                        this.unreadCount = this.notifications.filter(n => !n.is_read).length;
                    } catch (error) {
                        console.error('Error loading notifications:', error);
                    } finally {
                        this.isLoading = false;
                    }
                },

                async markAsRead(notificationId) {
                    try {
                        const notification = this.notifications.find(n => n.id === notificationId);
                        if (notification) {
                            notification.is_read = true;
                            this.unreadCount = this.notifications.filter(n => !n.is_read).length;
                        }
                    } catch (error) {
                        console.error('Error marking notification as read:', error);
                    }
                },

                async markAllAsRead() {
                    try {
                        this.notifications.forEach(notification => {
                            notification.is_read = true;
                        });
                        this.unreadCount = 0;
                    } catch (error) {
                        console.error('Error marking all notifications as read:', error);
                    }
                },

                toggleDropdown() {
                    this.open = !this.open;
                    if (this.open) {
                        this.loadNotifications();
                    }
                }
            }
        }
    </script>
</div>