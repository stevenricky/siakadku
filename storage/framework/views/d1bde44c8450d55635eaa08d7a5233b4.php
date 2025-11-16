<div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Maintenance Mode</h2>
        <div class="flex items-center space-x-4">
            <span class="text-sm font-medium <?php echo e($maintenanceMode ? 'text-red-600' : 'text-green-600'); ?>">
                <?php echo e($maintenanceMode ? '🟢 Maintenance Aktif' : '⚫ Maintenance Nonaktif'); ?>

            </span>
        </div>
    </div>

    <!-- Status Maintenance -->
    <div class="bg-<?php echo e($maintenanceMode ? 'red' : 'gray'); ?>-50 dark:bg-<?php echo e($maintenanceMode ? 'red' : 'gray'); ?>-900/20 border border-<?php echo e($maintenanceMode ? 'red' : 'gray'); ?>-200 dark:border-<?php echo e($maintenanceMode ? 'red' : 'gray'); ?>-700 rounded-lg p-4 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-<?php echo e($maintenanceMode ? 'red' : 'gray'); ?>-800 dark:text-<?php echo e($maintenanceMode ? 'red' : 'gray'); ?>-200">
                    Status Maintenance Mode
                </h3>
                <p class="text-<?php echo e($maintenanceMode ? 'red' : 'gray'); ?>-600 dark:text-<?php echo e($maintenanceMode ? 'red' : 'gray'); ?>-400 mt-1">
                    <!--[if BLOCK]><![endif]--><?php if($maintenanceMode): ?>
                        Sistem sedang dalam mode maintenance. Hanya admin yang bisa mengakses dengan kode khusus.
                    <?php else: ?>
                        Sistem berjalan normal. Semua user bisa mengakses aplikasi.
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </p>
                
                <!--[if BLOCK]><![endif]--><?php if($maintenanceMode): ?>
                <div class="mt-3 grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-play-circle text-green-500"></i>
                        <div>
                            <div class="font-medium text-gray-700 dark:text-gray-300">Mulai</div>
                            <div class="text-gray-600 dark:text-gray-400"><?php echo e(\Carbon\Carbon::parse($maintenanceStart)->translatedFormat('d M Y H:i')); ?></div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-flag-checkered text-blue-500"></i>
                        <div>
                            <div class="font-medium text-gray-700 dark:text-gray-300">Perkiraan Selesai</div>
                            <div class="text-gray-600 dark:text-gray-400"><?php echo e(\Carbon\Carbon::parse($maintenanceEnd)->translatedFormat('d M Y H:i')); ?></div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-clock text-orange-500"></i>
                        <div>
                            <div class="font-medium text-gray-700 dark:text-gray-300">Sisa Waktu</div>
                            <div class="text-gray-600 dark:text-gray-400"><?php echo e($this->getTimeRemaining()); ?></div>
                        </div>
                    </div>
                </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>
            <button wire:click="toggleMaintenance" 
                    wire:loading.attr="disabled"
                    wire:target="toggleMaintenance"
                    class="px-4 py-2 rounded-lg font-medium transition-colors <?php echo e($maintenanceMode ? 'bg-green-600 hover:bg-green-700 text-white' : 'bg-red-600 hover:bg-red-700 text-white'); ?> disabled:opacity-50">
                <span wire:loading.remove wire:target="toggleMaintenance">
                    <?php echo e($maintenanceMode ? 'Nonaktifkan Maintenance' : 'Aktifkan Maintenance'); ?>

                </span>
                <span wire:loading wire:target="toggleMaintenance">
                    <i class="fas fa-spinner fa-spin"></i> Memproses...
                </span>
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Jadwal Maintenance -->
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Jadwal Maintenance</h3>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Judul Maintenance
                    </label>
                    <input type="text" 
                           wire:model="maintenanceTitle"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                           placeholder="Contoh: Upgrade Server, Maintenance Rutin, dll">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Waktu Mulai
                        </label>
                        <input type="datetime-local" 
                               wire:model="maintenanceStart"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Perkiraan Selesai
                        </label>
                        <input type="datetime-local" 
                               wire:model="maintenanceEnd"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    </div>
                </div>

                <!-- Info Durasi -->
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg p-3">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-blue-700 dark:text-blue-300 font-medium">Perkiraan Durasi:</span>
                        <span class="text-blue-600 dark:text-blue-400"><?php echo e($this->getEstimatedDuration()); ?></span>
                    </div>
                </div>
                
                <button wire:click="updateSchedule" 
                        wire:loading.attr="disabled"
                        wire:target="updateSchedule"
                        class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium disabled:opacity-50">
                    <span wire:loading.remove wire:target="updateSchedule">
                        <i class="fas fa-calendar-check mr-2"></i>Simpan Jadwal
                    </span>
                    <span wire:loading wire:target="updateSchedule">
                        <i class="fas fa-spinner fa-spin"></i> Menyimpan...
                    </span>
                </button>
            </div>
        </div>

        <!-- Pesan Maintenance -->
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Pesan Maintenance</h3>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Pesan yang Ditampilkan
                    </label>
                    <textarea wire:model="maintenanceMessage" 
                              rows="4"
                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                              placeholder="Pesan yang akan ditampilkan saat maintenance mode aktif"></textarea>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                        Pesan ini akan ditampilkan kepada user saat mencoba mengakses sistem
                    </p>
                </div>
                
                <button wire:click="updateMessage" 
                        wire:loading.attr="disabled"
                        wire:target="updateMessage"
                        class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium disabled:opacity-50">
                    <span wire:loading.remove wire:target="updateMessage">
                        <i class="fas fa-save mr-2"></i>Simpan Pesan
                    </span>
                    <span wire:loading wire:target="updateMessage">
                        <i class="fas fa-spinner fa-spin"></i> Menyimpan...
                    </span>
                </button>
            </div>
        </div>
    </div>

    <!-- Informasi Real-time -->
    <div class="mt-6 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h4 class="font-bold text-lg mb-2">Informasi Real-time</h4>
                <p class="text-blue-100">Status maintenance akan diperbarui secara otomatis</p>
            </div>
            <div class="text-right">
                <div class="text-2xl font-bold"><?php echo e(now()->translatedFormat('H:i:s')); ?></div>
                <div class="text-blue-100 text-sm"><?php echo e(now()->translatedFormat('l, d F Y')); ?></div>
            </div>
        </div>
    </div>

    <!-- Informasi Cara Kerja -->
    <div class="mt-6 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg p-4">
        <h4 class="font-semibold text-blue-800 dark:text-blue-200 mb-2">Cara Kerja Maintenance Mode:</h4>
        <ul class="text-blue-700 dark:text-blue-300 text-sm space-y-1">
            <li>• Saat diaktifkan, semua user (kecuali admin) akan dialihkan ke halaman maintenance</li>
            <li>• Admin bisa login dengan kode akses khusus di halaman maintenance</li>
            <li>• Guru dan siswa tidak bisa mengakses sistem selama maintenance</li>
            <li>• Waktu maintenance dapat dijadwalkan sebelumnya</li>
            <li>• API requests akan menerima response 503 Service Unavailable</li>
        </ul>
    </div>

    <!-- Auto-refresh script -->
    <script>
        // Auto-refresh waktu setiap detik
        function updateClock() {
            const now = new Date();
            document.querySelector('[wire\\:key="current-time"]').textContent = 
                now.toLocaleTimeString('id-ID');
        }
        
        setInterval(updateClock, 1000);
        
        // Auto-refresh komponen setiap 30 detik untuk update sisa waktu
        setInterval(() => {
            Livewire.dispatch('refresh');
        }, 30000);
    </script>
</div><?php /**PATH C:\Users\user\siakad-sma\resources\views/livewire/admin/maintenance-setting.blade.php ENDPATH**/ ?>