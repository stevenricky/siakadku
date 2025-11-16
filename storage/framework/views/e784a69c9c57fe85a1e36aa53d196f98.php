<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?php echo e(\App\Helpers\SettingsHelper::schoolName()); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        /* Professional Background with Subtle Pattern */
        .professional-bg {
            background: 
                radial-gradient(circle at 20% 80%, rgba(120, 119, 198, 0.03) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 119, 198, 0.03) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(120, 219, 255, 0.02) 0%, transparent 50%),
                linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        }

        /* Premium Card Design */
        .premium-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.8);
            box-shadow: 
                0 25px 50px -12px rgba(0, 0, 0, 0.15),
                0 8px 16px rgba(0, 0, 0, 0.1),
                0 0 0 1px rgba(0, 0, 0, 0.02),
                inset 0 1px 0 rgba(255, 255, 255, 0.8);
        }

        /* Sophisticated Input Design */
        .sophisticated-input {
            background: rgba(255, 255, 255, 0.8);
            border: 1.5px solid #e2e8f0;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sophisticated-input:focus {
            background: rgba(255, 255, 255, 1);
            border-color: #3b82f6;
            box-shadow: 
                0 0 0 3px rgba(59, 130, 246, 0.1),
                0 2px 8px rgba(59, 130, 246, 0.08);
            transform: translateY(-1px);
        }

        /* Elegant Button */
        .elegant-button {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            color: white;
            font-weight: 600;
            letter-spacing: 0.025em;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .elegant-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: left 0.6s;
        }

        .elegant-button:hover::before {
            left: 100%;
        }

        .elegant-button:hover {
            transform: translateY(-2px);
            box-shadow: 
                0 12px 25px rgba(30, 41, 59, 0.25),
                0 0 0 1px rgba(30, 41, 59, 0.1);
        }

        /* Premium Checkbox */
        .premium-checkbox {
            appearance: none;
            width: 18px;
            height: 18px;
            border: 2px solid #cbd5e1;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
            position: relative;
        }

        .premium-checkbox:checked {
            background: #1e293b;
            border-color: #1e293b;
        }

        .premium-checkbox:checked::after {
            content: '✓';
            position: absolute;
            color: white;
            font-size: 12px;
            font-weight: bold;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        /* Subtle Animations */
        @keyframes professionalFadeIn {
            from {
                opacity: 0;
                transform: translateY(30px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .professional-fade-in {
            animation: professionalFadeIn 0.8s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        }

        /* Video Container */
        .video-container {
            position: relative;
            width: 50%;
            height: 100vh;
            overflow: hidden;
            background: #000;
        }

        .video-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.85) 0%, rgba(30, 41, 59, 0.9) 100%);
            z-index: 2;
        }

        video {
            position: absolute;
            top: 50%;
            left: 50%;
            min-width: 100%;
            min-height: 100%;
            width: 100%;
            height: 100%;
            transform: translate(-50%, -50%);
            object-fit: cover;
        }

        .video-content {
            position: absolute;
            z-index: 3;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 3rem;
        }

        /* Mobile Video */
        .mobile-video {
            height: 30vh;
            position: relative;
            overflow: hidden;
            background: #000;
        }

        .mobile-video::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.85) 0%, rgba(30, 41, 59, 0.9) 100%);
            z-index: 2;
        }

        /* Logo Container - Fixed untuk responsif */
        .logo-container {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo-img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            border-radius: 8px;
        }

        /* Improved logo containers */
        .logo-box {
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background: white;
        }

        .logo-box-sm {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            padding: 2px;
        }

        .logo-box-md {
            width: 56px;
            height: 56px;
            border-radius: 10px;
            padding: 2px;
        }

        .logo-box-lg {
            width: 64px;
            height: 64px;
            border-radius: 12px;
            padding: 2px;
        }

        /* Responsive adjustments */
        @media (max-width: 1024px) {
            .video-container {
                display: none;
            }
            
            .mobile-video {
                height: 25vh;
                margin-bottom: 1rem;
            }
            
            .premium-card {
                padding: 1.5rem;
            }
        }

        @media (min-width: 1025px) {
            .mobile-video {
                display: none;
            }
        }

        /* Compact form for no-scroll */
        .compact-form {
            max-height: calc(100vh - 2rem);
            overflow-y: auto;
        }

        .compact-form::-webkit-scrollbar {
            width: 4px;
        }

        .compact-form::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 2px;
        }

        .compact-form::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 2px;
        }
    </style>
