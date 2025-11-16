<!-- ========================================
     COPY PASTE INI KE SEMUA FILE LAYOUT
     - admin.blade.php
     - guest.blade.php  
     - guru.blade.php
     - siswa.blade.php
     - components/layouts/app.blade.php
     ======================================== -->

<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'SIAKAD SMA' }}</title>
    
    <!-- INITIAL THEME SCRIPT - HARUS DI ATAS SEMUA -->
    <script>
        const savedTheme = localStorage.getItem('theme');
        const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        const initialTheme = savedTheme || (systemPrefersDark ? 'dark' : 'light');
        
        if (initialTheme === 'dark') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
        window.initialTheme = initialTheme;
    </script>
    
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
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Font Awesome (opsional, hapus jika tidak perlu) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        [x-cloak] { display: none !important; }
        
        /* ========== VISION UI BACKGROUND - JANGAN DIUBAH! ========== */
        body {
            position: relative;
            overflow-x: hidden;
            min-height: 100vh;
        }

        /* Background gradient layer */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 0;
            pointer-events: none;
        }

        /* Light Mode Background */
        body:not(.dark)::before {
            background: 
                radial-gradient(ellipse 80% 50% at 50% -20%, rgba(120, 119, 198, 0.25), transparent),
                radial-gradient(ellipse 60% 50% at 0% 50%, rgba(59, 130, 246, 0.12), transparent),
                radial-gradient(ellipse 60% 50% at 100% 50%, rgba(236, 72, 153, 0.12), transparent),
                linear-gradient(135deg, #f8fafc 0%, #f1f5f9 50%, #e2e8f0 100%);
        }

        /* Dark Mode Background */
        body.dark::before {
            background:
                radial-gradient(ellipse 80% 50% at 50% -20%, rgba(59, 130, 246, 0.15), transparent),
                radial-gradient(ellipse 60% 50% at 0% 50%, rgba(16, 185, 129, 0.1), transparent),
                radial-gradient(ellipse 60% 50% at 100% 50%, rgba(236, 72, 153, 0.1), transparent),
                linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
        }

        /* Animated orb - kanan atas */
        body::after {
            content: '';
            position: fixed;
            border-radius: 50%;
            z-index: 0;
            animation: float 20s ease-in-out infinite;
            pointer-events: none;
        }

        body:not(.dark)::after {
            width: 600px;
            height: 600px;
            top: -300px;
            right: -300px;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.1) 0%, transparent 70%);
        }

        body.dark::after {
            width: 800px;
            height: 800px;
            top: -400px;
            right: -400px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.12) 0%, transparent 70%);
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(-30px, -30px) scale(1.1); }
            66% { transform: translate(30px, 30px) scale(0.9); }
        }

        /* Ensure all content is above background */
        body > * {
            position: relative;
            z-index: 10;
        }

        /* Glass card effect - gunakan class ini untuk card/panel */
        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
        }

        .dark .glass-card {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(148, 163, 184, 0.1);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
        }
    </style>
</head>
<body class="h-full">
    <!-- JANGAN TAMBAH bg-gray-50 atau dark:bg-gray-900 DI SINI! -->
    
    {{ $slot }}
    
</body>
</html>