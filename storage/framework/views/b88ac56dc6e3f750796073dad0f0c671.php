<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['role']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['role']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div x-data="sidebar" x-cloak>
    <!-- HAPUS SEMUA TOMBOL TOGGLE - semuanya dipindah ke header -->

    <!-- Sidebar Overlay -->
    <div x-show="mobileOpen" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="toggleMobile()" 
         class="fixed inset-0 bg-gray-600 bg-opacity-50 z-40 lg:hidden"
         style="display: none;">
    </div>

    <!-- Sidebar -->
    <div :class="{
        'w-64': !collapsed,
        'w-20': collapsed,
        '-translate-x-full lg:translate-x-0': !mobileOpen,
        'translate-x-0': mobileOpen
    }" 
        class="fixed inset-y-0 left-0 z-50 bg-white dark:bg-gray-800 shadow-xl transform transition-all duration-300 ease-in-out flex flex-col overflow-hidden">
        
        <!-- Header Sidebar - TANPA TOMBOL TOGGLE -->
        <div class="flex items-center justify-between h-16 px-4 border-b border-gray-200 dark:border-gray-700 shrink-0">
            <div class="flex items-center min-w-0 flex-1">
                <!-- Logo Sekolah -->
                <?php
                    use App\Helpers\SettingsHelper;
                    $schoolLogo = SettingsHelper::schoolLogo();
                    $schoolName = SettingsHelper::schoolName();
                ?>
                
                <?php if($schoolLogo): ?>
                <div class="shrink-0 w-8 h-8 rounded-lg flex items-center justify-center overflow-hidden">
                    <img src="<?php echo e($schoolLogo); ?>" 
                         alt="Logo <?php echo e($schoolName); ?>"
                         class="w-full h-full object-contain"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="w-8 h-8 bg-primary-600 rounded-lg flex items-center justify-center hidden">
                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                        </svg>
                    </div>
                </div>
                <?php else: ?>
                <div class="shrink-0 w-8 h-8 bg-primary-600 rounded-lg flex items-center justify-center">
                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                    </svg>
                </div>
                <?php endif; ?>
                
                <!-- Text Content -->
                <div class="ml-3 transition-all duration-300 overflow-hidden flex-1 min-w-0"
                     :class="collapsed ? 'max-w-0 opacity-0' : 'max-w-[160px] opacity-100'">
                    <h1 class="text-lg font-semibold text-gray-900 dark:text-white whitespace-nowrap truncate">
                        <?php echo e($schoolName); ?>

                    </h1>
                    <p class="text-xs text-gray-500 dark:text-gray-400 capitalize whitespace-nowrap truncate"><?php echo e($role); ?></p>
                </div>
            </div>
            
            <!-- HAPUS: Toggle Buttons section dihapus -->
        </div>
        
       <!-- Navigation --> <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto"> <?php if($role === 'admin'): ?> <!-- ==================== --> <!-- ADMIN NAVIGATION --> <!-- ==================== --> <a href="<?php echo e(route('admin.dashboard')); ?>" @click="closeMobile()" class="flex items-center px-3 py-3 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('admin.dashboard') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>" :class="collapsed ? 'justify-center' : ''"> <!-- PERBAIKAN: Container icon dengan penyesuaian margin --> <div class="w-5 h-5 flex items-center justify-center shrink-0" :class="collapsed ? '' : 'mr-3'"> <i class="fas fa-tachometer-alt text-center" style="width: 20px;"></i> </div> <span class="transition-all duration-300 whitespace-nowrap overflow-hidden" :class="collapsed ? 'w-0 opacity-0' : 'w-auto opacity-100'"> Dashboard Admin </span> </a>
                
                <!-- 🗄️ Data Master Section -->
                <div x-data="{ open: <?php echo e(request()->routeIs('admin.guru.*', 'admin.siswa.*', 'admin.kelas.*', 'admin.mapel.*', 'admin.tahun-ajaran.*', 'admin.semester.*', 'admin.ruangan.*', 'admin.ekstrakurikuler.*') ? 'true' : 'false'); ?> }" class="pt-4">
                    <button @click="open = !open" 
                            class="flex items-center justify-between w-full px-3 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200"
                            :class="collapsed ? 'justify-center' : ''">
                        <div class="flex items-center">
                            <i class="fas fa-database w-5 h-5 shrink-0"></i>
                            <span class="ml-3 transition-all duration-300 whitespace-nowrap overflow-hidden"
                                  :class="collapsed ? 'w-0 opacity-0' : 'w-auto opacity-100'">
                                 Data Master
                            </span>
                        </div>
                        <svg class="h-4 w-4 transition-transform duration-200 shrink-0" 
                             :class="{ 'rotate-180': open, 'hidden': collapsed }" 
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    
                    <div x-show="open" x-collapse 
                         class="mt-1 space-y-1 pl-8 border-l-2 border-gray-200 dark:border-gray-700"
                         :class="collapsed ? 'hidden' : ''">
                        <a href="<?php echo e(route('admin.guru.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('admin.guru.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-users w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Data Guru</span>
                        </a>

                        <a href="<?php echo e(route('admin.siswa.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('admin.siswa.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-user-graduate w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Data Siswa</span>
                        </a>
                        
                        <a href="<?php echo e(route('admin.kelas.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('admin.kelas.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-chalkboard-teacher w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Kelas & Jurusan</span>
                        </a>
                        
                        <a href="<?php echo e(route('admin.mapel.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('admin.mapel.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-book-open w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Mata Pelajaran</span>
                        </a>

                        <a href="<?php echo e(route('admin.tahun-ajaran.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('admin.tahun-ajaran.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-calendar w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Tahun Ajaran</span>
                        </a>

                        <a href="<?php echo e(route('admin.semester.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('admin.semester.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-calendar-alt w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Semester</span>
                        </a>

                        <a href="<?php echo e(route('admin.ruangan.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('admin.ruangan.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-building w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Ruangan / Gedung</span>
                        </a>

                        <a href="<?php echo e(route('admin.ekstrakurikuler.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('admin.ekstrakurikuler.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-football-ball w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Ekstrakurikuler</span>
                        </a>
                    </div>
                </div>

                <!-- 💰 Keuangan & SPP -->
                <div x-data="{ open: <?php echo e(request()->routeIs('admin.spp.*', 'admin.pembayaran.*', 'admin.tagihan.*', 'admin.biaya.*') ? 'true' : 'false'); ?> }" class="pt-2">
                    <button @click="open = !open" 
                            class="flex items-center justify-between w-full px-3 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200"
                            :class="collapsed ? 'justify-center' : ''">
                        <div class="flex items-center">
                            <i class="fas fa-money-bill-wave w-5 h-5 shrink-0"></i>
                            <span class="ml-3 transition-all duration-300 whitespace-nowrap overflow-hidden"
                                  :class="collapsed ? 'w-0 opacity-0' : 'w-auto opacity-100'">
                                 Keuangan & SPP
                            </span>
                        </div>
                        <svg class="h-4 w-4 transition-transform duration-200 shrink-0" 
                             :class="{ 'rotate-180': open, 'hidden': collapsed }" 
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    
                    <div x-show="open" x-collapse 
                         class="mt-1 space-y-1 pl-8 border-l-2 border-gray-200 dark:border-gray-700"
                         :class="collapsed ? 'hidden' : ''">
                        <a href="<?php echo e(route('admin.spp.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('admin.spp.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-cog w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Pengaturan SPP</span>
                        </a>
                        
                        <a href="<?php echo e(route('admin.tagihan.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('admin.tagihan.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-file-invoice w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Tagihan SPP</span>
                        </a>
                        
                        <a href="<?php echo e(route('admin.pembayaran.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('admin.pembayaran.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-credit-card w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Pembayaran SPP</span>
                        </a>
                        
                        <a href="<?php echo e(route('admin.biaya.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('admin.biaya.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-list-alt w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Kategori Biaya</span>
                        </a>
                    </div>
                </div>

                <!-- 📚 Perpustakaan -->
                <div x-data="{ open: <?php echo e(request()->routeIs('admin.perpustakaan.*', 'admin.buku.*', 'admin.peminjaman.*', 'admin.kategori-buku.*') ? 'true' : 'false'); ?> }" class="pt-2">
                    <button @click="open = !open" 
                            class="flex items-center justify-between w-full px-3 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200"
                            :class="collapsed ? 'justify-center' : ''">
                        <div class="flex items-center">
                            <i class="fas fa-book w-5 h-5 shrink-0"></i>
                            <span class="ml-3 transition-all duration-300 whitespace-nowrap overflow-hidden"
                                  :class="collapsed ? 'w-0 opacity-0' : 'w-auto opacity-100'">
                                 Perpustakaan
                            </span>
                        </div>
                        <svg class="h-4 w-4 transition-transform duration-200 shrink-0" 
                             :class="{ 'rotate-180': open, 'hidden': collapsed }" 
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    
                    <div x-show="open" x-collapse 
                         class="mt-1 space-y-1 pl-8 border-l-2 border-gray-200 dark:border-gray-700"
                         :class="collapsed ? 'hidden' : ''">
                        <!-- Data Buku -->
                        <a href="<?php echo e(route('admin.buku.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('admin.buku.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-book-open w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Data Buku</span>
                        </a>
                        
                        <!-- Kategori Buku -->
                        <a href="<?php echo e(route('admin.kategori-buku.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('admin.kategori-buku.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-tags w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Kategori Buku</span>
                        </a>
                        
                        <!-- Peminjaman Buku -->
                        <a href="<?php echo e(route('admin.peminjaman.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('admin.peminjaman.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-exchange-alt w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Peminjaman Buku</span>
                        </a>
                        
                        <!-- Inventaris Perpustakaan -->
                        <a href="<?php echo e(route('admin.inventaris-perpustakaan.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('admin.inventaris-perpustakaan.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-clipboard-list w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Inventaris</span>
                        </a>
                        
                        <!-- Laporan Peminjaman -->
                        <a href="<?php echo e(route('admin.laporan-peminjaman.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('admin.laporan-peminjaman.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-chart-bar w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Laporan</span>
                        </a>
                    </div>
                </div>

                <!-- 🏢 Inventaris Sekolah -->
                <div x-data="{ open: <?php echo e(request()->routeIs('admin.inventaris.*', 'admin.pemeliharaan.*') ? 'true' : 'false'); ?> }" class="pt-2">
                    <button @click="open = !open" 
                            class="flex items-center justify-between w-full px-3 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200"
                            :class="collapsed ? 'justify-center' : ''">
                        <div class="flex items-center">
                            <i class="fas fa-laptop-house w-5 h-5 shrink-0"></i>
                            <span class="ml-3 transition-all duration-300 whitespace-nowrap overflow-hidden"
                                  :class="collapsed ? 'w-0 opacity-0' : 'w-auto opacity-100'">
                                 Inventaris Sekolah
                            </span>
                        </div>
                        <svg class="h-4 w-4 transition-transform duration-200 shrink-0" 
                             :class="{ 'rotate-180': open, 'hidden': collapsed }" 
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    
                    <div x-show="open" x-collapse 
                         class="mt-1 space-y-1 pl-8 border-l-2 border-gray-200 dark:border-gray-700"
                         :class="collapsed ? 'hidden' : ''">
                        <a href="<?php echo e(route('admin.inventaris.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('admin.inventaris.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-boxes w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Data Inventaris</span>
                        </a>
                        
                        <a href="<?php echo e(route('admin.pemeliharaan.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('admin.pemeliharaan.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-tools w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Pemeliharaan</span>
                        </a>
                    </div>
                </div>

                <!-- 🏆 Ekstrakurikuler & Prestasi -->
                <div x-data="{ open: <?php echo e(request()->routeIs('admin.pendaftaran-ekskul.*', 'admin.prestasi.*', 'admin.kegiatan-ekskul.*') ? 'true' : 'false'); ?> }" class="pt-2">
                    <button @click="open = !open" 
                            class="flex items-center justify-between w-full px-3 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200"
                            :class="collapsed ? 'justify-center' : ''">
                        <div class="flex items-center">
                            <i class="fas fa-trophy w-5 h-5 shrink-0"></i>
                            <span class="ml-3 transition-all duration-300 whitespace-nowrap overflow-hidden"
                                  :class="collapsed ? 'w-0 opacity-0' : 'w-auto opacity-100'">
                                 Ekskul & Prestasi
                            </span>
                        </div>
                        <svg class="h-4 w-4 transition-transform duration-200 shrink-0" 
                             :class="{ 'rotate-180': open, 'hidden': collapsed }" 
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    
                    <div x-show="open" x-collapse 
                         class="mt-1 space-y-1 pl-8 border-l-2 border-gray-200 dark:border-gray-700"
                         :class="collapsed ? 'hidden' : ''">
                        <a href="<?php echo e(route('admin.pendaftaran-ekskul.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('admin.pendaftaran-ekskul.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-user-plus w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Pendaftaran Ekskul</span>
                        </a>
                        
                        <a href="<?php echo e(route('admin.prestasi.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('admin.prestasi.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-medal w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Prestasi Siswa</span>
                        </a>
                        
                        <a href="<?php echo e(route('admin.kegiatan-ekskul.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('admin.kegiatan-ekskul.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-calendar-check w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Kegiatan Ekskul</span>
                        </a>
                    </div>
                </div>

                <!-- 🧠 Konseling & BK -->
                <div x-data="{ open: <?php echo e(request()->routeIs('admin.konseling.*', 'admin.layanan-bk.*') ? 'true' : 'false'); ?> }" class="pt-2">
                    <button @click="open = !open" 
                            class="flex items-center justify-between w-full px-3 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200"
                            :class="collapsed ? 'justify-center' : ''">
                        <div class="flex items-center">
                            <i class="fas fa-hands-helping w-5 h-5 shrink-0"></i>
                            <span class="ml-3 transition-all duration-300 whitespace-nowrap overflow-hidden"
                                  :class="collapsed ? 'w-0 opacity-0' : 'w-auto opacity-100'">
                                 Konseling & BK
                            </span>
                        </div>
                        <svg class="h-4 w-4 transition-transform duration-200 shrink-0" 
                             :class="{ 'rotate-180': open, 'hidden': collapsed }" 
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    
                    <div x-show="open" x-collapse 
                         class="mt-1 space-y-1 pl-8 border-l-2 border-gray-200 dark:border-gray-700"
                         :class="collapsed ? 'hidden' : ''">
                        <a href="<?php echo e(route('admin.layanan-bk.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('admin.layanan-bk.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-comments w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Layanan BK</span>
                        </a>
                        
                        <a href="<?php echo e(route('admin.konseling.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('admin.konseling.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-calendar-alt w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Jadwal Konseling</span>
                        </a>
                        
                        <a href="<?php echo e(route('admin.catatan-bk.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('admin.catatan-bk.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-sticky-note w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Catatan BK</span>
                        </a>
                    </div>
                </div>

                <!-- 👨‍🎓 Alumni & Karir -->
                <div x-data="{ open: <?php echo e(request()->routeIs('admin.alumni.*', 'admin.tracer-study.*') ? 'true' : 'false'); ?> }" class="pt-2">
                    <button @click="open = !open" 
                            class="flex items-center justify-between w-full px-3 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200"
                            :class="collapsed ? 'justify-center' : ''">
                        <div class="flex items-center">
                            <i class="fas fa-user-graduate w-5 h-5 shrink-0"></i>
                            <span class="ml-3 transition-all duration-300 whitespace-nowrap overflow-hidden"
                                  :class="collapsed ? 'w-0 opacity-0' : 'w-auto opacity-100'">
                                 Alumni & Karir
                            </span>
                        </div>
                        <svg class="h-4 w-4 transition-transform duration-200 shrink-0" 
                             :class="{ 'rotate-180': open, 'hidden': collapsed }" 
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    
                    <div x-show="open" x-collapse 
                         class="mt-1 space-y-1 pl-8 border-l-2 border-gray-200 dark:border-gray-700"
                         :class="collapsed ? 'hidden' : ''">
                        <a href="<?php echo e(route('admin.alumni.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('admin.alumni.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-graduation-cap w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Data Alumni</span>
                        </a>
                        
                        <a href="<?php echo e(route('admin.tracer-study.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('admin.tracer-study.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-chart-line w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Tracer Study</span>
                        </a>
                        
                        <a href="<?php echo e(route('admin.beasiswa.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('admin.beasiswa.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-award w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Beasiswa</span>
                        </a>
                    </div>
                </div>

                <!-- 📋 Sistem Pendukung -->
                <div x-data="{ open: <?php echo e(request()->routeIs('admin.agenda.*', 'admin.surat.*', 'admin.arsip.*') ? 'true' : 'false'); ?> }" class="pt-2">
                    <button @click="open = !open" 
                            class="flex items-center justify-between w-full px-3 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200"
                            :class="collapsed ? 'justify-center' : ''">
                        <div class="flex items-center">
                            <i class="fas fa-clipboard-list w-5 h-5 shrink-0"></i>
                            <span class="ml-3 transition-all duration-300 whitespace-nowrap overflow-hidden"
                                  :class="collapsed ? 'w-0 opacity-0' : 'w-auto opacity-100'">
                                 Sistem Pendukung
                            </span>
                        </div>
                        <svg class="h-4 w-4 transition-transform duration-200 shrink-0" 
                             :class="{ 'rotate-180': open, 'hidden': collapsed }" 
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    
                    <div x-show="open" x-collapse 
                         class="mt-1 space-y-1 pl-8 border-l-2 border-gray-200 dark:border-gray-700"
                         :class="collapsed ? 'hidden' : ''">
                        <a href="<?php echo e(route('admin.agenda.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('admin.agenda.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-calendar-day w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Agenda Sekolah</span>
                        </a>
                        
                        <a href="<?php echo e(route('admin.surat.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('admin.surat.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-envelope w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Manajemen Surat</span>
                        </a>
                        
                        <a href="<?php echo e(route('admin.arsip.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('admin.arsip.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-archive w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Arsip Dokumen</span>
                        </a>
                    </div>
                </div>

                <!--  Akademik Section -->
                <div x-data="{ open: <?php echo e(request()->routeIs('admin.jadwal.*', 'admin.nilai.*') ? 'true' : 'false'); ?> }" class="pt-2">
                    <button @click="open = !open" 
                            class="flex items-center justify-between w-full px-3 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200"
                            :class="collapsed ? 'justify-center' : ''">
                        <div class="flex items-center">
                            <i class="fas fa-graduation-cap w-5 h-5 shrink-0"></i>
                            <span class="ml-3 transition-all duration-300 whitespace-nowrap overflow-hidden"
                                  :class="collapsed ? 'w-0 opacity-0' : 'w-auto opacity-100'">
                                 Akademik
                            </span>
                        </div>
                        <svg class="h-4 w-4 transition-transform duration-200 shrink-0" 
                             :class="{ 'rotate-180': open, 'hidden': collapsed }" 
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    
                    <div x-show="open" x-collapse 
                         class="mt-1 space-y-1 pl-8 border-l-2 border-gray-200 dark:border-gray-700"
                         :class="collapsed ? 'hidden' : ''">
                        <a href="<?php echo e(route('admin.jadwal.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('admin.jadwal.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-calendar-alt w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Jadwal Pelajaran</span>
                        </a>
                        
                        <a href="<?php echo e(route('admin.nilai.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('admin.nilai.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-chart-bar w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Monitoring Nilai</span>
                        </a>
                    </div>
                </div>

                <!-- 💬 Komunikasi & Informasi -->
                <div x-data="{ open: <?php echo e(request()->routeIs('admin.pengumuman.*', 'admin.pesan.*') ? 'true' : 'false'); ?> }" class="pt-2">
                    <button @click="open = !open" 
                            class="flex items-center justify-between w-full px-3 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200"
                            :class="collapsed ? 'justify-center' : ''">
                        <div class="flex items-center">
                            <i class="fas fa-comments w-5 h-5 shrink-0"></i>
                            <span class="ml-3 transition-all duration-300 whitespace-nowrap overflow-hidden"
                                  :class="collapsed ? 'w-0 opacity-0' : 'w-auto opacity-100'">
                                 Komunikasi
                            </span>
                        </div>
                        <svg class="h-4 w-4 transition-transform duration-200 shrink-0" 
                             :class="{ 'rotate-180': open, 'hidden': collapsed }" 
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    
                    <div x-show="open" x-collapse 
                         class="mt-1 space-y-1 pl-8 border-l-2 border-gray-200 dark:border-gray-700"
                         :class="collapsed ? 'hidden' : ''">
                        <a href="<?php echo e(route('admin.pengumuman.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('admin.pengumuman.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-bullhorn w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Pengumuman Sekolah</span>
                        </a>
                        
                        <a href="<?php echo e(route('admin.pesan.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('admin.pesan.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-comment-dots w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Pesan Internal</span>
                        </a>
                    </div>
                </div>

                <!-- 📊 Laporan Section -->
                <div x-data="{ open: <?php echo e(request()->routeIs('admin.laporan.*') ? 'true' : 'false'); ?> }" class="pt-2">
                    <button @click="open = !open" 
                            class="flex items-center justify-between w-full px-3 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200"
                            :class="collapsed ? 'justify-center' : ''">
                        <div class="flex items-center">
                            <i class="fas fa-chart-pie w-5 h-5 shrink-0"></i>
                            <span class="ml-3 transition-all duration-300 whitespace-nowrap overflow-hidden"
                                  :class="collapsed ? 'w-0 opacity-0' : 'w-auto opacity-100'">
                                 Laporan
                            </span>
                        </div>
                        <svg class="h-4 w-4 transition-transform duration-200 shrink-0" 
                             :class="{ 'rotate-180': open, 'hidden': collapsed }" 
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    
                    <div x-show="open" x-collapse 
                         class="mt-1 space-y-1 pl-8 border-l-2 border-gray-200 dark:border-gray-700"
                         :class="collapsed ? 'hidden' : ''">
                        <a href="<?php echo e(route('admin.laporan.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('admin.laporan.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-file-alt w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Laporan Akademik</span>
                        </a>
                        
                        <a href="<?php echo e(route('admin.laporan-keuangan.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('admin.laporan-keuangan.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-chart-line w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Laporan Keuangan</span>
                        </a>
                    </div>
                </div>

                <!-- ⚙️ Manajemen Sistem -->
                <div x-data="{ open: <?php echo e(request()->routeIs('admin.pengguna.*', 'admin.role.*', 'admin.pengaturan.*', 'admin.backup.*', 'admin.log.*') ? 'true' : 'false'); ?> }" class="pt-2">
    <button @click="open = !open" 
            class="flex items-center justify-between w-full px-3 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200"
            :class="collapsed ? 'justify-center' : ''">
        <div class="flex items-center">
            <i class="fas fa-cogs w-5 h-5 shrink-0"></i>
            <span class="ml-3 transition-all duration-300 whitespace-nowrap overflow-hidden"
                  :class="collapsed ? 'w-0 opacity-0' : 'w-auto opacity-100'">
                 Manajemen Sistem
            </span>
        </div>
        <svg class="h-4 w-4 transition-transform duration-200 shrink-0" 
             :class="{ 'rotate-180': open, 'hidden': collapsed }" 
             fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>
                    
                    <div x-show="open" x-collapse 
                         class="mt-1 space-y-1 pl-8 border-l-2 border-gray-200 dark:border-gray-700"
                         :class="collapsed ? 'hidden' : ''">
                        <a href="<?php echo e(route('admin.pengguna.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('admin.pengguna.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-users-cog w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Pengguna & Hak Akses</span>
                        </a>
                        
                        <a href="<?php echo e(route('admin.role.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('admin.role.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-user-shield w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Role & Permission</span>
                        </a>
                        
                        <a href="<?php echo e(route('admin.pengaturan.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('admin.pengaturan.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-sliders-h w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Pengaturan Umum</span>
                        </a>
                        
                        <a href="<?php echo e(route('admin.backup.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('admin.backup.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-database w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Backup & Restore</span>
                        </a>
                        
                        <a href="<?php echo e(route('admin.log.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('admin.log.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-history w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Log Aktivitas</span>
                        </a>

                        <!-- Cari bagian Manajemen Sistem dan ganti link maintenance -->
