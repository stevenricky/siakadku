<div>
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Data Guru</h1>
        <p class="text-gray-600 dark:text-gray-400">Kelola data guru dan staff pengajar</p>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <!-- Action Bar -->
    <div class="mb-6 flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-4 sm:space-y-0">
        <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4 w-full sm:w-auto">
            <!-- Search -->
            <div class="relative w-full sm:w-64">
                <input 
                    type="text" 
                    wire:model.live="search"
                    placeholder="Cari guru..." 
                    class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white text-sm"
                >
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>

            <!-- Filter Status -->
            <select 
                wire:model.live="statusFilter"
                class="w-full sm:w-auto border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white text-sm"
            >
                <option value="">Semua Status</option>
                <option value="aktif">Aktif</option>
                <option value="non-aktif">Non-Aktif</option>
            </select>

            <!-- Per Page Selector -->
            <select 
                wire:model.live="perPage" 
                class="w-full sm:w-auto border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white text-sm"
            >
                <option value="10">10 per halaman</option>
                <option value="25">25 per halaman</option>
                <option value="50">50 per halaman</option>
            </select>
        </div>

        <!-- Actions -->
        <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-3 w-full sm:w-auto">
            <!-- Export Button -->
            <button 
                wire:click="exportData"
                class="w-full sm:w-auto bg-secondary-600 hover:bg-secondary-700 text-white px-4 py-2 rounded-lg flex items-center justify-center space-x-2 transition-colors text-sm"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span>Ekspor</span>
            </button>

            <!-- Add Button -->
            <button 
                wire:click="openCreateForm"
                class="w-full sm:w-auto bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg flex items-center justify-center space-x-2 transition-colors text-sm"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>Tambah Guru</span>
            </button>
        </div>
    </div>

    <!-- Table untuk Desktop -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 hidden md:table">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('nip')">
                            <div class="flex items-center gap-1">
                                NIP
                                @if($sortField === 'nip')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"></path>
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer" wire:click="sortBy('nama_lengkap')">
                            <div class="flex items-center gap-1">
                                Nama Guru
                                @if($sortField === 'nama_lengkap')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"></path>
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jenis Kelamin</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">No. Telp</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($gurus as $guru)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                {{ $guru->nip }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $guru->nama_lengkap }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $guru->user->email }}</div>
                            </td>
