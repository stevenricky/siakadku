@php
use App\Helpers\SettingsHelper;
@endphp
<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        @php
            $pageTitle = $title ?? 'Dashboard';
            echo SettingsHelper::schoolName() . ' - ' . $pageTitle;
        @endphp
    </title>

    <!-- INITIAL THEME SCRIPT -->
    <script>
        const savedTheme = localStorage.getItem('theme');
        const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        const initialTheme = savedTheme || (systemPrefersDark ? 'dark' : 'light');
        
        if (initialTheme === 'dark') {
            document.documentElement.classList.add('dark');
            document.documentElement.style.colorScheme = 'dark';
        } else {
            document.documentElement.classList.remove('dark');
            document.documentElement.style.colorScheme = 'light';
        }
        
        window.initialTheme = initialTheme;
    </script>

    @livewireStyles

    <!-- CDN TAILWIND -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                        }
                    },
                    animation: {
                        'gradient': 'gradient 15s ease infinite',
                        'float': 'float 6s ease-in-out infinite',
                        'glow': 'glow 2s ease-in-out infinite alternate',
                        'slide-in': 'slideIn 0.5s ease-out',
                    }
                }
            }
        }
    </script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        [x-cloak] { display: none !important; }
        
        /* ========== SUPER ELEGAN GRADIENT & EFFECTS ========== */
        
        /* Animated Gradient Background */
        .bg-gradient-super {
            background: linear-gradient(-45deg, 
                #667eea 0%, 
                #764ba2 25%, 
                #f093fb 50%, 
                #f5576c 75%, 
                #4facfe 100%);
            background-size: 400% 400%;
            background-attachment: fixed;
            animation: gradient 15s ease infinite;
        }

        .dark .bg-gradient-super {
            background: linear-gradient(-45deg, 
                #0f172a 0%, 
                #1e293b 25%, 
                #334155 50%, 
                #475569 75%, 
                #64748b 100%);
            background-size: 400% 400%;
            background-attachment: fixed;
            animation: gradient 15s ease infinite;
        }

        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Floating Particles Effect */
        .particles-container::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                radial-gradient(2px 2px at 20% 30%, rgba(255,255,255,0.3) 0%, transparent 100%),
                radial-gradient(2px 2px at 40% 70%, rgba(255,255,255,0.2) 0%, transparent 100%),
                radial-gradient(1px 1px at 90px 40px, rgba(255,255,255,0.3) 0%, transparent 100%),
                radial-gradient(1px 1px at 130px 80px, rgba(255,255,255,0.2) 0%, transparent 100%),
                radial-gradient(2px 2px at 160px 30px, rgba(255,255,255,0.3) 0%, transparent 100%);
            background-repeat: repeat;
            background-size: 200px 200px;
            animation: particles 20s linear infinite;
            pointer-events: none;
            z-index: -1;
        }

        .dark .particles-container::before {
            background-image: 
                radial-gradient(2px 2px at 20% 30%, rgba(99, 102, 241, 0.3) 0%, transparent 100%),
                radial-gradient(2px 2px at 40% 70%, rgba(236, 72, 153, 0.2) 0%, transparent 100%),
                radial-gradient(1px 1px at 90px 40px, rgba(6, 182, 212, 0.3) 0%, transparent 100%);
        }

        @keyframes particles {
            from { transform: translateY(0px); }
            to { transform: translateY(-200px); }
        }

        /* Glass Morphism Effect */
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
        }

        .dark .glass-effect {
            background: rgba(15, 23, 42, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.5);
        }

        /* Enhanced Glass for Header */
        .glass-header {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        }

        .dark .glass-header {
            background: rgba(15, 23, 42, 0.9);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.3);
        }

        /* Enhanced Glass for Sidebar */
        .glass-sidebar {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px);
            border-right: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 4px 0 30px rgba(0, 0, 0, 0.1);
        }

        .dark .glass-sidebar {
            background: rgba(15, 23, 42, 0.85);
            border-right: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 4px 0 30px rgba(0, 0, 0, 0.3);
        }

        /* Glass Cards */
        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .dark .glass-card {
            background: rgba(30, 41, 59, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }

        /* Hover Effects */
        .hover-lift {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .hover-lift:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .dark .hover-lift:hover {
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
        }

        .glass-card.hover-lift:hover {
            background: rgba(255, 255, 255, 0.9);
        }

        .dark .glass-card.hover-lift:hover {
            background: rgba(30, 41, 59, 0.9);
        }

        /* Glow Effect */
        .glow-effect {
            animation: glow 2s ease-in-out infinite alternate;
        }

        @keyframes glow {
            from {
                box-shadow: 0 0 20px rgba(59, 130, 246, 0.5);
            }
            to {
                box-shadow: 0 0 30px rgba(59, 130, 246, 0.8);
            }
        }

        /* Slide In Animation */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Floating Animation */
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-15px) rotate(5deg); }
        }

        /* Button Effects */
        .btn-glow {
            background: linear-gradient(45deg, #3b82f6, #6366f1);
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
            transition: all 0.3s ease;
        }

        .btn-glow:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.6);
        }

        /* Text Shadows */
        .text-glow {
            text-shadow: 0 0 20px rgba(255, 255, 255, 0.5);
        }

        .dark .text-glow {
            text-shadow: 0 0 20px rgba(255, 255, 255, 0.3);
        }

        /* ========== EXISTING STYLES ========== */
        .no-transition * {
            transition: none !important;
        }

        .sidebar-overlay { z-index: 40; }
        .sidebar-container { z-index: 50; }
        .header-container { z-index: 45; }

        .notification-item {
            animation: slideIn 0.3s ease-out;
        }

        .notification-badge {
            animation: pulse 2s infinite;
        }

        /* Enhanced transitions */
        * {
            transition: background-color 0.5s ease, 
                       color 0.5s ease, 
                       border-color 0.5s ease,
                       transform 0.3s ease,
                       box-shadow 0.3s ease !important;
        }

        /* Force dark mode optimizations */
        .dark .text-gray-900 { color: #f8fafc !important; }
        .dark .text-gray-800 { color: #f1f5f9 !important; }
        .dark .text-gray-700 { color: #e2e8f0 !important; }
        .dark .text-gray-600 { color: #cbd5e1 !important; }
        .dark .text-gray-500 { color: #94a3b8 !important; }
        .dark .border-gray-200 { border-color: #374151 !important; }
        .dark .border-gray-300 { border-color: #4b5563 !important; }
    </style>
</head>
<body class="h-full bg-gradient-super particles-container no-transition" 
      x-data="globalApp()" 
      x-init="
        console.log('🌍 Root component initializing...');
        $nextTick(() => {
            document.body.classList.remove('no-transition');
            setTimeout(() => {
                if (Alpine.store('globalApp')) {
                    Alpine.store('globalApp').applyTheme();
                }
            }, 200);
        });
      ">
    
    <!-- SIDEBAR dengan glass effect super -->
    @if(auth()->check())
        @include('livewire.shared.sidebar', ['role' => auth()->user()->role])
    @endif

    <!-- MAIN CONTENT -->
    <div class="min-h-screen flex flex-col main-content-transition"
         :class="{
             'lg:ml-64': !isSidebarCollapsed && !isMobileSidebarOpen,
             'lg:ml-20': isSidebarCollapsed && !isMobileSidebarOpen,
             'ml-0': isMobileSidebarOpen
         }">
        
        <!-- HEADER dengan glass effect super -->
        @if(auth()->check())
            <header class="header-container glass-header sticky top-0 transition-all duration-500"
                    x-data="headerApp()">
                @include('livewire.shared.header-simple')
            </header>
        @endif
        
        <!-- MAIN CONTENT -->
        <main class="flex-1 p-4 lg:p-6 transition-all duration-500">
            <div class="space-y-6 animate-slide-in">
                {{ $slot }}
            </div>
        </main>

        <!-- FOOTER dengan glass effect -->
<footer class="glass-card mt-8 border-t border-gray-200 dark:border-gray-700 p-3 shrink-0">
    <div class="text-center">
        <p class="text-sm text-gray-600 dark:text-gray-400">
            &copy; {{ date('Y') }} {{ SettingsHelper::schoolName() }}. Hak Cipta Dilindungi.
        </p>
        <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
            Sistem Informasi Akademik Terpadu
        </p>
    </div>
</footer>
    </div>

    <!-- NOTIFICATION TOAST CONTAINER -->
    <div id="notification-container" class="fixed top-20 right-4 z-50 space-y-2 max-w-sm"></div>

    <!-- Scripts -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('globalApp', {
                isDarkMode: window.initialTheme === 'dark',
                
                init() {
                    console.log('🎯 Global store initialized with dark mode:', this.isDarkMode);
                    this.applyTheme();
                },
                
                toggleDarkMode() {
                    this.isDarkMode = !this.isDarkMode;
                    console.log('🌙 Dark mode toggled to:', this.isDarkMode);
                    
                    this.applyTheme();
                    this.saveTheme();
                    this.notifyThemeChange();
                },
                
                applyTheme() {
                    console.log('🎨 Applying theme:', this.isDarkMode ? 'dark' : 'light');
                    document.documentElement.classList.remove('dark', 'light');
                    if (this.isDarkMode) {
                        document.documentElement.classList.add('dark');
                        document.documentElement.style.colorScheme = 'dark';
                    } else {
                        document.documentElement.classList.add('light');
                        document.documentElement.style.colorScheme = 'light';
                    }
                },
                
                saveTheme() {
                    localStorage.setItem('theme', this.isDarkMode ? 'dark' : 'light');
                    fetch('/theme-toggle', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ theme: this.isDarkMode ? 'dark' : 'light' })
                    }).catch(err => console.log('Theme toggle error:', err));
                },
                
                notifyThemeChange() {
                    window.dispatchEvent(new CustomEvent('dark-mode-changed', {
                        detail: { darkMode: this.isDarkMode }
                    }));
                }
            });
            Alpine.store('globalApp').init();
        });

        function globalApp() {
            return {
                isSidebarCollapsed: localStorage.getItem('sidebar-collapsed') === 'true',
                isMobileSidebarOpen: false,
                
                init() {
                    console.log('🔄 Global app initialized');
                    const savedState = localStorage.getItem('sidebar-collapsed');
                    if (savedState !== null) {
                        this.isSidebarCollapsed = savedState === 'true';
                    }
                    
                    this.$watch('isSidebarCollapsed', (value) => {
                        localStorage.setItem('sidebar-collapsed', value);
                        window.dispatchEvent(new CustomEvent('sidebar-state-changed', {
                            detail: { collapsed: value }
                        }));
                    });
                    
                    this.$watch('isMobileSidebarOpen', (value) => {
                        document.body.style.overflow = value ? 'hidden' : '';
                    });
                    
                    window.addEventListener('toggle-mobile-sidebar', () => {
                        this.isMobileSidebarOpen = !this.isMobileSidebarOpen;
                    });
                    
                    window.addEventListener('toggle-desktop-sidebar', () => {
                        this.isSidebarCollapsed = !this.isSidebarCollapsed;
                    });
                    
                    window.addEventListener('toggle-dark-mode', () => {
                        Alpine.store('globalApp').toggleDarkMode();
                    });
                    
                    this.handleResize();
                    window.addEventListener('resize', this.handleResize.bind(this));
                },
                
                handleResize() {
                    if (window.innerWidth >= 1024) {
                        this.isMobileSidebarOpen = false;
                    }
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            console.log('🚀 DOM fully loaded');
            setTimeout(() => {
                if (Alpine.store('globalApp')) {
                    Alpine.store('globalApp').applyTheme();
                }
            }, 100);
        });

        // Add some interactive effects
        document.addEventListener('DOMContentLoaded', function() {
            // Add hover effects to all glass cards
            const cards = document.querySelectorAll('.glass-card');
            cards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-8px) scale(1.02)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) scale(1)';
                });
            });

            // Add click effects to buttons
            const buttons = document.querySelectorAll('button');
            buttons.forEach(button => {
                button.addEventListener('click', function() {
                    this.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        this.style.transform = 'scale(1)';
                    }, 150);
                });
            });
        });
    </script>

    @livewireScripts
</body>
</html>