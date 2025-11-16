<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'SIAKAD SMA' }}</title>
    
<script>
(function() {
    const savedTheme = localStorage.getItem('theme');
    const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    
    let theme = 'light';
    if (savedTheme) {
        theme = savedTheme;
    } else if (systemPrefersDark) {
        theme = 'dark';
    }
    
    // Apply theme immediately to HTML element
    if (theme === 'dark') {
        document.documentElement.classList.add('dark');
        
        // document.documentElement.style.backgroundColor = '#111827';
    } else {
        document.documentElement.classList.remove('dark');
        // document.documentElement.style.backgroundColor = '#f9fafb';
    }
    
    window.initialTheme = theme;
})();
</script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        [x-cloak] { display: none !important; }
        
        /* Smooth transitions setelah page load */
        .no-transition * {
            transition: none !important;
        }
    </style>
</head>
<body class="h-full bg-gray-50 dark:bg-gray-900 no-transition" x-data="globalApp()" 
      x-init="
        // Initialize dark mode from window.initialTheme
        isDarkMode = window.initialTheme === 'dark';
        
        // Remove no-transition class setelah Alpine siap
        setTimeout(() => {
            if ($el.classList.contains('no-transition')) {
                $el.classList.remove('no-transition');
            }
        }, 50);
      ">
    
    <!-- SIDEBAR -->
    @if(auth()->check())
        @include('livewire.shared.sidebar', ['role' => auth()->user()->role])
    @endif

    <!-- MAIN CONTENT -->
    <div class="min-h-screen flex flex-col transition-all duration-300 ease-in-out"
         :class="isSidebarCollapsed ? 'main-content-collapsed' : 'main-content-expanded'">
        
        <!-- HEADER -->
        @if(auth()->check())
            @include('livewire.shared.header-simple')
        @endif
        
        <!-- MAIN CONTENT -->
        <main class="flex-1 p-4 lg:p-6">
            {{ $slot }}
        </main>
    </div>

    <script>
        function globalApp() {
            return {
                isSidebarCollapsed: localStorage.getItem('sidebar-collapsed') === 'true',
                isMobileSidebarOpen: false,
                isDarkMode: window.initialTheme === 'dark',
                
                init() {
                    console.log('🔄 Global app initialized, dark mode:', this.isDarkMode);
                    
                    // Set initial state
                    const savedState = localStorage.getItem('sidebar-collapsed');
                    if (savedState !== null) {
                        this.isSidebarCollapsed = savedState === 'true';
                    }
                    
                    // Sync dengan localStorage
                    this.$watch('isSidebarCollapsed', (value) => {
                        localStorage.setItem('sidebar-collapsed', value);
                    });
                    
                    // Dark mode sync
                    this.$watch('isDarkMode', (value) => {
                        console.log('Dark mode changed to:', value);
                        
                        // Update HTML class immediately
                        if (value) {
                            document.documentElement.classList.add('dark');
                        } else {
                            document.documentElement.classList.remove('dark');
                        }
                        
                        // Update cookie via fetch
                        fetch('/theme-toggle', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ theme: value ? 'dark' : 'light' })
                        }).catch(err => console.log('Theme toggle error:', err));
                        
                        // Update localStorage
                        localStorage.setItem('theme', value ? 'dark' : 'light');
                    });
                    
                    // Listen untuk events dari sidebar
                    window.addEventListener('sidebar-state-changed', (e) => {
                        this.isSidebarCollapsed = e.detail.collapsed;
                        this.isMobileSidebarOpen = e.detail.mobileOpen;
                    });
                    
                    // Handle resize
                    this.handleResize();
                    window.addEventListener('resize', this.handleResize.bind(this));
                },
                
                handleResize() {
                    if (window.innerWidth >= 1024) {
                        this.isMobileSidebarOpen = false;
                    }
                },
                
                toggleDarkMode() {
                    this.isDarkMode = !this.isDarkMode;
                    console.log('Toggled dark mode to:', this.isDarkMode);
                }
            }
        }

        document.addEventListener('alpine:init', () => {
            console.log('✅ Alpine.js ready');
        });
    </script>
</body>
</html>