<a href="<?php echo e(route('admin.maintenance.index')); ?>" @click="closeMobile()"
   class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('admin.maintenance.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
    <i class="fas fa-tools w-4 h-4 shrink-0"></i>
    <span class="ml-3 text-sm whitespace-nowrap">Maintenance Mode</span>
</a>

                    </div>
                </div>

                <!-- 🔗 Integrasi -->
                <div x-data="{ open: <?php echo e(request()->routeIs('admin.api.*', 'admin.dapodik.*') ? 'true' : 'false'); ?> }" class="pt-2">
                    <button @click="open = !open" 
                            class="flex items-center justify-between w-full px-3 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200"
                            :class="collapsed ? 'justify-center' : ''">
                        <div class="flex items-center">
                            <i class="fas fa-link w-5 h-5 shrink-0"></i>
                            <span class="ml-3 transition-all duration-300 whitespace-nowrap overflow-hidden"
                                  :class="collapsed ? 'w-0 opacity-0' : 'w-auto opacity-100'">
                                 Integrasi
                            </span>
                        </div>
                        <svg class="h-4 w-4 transition-transform duration-200 shrink-0" 
                             :class="{ 'rotate-180': open, 'hidden': collapsed }" 
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    
                    <div x-show="open" x-collapse 
                         class="mt-1 space-y-1 pl-8 border-l-2 border-gray-200 dark:border-gray-700"
                         :class="collapsed ? 'hidden' : ''">
                        <a href="<?php echo e(route('admin.api.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('admin.api.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-key w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">API Key Management</span>
                        </a>
                        
                        <a href="<?php echo e(route('admin.dapodik.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('admin.dapodik.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-sync-alt w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Sinkronisasi Dapodik</span>
                        </a>
                    </div>
                </div>

            <?php elseif($role === 'guru'): ?>
                <!-- ==================== -->
                <!-- GURU NAVIGATION -->
<!-- ==================== -->
<a href="<?php echo e(route('guru.dashboard')); ?>" @click="closeMobile()"
   class="flex items-center px-3 py-3 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('guru.dashboard') ? 'bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>"
   :class="collapsed ? 'justify-center' : ''">
   <!-- Container ikon dengan penyesuaian margin -->
   <div class="w-5 h-5 flex items-center justify-center shrink-0" :class="collapsed ? '' : 'mr-3'">
       <i class="fas fa-tachometer-alt text-center" style="width: 20px;"></i>
   </div>
   <span class="transition-all duration-300 whitespace-nowrap overflow-hidden"
         :class="collapsed ? 'w-0 opacity-0' : 'w-auto opacity-100'">
       Dashboard Guru
   </span>
</a>

                <!-- 👨‍🏫 Pengajaran Section -->
                <div x-data="{ open: <?php echo e(request()->routeIs('guru.kelas.*', 'guru.nilai.*', 'guru.absensi.*', 'guru.jadwal.*') ? 'true' : 'false'); ?> }" class="pt-4">
                    <button @click="open = !open" 
                            class="flex items-center justify-between w-full px-3 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200"
                            :class="collapsed ? 'justify-center' : ''">
                        <div class="flex items-center">
                            <i class="fas fa-chalkboard-teacher w-5 h-5 shrink-0"></i>
                            <span class="ml-3 transition-all duration-300 whitespace-nowrap overflow-hidden"
                                  :class="collapsed ? 'w-0 opacity-0' : 'w-auto opacity-100'">
                                Pengajaran
                            </span>
                        </div>
                        <svg class="h-4 w-4 transition-transform duration-200 shrink-0" 
                             :class="{ 'rotate-180': open, 'hidden': collapsed }" 
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    
                    <div x-show="open" x-collapse 
                         class="mt-1 space-y-1 pl-8 border-l-2 border-gray-200 dark:border-gray-700"
                         :class="collapsed ? 'hidden' : ''">
                        <a href="<?php echo e(route('guru.kelas.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('guru.kelas.*') ? 'bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-chalkboard w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Kelas Diampu</span>
                        </a>
                        
                        <a href="<?php echo e(route('guru.nilai.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('guru.nilai.*') ? 'bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-edit w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Input Nilai</span>
                        </a>
                        
                        <a href="<?php echo e(route('guru.absensi.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('guru.absensi.*') ? 'bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-clipboard-check w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Absensi Siswa</span>
                        </a>
                        
                        <a href="<?php echo e(route('guru.jadwal.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('guru.jadwal.*') ? 'bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-calendar-alt w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Jadwal Mengajar</span>
                        </a>
                    </div>
                </div>

                <!-- 📚 Perencanaan -->
                <div x-data="{ open: <?php echo e(request()->routeIs('guru.rpp.*', 'guru.materi.*', 'guru.tugas.*') ? 'true' : 'false'); ?> }" class="pt-2">
                    <button @click="open = !open" 
                            class="flex items-center justify-between w-full px-3 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200"
                            :class="collapsed ? 'justify-center' : ''">
                        <div class="flex items-center">
                            <i class="fas fa-book w-5 h-5 shrink-0"></i>
                            <span class="ml-3 transition-all duration-300 whitespace-nowrap overflow-hidden"
                                  :class="collapsed ? 'w-0 opacity-0' : 'w-auto opacity-100'">
                                 Perencanaan
                            </span>
                        </div>
                        <svg class="h-4 w-4 transition-transform duration-200 shrink-0" 
                             :class="{ 'rotate-180': open, 'hidden': collapsed }" 
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    
                    <div x-show="open" x-collapse 
                         class="mt-1 space-y-1 pl-8 border-l-2 border-gray-200 dark:border-gray-700"
                         :class="collapsed ? 'hidden' : ''">
                        <a href="<?php echo e(route('guru.rpp.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('guru.rpp.*') ? 'bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-file-alt w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">RPP / Modul Ajar</span>
                        </a>
                        
                        <a href="<?php echo e(route('guru.materi.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('guru.materi.*') ? 'bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-book-open w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Materi Pembelajaran</span>
                        </a>
                        
                        <a href="<?php echo e(route('guru.tugas.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('guru.tugas.*') ? 'bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-tasks w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Tugas & Kuis</span>
                        </a>
                    </div>
                </div>

                <!-- 💰 Pembayaran SPP -->
                <div x-data="{ open: <?php echo e(request()->routeIs('guru.tagihan.*', 'guru.pembayaran.*') ? 'true' : 'false'); ?> }" class="pt-2">
                    <button @click="open = !open" 
                            class="flex items-center justify-between w-full px-3 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200"
                            :class="collapsed ? 'justify-center' : ''">
                        <div class="flex items-center">
                            <i class="fas fa-money-check w-5 h-5 shrink-0"></i>
                            <span class="ml-3 transition-all duration-300 whitespace-nowrap overflow-hidden"
                                  :class="collapsed ? 'w-0 opacity-0' : 'w-auto opacity-100'">
                                 Pembayaran SPP
                            </span>
                        </div>
                        <svg class="h-4 w-4 transition-transform duration-200 shrink-0" 
                             :class="{ 'rotate-180': open, 'hidden': collapsed }" 
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    
                    <div x-show="open" x-collapse 
                         class="mt-1 space-y-1 pl-8 border-l-2 border-gray-200 dark:border-gray-700"
                         :class="collapsed ? 'hidden' : ''">
                        <a href="<?php echo e(route('guru.tagihan.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('guru.tagihan.*') ? 'bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-file-invoice w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Tagihan Siswa</span>
                        </a>
                        
                        <a href="<?php echo e(route('guru.pembayaran.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('guru.pembayaran.*') ? 'bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-receipt w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Konfirmasi Bayar</span>
                        </a>
                    </div>
                </div>

                <!-- 📚 Perpustakaan -->
                <div x-data="{ open: <?php echo e(request()->routeIs('guru.peminjaman-buku.*') ? 'true' : 'false'); ?> }" class="pt-2">
                    <button @click="open = !open" 
                            class="flex items-center justify-between w-full px-3 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200"
                            :class="collapsed ? 'justify-center' : ''">
                        <div class="flex items-center">
                            <i class="fas fa-book-reader w-5 h-5 shrink-0"></i>
                            <span class="ml-3 transition-all duration-300 whitespace-nowrap overflow-hidden"
                                  :class="collapsed ? 'w-0 opacity-0' : 'w-auto opacity-100'">
                                 Perpustakaan
                            </span>
                        </div>
                        <svg class="h-4 w-4 transition-transform duration-200 shrink-0" 
                             :class="{ 'rotate-180': open, 'hidden': collapsed }" 
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    
                    <div x-show="open" x-collapse 
                         class="mt-1 space-y-1 pl-8 border-l-2 border-gray-200 dark:border-gray-700"
                         :class="collapsed ? 'hidden' : ''">
                        <a href="<?php echo e(route('guru.peminjaman-buku.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('guru.peminjaman-buku.*') ? 'bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-exchange-alt w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Peminjaman Buku</span>
                        </a>
                    </div>
                </div>

                <!-- 🏢 Inventaris -->
                <div x-data="{ open: <?php echo e(request()->routeIs('guru.peminjaman-inventaris.*') ? 'true' : 'false'); ?> }" class="pt-2">
                    <button @click="open = !open" 
                            class="flex items-center justify-between w-full px-3 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200"
                            :class="collapsed ? 'justify-center' : ''">
                        <div class="flex items-center">
                            <i class="fas fa-toolbox w-5 h-5 shrink-0"></i>
                            <span class="ml-3 transition-all duration-300 whitespace-nowrap overflow-hidden"
                                  :class="collapsed ? 'w-0 opacity-0' : 'w-auto opacity-100'">
                                 Inventaris
                            </span>
                        </div>
                        <svg class="h-4 w-4 transition-transform duration-200 shrink-0" 
                             :class="{ 'rotate-180': open, 'hidden': collapsed }" 
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    
                    <div x-show="open" x-collapse 
                         class="mt-1 space-y-1 pl-8 border-l-2 border-gray-200 dark:border-gray-700"
                         :class="collapsed ? 'hidden' : ''">
                        <a href="<?php echo e(route('guru.peminjaman-inventaris.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('guru.peminjaman-inventaris.*') ? 'bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-laptop-house w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Peminjaman Barang</span>
                        </a>
                    </div>
                </div>

                <!-- 🧠 Konseling -->
                <div x-data="{ open: <?php echo e(request()->routeIs('guru.konseling.*') ? 'true' : 'false'); ?> }" class="pt-2">
                    <button @click="open = !open" 
                            class="flex items-center justify-between w-full px-3 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200"
                            :class="collapsed ? 'justify-center' : ''">
                        <div class="flex items-center">
                            <i class="fas fa-hands-helping w-5 h-5 shrink-0"></i>
                            <span class="ml-3 transition-all duration-300 whitespace-nowrap overflow-hidden"
                                  :class="collapsed ? 'w-0 opacity-0' : 'w-auto opacity-100'">
                                 Konseling
                            </span>
                        </div>
                        <svg class="h-4 w-4 transition-transform duration-200 shrink-0" 
                             :class="{ 'rotate-180': open, 'hidden': collapsed }" 
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    
                    <div x-show="open" x-collapse 
                         class="mt-1 space-y-1 pl-8 border-l-2 border-gray-200 dark:border-gray-700"
                         :class="collapsed ? 'hidden' : ''">
                        <a href="<?php echo e(route('guru.konseling.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('guru.konseling.*') ? 'bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-comments w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Konseling Siswa</span>
                        </a>
                    </div>
                </div>

                <!-- 📋 Sistem Pendukung -->
                <div x-data="{ open: <?php echo e(request()->routeIs('guru.agenda.*', 'guru.surat.*') ? 'true' : 'false'); ?> }" class="pt-2">
                    <button @click="open = !open" 
                            class="flex items-center justify-between w-full px-3 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200"
                            :class="collapsed ? 'justify-center' : ''">
                        <div class="flex items-center">
                            <i class="fas fa-tasks w-5 h-5 shrink-0"></i>
                            <span class="ml-3 transition-all duration-300 whitespace-nowrap overflow-hidden"
                                  :class="collapsed ? 'w-0 opacity-0' : 'w-auto opacity-100'">
                                 Sistem Pendukung
                            </span>
                        </div>
                        <svg class="h-4 w-4 transition-transform duration-200 shrink-0" 
                             :class="{ 'rotate-180': open, 'hidden': collapsed }" 
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    
                    <div x-show="open" x-collapse 
                         class="mt-1 space-y-1 pl-8 border-l-2 border-gray-200 dark:border-gray-700"
                         :class="collapsed ? 'hidden' : ''">
                        <a href="<?php echo e(route('guru.agenda.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('guru.agenda.*') ? 'bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-calendar-day w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Agenda Kelas</span>
                        </a>
                        
                        <a href="<?php echo e(route('guru.surat.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('guru.surat.*') ? 'bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-envelope w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Buat Surat</span>
                        </a>
                    </div>
                </div>

                <!-- 💬 Komunikasi -->
                <div x-data="{ open: <?php echo e(request()->routeIs('guru.pengumuman.*', 'guru.pesan.*') ? 'true' : 'false'); ?> }" class="pt-2">
                    <button @click="open = !open" 
                            class="flex items-center justify-between w-full px-3 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200"
                            :class="collapsed ? 'justify-center' : ''">
                        <div class="flex items-center">
                            <i class="fas fa-comments w-5 h-5 shrink-0"></i>
                            <span class="ml-3 transition-all duration-300 whitespace-nowrap overflow-hidden"
                                  :class="collapsed ? 'w-0 opacity-0' : 'w-auto opacity-100'">
                                     Komunikasi
                            </span>
                        </div>
                        <svg class="h-4 w-4 transition-transform duration-200 shrink-0" 
                             :class="{ 'rotate-180': open, 'hidden': collapsed }" 
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    
                    <div x-show="open" x-collapse 
                         class="mt-1 space-y-1 pl-8 border-l-2 border-gray-200 dark:border-gray-700"
                         :class="collapsed ? 'hidden' : ''">
                        <a href="<?php echo e(route('guru.pengumuman.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('guru.pengumuman.*') ? 'bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-bullhorn w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Pengumuman Kelas</span>
                        </a>
                        
                        <a href="<?php echo e(route('guru.pesan.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('guru.pesan.*') ? 'bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-comment-dots w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Pesan / Chat Siswa</span>
                        </a>
                    </div>
                </div>

                <!-- 📊 Monitoring -->
                <div x-data="{ open: <?php echo e(request()->routeIs('guru.kehadiran.*', 'guru.performa.*') ? 'true' : 'false'); ?> }" class="pt-2">
                    <button @click="open = !open" 
                            class="flex items-center justify-between w-full px-3 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200"
                            :class="collapsed ? 'justify-center' : ''">
                        <div class="flex items-center">
                            <i class="fas fa-chart-line w-5 h-5 shrink-0"></i>
                            <span class="ml-3 transition-all duration-300 whitespace-nowrap overflow-hidden"
                                  :class="collapsed ? 'w-0 opacity-0' : 'w-auto opacity-100'">
                                 Monitoring
                            </span>
                        </div>
                        <svg class="h-4 w-4 transition-transform duration-200 shrink-0" 
                             :class="{ 'rotate-180': open, 'hidden': collapsed }" 
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    
                    <div x-show="open" x-collapse 
                         class="mt-1 space-y-1 pl-8 border-l-2 border-gray-200 dark:border-gray-700"
                         :class="collapsed ? 'hidden' : ''">
                        <a href="<?php echo e(route('guru.kehadiran.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('guru.kehadiran.*') ? 'bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-user-check w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Kehadiran</span>
                        </a>
                        
                        <a href="<?php echo e(route('guru.performa.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('guru.performa.*') ? 'bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-chart-bar w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Performa Siswa</span>
                        </a>
                    </div>
                </div>

                <!-- 📊 Laporan Section -->
                <div x-data="{ open: <?php echo e(request()->routeIs('guru.laporan.*') ? 'true' : 'false'); ?> }" class="pt-2">
                    <button @click="open = !open" 
                            class="flex items-center justify-between w-full px-3 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200"
                            :class="collapsed ? 'justify-center' : ''">
                        <div class="flex items-center">
                            <i class="fas fa-file-pdf w-5 h-5 shrink-0"></i>
                            <span class="ml-3 transition-all duration-300 whitespace-nowrap overflow-hidden"
                                  :class="collapsed ? 'w-0 opacity-0' : 'w-auto opacity-100'">
                                 Laporan
                            </span>
                        </div>
                        <svg class="h-4 w-4 transition-transform duration-200 shrink-0" 
                             :class="{ 'rotate-180': open, 'hidden': collapsed }" 
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    
                    <div x-show="open" x-collapse 
                         class="mt-1 space-y-1 pl-8 border-l-2 border-gray-200 dark:border-gray-700"
                         :class="collapsed ? 'hidden' : ''">
                        <a href="<?php echo e(route('guru.laporan.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('guru.laporan.*') ? 'bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-chart-pie w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Laporan Nilai</span>
                        </a>
                    </div>
                </div>

            <?php elseif($role === 'siswa'): ?>
               <!-- ==================== -->
<!-- SISWA NAVIGATION -->
<!-- ==================== -->
<a href="<?php echo e(route('siswa.dashboard')); ?>" @click="closeMobile()"
   class="flex items-center px-3 py-3 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('siswa.dashboard') ? 'bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>"
   :class="collapsed ? 'justify-center' : ''">
   <!-- Container ikon dengan penyesuaian margin -->
   <div class="w-5 h-5 flex items-center justify-center shrink-0" :class="collapsed ? '' : 'mr-3'">
       <i class="fas fa-tachometer-alt text-center" style="width: 20px;"></i>
   </div>
   <span class="transition-all duration-300 whitespace-nowrap overflow-hidden"
         :class="collapsed ? 'w-0 opacity-0' : 'w-auto opacity-100'">
       Dashboard Siswa
   </span>
</a>

                <!-- 🎒 Akademik Section -->
                <div x-data="{ open: <?php echo e(request()->routeIs('siswa.jadwal', 'siswa.nilai', 'siswa.absensi', 'siswa.tugas.*', 'siswa.materi.*', 'siswa.ujian.*', 'siswa.rencana.*') ? 'true' : 'false'); ?> }" class="pt-4">
                    <button @click="open = !open" 
                            class="flex items-center justify-between w-full px-3 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200"
                            :class="collapsed ? 'justify-center' : ''">
                        <div class="flex items-center">
                            <i class="fas fa-graduation-cap w-5 h-5 shrink-0"></i>
                            <span class="ml-3 transition-all duration-300 whitespace-nowrap overflow-hidden"
                                  :class="collapsed ? 'w-0 opacity-0' : 'w-auto opacity-100'">
                                 Akademik
                            </span>
                        </div>
                        <svg class="h-4 w-4 transition-transform duration-200 shrink-0" 
                             :class="{ 'rotate-180': open, 'hidden': collapsed }" 
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    
                    <div x-show="open" x-collapse 
                         class="mt-1 space-y-1 pl-8 border-l-2 border-gray-200 dark:border-gray-700"
                         :class="collapsed ? 'hidden' : ''">
                        
                        
                        <a href="<?php echo e(route('siswa.jadwal')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('siswa.jadwal') ? 'bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-calendar-alt w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Jadwal Pelajaran</span>
                        </a>
                        
                        <a href="<?php echo e(route('siswa.nilai')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('siswa.nilai') ? 'bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-chart-bar w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Nilai & Rapor</span>
                        </a>
                        
                        <a href="<?php echo e(route('siswa.absensi')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('siswa.absensi') ? 'bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-clipboard-check w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Data Absensi</span>
                        </a>

                        <a href="<?php echo e(route('siswa.tugas.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('siswa.tugas.*') ? 'bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-tasks w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Tugas & Kuis</span>
                        </a>

                        <a href="<?php echo e(route('siswa.materi.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('siswa.materi.*') ? 'bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-book w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Materi Pembelajaran</span>
                        </a>

                        <a href="<?php echo e(route('siswa.ujian.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('siswa.ujian.*') ? 'bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-file-alt w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Ujian Online</span>
                        </a>

                        <a href="<?php echo e(route('siswa.rencana.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('siswa.rencana.*') ? 'bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-calendar-check w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Rencana Belajar</span>
                        </a>
                    </div>
                </div>

                <!-- 💰 Pembayaran SPP -->
                <div x-data="{ open: <?php echo e(request()->routeIs('siswa.tagihan.*', 'siswa.pembayaran.*') ? 'true' : 'false'); ?> }" class="pt-2">
                    <button @click="open = !open" 
                            class="flex items-center justify-between w-full px-3 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200"
                            :class="collapsed ? 'justify-center' : ''">
                        <div class="flex items-center">
                            <i class="fas fa-credit-card w-5 h-5 shrink-0"></i>
                            <span class="ml-3 transition-all duration-300 whitespace-nowrap overflow-hidden"
                                  :class="collapsed ? 'w-0 opacity-0' : 'w-auto opacity-100'">
                                 Pembayaran SPP
                            </span>
                        </div>
                        <svg class="h-4 w-4 transition-transform duration-200 shrink-0" 
                             :class="{ 'rotate-180': open, 'hidden': collapsed }" 
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    
                    <div x-show="open" x-collapse 
                         class="mt-1 space-y-1 pl-8 border-l-2 border-gray-200 dark:border-gray-700"
                         :class="collapsed ? 'hidden' : ''">
                        <a href="<?php echo e(route('siswa.tagihan.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('siswa.tagihan.*') ? 'bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-file-invoice w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Tagihan SPP</span>
                        </a>
                        
                        <a href="<?php echo e(route('siswa.pembayaran.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('siswa.pembayaran.*') ? 'bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-receipt w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Riwayat Bayar</span>
                        </a>
                        
                        <a href="<?php echo e(route('siswa.pembayaran-online.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('siswa.pembayaran-online.*') ? 'bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-mobile-alt w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Bayar Online</span>
                        </a>
                    </div>
                </div>

                <!-- 📚 Perpustakaan -->
                <div x-data="{ open: <?php echo e(request()->routeIs('siswa.katalog-buku.*', 'siswa.peminjaman-buku.*', 'siswa.riwayat-peminjaman.*') ? 'true' : 'false'); ?> }" class="pt-2">
                    <button @click="open = !open" 
                            class="flex items-center justify-between w-full px-3 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200"
                            :class="collapsed ? 'justify-center' : ''">
                        <div class="flex items-center">
                            <i class="fas fa-book w-5 h-5 shrink-0"></i>
                            <span class="ml-3 transition-all duration-300 whitespace-nowrap overflow-hidden"
                                  :class="collapsed ? 'w-0 opacity-0' : 'w-auto opacity-100'">
                                 Perpustakaan
                            </span>
                        </div>
                        <svg class="h-4 w-4 transition-transform duration-200 shrink-0" 
                             :class="{ 'rotate-180': open, 'hidden': collapsed }" 
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    
                    <div x-show="open" x-collapse 
                         class="mt-1 space-y-1 pl-8 border-l-2 border-gray-200 dark:border-gray-700"
                         :class="collapsed ? 'hidden' : ''">
                        <a href="<?php echo e(route('siswa.katalog-buku.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('siswa.katalog-buku.*') ? 'bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-search w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Katalog Buku</span>
                        </a>
                        
                        <a href="<?php echo e(route('siswa.peminjaman-buku.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('siswa.peminjaman-buku.*') ? 'bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-exchange-alt w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Peminjaman Buku</span>
                        </a>
                        
                        <a href="<?php echo e(route('siswa.riwayat-peminjaman.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('siswa.riwayat-peminjaman.*') ? 'bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-history w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Riwayat Peminjaman</span>
                        </a>
                    </div>
                </div>

                <!-- 🏆 Ekstrakurikuler -->
                <div x-data="{ open: <?php echo e(request()->routeIs('siswa.ekskul.*', 'siswa.prestasi.*') ? 'true' : 'false'); ?> }" class="pt-2">
                    <button @click="open = !open" 
                            class="flex items-center justify-between w-full px-3 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200"
                            :class="collapsed ? 'justify-center' : ''">
                        <div class="flex items-center">
                            <i class="fas fa-trophy w-5 h-5 shrink-0"></i>
                            <span class="ml-3 transition-all duration-300 whitespace-nowrap overflow-hidden"
                                  :class="collapsed ? 'w-0 opacity-0' : 'w-auto opacity-100'">
                                 Ekstrakurikuler
                            </span>
                        </div>
                        <svg class="h-4 w-4 transition-transform duration-200 shrink-0" 
                             :class="{ 'rotate-180': open, 'hidden': collapsed }" 
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    
                    <div x-show="open" x-collapse 
                         class="mt-1 space-y-1 pl-8 border-l-2 border-gray-200 dark:border-gray-700"
                         :class="collapsed ? 'hidden' : ''">
                        <a href="<?php echo e(route('siswa.daftar-ekskul.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('siswa.daftar-ekskul.*') ? 'bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-user-plus w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Daftar Ekskul</span>
                        </a>
                        
                        <a href="<?php echo e(route('siswa.kegiatan-ekskul.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('siswa.kegiatan-ekskul.*') ? 'bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-calendar-check w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Kegiatan Ekskul</span>
                        </a>
                        
                        <a href="<?php echo e(route('siswa.prestasi-saya.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('siswa.prestasi-saya.*') ? 'bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-medal w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Prestasi Saya</span>
                        </a>
                    </div>
                </div>

                <!-- 🧠 Konseling & BK -->
                <div x-data="{ open: <?php echo e(request()->routeIs('siswa.konseling.*', 'siswa.layanan-bk.*') ? 'true' : 'false'); ?> }" class="pt-2">
                    <button @click="open = !open" 
                            class="flex items-center justify-between w-full px-3 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200"
                            :class="collapsed ? 'justify-center' : ''">
                        <div class="flex items-center">
                            <i class="fas fa-hands-helping w-5 h-5 shrink-0"></i>
                            <span class="ml-3 transition-all duration-300 whitespace-nowrap overflow-hidden"
                                  :class="collapsed ? 'w-0 opacity-0' : 'w-auto opacity-100'">
                                 Konseling & BK
                            </span>
                        </div>
                        <svg class="h-4 w-4 transition-transform duration-200 shrink-0" 
                             :class="{ 'rotate-180': open, 'hidden': collapsed }" 
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    
                    <div x-show="open" x-collapse 
                         class="mt-1 space-y-1 pl-8 border-l-2 border-gray-200 dark:border-gray-700"
                         :class="collapsed ? 'hidden' : ''">
                        <a href="<?php echo e(route('siswa.layanan-bk.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('siswa.layanan-bk.*') ? 'bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-comments w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Layanan BK</span>
                        </a>
                        
                        <a href="<?php echo e(route('siswa.jadwal-konseling.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('siswa.jadwal-konseling.*') ? 'bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-calendar-alt w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Jadwal Konseling</span>
                        </a>
                        
                        <a href="<?php echo e(route('siswa.riwayat-konseling.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('siswa.riwayat-konseling.*') ? 'bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-history w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Riwayat Konseling</span>
                        </a>
                    </div>
                </div>

                <!-- 👨‍🎓 Karir & Beasiswa -->
                <div x-data="{ open: <?php echo e(request()->routeIs('siswa.karir.*', 'siswa.beasiswa.*') ? 'true' : 'false'); ?> }" class="pt-2">
                    <button @click="open = !open" 
                            class="flex items-center justify-between w-full px-3 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200"
                            :class="collapsed ? 'justify-center' : ''">
                        <div class="flex items-center">
                            <i class="fas fa-briefcase w-5 h-5 shrink-0"></i>
                            <span class="ml-3 transition-all duration-300 whitespace-nowrap overflow-hidden"
                                  :class="collapsed ? 'w-0 opacity-0' : 'w-auto opacity-100'">
                                 Karir & Beasiswa
                            </span>
                        </div>
                        <svg class="h-4 w-4 transition-transform duration-200 shrink-0" 
                             :class="{ 'rotate-180': open, 'hidden': collapsed }" 
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    
                    <div x-show="open" x-collapse 
                         class="mt-1 space-y-1 pl-8 border-l-2 border-gray-200 dark:border-gray-700"
                         :class="collapsed ? 'hidden' : ''">
                        <a href="<?php echo e(route('siswa.lowongan-kerja.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('siswa.lowongan-kerja.*') ? 'bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-search-dollar w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Lowongan Kerja</span>
                        </a>
                        
                        <a href="<?php echo e(route('siswa.beasiswa.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('siswa.beasiswa.*') ? 'bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-award w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Beasiswa</span>
                        </a>
                        
                        <a href="<?php echo e(route('siswa.informasi-karir.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('siswa.informasi-karir.*') ? 'bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-info-circle w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Informasi Karir</span>
                        </a>
                    </div>
                </div>

                <!-- 📋 Agenda & Informasi -->
                <div x-data="{ open: <?php echo e(request()->routeIs('siswa.agenda.*') ? 'true' : 'false'); ?> }" class="pt-2">
                    <button @click="open = !open" 
                            class="flex items-center justify-between w-full px-3 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200"
                            :class="collapsed ? 'justify-center' : ''">
                        <div class="flex items-center">
                            <i class="fas fa-calendar-day w-5 h-5 shrink-0"></i>
                            <span class="ml-3 transition-all duration-300 whitespace-nowrap overflow-hidden"
                                  :class="collapsed ? 'w-0 opacity-0' : 'w-auto opacity-100'">
                                 Agenda & Informasi
                            </span>
                        </div>
                        <svg class="h-4 w-4 transition-transform duration-200 shrink-0" 
                             :class="{ 'rotate-180': open, 'hidden': collapsed }" 
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    
                    <div x-show="open" x-collapse 
                         class="mt-1 space-y-1 pl-8 border-l-2 border-gray-200 dark:border-gray-700"
                         :class="collapsed ? 'hidden' : ''">
                        <a href="<?php echo e(route('siswa.agenda-sekolah.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('siswa.agenda-sekolah.*') ? 'bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-calendar w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Agenda Sekolah</span>
                        </a>
                    </div>
                </div>

                <!-- 💬 Interaksi -->
                <div x-data="{ open: <?php echo e(request()->routeIs('siswa.pengumuman.*', 'siswa.pesan.*', 'siswa.forum.*') ? 'true' : 'false'); ?> }" class="pt-2">
                    <button @click="open = !open" 
                            class="flex items-center justify-between w-full px-3 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200"
                            :class="collapsed ? 'justify-center' : ''">
                        <div class="flex items-center">
                            <i class="fas fa-users w-5 h-5 shrink-0"></i>
                            <span class="ml-3 transition-all duration-300 whitespace-nowrap overflow-hidden"
                                  :class="collapsed ? 'w-0 opacity-0' : 'w-auto opacity-100'">
                                 Interaksi
                            </span>
                        </div>
                        <svg class="h-4 w-4 transition-transform duration-200 shrink-0" 
                             :class="{ 'rotate-180': open, 'hidden': collapsed }" 
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    
                    <div x-show="open" x-collapse 
                         class="mt-1 space-y-1 pl-8 border-l-2 border-gray-200 dark:border-gray-700"
                         :class="collapsed ? 'hidden' : ''">
                        <a href="<?php echo e(route('siswa.pengumuman.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('siswa.pengumuman.*') ? 'bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-bullhorn w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Pengumuman Sekolah</span>
                        </a>
                        
                        <a href="<?php echo e(route('siswa.pesan.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('siswa.pesan.*') ? 'bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-comments w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Pesan ke Guru</span>
                        </a>
                        
                        <a href="<?php echo e(route('siswa.forum.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('siswa.forum.*') ? 'bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-users w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Forum Diskusi</span>
                        </a>
                    </div>
                </div>

                <!-- 📊 Laporan & Arsip -->
                <div x-data="{ open: <?php echo e(request()->routeIs('siswa.rapor.*', 'siswa.rekap.*', 'siswa.peringkat.*') ? 'true' : 'false'); ?> }" class="pt-2">
                    <button @click="open = !open" 
                            class="flex items-center justify-between w-full px-3 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200"
                            :class="collapsed ? 'justify-center' : ''">
                        <div class="flex items-center">
                            <i class="fas fa-chart-line w-5 h-5 shrink-0"></i>
                            <span class="ml-3 transition-all duration-300 whitespace-nowrap overflow-hidden"
                                  :class="collapsed ? 'w-0 opacity-0' : 'w-auto opacity-100'">
                                 Laporan & Arsip
                            </span>
                        </div>
                        <svg class="h-4 w-4 transition-transform duration-200 shrink-0" 
                             :class="{ 'rotate-180': open, 'hidden': collapsed }" 
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    
                    <div x-show="open" x-collapse 
                         class="mt-1 space-y-1 pl-8 border-l-2 border-gray-200 dark:border-gray-700"
                         :class="collapsed ? 'hidden' : ''">
                        <a href="<?php echo e(route('siswa.rapor.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('siswa.rapor.*') ? 'bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-print w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Cetak Rapor</span>
                        </a>
                        
                                               <a href="<?php echo e(route('siswa.rekap.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('siswa.rekap.*') ? 'bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-chart-line w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Rekap Absensi</span>
                        </a>
                        
                        <a href="<?php echo e(route('siswa.peringkat.index')); ?>" @click="closeMobile()"
                           class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('siswa.peringkat.*') ? 'bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                            <i class="fas fa-trophy w-4 h-4 shrink-0"></i>
                            <span class="ml-3 text-sm whitespace-nowrap">Peringkat Kelas</span>
                        </a>
                    </div>
                </div>

            <?php else: ?>
                <!-- DEFAULT FALLBACK -->
                <a href="/" @click="closeMobile()"
                   class="flex items-center px-3 py-3 rounded-lg transition-all duration-200 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                   :class="collapsed ? 'justify-center' : ''">
                    <i class="fas fa-home w-5 h-5 shrink-0"></i>
                    <span class="ml-3 transition-all duration-300 whitespace-nowrap overflow-hidden"
                          :class="collapsed ? 'w-0 opacity-0' : 'w-auto opacity-100'">
                        Beranda
                    </span>
                </a>
            <?php endif; ?>
        </nav>

        <!-- Sidebar Footer -->
        <div class="p-3 border-t border-gray-200 dark:border-gray-700 shrink-0">
            <div class="flex items-center" :class="collapsed ? 'justify-center' : ''">
                <div class="w-8 h-8 bg-primary-600 rounded-full flex items-center justify-center shrink-0">
                    <span class="text-sm font-medium text-white">
                        <?php echo e(strtoupper(substr(auth()->user()->name, 0, 1))); ?>

                    </span>
                </div>
                <div class="ml-3 transition-all duration-300 overflow-hidden min-w-0"
                     :class="collapsed ? 'w-0 opacity-0' : 'w-auto opacity-100'">
                    <p class="text-sm font-medium text-gray-900 dark:text-white whitespace-nowrap truncate"><?php echo e(auth()->user()->name); ?></p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 capitalize whitespace-nowrap truncate"><?php echo e(auth()->user()->role); ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('sidebar', () => ({
        collapsed: localStorage.getItem('sidebar-collapsed') === 'true',
        mobileOpen: false,

        init() {
            console.log('🔄 Sidebar Alpine initialized');
            console.log('Initial state - collapsed:', this.collapsed, 'mobileOpen:', this.mobileOpen);
            
            // Listen untuk events dari header
            window.addEventListener('toggle-mobile-sidebar', () => {
                console.log('📱 Received mobile toggle event in sidebar');
                this.toggleMobile();
            });

            window.addEventListener('toggle-desktop-sidebar', () => {
                console.log('🖥️ Received desktop toggle event in sidebar');
                this.toggleDesktop();
            });

            // Handle resize
            this.handleResize();
            window.addEventListener('resize', this.handleResize.bind(this));

            // Dispatch initial state
            setTimeout(() => {
                this.dispatchSidebarState();
            }, 100);
        },

        handleResize() {
            if (window.innerWidth >= 1024) {
                this.mobileOpen = false;
                document.body.style.overflow = 'auto';
                this.dispatchSidebarState();
            }
        },

        toggleDesktop() {
            console.log('🖥️ Toggling desktop sidebar, current collapsed:', this.collapsed);
            this.collapsed = !this.collapsed;
            localStorage.setItem('sidebar-collapsed', this.collapsed);
            this.dispatchSidebarState();
        },

        toggleMobile() {
            console.log('📱 Toggling mobile sidebar, current mobileOpen:', this.mobileOpen);
            this.mobileOpen = !this.mobileOpen;
            
            if (this.mobileOpen) {
                document.body.style.overflow = 'hidden';
                // Pastikan sidebar expanded saat mobile dibuka
                this.collapsed = false;
            } else {
                document.body.style.overflow = 'auto';
            }
            this.dispatchSidebarState();
        },

        closeMobile() {
            console.log('📱 Closing mobile sidebar');
            if (window.innerWidth < 1024) {
                this.mobileOpen = false;
                document.body.style.overflow = 'auto';
                this.dispatchSidebarState();
            }
        },
        
        dispatchSidebarState() {
            const state = { 
                collapsed: this.collapsed, 
                mobileOpen: this.mobileOpen 
            };
            console.log('📢 Dispatching sidebar state:', state);
            
            window.dispatchEvent(new CustomEvent('sidebar-state-changed', {
                detail: state
            }));
        }
    }));
});
</script><?php /**PATH C:\Users\user\siakad-sma\resources\views/livewire/shared/sidebar.blade.php ENDPATH**/ ?>