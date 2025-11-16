<div>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 dark:from-gray-900 dark:to-slate-800 py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold bg-gradient-to-r from-violet-600 to-indigo-600 bg-clip-text text-transparent">
                    Profil Saya
                </h1>
                <p class="text-lg text-gray-600 dark:text-gray-400 mt-3">
                    Kelola informasi profil dan akun Anda
                </p>
            </div>

            <!-- Success Message -->
            @if (session('message'))
                <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ session('message') }}
                    </div>
                </div>
            @endif

            <!-- Navigation Tabs -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg border border-slate-200 dark:border-slate-700 mb-8">
                <div class="flex overflow-x-auto">
                    <button wire:click="setActiveTab('personal')" 
                            class="flex-1 px-6 py-4 text-center font-medium border-b-2 transition-all duration-300 {{ $activeTab === 'personal' ? 'border-violet-600 text-violet-600 dark:text-violet-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:hover:text-gray-300' }}">
                        <i class="fas fa-user-edit mr-2"></i>Informasi Pribadi
                    </button>
                    
                    <button wire:click="setActiveTab('biodata')" 
                            class="flex-1 px-6 py-4 text-center font-medium border-b-2 transition-all duration-300 {{ $activeTab === 'biodata' ? 'border-violet-600 text-violet-600 dark:text-violet-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:hover:text-gray-300' }}">
                        <i class="fas fa-id-card mr-2"></i>Biodata Lengkap
                    </button>
                    
                    @if(auth()->user()->role === 'siswa')
                    <button wire:click="setActiveTab('orangtua')" 
                            class="flex-1 px-6 py-4 text-center font-medium border-b-2 transition-all duration-300 {{ $activeTab === 'orangtua' ? 'border-violet-600 text-violet-600 dark:text-violet-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:hover:text-gray-300' }}">
                        <i class="fas fa-users mr-2"></i>Data Orang Tua
                    </button>
                    @endif
                    
                    @if(auth()->user()->role === 'guru')
                    <button wire:click="setActiveTab('mengajar')" 
                            class="flex-1 px-6 py-4 text-center font-medium border-b-2 transition-all duration-300 {{ $activeTab === 'mengajar' ? 'border-violet-600 text-violet-600 dark:text-violet-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:hover:text-gray-300' }}">
                        <i class="fas fa-chalkboard-teacher mr-2"></i>Data Mengajar
                    </button>
                    @endif
                    
                    <button wire:click="setActiveTab('security')" 
                            class="flex-1 px-6 py-4 text-center font-medium border-b-2 transition-all duration-300 {{ $activeTab === 'security' ? 'border-violet-600 text-violet-600 dark:text-violet-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:hover:text-gray-300' }}">
                        <i class="fas fa-shield-alt mr-2"></i>Keamanan
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 p-6 sticky top-8">
                        <!-- Foto Profil dengan Upload -->
                        <div class="text-center mb-6">
                            <div class="relative inline-block">
                                <div class="w-32 h-32 rounded-2xl bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white text-4xl font-bold mb-4 mx-auto shadow-lg overflow-hidden">
                                    @if($foto_profil)
                                        <img src="{{ $fotoUrl }}" 
                                             alt="Foto Profil" 
                                             class="w-full h-full rounded-2xl object-cover">
                                    @else
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    @endif
                                </div>
                                
                                <!-- Upload Button -->
                                <label for="avatar" class="absolute -bottom-2 -right-2 bg-emerald-500 hover:bg-emerald-600 text-white p-2 rounded-full shadow-lg transition-all duration-300 transform hover:scale-110 cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </label>
                                <input type="file" id="avatar" wire:model="avatar" class="hidden" accept="image/*">
                                
                                <!-- Remove Photo Button -->
                                @if($foto_profil)
                                <button wire:click="removePhoto" wire:loading.attr="disabled" 
                                        class="absolute -bottom-2 -left-2 bg-rose-500 hover:bg-rose-600 text-white p-2 rounded-full shadow-lg transition-all duration-300 transform hover:scale-110">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                                @endif
                            </div>
                            
                            <!-- Error Message -->
                            @error('avatar')
                                <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                            
                            <!-- Upload Preview -->
                            @if($avatar)
                                <div class="mt-3 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                                    <p class="text-sm text-blue-600 dark:text-blue-400 font-medium">
                                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Foto baru dipilih
                                    </p>
                                    <p class="text-xs text-blue-500 dark:text-blue-300 mt-1">
                                        {{ $avatar->getClientOriginalName() }}
                                    </p>
                                </div>
                            @endif
                            
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mt-4">
                                {{ auth()->user()->name }}
                            </h2>
                            <p class="text-sm text-emerald-600 dark:text-emerald-400 font-medium">
                                {{ ucfirst(auth()->user()->role) }}
                            </p>
                            
                            <!-- Save Photo Button (muncul hanya saat ada foto baru) -->
                            @if($avatar)
                                <button wire:click="updateProfile" 
                                        wire:loading.attr="disabled"
                                        class="mt-3 bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                                    <span wire:loading.remove>Simpan Foto</span>
                                    <span wire:loading class="flex items-center">
                                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Mengupload...
                                    </span>
                                </button>
                            @endif
                        </div>

                        <!-- Quick Stats -->
                        <div class="space-y-3">
                            <div class="flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-700 rounded-xl">
                                <div class="flex items-center space-x-3">
                                    <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                                        <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-300">Status</span>
                                </div>
                                <span class="text-sm font-semibold text-emerald-600 dark:text-emerald-400">
                                    Aktif
                                </span>
                            </div>
                            
                            <div class="flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-700 rounded-xl">
                                <div class="flex items-center space-x-3">
                                    <div class="p-2 bg-green-100 dark:bg-green-900 rounded-lg">
                                        <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-300">Bergabung</span>
                                </div>
                                <span class="text-sm font-semibold text-gray-900 dark:text-white">
                                    {{ auth()->user()->created_at->format('M Y') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Tab: Personal Info -->
                    @if($activeTab === 'personal')
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
                        <div class="bg-gradient-to-r from-violet-600 to-indigo-600 px-6 py-4">
                            <h3 class="text-lg font-semibold text-white">Informasi Pribadi</h3>
                        </div>
                        <div class="p-6">
                            <form wire:submit="updateProfile">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama</label>
                                        <input type="text" wire:model="name" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 transition-all duration-300">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email</label>
                                        <input type="email" wire:model="email" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 transition-all duration-300">
                                    </div>
                                </div>
                                <button type="submit" class="mt-6 bg-violet-600 hover:bg-violet-700 text-white px-6 py-3 rounded-xl font-medium transition-colors duration-200">
                                    Simpan Perubahan
                                </button>
                            </form>
                        </div>
                    </div>
                    @endif

                    <!-- Tab: Biodata -->
                    @if($activeTab === 'biodata')
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
                        <div class="bg-gradient-to-r from-emerald-600 to-teal-600 px-6 py-4">
                            <h3 class="text-lg font-semibold text-white">Biodata Lengkap</h3>
                        </div>
                        <div class="p-6">
                            @if(auth()->user()->role === 'siswa')
                                <form wire:submit="updateProfile">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">NIS</label>
                                            <input type="text" wire:model="nis" readonly class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-600 dark:text-white cursor-not-allowed">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">NISN</label>
                                            <input type="text" wire:model="nisn" readonly class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-600 dark:text-white cursor-not-allowed">
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Lengkap</label>
                                            <input type="text" wire:model="nama_lengkap" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-300">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Jenis Kelamin</label>
                                            <select wire:model="jenis_kelamin" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-300">
                                                <option value="L">Laki-laki</option>
                                                <option value="P">Perempuan</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tempat Lahir</label>
                                            <input type="text" wire:model="tempat_lahir" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-300">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tanggal Lahir</label>
                                            <input type="date" wire:model="tanggal_lahir" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-300">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">No. Telepon</label>
                                            <input type="text" wire:model="no_telp" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-300">
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Alamat</label>
                                            <textarea wire:model="alamat" rows="3" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-300 resize-none"></textarea>
                                        </div>
                                    </div>
                                    <button type="submit" class="mt-6 bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-xl font-medium transition-colors duration-200">
                                        Simpan Biodata
                                    </button>
                                </form>
                            @elseif(auth()->user()->role === 'guru')
                                <form wire:submit="updateProfile">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">NIP</label>
                                            <input type="text" wire:model="nip" readonly class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-600 dark:text-white cursor-not-allowed">
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Lengkap</label>
                                            <input type="text" wire:model="guru_nama_lengkap" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-300">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Jenis Kelamin</label>
                                            <select wire:model="guru_jenis_kelamin" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-300">
                                                <option value="L">Laki-laki</option>
                                                <option value="P">Perempuan</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tempat Lahir</label>
                                            <input type="text" wire:model="guru_tempat_lahir" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-300">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tanggal Lahir</label>
                                            <input type="date" wire:model="guru_tanggal_lahir" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-300">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">No. Telepon</label>
                                            <input type="text" wire:model="guru_no_telp" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-300">
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Alamat</label>
                                            <textarea wire:model="guru_alamat" rows="3" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-300 resize-none"></textarea>
                                        </div>
                                    </div>
                                    <button type="submit" class="mt-6 bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-xl font-medium transition-colors duration-200">
                                        Simpan Biodata
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Tab: Security - DISABLED -->
                    @if($activeTab === 'security')
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
                        <div class="bg-gradient-to-r from-amber-500 to-orange-500 px-6 py-4">
                            <h3 class="text-lg font-semibold text-white">Keamanan Akun</h3>
                        </div>
                        <div class="p-6">
                            <!-- Disabled Message -->
                            <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl p-4 mb-6">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                    </svg>
                                    <div>
                                        <h4 class="text-sm font-medium text-amber-800 dark:text-amber-300">Fitur Dinonaktifkan</h4>
                                        <p class="text-sm text-amber-700 dark:text-amber-400 mt-1">
                                            Fitur ubah password sementara dinonaktifkan untuk keperluan testing. 
                                            Silakan hubungi administrator untuk mengubah password.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Disabled Password Form -->
                            <div class="space-y-4 opacity-50 cursor-not-allowed">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">
                                        Password Saat Ini
                                    </label>
                                    <input 
                                        type="password" 
                                        disabled
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-gray-100 dark:bg-gray-600 text-gray-500"
                                        placeholder="Fitur dinonaktifkan"
                                    >
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">
                                            Password Baru
                                        </label>
                                        <input 
                                            type="password" 
                                            disabled
                                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-gray-100 dark:bg-gray-600 text-gray-500"
                                            placeholder="Fitur dinonaktifkan"
                                        >
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">
                                            Konfirmasi Password
                                        </label>
                                        <input 
                                            type="password" 
                                            disabled
                                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-gray-100 dark:bg-gray-600 text-gray-500"
                                            placeholder="Fitur dinonaktifkan"
                                        >
                                    </div>
                                </div>
                                <button 
                                    type="button" 
                                    disabled
                                    class="w-full bg-gray-400 text-gray-200 px-6 py-3 rounded-xl font-medium cursor-not-allowed"
                                >
                                    Ubah Password (Dinonaktifkan)
                                </button>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Tab: Orang Tua -->
                    @if($activeTab === 'orangtua' && auth()->user()->role === 'siswa')
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-600 to-cyan-600 px-6 py-4">
                            <h3 class="text-lg font-semibold text-white">Data Orang Tua</h3>
                        </div>
                        <div class="p-6">
                            <p class="text-gray-600 dark:text-gray-400">
                                Data orang tua akan ditampilkan di sini. (Konten dapat dikembangkan sesuai kebutuhan)
                            </p>
                        </div>
                    </div>
                    @endif

                    <!-- Tab: Mengajar -->
                    @if($activeTab === 'mengajar' && auth()->user()->role === 'guru')
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
                        <div class="bg-gradient-to-r from-purple-600 to-pink-600 px-6 py-4">
                            <h3 class="text-lg font-semibold text-white">Data Mengajar</h3>
                        </div>
                        <div class="p-6">
                            <p class="text-gray-600 dark:text-gray-400">
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
        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
    </style>
</div>