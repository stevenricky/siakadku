<div>
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Manajemen Kelas & Jurusan</h1>
        <p class="text-gray-600 dark:text-gray-400">Kelola data kelas, jurusan, dan wali kelas</p>
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

    <!-- Action Buttons -->
<div class="mb-6 flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-4 sm:space-y-0">
    <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
        <!-- Search Input -->
        <div class="relative w-full sm:w-64">
            <input 
                type="text" 
                wire:model.live="search"
                placeholder="Cari kelas..." 
                class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white text-sm"
            >
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
        </div>

        <!-- Items Per Page -->
        <select 
            wire:model.live="perPage"
            class="w-full sm:w-auto border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white text-sm"
        >
            <option value="10">10 per halaman</option>
            <option value="25">25 per halaman</option>
            <option value="50">50 per halaman</option>
        </select>
    </div>

    <!-- Add Button -->
    <button 
        wire:click="openCreateForm"
        class="w-full sm:w-auto bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg flex items-center justify-center space-x-2 transition-colors text-sm"
    >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        <span>Tambah Kelas</span>
    </button>
</div>

    <!-- Table untuk Desktop -->
<div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 hidden md:table">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Kelas
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Tingkat
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Jurusan
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Wali Kelas
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Kapasitas
                    </th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($kelas as $kelasItem)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $kelasItem->nama_kelas }}
                            </div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                Kelas {{ $kelasItem->tingkat }}
                            </span>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            {{ $kelasItem->jurusan }}
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            @if($kelasItem->waliKelas)
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $kelasItem->waliKelas->nama_guru }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $kelasItem->waliKelas->nip }}
                                </div>
                            @else
                                <span class="text-sm text-gray-500 dark:text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            {{ $kelasItem->kapasitas }} siswa
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button 
                                wire:click="openEditForm({{ $kelasItem->id }})"
                                class="text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300 mr-3"
                            >
                                Edit
                            </button>
                            <!-- ⭐⭐ PERBAIKAN: Tombol delete dinonaktifkan ⭐⭐ -->
                            <button 
                                disabled
                                class="text-gray-400 cursor-not-allowed opacity-50"
                                title="Fungsi hapus dinonaktifkan"
                            >
                                Hapus
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                            Tidak ada data kelas.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Card View untuk Mobile -->
        <div class="md:hidden space-y-4 p-4">
            @forelse($kelas as $kelasItem)
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 shadow-sm">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                {{ $kelasItem->nama_kelas }}
                            </h3>
                            <div class="flex items-center space-x-2 mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    Kelas {{ $kelasItem->tingkat }}
                                </span>
                                <span class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ $kelasItem->jurusan }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                        <div class="flex justify-between">
                            <span>Wali Kelas:</span>
                            <span class="text-gray-900 dark:text-white font-medium">
                                @if($kelasItem->waliKelas)
                                    {{ $kelasItem->waliKelas->nama_guru }}
                                @else
                                    -
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span>Kapasitas:</span>
                            <span class="text-gray-900 dark:text-white font-medium">
                                {{ $kelasItem->kapasitas }} siswa
                            </span>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-2 mt-4 pt-3 border-t border-gray-200 dark:border-gray-700">
                        <button 
                            wire:click="openEditForm({{ $kelasItem->id }})"
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
                    Tidak ada data kelas.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Pagination -->
    @if($kelas->hasPages())
        <div class="px-4 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $kelas->links() }}
        </div>
    @endif
</div>

    <!-- Form Modal -->
@if($showForm)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-md mx-auto max-h-[90vh] overflow-y-auto">
            <div class="p-4 sm:p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        {{ $formType === 'create' ? 'Tambah Kelas' : 'Edit Kelas' }}
                    </h3>
                    <button 
                        type="button"
                        wire:click="$set('showForm', false)"
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="saveKelas">
                    <!-- Form fields tetap sama, tapi tambahkan class responsive -->
                    <div class="space-y-4">
                        <!-- Nama Kelas -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Nama Kelas
                            </label>
                            <input 
                                type="text" 
                                wire:model="nama_kelas"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white text-sm"
                                placeholder="Contoh: X IPA 1"
                            >
                            @error('nama_kelas') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Tingkat -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Tingkat
                            </label>
                            <select 
                                wire:model="tingkat"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white text-sm"
                            >
                                <option value="">Pilih Tingkat</option>
                                @foreach($tingkatList as $tingkatItem)
                                    <option value="{{ $tingkatItem }}">Kelas {{ $tingkatItem }}</option>
                                @endforeach
                            </select>
                            @error('tingkat') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Jurusan -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Jurusan
                            </label>
                            <select 
                                wire:model="jurusan"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white text-sm"
                            >
                                <option value="">Pilih Jurusan</option>
                                @foreach($jurusanList as $jurusanItem)
                                    <option value="{{ $jurusanItem }}">{{ $jurusanItem }}</option>
                                @endforeach
                            </select>
                            @error('jurusan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Wali Kelas -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Wali Kelas
                            </label>
                            <select 
                                wire:model="wali_kelas_id"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white text-sm"
                            >
                                <option value="">Pilih Wali Kelas</option>
                                @foreach($guruList as $guru)
                                    <option value="{{ $guru->id }}">{{ $guru->nama_guru }}</option>
                                @endforeach
                            </select>
                            @error('wali_kelas_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Kapasitas -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Kapasitas Siswa
                            </label>
                            <input 
                                type="number" 
                                wire:model="kapasitas"
                                min="1"
                                max="50"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white text-sm"
                            >
                            @error('kapasitas') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex flex-col sm:flex-row sm:justify-end sm:space-x-3 space-y-3 sm:space-y-0 mt-6">
                        <button 
                            type="button"
                            wire:click="$set('showForm', false)"
                            class="w-full sm:w-auto px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600 transition-colors"
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

    <!-- Loading Indicator -->
    <div wire:loading class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 flex items-center space-x-3">
            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-primary-600"></div>
            <span class="text-gray-900 dark:text-white">Memuat...</span>
        </div>
    </div>
</div>