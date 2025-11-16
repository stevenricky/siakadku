<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'SIAKAD SMA - Admin' }}</title>
    
    <script>
        const savedTheme = localStorage.getItem('theme');
        const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        const initialTheme = savedTheme || (systemPrefersDark ? 'dark' : 'light');
        if (initialTheme === 'dark') {
            document.documentElement.classList.add('dark');
        }
    </script>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff', 500: '#3b82f6', 600: '#2563eb', 700: '#1d4ed8'
                        }
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        body { position: relative; overflow-x: hidden; min-height: 100vh; }
        body::before {
            content: ''; position: fixed; top: 0; left: 0; right: 0; bottom: 0;
            z-index: 0; pointer-events: none;
        }
        body:not(.dark)::before {
            background: 
                radial-gradient(ellipse 80% 50% at 50% -20%, rgba(120, 119, 198, 0.25), transparent),
                radial-gradient(ellipse 60% 50% at 0% 50%, rgba(59, 130, 246, 0.12), transparent),
                radial-gradient(ellipse 60% 50% at 100% 50%, rgba(236, 72, 153, 0.12), transparent),
                linear-gradient(135deg, #f8fafc 0%, #f1f5f9 50%, #e2e8f0 100%);
        }
        body.dark::before {
            background:
                radial-gradient(ellipse 80% 50% at 50% -20%, rgba(59, 130, 246, 0.15), transparent),
                radial-gradient(ellipse 60% 50% at 0% 50%, rgba(16, 185, 129, 0.1), transparent),
                radial-gradient(ellipse 60% 50% at 100% 50%, rgba(236, 72, 153, 0.1), transparent),
                linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
        }
        body::after {
            content: ''; position: fixed; border-radius: 50%; z-index: 0;
            animation: float 20s ease-in-out infinite; pointer-events: none;
        }
        body:not(.dark)::after {
            width: 600px; height: 600px; top: -300px; right: -300px;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.1) 0%, transparent 70%);
        }
        body.dark::after {
            width: 800px; height: 800px; top: -400px; right: -400px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.12) 0%, transparent 70%);
        }
        @keyframes float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(-30px, -30px) scale(1.1); }
            66% { transform: translate(30px, 30px) scale(0.9); }
        }
        body > * { position: relative; z-index: 10; }
    </style>
</head>
<body class="h-full">
    {{ $slot }}
</body>
</html>