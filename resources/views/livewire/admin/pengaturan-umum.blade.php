<div>
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 shadow mb-6">
        <div class="max-w-7xl mx-auto px-4 py-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Pengaturan Umum</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Kelola pengaturan sistem sekolah</p>
                </div>
                <button wire:click="saveSettings"
                        wire:loading.attr="disabled"
                        class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg flex items-center justify-center transition-colors disabled:opacity-50">
                    <i class="fas fa-save mr-2"></i>
                    <span wire:loading.remove>Simpan Pengaturan</span>
                    <span wire:loading>
                        <i class="fas fa-spinner fa-spin mr-2"></i>
                        Menyimpan...
                    </span>
                </button>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    <div class="max-w-7xl mx-auto px-4 mb-6">
        @if(session()->has('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 rounded-lg p-4 dark:bg-green-800/10 dark:border-green-900 dark:text-green-500 flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
        @endif

        @if(session()->has('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 rounded-lg p-4 dark:bg-red-800/10 dark:border-red-900 dark:text-red-500 flex items-center">
            <i class="fas fa-exclamation-circle mr-2"></i>
            {{ session('error') }}
        </div>
        @endif
    </div>

    <!-- Content -->
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column - Settings Forms -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Informasi Sekolah -->
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center">
                        <i class="fas fa-school mr-3 text-blue-600 dark:text-blue-400"></i>
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white">Informasi Sekolah</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nama Sekolah -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Nama Sekolah *
                                </label>
                                <input type="text" 
                                       wire:model="settings.nama_sekolah"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Masukkan nama sekolah">
                                @error('settings.nama_sekolah')
                                <p class="text-red-500 text-xs mt-1 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>

                            <!-- Alamat -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Alamat Lengkap
                                </label>
                                <textarea wire:model="settings.alamat_sekolah"
                                          rows="3"
                                          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                          placeholder="Masukkan alamat lengkap sekolah"></textarea>
                                @error('settings.alamat_sekolah')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Telepon & Email -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Telepon
                                </label>
                                <input type="text" 
                                       wire:model="settings.telepon_sekolah"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="(021) 1234567">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Email
                                </label>
                                <input type="email" 
                                       wire:model="settings.email_sekolah"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="info@sekolah.sch.id">
                            </div>

                            <!-- Website & Tahun Ajaran -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Website
                                </label>
                                <input type="url" 
                                       wire:model="settings.website_sekolah"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="https://sekolah.sch.id">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Tahun Ajaran Aktif
                                </label>
                                <input type="text" 
                                       wire:model="settings.tahun_ajaran_aktif"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="2024/2025">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kepala Sekolah -->
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center">
                        <i class="fas fa-user-tie mr-3 text-green-600 dark:text-green-400"></i>
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white">Kepala Sekolah</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Nama Kepala Sekolah
                                </label>
                                <input type="text" 
                                       wire:model="settings.kepala_sekolah"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Nama lengkap kepala sekolah">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    NIP
                                </label>
                                <input type="text" 
                                       wire:model="settings.nip_kepala_sekolah"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Nomor Induk Pegawai">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pengaturan Sistem -->
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center">
                        <i class="fas fa-cogs mr-3 text-purple-600 dark:text-purple-400"></i>
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white">Pengaturan Sistem</h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <!-- Logo Sekolah -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Logo Sekolah
                                </label>
                                <div class="flex items-center space-x-4">
                                    @if($settings['logo_sekolah'])
                                    <img src="{{ $settings['logo_sekolah'] }}" 
                                         alt="Logo Sekolah" 
                                         class="h-16 w-16 object-cover rounded-lg border border-gray-300 dark:border-gray-600">
                                    @else
                                    <div class="h-16 w-16 bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-school text-gray-400 text-xl"></i>
                                    </div>
                                    @endif
                                    <div class="flex-1">
                                        <input type="file" 
                                               wire:model="logoFile"
                                               class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-blue-900 dark:file:text-blue-300">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            Format: JPG, PNG, SVG. Maksimal 2MB
                                        </p>
                                        @error('logoFile')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Pengaturan Waktu -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Zona Waktu
                                    </label>
                                    <select wire:model="settings.zona_waktu"
                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="Asia/Jakarta">WIB (Jakarta)</option>
                                        <option value="Asia/Makassar">WITA (Makassar)</option>
                                        <option value="Asia/Jayapura">WIT (Jayapura)</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Format Tanggal
                                    </label>
                                    <select wire:model="settings.format_tanggal"
                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="d/m/Y">DD/MM/YYYY</option>
                                        <option value="m/d/Y">MM/DD/YYYY</option>
                                        <option value="Y-m-d">YYYY-MM-DD</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Additional Info -->
            <div class="space-y-6">
                <!-- Status Sistem -->
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Status Sistem</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Versi Aplikasi</span>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">v1.0.0</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Total Pengguna</span>
                                <span class="text-sm font-medium text-green-600 dark:text-green-400">
                                    {{ \App\Models\User::count() }} users
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Status Database</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                    <i class="fas fa-check mr-1"></i>
                                    Connected
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Terakhir Backup</span>
                                <span class="text-sm text-gray-500 dark:text-gray-400">-</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Quick Actions</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <a href="{{ route('admin.backup.index') }}" 
                               class="w-full flex items-center px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors">
                                <i class="fas fa-download mr-3 text-blue-600 dark:text-blue-400"></i>
                                Backup Database
                            </a>
                            <a href="{{ route('admin.log.index') }}" 
                               class="w-full flex items-center px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors">
                                <i class="fas fa-history mr-3 text-green-600 dark:text-green-400"></i>
                                Lihat Log Aktivitas
                            </a>
                            <a href="{{ route('admin.role.index') }}" 
                               class="w-full flex items-center px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors">
                                <i class="fas fa-user-shield mr-3 text-purple-600 dark:text-purple-400"></i>
                                Kelola Role & Permission
                            </a>
                            <button wire:click="resetToDefault"
                                    wire:confirm="Apakah Anda yakin ingin mengembalikan semua pengaturan ke default?"
                                    class="w-full flex items-center px-4 py-3 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                                <i class="fas fa-undo mr-3"></i>
                                Reset ke Default
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Informasi Support -->
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6">
                    <div class="flex items-start">
                        <i class="fas fa-life-ring text-blue-600 dark:text-blue-400 text-lg mt-1 mr-3"></i>
                        <div>
                            <h4 class="text-sm font-medium text-blue-900 dark:text-blue-100 mb-2">
                                Butuh Bantuan?
                            </h4>
                            <p class="text-sm text-blue-700 dark:text-blue-300 mb-3">
                                Hubungi tim support untuk bantuan teknis dan pertanyaan tentang pengaturan sistem.
                            </p>
                            <div class="space-y-1 text-xs text-blue-600 dark:text-blue-400">
                                <div class="flex items-center">
                                    <i class="fas fa-envelope mr-2"></i>
                                    <span>support@siakad.sch.id</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-phone mr-2"></i>
                                    <span>(021) 1234-5678</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div wire:loading.class.remove="hidden" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 dark:bg-blue-900">
                    <i class="fas fa-spinner fa-spin text-blue-600 dark:text-blue-400"></i>
                </div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mt-2">Menyimpan Pengaturan</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Harap tunggu sebentar...</p>
            </div>
        </div>
    </div>
</div>