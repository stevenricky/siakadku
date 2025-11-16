<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance Mode - Sistem Sekolah</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        html, body {
            height: 100vh;
            width: 100vw;
            overflow: hidden;
            background: linear-gradient(180deg, #f1f5f9 0%, #e2e8f0 100%);
        }
        
        .container-wrapper {
            height: 100vh;
            width: 100vw;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem;
        }
        
        @media (min-width: 1024px) {
            .container-wrapper {
                padding: 0.75rem;
            }
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            width: 100%;
            max-width: 1200px;
            height: fit-content;
            max-height: 98vh;
            overflow-y: auto;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #64748b 0%, #475569 100%);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(71, 85, 105, 0.25);
        }

        /* Scrollbar styling */
        .glass-card::-webkit-scrollbar {
            width: 6px;
        }
        
        .glass-card::-webkit-scrollbar-track {
            background: transparent;
        }
        
        .glass-card::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }
    </style>
</head>
<body>
    <div class="container-wrapper">
        <div class="glass-card rounded-xl lg:rounded-2xl p-3 sm:p-4 lg:p-5">
            
            <!-- Header -->
            <div class="text-center mb-2 lg:mb-3">
                <div class="inline-flex items-center justify-center w-12 h-12 lg:w-14 lg:h-14 bg-gradient-to-br from-slate-700 to-slate-800 rounded-xl mb-2 shadow-lg">
                    <svg class="w-6 h-6 lg:w-7 lg:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                
                <h1 class="text-lg sm:text-xl lg:text-2xl font-bold text-slate-800 mb-1.5">
                    Maintenance Mode
                </h1>
                
                <div class="w-14 lg:w-16 h-0.5 bg-slate-700 rounded-full mx-auto mb-1.5"></div>
                
                <p class="text-xs text-slate-600 px-4 max-w-xl mx-auto">
                    Kami sedang melakukan peningkatan sistem untuk pengalaman yang lebih baik.
                </p>
            </div>

            <!-- Countdown Timer -->
            <div class="bg-gradient-to-br from-slate-700 to-slate-800 rounded-xl p-2.5 lg:p-3 mb-2.5 lg:mb-3">
                <div class="text-center mb-2">
                    <h3 class="text-sm font-bold text-white mb-0.5">Estimasi Waktu</h3>
                    <p class="text-slate-300 text-xs">Sistem akan kembali aktif dalam</p>
                </div>
                
                <div class="grid grid-cols-3 gap-2 mb-2">
                    <div class="bg-slate-600/30 rounded-lg p-2 backdrop-blur-sm text-center">
                        <div class="text-xl sm:text-2xl font-bold text-white" id="countdown-hours">02</div>
                        <div class="text-xs text-slate-300">Jam</div>
                    </div>
                    <div class="bg-slate-600/30 rounded-lg p-2 backdrop-blur-sm text-center">
                        <div class="text-xl sm:text-2xl font-bold text-white" id="countdown-minutes">00</div>
                        <div class="text-xs text-slate-300">Menit</div>
                    </div>
                    <div class="bg-slate-600/30 rounded-lg p-2 backdrop-blur-sm text-center">
                        <div class="text-xl sm:text-2xl font-bold text-white" id="countdown-seconds">00</div>
                        <div class="text-xs text-slate-300">Detik</div>
                    </div>
                </div>
                
                <div class="bg-slate-600/20 rounded-full h-1 overflow-hidden">
                    <div class="bg-white h-1 rounded-full transition-all duration-1000" id="progress-bar" style="width: 0%"></div>
                </div>
            </div>

            <!-- Schedule & Admin Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-2.5 mb-2.5">
                
                <!-- Schedule Info -->
                <div class="bg-slate-50 rounded-xl p-2.5 lg:p-3 border border-slate-200">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-xs lg:text-sm font-bold text-slate-800">Jadwal Maintenance</h3>
                        <span class="text-xs font-semibold text-slate-700 bg-slate-200 px-2 py-0.5 rounded-full">Active</span>
                    </div>
                    
                    <div class="space-y-1.5">
                        <div class="flex items-center space-x-2 p-2 bg-white rounded-lg border border-slate-200">
                            <div class="w-7 h-7 bg-slate-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-3.5 h-3.5 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <div class="text-xs text-slate-600">Waktu Mulai</div>
                                <div class="text-sm font-bold text-slate-800" id="start-time">14:00</div>
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-2 p-2 bg-white rounded-lg border border-slate-200">
                            <div class="w-7 h-7 bg-slate-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-3.5 h-3.5 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <div class="text-xs text-slate-600">Estimasi Selesai</div>
                                <div class="text-sm font-bold text-slate-800" id="end-time">16:00</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Admin Access -->
                <div class="bg-slate-800 rounded-xl p-2.5 lg:p-3 text-white">
                    <div class="flex items-center mb-2">
                        <div class="w-7 h-7 bg-slate-700 rounded-lg flex items-center justify-center mr-2 flex-shrink-0">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-xs lg:text-sm">Admin Access</h3>
                            <p class="text-slate-400 text-xs">Masukkan kode akses</p>
                        </div>
                    </div>
                    
                    <form action="<?php echo e(route('maintenance.access')); ?>" method="POST" class="space-y-2">
                        <?php echo csrf_field(); ?>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                </svg>
                            </div>
                            <input type="password" 
                                   name="access_code" 
                                   placeholder="Masukkan kode"
                                   class="w-full pl-10 pr-10 py-2 text-sm bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:border-slate-500 transition-all"
                                   required>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <button type="button" onclick="togglePassword()" class="text-slate-400 hover:text-white transition-colors">
                                    <svg id="eye-icon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <?php $__errorArgs = ['access_code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="bg-red-900/30 border border-red-700/50 rounded-lg p-2 flex items-center">
                            <svg class="w-4 h-4 text-red-400 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-red-200 text-xs"><?php echo e($message); ?></span>
                        </div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        
                        <button type="submit" class="btn-primary w-full py-2 px-4 rounded-lg text-white font-semibold text-sm flex items-center justify-center space-x-2 shadow-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path>
                            </svg>
                            <span>Akses Sistem</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Status Cards -->
            <div class="grid grid-cols-3 gap-2 mb-2">
                <div class="bg-white rounded-lg p-2 text-center border border-slate-200">
                    <div class="w-6 h-6 lg:w-7 lg:h-7 bg-slate-100 rounded-lg flex items-center justify-center mx-auto mb-1">
                        <svg class="w-3 h-3 lg:w-3.5 lg:h-3.5 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path>
                        </svg>
                    </div>
                    <p class="text-xs font-semibold text-slate-800">Status</p>
                    <p class="text-xs text-slate-600">Maintenance</p>
                </div>
                
                <div class="bg-white rounded-lg p-2 text-center border border-slate-200">
                    <div class="w-6 h-6 lg:w-7 lg:h-7 bg-slate-100 rounded-lg flex items-center justify-center mx-auto mb-1">
                        <svg class="w-3 h-3 lg:w-3.5 lg:h-3.5 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <p class="text-xs font-semibold text-slate-800">Durasi</p>
                    <p class="text-xs text-slate-600">~2 Jam</p>
                </div>
                
                <div class="bg-white rounded-lg p-2 text-center border border-slate-200">
                    <div class="w-6 h-6 lg:w-7 lg:h-7 bg-slate-100 rounded-lg flex items-center justify-center mx-auto mb-1">
                        <svg class="w-3 h-3 lg:w-3.5 lg:h-3.5 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <p class="text-xs font-semibold text-slate-800">Tim</p>
                    <p class="text-xs text-slate-600">IT Support</p>
                </div>
            </div>

            <!-- Contact Info -->
            <div class="text-center pt-2 border-t border-slate-200">
                <p class="text-xs text-slate-600 mb-1.5">Butuh bantuan? Hubungi support kami</p>
                <div class="flex flex-col sm:flex-row justify-center items-center gap-1.5 text-xs">
                    <a href="mailto:rickysilaban384@gmail.com" class="text-slate-700 hover:text-slate-900 transition-colors font-medium flex items-center space-x-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <span>Email Support</span>
                    </a>
                    <span class="hidden sm:inline text-slate-300">•</span>
                    <a href="tel:+627818894504" class="text-slate-700 hover:text-slate-900 transition-colors font-medium flex items-center space-x-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        <span>Telepon Support</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.querySelector('input[name="access_code"]');
            const icon = document.getElementById('eye-icon');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>';
            } else {
                input.type = 'password';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>';
            }
        }

        function startCountdown() {
            const now = new Date();
            const endTime = new Date(now.getTime() + (2 * 60 * 60 * 1000));
            const startTime = now.getTime();
            const totalDuration = endTime - startTime;
            
            document.getElementById('start-time').textContent = 
                now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
            document.getElementById('end-time').textContent = 
                endTime.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
            
            function updateCountdown() {
                const now = new Date().getTime();
                const timeLeft = endTime - now;
                
                if (timeLeft > 0) {
                    const hours = Math.floor(timeLeft / (1000 * 60 * 60));
                    const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);
                    
                    document.getElementById('countdown-hours').textContent = hours.toString().padStart(2, '0');
                    document.getElementById('countdown-minutes').textContent = minutes.toString().padStart(2, '0');
                    document.getElementById('countdown-seconds').textContent = seconds.toString().padStart(2, '0');
                    
                    const progress = ((totalDuration - timeLeft) / totalDuration) * 100;
                    document.getElementById('progress-bar').style.width = `${progress}%`;
                } else {
                    document.getElementById('countdown-hours').textContent = '00';
                    document.getElementById('countdown-minutes').textContent = '00';
                    document.getElementById('countdown-seconds').textContent = '00';
                    document.getElementById('progress-bar').style.width = '100%';
                }
            }
            
            updateCountdown();
            setInterval(updateCountdown, 1000);
        }

        document.addEventListener('DOMContentLoaded', startCountdown);
    </script>
</body>
</html><?php /**PATH C:\Users\user\siakad-sma\resources\views/maintenance.blade.php ENDPATH**/ ?>