<td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
    {{ $guru->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
</td>                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $guru->no_telp }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $guru->status === 'aktif' ? 'bg-success-100 text-success-800' : 'bg-danger-100 text-danger-800' }}">
                                    {{ ucfirst($guru->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end items-center space-x-2">
                                    <button 
                                        wire:click="showDetail({{ $guru->id }})"
                                        class="text-info-600 hover:text-info-900 dark:text-info-400 dark:hover:text-info-300 transition-colors"
                                        title="Detail"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                    <button 
                                        wire:click="openEditForm({{ $guru->id }})"
                                        class="text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300 transition-colors"
                                        title="Edit"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                    <!-- ⭐⭐ PERBAIKAN: Tombol delete dinonaktifkan ⭐⭐ -->
                                    <button 
                                        disabled
                                        class="text-gray-400 cursor-not-allowed opacity-50"
                                        title="Fungsi hapus dinonaktifkan"
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
                            <td colspan="6" class="px-4 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                Tidak ada data guru ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Card View untuk Mobile -->
            <div class="md:hidden space-y-4 p-4">
                @forelse($gurus as $guru)
                    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 shadow-sm">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ $guru->nama_lengkap }}
                                </h3>
                                <div class="flex items-center space-x-2 mt-1">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ $guru->nip }}
                                    </span>
                                    <span class="inline-flex px-2 py-0.5 text-xs font-semibold rounded-full {{ $guru->status === 'aktif' ? 'bg-success-100 text-success-800' : 'bg-danger-100 text-danger-800' }}">
                                        {{ ucfirst($guru->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                            <div class="flex justify-between">
                                <span>Email:</span>
                                <span class="text-gray-900 dark:text-white font-medium">
                                    {{ $guru->user->email }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span>Jenis Kelamin:</span>
                                <span class="text-gray-900 dark:text-white font-medium">
                                    {{ $guru->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span>No. Telp:</span>
                                <span class="text-gray-900 dark:text-white font-medium">
                                    {{ $guru->no_telp }}
                                </span>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-2 mt-4 pt-3 border-t border-gray-200 dark:border-gray-700">
                            <button 
                                wire:click="showDetail({{ $guru->id }})"
                                class="px-3 py-1 text-sm text-info-600 hover:text-info-800 dark:text-info-400 dark:hover:text-info-300 border border-info-600 dark:border-info-400 rounded-lg transition-colors"
                            >
                                Detail
                            </button>
                            <button 
                                wire:click="openEditForm({{ $guru->id }})"
                                class="px-3 py-1 text-sm text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300 border border-primary-600 dark:border-primary-400 rounded-lg transition-colors"
                            >
                                Edit
                            </button>
                            <!-- ⭐⭐ PERBAIKAN: Tombol delete dinonaktifkan ⭐⭐ -->
                            <button 
                                disabled
                                class="px-3 py-1 text-sm text-gray-400 cursor-not-allowed opacity-50 border border-gray-300 dark:border-gray-600 rounded-lg"
                            >
                                Hapus
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                        Tidak ada data guru ditemukan.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Pagination -->
        @if($gurus->hasPages())
            <div class="px-4 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $gurus->links() }}
            </div>
        @endif
    </div>

    <!-- Form Modal -->
    @if($showForm)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                <div class="p-4 sm:p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $formType === 'create' ? 'Tambah Guru Baru' : 'Edit Data Guru' }}
                        </h3>
                        <button 
                            wire:click="resetForm"
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
                        >
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <form wire:submit="saveGuru">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <!-- Nama Lengkap -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Nama Lengkap *
                                </label>
                                <input 
                                    type="text" 
                                    wire:model="nama_lengkap"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white text-sm"
                                    required
                                >
                                @error('nama_lengkap') <span class="text-danger-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- NIP -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    NIP *
                                </label>
                                <input 
                                    type="text" 
                                    wire:model="nip"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white text-sm"
                                    required
                                >
                                @error('nip') <span class="text-danger-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Email *
                                </label>
                                <input 
                                    type="email" 
                                    wire:model="email"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white text-sm"
                                    required
                                >
                                @error('email') <span class="text-danger-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Jenis Kelamin -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Jenis Kelamin *
                                </label>
                                <select 
                                    wire:model="jenis_kelamin"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white text-sm"
                                    required
                                >
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                                @error('jenis_kelamin') <span class="text-danger-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Tempat Lahir -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Tempat Lahir *
                                </label>
                                <input 
                                    type="text" 
                                    wire:model="tempat_lahir"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white text-sm"
                                    required
                                >
                                @error('tempat_lahir') <span class="text-danger-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Tanggal Lahir -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Tanggal Lahir *
                                </label>
                                <input 
                                    type="date" 
                                    wire:model="tanggal_lahir"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white text-sm"
                                    required
                                >
                                @error('tanggal_lahir') <span class="text-danger-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- No. Telp -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    No. Telepon *
                                </label>
                                <input 
                                    type="text" 
                                    wire:model="no_telp"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white text-sm"
                                    required
                                >
                                @error('no_telp') <span class="text-danger-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Status -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Status *
                                </label>
                                <select 
                                    wire:model="status"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white text-sm"
                                    required
                                >
                                    <option value="aktif">Aktif</option>
                                    <option value="non-aktif">Non-Aktif</option>
                                </select>
                                @error('status') <span class="text-danger-500 text-xs">{{ $message }}</span> @enderror
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
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white text-sm"
                                required
                            ></textarea>
                            @error('alamat') <span class="text-danger-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Form Actions -->
                        <div class="flex flex-col sm:flex-row sm:justify-end sm:space-x-3 space-y-3 sm:space-y-0 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <button 
                                type="button"
                                wire:click="resetForm"
                                class="w-full sm:w-auto px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors"
                            >
                                Batal
                            </button>
                            <button 
                                type="submit"
                                class="w-full sm:w-auto px-4 py-2 text-sm font-medium text-white bg-primary-600 border border-transparent rounded-lg hover:bg-primary-700 transition-colors"
                            >
                                {{ $formType === 'create' ? 'Simpan' : 'Update' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Detail Modal -->
    @if($showDetailModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-md mx-auto max-h-[90vh] overflow-y-auto">
                <div class="p-4 sm:p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Detail Guru
                        </h3>
                        <button 
                            wire:click="$set('showDetailModal', false)"
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
                        >
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    @if($selectedGuru)
                        <div class="space-y-4">
                            <div class="flex items-center space-x-4">
                                <div class="w-16 h-16 bg-primary-100 dark:bg-primary-900 rounded-full flex items-center justify-center">
                                    <span class="text-primary-600 dark:text-primary-400 text-xl font-semibold">
                                        {{ substr($selectedGuru->nama_lengkap, 0, 1) }}
                                    </span>
                                </div>
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white">
                                        {{ $selectedGuru->nama_lengkap }}
                                    </h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $selectedGuru->nip }}
                                    </p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 gap-3 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-500 dark:text-gray-400">Email:</span>
                                    <span class="text-gray-900 dark:text-white font-medium">{{ $selectedGuru->user->email }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500 dark:text-gray-400">Jenis Kelamin:</span>
                                    <span class="text-gray-900 dark:text-white font-medium">
                                        {{ $selectedGuru->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500 dark:text-gray-400">Tempat, Tanggal Lahir:</span>
                                    <span class="text-gray-900 dark:text-white font-medium text-right">
                                        {{ $selectedGuru->tempat_lahir }}, {{ \Carbon\Carbon::parse($selectedGuru->tanggal_lahir)->format('d/m/Y') }}
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500 dark:text-gray-400">No. Telepon:</span>
                                    <span class="text-gray-900 dark:text-white font-medium">{{ $selectedGuru->no_telp }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500 dark:text-gray-400">Status:</span>
                                    <span class="inline-flex px-2 py-0.5 text-xs font-semibold rounded-full {{ $selectedGuru->status === 'aktif' ? 'bg-success-100 text-success-800' : 'bg-danger-100 text-danger-800' }}">
                                        {{ ucfirst($selectedGuru->status) }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-gray-500 dark:text-gray-400">Alamat:</span>
                                    <p class="text-gray-900 dark:text-white font-medium mt-1">
                                        {{ $selectedGuru->alamat }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if($showDeleteModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-md mx-auto">
                <div class="p-6">
                    <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 dark:bg-red-900 rounded-full">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                            Hapus Data Guru
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Apakah Anda yakin ingin menghapus data guru <strong>{{ $guruToDelete?->nama_lengkap }}</strong>? Tindakan ini tidak dapat dibatalkan.
                            </p>
                        </div>
                    </div>
                    <div class="mt-4 flex flex-col sm:flex-row sm:justify-end sm:space-x-3 space-y-3 sm:space-y-0">
                        <button 
                            type="button"
                            wire:click="$set('showDeleteModal', false)"
                            class="w-full sm:w-auto px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors"
                        >
                            Batal
                        </button>
                        <button 
                            type="button"
                            wire:click="deleteGuru"
                            class="w-full sm:w-auto px-4 py-2 text-sm font-medium text-white bg-danger-600 border border-transparent rounded-lg hover:bg-danger-700 transition-colors"
                        >
                            Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Loading Indicator -->
    <div wire:loading class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 flex items-center space-x-3">
            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-primary-600"></div>
            <span class="text-gray-900 dark:text-white">Memuat...</span>
        </div>
    </div>
</div>