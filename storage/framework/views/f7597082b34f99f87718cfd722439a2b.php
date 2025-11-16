<div class="p-4 sm:p-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">Backup & Restore Database</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Kelola backup dan restore data sistem</p>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-3 items-start sm:items-center w-full sm:w-auto">
            <!-- Backup Type Selection -->
            <div class="bg-white dark:bg-gray-700 p-3 rounded-lg border border-gray-200 dark:border-gray-600 w-full sm:w-auto">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tipe Backup:</label>
                <div class="flex flex-col space-y-2">
                    <label class="inline-flex items-center">
                        <input type="radio" wire:model="backupType" value="json" class="text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                            JSON - Untuk migrasi data
                        </span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" wire:model="backupType" value="sql" class="text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                            SQL - Data saja (aman untuk restore)
                        </span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" wire:model="backupType" value="sql_full" class="text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                            SQL Full - Struktur + Data (IF NOT EXISTS)
                        </span>
                    </label>
                </div>
            </div>

            <button 
                wire:click="createBackup" 
                wire:loading.attr="disabled"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center disabled:opacity-50 transition-colors w-full sm:w-auto justify-center"
            >
                <svg wire:loading.remove wire:target="createBackup" class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                </svg>
                <svg wire:loading wire:target="createBackup" class="animate-spin w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span wire:loading.remove wire:target="createBackup">
                    Buat Backup 
                    <!--[if BLOCK]><![endif]--><?php if($backupType === 'sql_full'): ?>
                        (SQL Full)
                    <?php elseif($backupType === 'sql'): ?>
                        (SQL Data)
                    <?php else: ?>
                        (JSON)
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </span>
                <span wire:loading wire:target="createBackup">Membuat Backup...</span>
            </button>
        </div>
    </div>

    <!-- Flash Messages -->
    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = ['success' => 'green', 'error' => 'red', 'info' => 'blue']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type => $color): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <!--[if BLOCK]><![endif]--><?php if(session()->has($type)): ?>
            <div 
                x-data="{ show: true }" 
                x-show="show" 
                x-init="setTimeout(() => show = false, 5000)" 
                class="bg-<?php echo e($color); ?>-100 border border-<?php echo e($color); ?>-400 text-<?php echo e($color); ?>-700 px-4 py-3 rounded-lg mb-6 flex items-center justify-between"
            >
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path 
                            stroke-linecap="round" 
                            stroke-linejoin="round" 
                            stroke-width="2" 
                            d="<?php if($type=='success'): ?>M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z
                            <?php elseif($type=='error'): ?>M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z
                            <?php else: ?> M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z <?php endif; ?>"
                        />
                    </svg>
                    <span><?php echo e(session($type)); ?></span>
                </div>
                <button @click="show = false" class="text-<?php echo e($color); ?>-700 hover:text-<?php echo e($color); ?>-900">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->

    <!-- Daftar Backup -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
        <div class="p-4 sm:p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Daftar Backup Tersedia</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                <!--[if BLOCK]><![endif]--><?php if($loadingBackups): ?>
                    Memuat daftar backup...
                <?php else: ?>
                    Total <?php echo e(count($backups)); ?> backup ditemukan
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </p>
        </div>
        
        <div class="p-4 sm:p-6">
            <!--[if BLOCK]><![endif]--><?php if($loadingBackups): ?>
                <div class="text-center py-8">
                    <svg class="animate-spin w-8 h-8 mx-auto text-blue-600" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p class="mt-4 text-gray-500 dark:text-gray-400">Memuat daftar backup...</p>
                </div>
            <?php elseif(count($backups) > 0): ?>
                <!-- Desktop Table View -->
                <div class="hidden lg:block overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nama File</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tipe</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ukuran</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal Dibuat</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $backups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $backup): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-4 py-4 flex items-center">
                                        <!--[if BLOCK]><![endif]--><?php if(($backup['type'] ?? '') === 'json'): ?>
                                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        <?php elseif(($backup['type'] ?? '') === 'sql'): ?>
                                            <svg class="w-5 h-5 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/>
                                            </svg>
                                        <?php else: ?>
                                            <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        <span class="text-sm font-medium text-gray-900 dark:text-white"><?php echo e($backup['name']); ?></span>
                                    </td>
                                    <td class="px-4 py-4">
                                        <!--[if BLOCK]><![endif]--><?php if(($backup['type'] ?? '') === 'json'): ?>
                                            <span class="inline-flex items-center px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full dark:bg-green-900 dark:text-green-200">
                                                JSON
                                            </span>
                                        <?php elseif(($backup['type'] ?? '') === 'sql'): ?>
                                            <!--[if BLOCK]><![endif]--><?php if(str_contains($backup['name'], 'full_backup')): ?>
                                                <span class="inline-flex items-center px-2 py-1 bg-purple-100 text-purple-800 text-xs font-medium rounded-full dark:bg-purple-900 dark:text-purple-200">
                                                    SQL Full
                                                </span>
                                            <?php else: ?>
                                                <span class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full dark:bg-blue-900 dark:text-blue-200">
                                                    SQL Data
                                                </span>
                                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        <?php else: ?>
                                            <span class="inline-flex items-center px-2 py-1 bg-gray-100 text-gray-800 text-xs font-medium rounded-full dark:bg-gray-700 dark:text-gray-300">
                                                <?php echo e(strtoupper($backup['type'] ?? 'UNKNOWN')); ?>

                                            </span>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-900 dark:text-white"><?php echo e($backup['size']); ?></td>
                                    <td class="px-4 py-4 text-sm text-gray-900 dark:text-white"><?php echo e(\Carbon\Carbon::parse($backup['date'])->translatedFormat('d M Y H:i')); ?></td>
                                    <td class="px-4 py-4">
                                        <div class="flex items-center space-x-2">
                                            <button 
                                                wire:click="downloadBackup('<?php echo e($backup['name']); ?>')"
                                                class="inline-flex items-center px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-sm rounded-lg transition-colors"
                                            >
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                                </svg>
                                                Download
                                            </button>
                                            
                                            <button 
                                                wire:click="restoreBackup('<?php echo e($backup['name']); ?>')"
                                                class="inline-flex items-center px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition-colors"
                                            >
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                                </svg>
                                                Restore
                                            </button>

                                            <button 
                                                x-on:click="if (confirm('Yakin ingin menghapus backup <?php echo e($backup['name']); ?>?')) { $wire.deleteBackup('<?php echo e($backup['name']); ?>') }"
                                                class="inline-flex items-center px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-sm rounded-lg transition-colors"
                                            >
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                                Hapus
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <div class="lg:hidden space-y-4">
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $backups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $backup): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center">
                                    <!--[if BLOCK]><![endif]--><?php if(($backup['type'] ?? '') === 'json'): ?>
                                        <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    <?php elseif(($backup['type'] ?? '') === 'sql'): ?>
                                        <svg class="w-5 h-5 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/>
                                        </svg>
                                    <?php else: ?>
                                        <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900 dark:text-white truncate max-w-[200px]"><?php echo e($backup['name']); ?></h4>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1"><?php echo e(\Carbon\Carbon::parse($backup['date'])->translatedFormat('d M Y H:i')); ?></p>
                                    </div>
                                </div>
                                <!--[if BLOCK]><![endif]--><?php if(($backup['type'] ?? '') === 'json'): ?>
                                    <span class="inline-flex items-center px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full dark:bg-green-900 dark:text-green-200">
                                        JSON
                                    </span>
                                <?php elseif(($backup['type'] ?? '') === 'sql'): ?>
                                    <!--[if BLOCK]><![endif]--><?php if(str_contains($backup['name'], 'full_backup')): ?>
                                        <span class="inline-flex items-center px-2 py-1 bg-purple-100 text-purple-800 text-xs font-medium rounded-full dark:bg-purple-900 dark:text-purple-200">
                                            SQL Full
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full dark:bg-blue-900 dark:text-blue-200">
                                            SQL Data
                                        </span>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2 py-1 bg-gray-100 text-gray-800 text-xs font-medium rounded-full dark:bg-gray-700 dark:text-gray-300">
                                        <?php echo e(strtoupper($backup['type'] ?? 'UNKNOWN')); ?>

                                    </span>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <p class="text-sm text-gray-600 dark:text-gray-400">Ukuran: <?php echo e($backup['size']); ?></p>
                                <div class="flex items-center space-x-2">
                                    <button 
                                        wire:click="downloadBackup('<?php echo e($backup['name']); ?>')"
                                        class="inline-flex items-center p-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors"
                                        title="Download"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                        </svg>
                                    </button>
                                    
                                    <button 
                                        wire:click="restoreBackup('<?php echo e($backup['name']); ?>')"
                                        class="inline-flex items-center p-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors"
                                        title="Restore"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                        </svg>
                                    </button>

                                    <button 
                                        x-on:click="if (confirm('Yakin ingin menghapus backup <?php echo e($backup['name']); ?>?')) { $wire.deleteBackup('<?php echo e($backup['name']); ?>') }"
                                        class="inline-flex items-center p-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors"
                                        title="Hapus"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            <?php else: ?>
                <div class="text-center py-8">
                    <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="mt-4 text-gray-500 dark:text-gray-400">Belum ada backup yang tersedia</p>
                    <p class="text-sm text-gray-400 mt-1">Klik tombol "Buat Backup" untuk membuat backup pertama</p>
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>
    </div>

    <!-- Informasi -->
    <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4 sm:p-6">
        <h3 class="text-lg font-semibold text-yellow-800 dark:text-yellow-400 mb-3 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
            </svg>
            Informasi Penting
        </h3>
        <ul class="text-yellow-700 dark:text-yellow-300 space-y-2 text-sm">
            <li>• <strong>JSON Backup</strong>: Format mudah dibaca, cocok untuk migrasi data antar sistem</li>
            <li>• <strong>SQL Data Only</strong>: Hanya backup data, aman untuk restore ke database yang sudah ada</li>
            <li>• <strong>SQL Full</strong>: Backup struktur + data dengan CREATE TABLE IF NOT EXISTS untuk keamanan</li>
            <li>• Backup akan menyimpan seluruh data database termasuk siswa, guru, nilai, dan transaksi</li>
            <li>• Pastikan storage server memiliki cukup space sebelum membuat backup</li>
            <li>• Disarankan melakukan backup secara berkala (minimal seminggu sekali)</li>
            <li>• Simpan backup di lokasi yang aman dan terpisah dari server</li>
        </ul>
    </div>
</div><?php /**PATH C:\Users\user\siakad-sma\resources\views/livewire/admin/backup-restore.blade.php ENDPATH**/ ?>