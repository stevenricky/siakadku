<div>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 dark:from-gray-900 dark:to-slate-800 py-4 sm:py-8">
        <div class="max-w-6xl mx-auto px-3 sm:px-4 sm:px-6 lg:px-8">
            <!-- Header - Mobile Optimized -->
            <div class="text-center mb-6 sm:mb-8">
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold bg-gradient-to-r from-violet-600 to-indigo-600 bg-clip-text text-transparent">
                    Profil Saya
                </h1>
                <p class="text-sm sm:text-lg text-gray-600 dark:text-gray-400 mt-2 sm:mt-3">
                    Kelola informasi profil dan akun Anda
                </p>
            </div>

            <!-- Mobile Menu Button (Hamburger) -->
            <div class="lg:hidden mb-4">
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-slate-200 dark:border-slate-700 p-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Pilih Menu:
                    </label>
                    <select wire:model="activeTab" class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-violet-500">
                        <option value="personal">📝 Informasi Pribadi</option>
                        <option value="photo">📷 Foto Profil</option>
                        <option value="biodata">👤 Biodata Lengkap</option>
                        @if(auth()->user()->role === 'siswa')
                        <option value="orangtua">👨‍👩‍👧‍👦 Data Orang Tua</option>
                        @endif
                        @if(auth()->user()->role === 'guru')
                        <option value="mengajar">📚 Data Mengajar</option>
                        @endif
                        <option value="security">🔒 Keamanan</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8">
                <!-- Sidebar - Sticky on Desktop, Normal on Mobile -->
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 p-4 sm:p-6 lg:sticky lg:top-4">
                        <!-- Foto Profil -->
                        <div class="text-center mb-4 sm:mb-6">
                            <div class="relative inline-block">
                                <div class="w-20 h-20 sm:w-24 sm:h-24 lg:w-32 lg:h-32 rounded-2xl bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white text-xl sm:text-2xl lg:text-4xl font-bold mb-3 sm:mb-4 mx-auto shadow-lg overflow-hidden">
                                    @if($fotoUrl)
                                        <img src="{{ $fotoUrl }}" alt="Foto Profil" class="w-full h-full rounded-2xl object-cover">
                                    @else
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    @endif
                                </div>
                            </div>
                            <h2 class="text-lg sm:text-xl font-semibold text-gray-900 dark:text-white mt-3 sm:mt-4 line-clamp-2">
                                {{ auth()->user()->name }}
                            </h2>
                            <p class="text-xs sm:text-sm text-emerald-600 dark:text-emerald-400 font-medium">
                                {{ ucfirst(auth()->user()->role) }}
                            </p>
                        </div>

                        <!-- Desktop Navigation Tabs (Hidden on Mobile) -->
                        <div class="hidden lg:block space-y-2">
                            <button wire:click="setActiveTab('personal')" 
                                    class="w-full text-left px-4 py-3 rounded-xl transition-all duration-300 {{ $activeTab === 'personal' ? 'bg-violet-100 dark:bg-violet-900 text-violet-700 dark:text-violet-300 border border-violet-200 dark:border-violet-700' : 'text-gray-600 dark:text-gray-400 hover:bg-slate-100 dark:hover:bg-slate-700' }}">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    <span>Informasi Pribadi</span>
                                </div>
                            </button>
                            
                            <button wire:click="setActiveTab('photo')" 
                                    class="w-full text-left px-4 py-3 rounded-xl transition-all duration-300 {{ $activeTab === 'photo' ? 'bg-violet-100 dark:bg-violet-900 text-violet-700 dark:text-violet-300 border border-violet-200 dark:border-violet-700' : 'text-gray-600 dark:text-gray-400 hover:bg-slate-100 dark:hover:bg-slate-700' }}">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span>Foto Profil</span>
                                </div>
                            </button>
                            
                            <button wire:click="setActiveTab('biodata')" 
                                    class="w-full text-left px-4 py-3 rounded-xl transition-all duration-300 {{ $activeTab === 'biodata' ? 'bg-violet-100 dark:bg-violet-900 text-violet-700 dark:text-violet-300 border border-violet-200 dark:border-violet-700' : 'text-gray-600 dark:text-gray-400 hover:bg-slate-100 dark:hover:bg-slate-700' }}">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <span>Biodata Lengkap</span>
                                </div>
                            </button>
                            
                            @if(auth()->user()->role === 'siswa')
                            <button wire:click="setActiveTab('orangtua')" 
                                    class="w-full text-left px-4 py-3 rounded-xl transition-all duration-300 {{ $activeTab === 'orangtua' ? 'bg-violet-100 dark:bg-violet-900 text-violet-700 dark:text-violet-300 border border-violet-200 dark:border-violet-700' : 'text-gray-600 dark:text-gray-400 hover:bg-slate-100 dark:hover:bg-slate-700' }}">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    <span>Data Orang Tua</span>
                                </div>
                            </button>
                            @endif
                            
                            @if(auth()->user()->role === 'guru')
                            <button wire:click="setActiveTab('mengajar')" 
                                    class="w-full text-left px-4 py-3 rounded-xl transition-all duration-300 {{ $activeTab === 'mengajar' ? 'bg-violet-100 dark:bg-violet-900 text-violet-700 dark:text-violet-300 border border-violet-200 dark:border-violet-700' : 'text-gray-600 dark:text-gray-400 hover:bg-slate-100 dark:hover:bg-slate-700' }}">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                    <span>Data Mengajar</span>
                                </div>
                            </button>
                            @endif
                            
                            <button wire:click="setActiveTab('security')" 
                                    class="w-full text-left px-4 py-3 rounded-xl transition-all duration-300 {{ $activeTab === 'security' ? 'bg-violet-100 dark:bg-violet-900 text-violet-700 dark:text-violet-300 border border-violet-200 dark:border-violet-700' : 'text-gray-600 dark:text-gray-400 hover:bg-slate-100 dark:hover:bg-slate-700' }}">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                    <span>Keamanan Akun</span>
                                </div>
                            </button>
                        </div>

                        <!-- Quick Stats -->
                        <div class="mt-4 sm:mt-6 space-y-2 sm:space-y-3">
                            <div class="flex items-center justify-between p-2 sm:p-3 bg-slate-50 dark:bg-slate-700 rounded-xl">
                                <div class="flex items-center space-x-2 sm:space-x-3">
                                    <div class="p-1 sm:p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>
                                    <span class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-300">Status</span>
                                </div>
                                <span class="text-xs sm:text-sm font-semibold text-emerald-600 dark:text-emerald-400">
                                    Aktif
                                </span>
                            </div>
                            
                            <div class="flex items-center justify-between p-2 sm:p-3 bg-slate-50 dark:bg-slate-700 rounded-xl">
                                <div class="flex items-center space-x-2 sm:space-x-3">
                                    <div class="p-1 sm:p-2 bg-green-100 dark:bg-green-900 rounded-lg">
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <span class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-300">Bergabung</span>
                                </div>
                                <span class="text-xs sm:text-sm font-semibold text-gray-900 dark:text-white">
                                    {{ auth()->user()->created_at->format('M Y') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-4 sm:space-y-6 lg:space-y-8">
                    <!-- Tab: Personal Info -->
                    @if($activeTab === 'personal')
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
                        <div class="bg-gradient-to-r from-violet-600 to-indigo-600 px-4 sm:px-6 py-3 sm:py-4">
                            <h3 class="text-lg font-semibold text-white flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Informasi Pribadi
                            </h3>
                        </div>
                        <div class="p-4 sm:p-6">
                            <form wire:submit="updateProfile">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                                    <div class="sm:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Lengkap</label>
                                        <input type="text" wire:model="name" class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 dark:border-gray-600 rounded-lg sm:rounded-xl dark:bg-gray-700 dark:text-white text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-violet-500">
                                        @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>
                                    <div class="sm:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email</label>
                                        <input type="email" wire:model="email" class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 dark:border-gray-600 rounded-lg sm:rounded-xl dark:bg-gray-700 dark:text-white text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-violet-500">
                                        @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                                <button type="submit" class="mt-4 sm:mt-6 w-full sm:w-auto bg-violet-600 hover:bg-violet-700 text-white px-4 sm:px-6 py-2 sm:py-3 rounded-lg sm:rounded-xl font-medium transition-colors duration-200 text-sm sm:text-base">
                                    Simpan Perubahan
                                </button>
                            </form>
                        </div>
                    </div>
                    @endif

                    <!-- Tab: Photo Profile -->
                    @if($activeTab === 'photo')
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
                        <div class="bg-gradient-to-r from-pink-600 to-rose-600 px-4 sm:px-6 py-3 sm:py-4">
                            <h3 class="text-lg font-semibold text-white flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Foto Profil
                            </h3>
                        </div>
                        <div class="p-4 sm:p-6">
                            <form wire:submit="updateProfile">
                                <div class="flex items-center space-x-6 mb-6">
                                    <div class="shrink-0">
                                        <img id="avatar-preview" class="h-24 w-24 object-cover rounded-full border-4 border-slate-200 dark:border-slate-700" 
                                             @if($fotoUrl) src="{{ $fotoUrl }}" @else src="{{ asset('images/default-avatar.png') }}" @endif alt="Current profile photo">
                                    </div>
                                    <div class="flex-1">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Ganti Foto
                                        </label>
                                        <input type="file" wire:model="avatar" class="block w-full text-sm text-gray-500 dark:text-gray-400
                                            file:mr-4 file:py-2 file:px-4
                                            file:rounded-full file:border-0
                                            file:text-sm file:font-semibold
                                            file:bg-violet-50 file:text-violet-700
                                            hover:file:bg-violet-100
                                            dark:file:bg-violet-900 dark:file:text-violet-300
                                            cursor-pointer"/>
                                        @error('avatar') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">PNG, JPG hingga 2MB</p>
                                    </div>
                                </div>
                                <div class="flex space-x-3">
                                    <button type="submit" class="bg-pink-600 hover:bg-pink-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                        <i class="fas fa-upload mr-2"></i>Upload Foto
                                    </button>
                                    @if(auth()->user()->foto_profil)
                                        <button type="button" wire:click="removePhoto" wire:confirm="Yakin ingin menghapus foto profil?" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                            <i class="fas fa-trash mr-2"></i>Hapus
                                        </button>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                    @endif

                    <!-- Tab: Biodata -->
                    @if($activeTab === 'biodata')
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
                        <div class="bg-gradient-to-r from-emerald-600 to-teal-600 px-4 sm:px-6 py-3 sm:py-4">
                            <h3 class="text-lg font-semibold text-white flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Biodata Lengkap
                            </h3>
                        </div>
                        <div class="p-4 sm:p-6">
                            @if(auth()->user()->role === 'siswa')
                                <form wire:submit="updateProfile">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">NIS</label>
                                            <input type="text" wire:model="nis" readonly class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 dark:border-gray-600 rounded-lg sm:rounded-xl bg-gray-50 dark:bg-gray-600 dark:text-white text-sm sm:text-base cursor-not-allowed">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">NISN</label>
                                            <input type="text" wire:model="nisn" readonly class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 dark:border-gray-600 rounded-lg sm:rounded-xl bg-gray-50 dark:bg-gray-600 dark:text-white text-sm sm:text-base cursor-not-allowed">
                                        </div>
                                        <div class="sm:col-span-2">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Lengkap</label>
                                            <input type="text" wire:model="nama_lengkap" class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 dark:border-gray-600 rounded-lg sm:rounded-xl dark:bg-gray-700 dark:text-white text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                            @error('nama_lengkap') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Jenis Kelamin</label>
                                            <select wire:model="jenis_kelamin" class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 dark:border-gray-600 rounded-lg sm:rounded-xl dark:bg-gray-700 dark:text-white text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                                <option value="L">Laki-laki</option>
                                                <option value="P">Perempuan</option>
                                            </select>
                                            @error('jenis_kelamin') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tempat Lahir</label>
                                            <input type="text" wire:model="tempat_lahir" class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 dark:border-gray-600 rounded-lg sm:rounded-xl dark:bg-gray-700 dark:text-white text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                            @error('tempat_lahir') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tanggal Lahir</label>
                                            <input type="date" wire:model="tanggal_lahir" class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 dark:border-gray-600 rounded-lg sm:rounded-xl dark:bg-gray-700 dark:text-white text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                            @error('tanggal_lahir') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">No. Telepon</label>
                                            <input type="text" wire:model="no_telp" class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 dark:border-gray-600 rounded-lg sm:rounded-xl dark:bg-gray-700 dark:text-white text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                            @error('no_telp') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Kelas</label>
                                            <select wire:model="kelas_id" class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 dark:border-gray-600 rounded-lg sm:rounded-xl dark:bg-gray-700 dark:text-white text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                                <option value="">Pilih Kelas</option>
                                                @foreach($kelas_list as $id => $nama)
                                                    <option value="{{ $id }}">{{ $nama }}</option>
                                                @endforeach
                                            </select>
                                            @error('kelas_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tahun Ajaran</label>
                                            <select wire:model="tahun_ajaran_id" class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 dark:border-gray-600 rounded-lg sm:rounded-xl dark:bg-gray-700 dark:text-white text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                                <option value="">Pilih Tahun Ajaran</option>
                                                @foreach($tahun_ajaran_list as $id => $nama)
                                                    <option value="{{ $id }}">{{ $nama }}</option>
                                                @endforeach
                                            </select>
                                            @error('tahun_ajaran_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                        </div>
                                        <div class="sm:col-span-2">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Alamat</label>
                                            <textarea wire:model="alamat" rows="3" class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 dark:border-gray-600 rounded-lg sm:rounded-xl dark:bg-gray-700 dark:text-white text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-emerald-500 resize-none"></textarea>
                                            @error('alamat') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                        </div>
                                    </div>
                                    <button type="submit" class="mt-4 sm:mt-6 w-full sm:w-auto bg-emerald-600 hover:bg-emerald-700 text-white px-4 sm:px-6 py-2 sm:py-3 rounded-lg sm:rounded-xl font-medium transition-colors duration-200 text-sm sm:text-base">
                                        Simpan Biodata
                                    </button>
                                </form>
                            @elseif(auth()->user()->role === 'guru')
                                <form wire:submit="updateProfile">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">NIP</label>
                                            <input type="text" wire:model="nip" readonly class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 dark:border-gray-600 rounded-lg sm:rounded-xl bg-gray-50 dark:bg-gray-600 dark:text-white text-sm sm:text-base cursor-not-allowed">
                                        </div>
                                        <div class="sm:col-span-2">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Lengkap</label>
                                            <input type="text" wire:model="guru_nama_lengkap" class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 dark:border-gray-600 rounded-lg sm:rounded-xl dark:bg-gray-700 dark:text-white text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                            @error('guru_nama_lengkap') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Jenis Kelamin</label>
                                            <select wire:model="guru_jenis_kelamin" class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 dark:border-gray-600 rounded-lg sm:rounded-xl dark:bg-gray-700 dark:text-white text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                                <option value="L">Laki-laki</option>
                                                <option value="P">Perempuan</option>
                                            </select>
                                            @error('guru_jenis_kelamin') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tempat Lahir</label>
                                            <input type="text" wire:model="guru_tempat_lahir" class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 dark:border-gray-600 rounded-lg sm:rounded-xl dark:bg-gray-700 dark:text-white text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                            @error('guru_tempat_lahir') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tanggal Lahir</label>
                                            <input type="date" wire:model="guru_tanggal_lahir" class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 dark:border-gray-600 rounded-lg sm:rounded-xl dark:bg-gray-700 dark:text-white text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                            @error('guru_tanggal_lahir') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">No. Telepon</label>
                                            <input type="text" wire:model="guru_no_telp" class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 dark:border-gray-600 rounded-lg sm:rounded-xl dark:bg-gray-700 dark:text-white text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                            @error('guru_no_telp') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                        </div>
                                        <div class="sm:col-span-2">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Alamat</label>
                                            <textarea wire:model="guru_alamat" rows="3" class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 dark:border-gray-600 rounded-lg sm:rounded-xl dark:bg-gray-700 dark:text-white text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-emerald-500 resize-none"></textarea>
                                            @error('guru_alamat') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                        </div>
                                    </div>
                                    <button type="submit" class="mt-4 sm:mt-6 w-full sm:w-auto bg-emerald-600 hover:bg-emerald-700 text-white px-4 sm:px-6 py-2 sm:py-3 rounded-lg sm:rounded-xl font-medium transition-colors duration-200 text-sm sm:text-base">
                                        Simpan Biodata
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Tab: Security - ENABLED -->
                    @if($activeTab === 'security')
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
                        <div class="bg-gradient-to-r from-amber-500 to-orange-500 px-4 sm:px-6 py-3 sm:py-4">
                            <h3 class="text-lg font-semibold text-white flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                Keamanan Akun
                            </h3>
                        </div>
                        <div class="p-4 sm:p-6">
                            <form wire:submit="updatePassword">
                                <div class="space-y-4">
                                    <div>
                                        <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Password Saat Ini</label>
                                        <input type="password" id="current_password" wire:model="current_password" class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 dark:border-gray-600 rounded-lg sm:rounded-xl dark:bg-gray-700 dark:text-white text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-amber-500" placeholder="Masukkan password lama">
                                        @error('current_password')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label for="new_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Password Baru</label>
                                            <input type="password" id="new_password" wire:model="new_password" class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 dark:border-gray-600 rounded-lg sm:rounded-xl dark:bg-gray-700 dark:text-white text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-amber-500" placeholder="Minimal 8 karakter">
                                            @error('new_password')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Konfirmasi Password Baru</label>
                                            <input type="password" id="new_password_confirmation" wire:model="new_password_confirmation" class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 dark:border-gray-600 rounded-lg sm:rounded-xl dark:bg-gray-700 dark:text-white text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-amber-500" placeholder="Ketik ulang password baru">
                                            @error('new_password_confirmation')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <button type="submit" class="w-full sm:w-auto bg-amber-600 hover:bg-amber-700 text-white px-4 sm:px-6 py-2 sm:py-3 rounded-lg sm:rounded-xl font-medium transition-colors duration-200 text-sm sm:text-base">
                                        <i class="fas fa-key mr-2"></i>Ubah Password
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endif

                    <!-- Other Tabs (Orangtua, Mengajar) -->
                    @if($activeTab === 'orangtua' && auth()->user()->role === 'siswa')
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-600 to-cyan-600 px-4 sm:px-6 py-3 sm:py-4">
                            <h3 class="text-lg font-semibold text-white">Data Orang Tua</h3>
                        </div>
                        <div class="p-4 sm:p-6">
                            <p class="text-gray-600 dark:text-gray-400 text-sm sm:text-base">
                                Data orang tua akan ditampilkan di sini. (Konten dapat dikembangkan sesuai kebutuhan)
                            </p>
                        </div>
                    </div>
                    @endif

                    @if($activeTab === 'mengajar' && auth()->user()->role === 'guru')
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
                        <div class="bg-gradient-to-r from-purple-600 to-pink-600 px-4 sm:px-6 py-3 sm:py-4">
                            <h3 class="text-lg font-semibold text-white">Data Mengajar</h3>
                        </div>
                        <div class="p-4 sm:p-6">
                            <p class="text-gray-600 dark:text-gray-400 text-sm sm:text-base">
                                Data mengajar akan ditampilkan di sini. (Konten dapat dikembangkan sesuai kebutuhan)
                            </p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</div>