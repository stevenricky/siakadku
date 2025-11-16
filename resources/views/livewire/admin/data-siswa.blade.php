<div>
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Data Siswa</h1>
        <p class="text-gray-600 dark:text-gray-400">Kelola data siswa dan peserta didik</p>
    </div>

    <!-- Action Bar -->
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <!-- Search -->
        <div class="w-full sm:w-auto">
            <div class="relative">
                <input 
                    type="text" 
                    wire:model.live="search"
                    placeholder="Cari siswa..." 
                    class="w-full sm:w-64 pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                >
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center gap-3">
            <!-- Per Page Selector -->
            <select wire:model.live="perPage" class="border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-white">
                <option value="10">10 per halaman</option>
                <option value="25">25 per halaman</option>
                <option value="50">50 per halaman</option>
            </select>

            <!-- Add Button -->
            <button 
                wire:click="openCreateForm"
                class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Siswa
            </button>
        </div>
    </div>

    <!-- Card Layout for Mobile -->
    <div class="md:hidden bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden mb-6">
        <div class="p-4">
            @forelse($siswas as $siswa)
                <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-4 last:border-0 last:pb-0 last:mb-0">
                    <!-- Header with NIS and Status -->
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">NIS</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $siswa->nis }}</p>
                        </div>
                        @php
                            $statusColors = [
                                'aktif' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                'lulus' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200', 
                                'pindah' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200'
                            ];
                        @endphp
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$siswa->status] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                            {{ ucfirst($siswa->status) }}
                        </span>
                    </div>
                    
                    <!-- Name and Email -->
                    <div class="mb-3">
                        <h3 class="text-base font-medium text-gray-900 dark:text-white">{{ $siswa->nama_lengkap }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $siswa->user->email }}</p>
                    </div>
                    
                    <!-- Details Grid -->
                    <div class="grid grid-cols-2 gap-2 mb-3 text-sm">
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Kelas</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $siswa->kelas->nama_kelas }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Jenis Kelamin</p>
                            <p class="font-medium text-gray-900 dark:text-white">
                                @if($siswa->getRawOriginal('jenis_kelamin') === 'L')
                                    Laki-laki
                                @else
                                    Perempuan
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">NISN</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $siswa->nisn }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">No. Telp</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $siswa->no_telp }}</p>
                        </div>
                    </div>
                    
                    <!-- Actions -->
                    <div class="flex gap-2">
                        <button 
                            wire:click="openEditForm({{ $siswa->id }})"
                            class="flex-1 bg-blue-50 text-blue-600 hover:bg-blue-100 dark:bg-blue-900 dark:text-blue-400 dark:hover:bg-blue-800 px-3 py-2 rounded-lg text-sm font-medium flex items-center justify-center gap-1 transition-colors"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit
                        </button>
                        <button 
                            class="flex-1 bg-gray-100 text-gray-400 px-3 py-2 rounded-lg text-sm font-medium flex items-center justify-center gap-1 cursor-not-allowed"
                            title="Fitur hapus sementara dinonaktifkan"
                            onclick="alert('❌ Fitur hapus data siswa sedang dinonaktifkan!')"
                            disabled
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Hapus
                        </button>
                    </div>
                </div>
            @empty
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Tidak ada data siswa ditemukan.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination for Mobile -->
        @if($siswas->hasPages())
            <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                {{ $siswas->links() }}
            </div>
        @endif
    </div>

    <!-- Table Layout for Desktop -->
    <div class="hidden md:block bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('nis')">
                            <div class="flex items-center gap-1">
                                NIS
                                @if($sortField === 'nis')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"></path>
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('nama_lengkap')">
                            <div class="flex items-center gap-1">
                                Nama Siswa
                                @if($sortField === 'nama_lengkap')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"></path>
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kelas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jenis Kelamin</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($siswas as $siswa)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                {{ $siswa->nis }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $siswa->nama_lengkap }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $siswa->user->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $siswa->kelas->nama_kelas }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                @if($siswa->getRawOriginal('jenis_kelamin') === 'L')
                                    Laki-laki
                                @else
                                    Perempuan
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusColors = [
                                        'aktif' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                        'lulus' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200', 
                                        'pindah' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200'
                                    ];
                                @endphp
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$siswa->status] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                                    {{ ucfirst($siswa->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center gap-2">
                                    <button 
                                        wire:click="openEditForm({{ $siswa->id }})"
                                        class="text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300 transition-colors"
                                        title="Edit"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                    
                                    <button 
                                        class="text-gray-400 cursor-not-allowed opacity-50"
                                        title="Fitur hapus sementara dinonaktifkan"
                                        onclick="alert('❌ Fitur hapus data siswa sedang dinonaktifkan!')"
                                        disabled
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                Tidak ada data siswa ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination for Desktop -->
        @if($siswas->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $siswas->links() }}
            </div>
        @endif
    </div>

    <!-- Form Modal -->
    @if($showForm)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $formType === 'create' ? 'Tambah Siswa Baru' : 'Edit Data Siswa' }}
                        </h3>
                        <button 
                            wire:click="closeForm"
                            type="button"
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
                        >
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <form wire:submit="saveSiswa">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <!-- Nama Lengkap -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Nama Lengkap *
                                </label>
                                <input 
                                    type="text" 
                                    wire:model="nama_lengkap"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                                    required
                                >
                                @error('nama_lengkap') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- NIS -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    NIS *
                                </label>
                                <input 
                                    type="text" 
                                    wire:model="nis"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                                    required
                                >
                                @error('nis') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- NISN -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    NISN *
                                </label>
                                <input 
                                    type="text" 
                                    wire:model="nisn"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                                    required
                                >
                                @error('nisn') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Email *
                                </label>
                                <input 
                                    type="email" 
                                    wire:model="email"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                                    required
                                >
                                @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Jenis Kelamin -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Jenis Kelamin *
                                </label>
                                <select 
                                    wire:model="jenis_kelamin"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                                    required
                                >
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                                @error('jenis_kelamin') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Kelas -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Kelas & Wali Kelas *
                                </label>
                                <select 
                                    wire:model="kelas_id"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                                    required
                                >
                                    <option value="">Pilih Kelas</option>
                                    @foreach($kelasList as $kelas)
                                        @php
                                            $jumlahSiswa = $kelas->siswa->count();
                                            $ketersediaan = $kelas->kapasitas - $jumlahSiswa;
                                            $waliKelas = $kelas->waliKelas ? $kelas->waliKelas->nama_lengkap : 'Belum ada';
                                            $statusKelas = $ketersediaan > 0 ? "Tersedia {$ketersediaan}" : 'Penuh';
                                        @endphp
                                        <option value="{{ $kelas->id }}" 
                                            {{ $ketersediaan <= 0 ? 'disabled' : '' }}>
                                            {{ $kelas->nama_kelas }} - 
                                            Wali: {{ $waliKelas }} - 
                                            {{ $jumlahSiswa }}/{{ $kelas->kapasitas }} ({{ $statusKelas }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('kelas_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                
                                <!-- Info tambahan -->
                                @if($kelas_id)
                                    @php
                                        $selectedKelas = $kelasList->firstWhere('id', $kelas_id);
                                        $waliKelas = $selectedKelas ? $selectedKelas->waliKelas : null;
                                    @endphp
                                    @if($waliKelas)
                                        <div class="mt-2 p-2 bg-blue-50 dark:bg-blue-900 rounded-lg">
                                            <p class="text-sm text-blue-700 dark:text-blue-300">
                                                <strong>Wali Kelas:</strong> {{ $waliKelas->nama_lengkap }}<br>
                                                <strong>NIP:</strong> {{ $waliKelas->nip }}<br>
                                                <strong>No. Telp:</strong> {{ $waliKelas->no_telp }}
                                            </p>
                                        </div>
                                    @else
                                        <div class="mt-2 p-2 bg-yellow-50 dark:bg-yellow-900 rounded-lg">
                                            <p class="text-sm text-yellow-700 dark:text-yellow-300">
                                                <strong>Perhatian:</strong> Kelas ini belum memiliki wali kelas
                                            </p>
                                        </div>
                                    @endif
                                @endif
                            </div>

                            <!-- Tempat Lahir -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Tempat Lahir *
                                </label>
                                <input 
                                    type="text" 
                                    wire:model="tempat_lahir"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                                    required
                                >
                                @error('tempat_lahir') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Tanggal Lahir -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Tanggal Lahir *
                                </label>
                                <input 
                                    type="date" 
                                    wire:model="tanggal_lahir"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                                    required
                                >
                                @error('tanggal_lahir') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- No. Telp -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    No. Telepon *
                                </label>
                                <input 
                                    type="text" 
                                    wire:model="no_telp"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                                    required
                                >
                                @error('no_telp') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Status -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Status *
                                </label>
                                <select 
                                    wire:model="status"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                                    required
                                >
                                    <option value="aktif">Aktif</option>
                                    <option value="lulus">Lulus</option>
                                    <option value="pindah">Pindah</option>
                                </select>
                                @error('status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Alamat -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Alamat Lengkap *
                            </label>
                            <textarea 
                                wire:model="alamat"
                                rows="3"
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                                required
                            ></textarea>
                            @error('alamat') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Form Actions -->
                        <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <button 
                                type="button"
                                wire:click="closeForm"
                                class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors"
                            >
                                Batal
                            </button>
                            <button 
                                type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-primary-600 border border-transparent rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors"
                            >
                                {{ $formType === 'create' ? 'Simpan' : 'Update' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="fixed top-4 right-4 z-50">
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg shadow-lg flex items-center gap-3">
                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="fixed top-4 right-4 z-50">
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg shadow-lg flex items-center gap-3">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    document.addEventListener('livewire:initialized', () => {
        // Auto close flash messages setelah 5 detik
        setTimeout(() => {
            const flashMessages = document.querySelectorAll('.fixed');
            flashMessages.forEach(message => {
                if (message.textContent.includes('success') || message.textContent.includes('error')) {
                    message.style.display = 'none';
                }
            });
        }, 5000);
    });
    </script>
</div>