@php
use App\Helpers\SettingsHelper;
@endphp

<header class="header-container shadow-sm border-b border-gray-200/50 dark:border-gray-700/50 sticky top-0 transition-all duration-300 z-50"
        x-data="headerApp()"
        :class="{
            'backdrop-blur-lg bg-white/90 dark:bg-gray-800/90 shadow-lg': isScrolled,
            'bg-white dark:bg-gray-900': !isScrolled
        }">
    <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
        <!-- Left Section -->
        <div class="flex items-center space-x-3 lg:space-x-4">
            <!-- Mobile menu button -->
            <button @click="toggleMobileSidebar()" 
                    class="lg:hidden p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none transition-colors duration-200"
                    title="Toggle Sidebar Mobile">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            
            <!-- Desktop sidebar toggle -->
            <button @click="toggleDesktopSidebar()" 
                    class="hidden lg:flex items-center justify-center w-8 h-8 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200"
                    :class="{ 'rotate-180': sidebarCollapsed }"
                    title="Toggle Sidebar Desktop">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                </svg>
            </button>
            
            <!-- School Logo -->
            @php
                $schoolLogo = SettingsHelper::schoolLogo();
                $schoolName = SettingsHelper::schoolName();
            @endphp
            
            @if($schoolLogo)
            <div class="hidden md:flex items-center">
                <img src="{{ $schoolLogo }}" 
                     alt="Logo {{ $schoolName }}"
                     class="h-10 w-10 object-contain rounded-lg"
                     onerror="this.style.display='none'">
            </div>
            @endif
            
            <!-- Page Title -->
            <div class="flex flex-col">
                <h1 class="text-lg lg:text-xl font-semibold text-gray-900 dark:text-white leading-tight">
                    @php
                        $currentRoute = request()->route()->getName();
                        $titles = [
                            'admin.dashboard' => 'Dashboard Admin',
                            'admin.guru.index' => 'Data Guru',
                            'admin.siswa.index' => 'Data Siswa', 
                            'admin.kelas.index' => 'Kelas & Jurusan',
                            'admin.mapel.index' => 'Mata Pelajaran',
                            'admin.jadwal.index' => 'Jadwal Pelajaran',
                            'admin.nilai.index' => 'Monitoring Nilai',
                            'admin.laporan.index' => 'Laporan Akademik',
                            'guru.dashboard' => 'Dashboard Guru',
                            'siswa.dashboard' => 'Dashboard Siswa',
                            'admin.pengaturan.index' => 'Pengaturan Sistem',
                            'admin.pengguna.index' => 'Manajemen Pengguna',
                            'profile.edit' => 'Edit Profil'
                        ];
                        echo $titles[$currentRoute] ?? 'Dashboard';
                    @endphp
                </h1>
                <p class="text-xs lg:text-sm text-gray-500 dark:text-gray-400 leading-tight">
                    {{ $schoolName }}
                </p>
            </div>
        </div>
        
        <!-- Right Section -->
        <div class="flex items-center space-x-2 lg:space-x-4">
            <!-- Real-time Clock - Desktop -->
            <div class="hidden md:flex flex-col items-end">
                <div id="header-real-time-clock" class="text-sm font-medium text-gray-900 dark:text-white font-mono"></div>
                <div id="header-real-time-date" class="text-xs text-gray-500 dark:text-gray-400"></div>
            </div>

            <!-- Real-time Clock - Mobile -->
            <div class="md:hidden flex items-center">
                <div id="mobile-real-time-clock" class="text-sm font-medium text-gray-900 dark:text-white font-mono"></div>
            </div>

            <!-- Dark Mode Toggle -->
            <button @click="toggleDarkMode()"
                class="p-2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors duration-200"
                title="Toggle Dark Mode">
                <!-- Light Icon -->
                <svg x-show="!darkMode" x-cloak class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <!-- Dark Icon -->
                <svg x-show="darkMode" x-cloak class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                </svg>
            </button>
            
            <!-- User Menu -->
            <div class="relative">
                <button @click="open = !open" class="flex items-center space-x-2 text-sm focus:outline-none transition-colors duration-200">
                    @php
                        $user = auth()->user();
                        $fotoUrl = $user->foto_profil ? Storage::disk('public')->url($user->foto_profil) . '?v=' . time() : null;
                    @endphp
                    
                    <div class="h-8 w-8 bg-blue-600 rounded-full flex items-center justify-center overflow-hidden ring-2 ring-white dark:ring-gray-700 shadow-sm">
                        @if($fotoUrl)
                            <img src="{{ $fotoUrl }}" 
                                 alt="Foto Profil {{ $user->name }}"
                                 class="w-full h-full object-cover"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <span class="text-sm font-medium text-white hidden">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </span>
                        @else
                            <span class="text-sm font-medium text-white">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </span>
                        @endif
                    </div>
                    <div class="text-left hidden lg:block">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 capitalize">{{ $user->role }}</p>
                    </div>
                </button>
                
                <!-- User Dropdown -->
                <div x-show="open" 
                     @click.away="open = false" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-56 bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-200 dark:border-gray-700 z-50 backdrop-blur-sm bg-white/95 dark:bg-gray-800/95"
                     style="display: none;">
                    <!-- User Info -->
                    <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $user->name }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $user->email }}</p>
                        <div class="mt-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 capitalize">
                                {{ $user->role }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Menu Items -->
                    <div class="p-2 space-y-1">
                        <a href="{{ route('profile.edit') }}" 
                           class="flex items-center px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors duration-200 group">
                            <svg class="h-4 w-4 mr-3 text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Profil Saya
                        </a>
                        
                        @if(auth()->user()->role === 'admin')
                            @if(Route::has('admin.pengaturan.index'))
                                <a href="{{ route('admin.pengaturan.index') }}" 
                                   class="flex items-center px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors duration-200 group">
                                    <svg class="h-4 w-4 mr-3 text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Pengaturan Sistem
                                </a>
                            @endif
                        @endif
                    </div>
                    
                    <!-- Logout -->
                    <div class="p-2 border-t border-gray-200 dark:border-gray-700">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" 
                                    class="flex items-center w-full px-3 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors duration-200 group">
                                <svg class="h-4 w-4 mr-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
// Header App - Sync dengan global store
function headerApp() {
    return {
        open: false,
        sidebarCollapsed: localStorage.getItem('sidebar-collapsed') === 'true',
        isScrolled: false,
        darkMode: Alpine.store('globalApp').isDarkMode,
        
        init() {
            console.log('🔄 Header initialized');
            
            // Initialize header clocks
            this.updateHeaderClocks();
            setInterval(() => this.updateHeaderClocks(), 1000);
            
            // Listen for sidebar state changes
            window.addEventListener('sidebar-state-changed', (event) => {
                this.sidebarCollapsed = event.detail.collapsed;
            });

            // Listen for dark mode changes dari global store
            window.addEventListener('dark-mode-changed', (event) => {
                this.darkMode = event.detail.darkMode;
                console.log('📢 Header received dark mode:', this.darkMode);
                
                // Force update UI elements jika diperlukan
                this.$nextTick(() => {
                    // Update icon visibility
                    this.$refs.darkIcon?.classList.toggle('hidden', this.darkMode);
                    this.$refs.lightIcon?.classList.toggle('hidden', !this.darkMode);
                });
            });

            // Initialize scroll handler
            this.handleScroll();
            window.addEventListener('scroll', this.handleScroll.bind(this));

            // Check initial scroll position
            setTimeout(() => {
                this.handleScroll();
            }, 100);
            
            // Sync initial dark mode state dari global store
            this.darkMode = Alpine.store('globalApp').isDarkMode;
            
            // Force initial icon state
            this.$nextTick(() => {
                const darkIcon = document.querySelector('[x-show="darkMode"]');
                const lightIcon = document.querySelector('[x-show="!darkMode"]');
                if (darkIcon && lightIcon) {
                    darkIcon.style.display = this.darkMode ? 'block' : 'none';
                    lightIcon.style.display = this.darkMode ? 'none' : 'block';
                }
            });
        },
        
        handleScroll() {
            // Tambahkan sedikit threshold untuk mencegah perubahan terlalu sensitif
            this.isScrolled = window.scrollY > 30;
        },
        
        updateHeaderClocks() {
            const now = new Date();
            @php
                $timezone = SettingsHelper::timezone();
            @endphp
            
            const timeOptions = {
                timeZone: '{{ $timezone }}',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            };
            const timeShortOptions = {
                timeZone: '{{ $timezone }}',
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            };
            const dateOptions = {
                timeZone: '{{ $timezone }}',
                weekday: 'short',
                day: 'numeric',
                month: 'short',
                year: 'numeric'
            };
            
            const timeFormatter = new Intl.DateTimeFormat('id-ID', timeOptions);
            const timeShortFormatter = new Intl.DateTimeFormat('id-ID', timeShortOptions);
            const dateFormatter = new Intl.DateTimeFormat('id-ID', dateOptions);
            
            const timeString = timeFormatter.format(now);
            const timeShortString = timeShortFormatter.format(now);
            const dateString = dateFormatter.format(now);
            
            // Update header clocks
            const headerClock = document.getElementById('header-real-time-clock');
            const headerDate = document.getElementById('header-real-time-date');
            const mobileClock = document.getElementById('mobile-real-time-clock');
            
            if (headerClock) headerClock.textContent = timeString;
            if (headerDate) headerDate.textContent = dateString;
            if (mobileClock) mobileClock.textContent = timeShortString;
        },
        
        toggleMobileSidebar() {
            window.dispatchEvent(new CustomEvent('toggle-mobile-sidebar'));
        },
        
        toggleDesktopSidebar() {
            window.dispatchEvent(new CustomEvent('toggle-desktop-sidebar'));
        },
        
        toggleDarkMode() {
            console.log('🌙 Header triggering dark mode toggle');
            Alpine.store('globalApp').toggleDarkMode();
        }
    }
}
</script>