</head>
<body class="h-full">
    <div class="min-h-screen flex">
        <!-- Left Section - Video Background -->
        <div class="video-container hidden lg:block">
            <video autoplay muted loop playsinline>
                <source src="<?php echo e(asset('videos/login.mp4')); ?>" type="video/mp4">
            </video>

            <!-- Content Over Video -->
            <div class="video-content">
                <!-- Top Content with Dynamic Logo -->
                <div class="professional-fade-in">
                    <div class="flex items-center space-x-4">
                        <?php
                            $schoolLogo = \App\Helpers\SettingsHelper::schoolLogo();
                            $schoolName = \App\Helpers\SettingsHelper::schoolName();
                        ?>
                        
                        <?php if($schoolLogo): ?>
                        <div class="logo-box logo-box-lg shadow-lg">
                            <div class="logo-container">
                                <img src="<?php echo e($schoolLogo); ?>" 
                                     alt="Logo <?php echo e($schoolName); ?>"
                                     class="logo-img"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="w-full h-full bg-gradient-to-br from-slate-800 to-slate-900 rounded-xl flex items-center justify-center hidden">
                                    <i class="fas fa-graduation-cap text-xl text-white"></i>
                                </div>
                            </div>
                        </div>
                        <?php else: ?>
                        <div class="w-16 h-16 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center border border-white/20">
                            <i class="fas fa-graduation-cap text-2xl text-white"></i>
                        </div>
                        <?php endif; ?>
                        
                        <div>
                            <h1 class="text-2xl font-bold text-white tracking-tight"><?php echo e($schoolName); ?></h1>
                            <p class="text-gray-300 text-sm font-light">Sistem Manajemen Akademik</p>
                        </div>
                    </div>
                </div>

                <!-- Middle Content -->
                <div class="professional-fade-in" style="animation-delay: 0.2s;">
                    <h2 class="text-5xl font-bold text-white mb-6 leading-tight tracking-tight">
                        Keunggulan dalam<br>Pendidikan Digital
                    </h2>
                    <p class="text-gray-300 text-lg mb-8 max-w-md font-light leading-relaxed">
                        Pengalaman belajar transformatif didukung oleh teknologi mutakhir dan solusi pendidikan inovatif.
                    </p>
                    
                    <div class="space-y-4">
                        <div class="flex items-start space-x-4">
                            <div class="w-6 h-6 rounded-full bg-white/10 backdrop-blur-sm flex items-center justify-center flex-shrink-0 mt-1 border border-white/20">
                                <i class="fas fa-check text-xs text-white"></i>
                            </div>
                            <div>
                                <p class="text-white font-semibold">Keamanan Enterprise-Grade</p>
                                <p class="text-gray-300 text-sm font-light">Enkripsi level bank dan perlindungan data</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-4">
                            <div class="w-6 h-6 rounded-full bg-white/10 backdrop-blur-sm flex items-center justify-center flex-shrink-0 mt-1 border border-white/20">
                                <i class="fas fa-check text-xs text-white"></i>
                            </div>
                            <div>
                                <p class="text-white font-semibold">Analitik Real-Time</p>
                                <p class="text-gray-300 text-sm font-light">Wawasan komprehensif dan pelacakan performa</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bottom Content -->
                <div class="professional-fade-in" style="animation-delay: 0.4s;">
                    <p class="text-gray-400 text-sm font-light">&copy; <?php echo e(date('Y')); ?> <?php echo e($schoolName); ?>. Hak cipta dilindungi.</p>
                </div>
            </div>
        </div>

        <!-- Right Section - Professional Login Form -->
        <div class="professional-bg w-full lg:w-1/2 flex items-center justify-center p-4 lg:p-8">
            <div class="w-full max-w-md compact-form">
                <!-- Mobile Video Header -->
                <div class="mobile-video lg:hidden mb-4 rounded-2xl overflow-hidden">
                    <video autoplay muted loop playsinline class="w-full h-full object-cover">
                        <source src="<?php echo e(asset('videos/login.mp4')); ?>" type="video/mp4">
                    </video>
                    <div class="absolute inset-0 flex items-center justify-center z-10">
                        <div class="text-center">
                            <?php if($schoolLogo): ?>
                            <div class="logo-box logo-box-md shadow-lg mx-auto mb-3">
                                <div class="logo-container">
                                    <img src="<?php echo e($schoolLogo); ?>" 
                                         alt="Logo <?php echo e($schoolName); ?>"
                                         class="logo-img"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <div class="w-full h-full bg-gradient-to-br from-slate-800 to-slate-900 rounded-xl flex items-center justify-center hidden">
                                        <i class="fas fa-graduation-cap text-lg text-white"></i>
                                    </div>
                                </div>
                            </div>
                            <?php else: ?>
                            <div class="w-14 h-14 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center border border-white/20 mx-auto mb-3">
                                <i class="fas fa-graduation-cap text-xl text-white"></i>
                            </div>
                            <?php endif; ?>
                            <h1 class="text-xl font-bold text-white tracking-tight"><?php echo e($schoolName); ?></h1>
                            <p class="text-gray-300 text-xs font-light">Sistem Manajemen Akademik</p>
                        </div>
                    </div>
                </div>

                <!-- Premium Form Card -->
                <div class="premium-card rounded-3xl p-6 professional-fade-in">
                    <!-- Form Header -->
                    <div class="mb-6 text-center">
                        <div class="flex items-center justify-center space-x-3 mb-3">
                            <?php if($schoolLogo): ?>
                            <div class="logo-box logo-box-sm shadow-md">
                                <div class="logo-container">
                                    <img src="<?php echo e($schoolLogo); ?>" 
                                         alt="Logo <?php echo e($schoolName); ?>"
                                         class="logo-img"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <div class="w-full h-full bg-gradient-to-br from-slate-800 to-slate-900 rounded flex items-center justify-center hidden">
                                        <i class="fas fa-graduation-cap text-sm text-white"></i>
                                    </div>
                                </div>
                            </div>
                            <?php else: ?>
                            <div class="w-10 h-10 bg-gradient-to-br from-slate-800 to-slate-900 rounded-xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-graduation-cap text-lg text-white"></i>
                            </div>
                            <?php endif; ?>
                            <div class="text-left">
                                <h1 class="text-base font-bold text-slate-900"><?php echo e($schoolName); ?></h1>
                                <p class="text-slate-600 text-xs font-light">Portal Akses Aman</p>
                            </div>
                        </div>
                        
                        <h2 class="text-xl font-bold text-slate-900 mb-2 tracking-tight">
                            Selamat Datang Kembali
                        </h2>
                        <p class="text-slate-600 text-sm font-light">
                            Masuk ke akun Anda untuk melanjutkan
                        </p>
                    </div>

                    <!-- Login Form -->
                    <form action="<?php echo e(route('login')); ?>" method="POST" class="space-y-4">
                        <?php echo csrf_field(); ?>
                        
                        <!-- Email Field -->
                        <div>
                            <label for="email" class="block text-sm font-semibold text-slate-700 mb-2 tracking-tight">
                                Alamat Email
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-envelope text-slate-400 text-sm"></i>
                                </div>
                                <input 
                                    id="email" 
                                    name="email" 
                                    type="email" 
                                    autocomplete="email" 
                                    required 
                                    class="sophisticated-input w-full pl-10 pr-4 py-3 rounded-xl text-slate-900 placeholder-slate-500 text-sm font-medium tracking-tight"
                                    placeholder="Masukkan alamat email Anda"
                                    value="<?php echo e(old('email')); ?>">
                            </div>
                            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-500 text-xs mt-1 font-medium tracking-tight"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Password Field -->
                        <div>
                            <label for="password" class="block text-sm font-semibold text-slate-700 mb-2 tracking-tight">
                                Password
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-lock text-slate-400 text-sm"></i>
                                </div>
                                <input 
                                    id="password" 
                                    name="password" 
                                    type="password" 
                                    autocomplete="current-password" 
                                    required 
                                    class="sophisticated-input w-full pl-10 pr-12 py-3 rounded-xl text-slate-900 placeholder-slate-500 text-sm font-medium tracking-tight"
                                    placeholder="Masukkan password Anda">
                                <button 
                                    type="button" 
                                    onclick="togglePassword()" 
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 transition-colors duration-200">
                                    <i id="eye-icon" class="fas fa-eye text-sm"></i>
                                </button>
                            </div>
                            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-500 text-xs mt-1 font-medium tracking-tight"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Remember & Forgot -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input 
                                    id="remember" 
                                    name="remember" 
                                    type="checkbox" 
                                    class="premium-checkbox">
                                <label for="remember" class="ml-2 text-sm text-slate-700 font-medium tracking-tight cursor-pointer select-none">
                                    Ingat saya
                                </label>
                            </div>
                            <a href="#" class="text-sm text-slate-600 hover:text-slate-900 font-medium tracking-tight transition-colors duration-200">
                                Lupa password?
                            </a>
                        </div>

                        <!-- Login Button -->
                        <button 
                            type="submit" 
                            class="elegant-button w-full py-3 px-4 rounded-xl text-white font-semibold text-sm tracking-tight mt-3">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Masuk ke Dashboard
                        </button>

                        <!-- Support & Security -->
                        <div class="space-y-3 mt-4">
                            <!-- Security Info -->
                            <div class="flex items-center justify-center space-x-2 text-slate-600">
                                <i class="fas fa-shield-alt text-xs"></i>
                                <span class="text-xs font-medium tracking-tight">Dilindungi keamanan enterprise</span>
                            </div>

                            <!-- Support -->
                            <div class="text-center">
                                <p class="text-sm text-slate-600 font-medium tracking-tight">
                                    Butuh bantuan? 
                                    <a href="#" class="text-slate-900 hover:text-slate-700 font-semibold transition-colors duration-200">
                                        Hubungi Support
                                    </a>
                                </p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const icon = document.getElementById('eye-icon');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
                icon.classList.add('text-slate-600');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.remove('text-slate-600');
                icon.classList.add('fa-eye');
            }
        }

        // Enhanced form interactions
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('.sophisticated-input');
            
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('scale-105');
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('scale-105');
                });
            });

            // Add ripple effect to button
            const button = document.querySelector('.elegant-button');
            if (button) {
                button.addEventListener('click', function(e) {
                    const ripple = document.createElement('span');
                    const rect = this.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size / 2;
                    const y = e.clientY - rect.top - size / 2;
                    
                    ripple.style.cssText = `
                        position: absolute;
                        border-radius: 50%;
                        background: rgba(255, 255, 255, 0.6);
                        transform: scale(0);
                        animation: ripple 0.6s linear;
                        pointer-events: none;
                        width: ${size}px;
                        height: ${size}px;
                        left: ${x}px;
                        top: ${y}px;
                    `;
                    
                    this.appendChild(ripple);
                    
                    setTimeout(() => {
                        ripple.remove();
                    }, 600);
                });
            }
        });

        // Add CSS for ripple effect
        const style = document.createElement('style');
        style.textContent = `
            @keyframes ripple {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html><?php /**PATH C:\Users\user\siakad-sma\resources\views/auth/login.blade.php ENDPATH**/ ?>