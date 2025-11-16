<div>
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Manajemen Mata Pelajaran</h1>
        <p class="text-gray-600 dark:text-gray-400">Kelola data mata pelajaran SMA</p>
    </div>

    <!-- Action Bar -->
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <!-- Search and Filters -->
        <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto">
            <!-- Search Input -->
            <div class="w-full sm:w-64">
                <div class="relative">
                    <input 
                        type="text" 
                        wire:model.live.debounce.300ms="search"
                        placeholder="Cari mata pelajaran..." 
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                    >
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Filters Container -->
            <div class="flex flex-wrap gap-2">
                <!-- Filter by Tingkat -->
                <select 
                    wire:model.live="tingkatFilter"
                    class="flex-1 min-w-[120px] border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white text-sm"
                >
                    <option value="">Semua Tingkat</option>
                    <option value="10">Kelas 10</option>
                    <option value="11">Kelas 11</option>
                    <option value="12">Kelas 12</option>
                </select>

                <!-- Filter by Jurusan -->
                <select 
                    wire:model.live="jurusanFilter"
                    class="flex-1 min-w-[120px] border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white text-sm"
                >
                    <option value="">Semua Jurusan</option>
                    <option value="Umum">Umum</option>
                    <option value="IPA">IPA</option>
                    <option value="IPS">IPS</option>
                </select>

                <!-- Per Page Selector -->
                <select 
                    wire:model.live="perPage"
                    class="flex-1 min-w-[140px] border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white text-sm"
                >
                    <option value="5">5 per halaman</option>
                    <option value="10">10 per halaman</option>
                    <option value="25">25 per halaman</option>
                </select>

                <!-- Reset Filters Button -->
                <button 
                    wire:click="resetFilters"
                    class="flex items-center gap-2 px-3 py-2 text-sm bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    <span class="hidden sm:inline">Reset</span>
                </button>
            </div>
        </div>

        <!-- Add Button -->
        <button 
            wire:click="openCreateForm"
            class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg flex items-center justify-center gap-2 transition-colors w-full sm:w-auto"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span>Tambah Mapel</span>
        </button>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg dark:bg-green-900 dark:border-green-700 dark:text-green-300">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg dark:bg-red-900 dark:border-red-700 dark:text-red-300">
            {{ session('error') }}
        </div>
    @endif

    <!-- Table Container -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <!-- Mobile Card View -->
        <div class="sm:hidden space-y-3 p-4">
            @forelse($mapels as $mapel)
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                    <div class="space-y-3">
                        <!-- Header -->
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900 dark:text-white text-sm">
                                    {{ $mapel->nama_mapel }}
                                </h3>
                                <p class="text-xs text-gray-600 dark:text-gray-400">
                                    {{ $mapel->kode_mapel }}
                                </p>
                            </div>
                            <div class="flex items-center gap-1">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    K{{ $mapel->tingkat }}
                                </span>
                            </div>
                        </div>

                        <!-- Details -->
                        <div class="grid grid-cols-2 gap-2 text-xs">
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                <span>Jurusan: {{ $mapel->jurusan ?? 'Umum' }}</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>KKM: {{ $mapel->kkm }}</span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-between items-center pt-2 border-t border-gray-200 dark:border-gray-600">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                Aktif
                            </span>
                            <div class="flex items-center gap-2">
                                <button 
                                    wire:click="openEditForm({{ $mapel->id }})"
                                    class="text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300 text-sm font-medium px-3 py-1 rounded border border-primary-200 dark:border-primary-800 hover:bg-primary-50 dark:hover:bg-primary-900/20 transition-colors"
                                >
                                    Edit
                                </button>
                                <!-- ⭐⭐ PERBAIKAN: Tombol delete dinonaktifkan ⭐⭐ -->
                                <button 
                                    disabled
                                    class="text-gray-400 cursor-not-allowed opacity-50 text-sm font-medium px-3 py-1 rounded border border-gray-300 dark:border-gray-600"
                                >
                                    Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-8">
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <p class="text-lg font-medium text-gray-900 dark:text-white mb-1">Tidak ada data mata pelajaran</p>
                    <p class="text-gray-600 dark:text-gray-400">Mulai dengan menambahkan mata pelajaran baru</p>
                </div>
            @endforelse
        </div>

        <!-- Desktop Table View -->
        <div class="hidden sm:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Nama Mapel
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Tingkat
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Jurusan
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            KKM
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($mapels as $mapel)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $mapel->nama_mapel }}
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $mapel->kode_mapel }}
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    Kelas {{ $mapel->tingkat }}
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ $mapel->jurusan ?? 'Umum' }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ $mapel->kkm }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    Aktif
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end items-center gap-2">
                                    <button 
                                        wire:click="openEditForm({{ $mapel->id }})"
                                        class="text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300 transition-colors p-1 rounded hover:bg-primary-50 dark:hover:bg-primary-900/20"
                                        title="Edit"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <!-- ⭐⭐ PERBAIKAN: Tombol delete dinonaktifkan ⭐⭐ -->
                                    <button 
                                        disabled
                                        class="text-gray-400 cursor-not-allowed opacity-50 transition-colors p-1 rounded"
                                        title="Fungsi hapus dinonaktifkan"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-500 dark:text-gray-400">
                                    <svg class="w-12 h-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                    <p class="text-lg font-medium mb-1">Tidak ada data mata pelajaran</p>
                                    <p class="text-sm">Mulai dengan menambahkan mata pelajaran baru</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($mapels->hasPages())
            <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-3">
                    <div class="text-sm text-gray-700 dark:text-gray-300">
                        Menampilkan {{ $mapels->firstItem() }} - {{ $mapels->lastItem() }} dari {{ $mapels->total() }} hasil
                    </div>
                    <div>
                        {{ $mapels->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Form Modal -->
    @if($showForm)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-2 z-50" x-data>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-2xl max-h-[95vh] overflow-y-auto m-2" 
                 x-on:click.outside="$wire.closeForm()">
                <div class="p-4 sm:p-6">
                    <div class="flex justify-between items-center mb-4 sm:mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $formType === 'create' ? 'Tambah Mata Pelajaran' : 'Edit Mata Pelajaran' }}
                        </h3>
                        <button 
                            wire:click="closeForm"
                            type="button"
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors p-1"
                        >
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <form wire:submit.prevent="saveMapel">
                        <div class="grid grid-cols-1 gap-4 mb-6">
                            <!-- Kode Mapel -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Kode Mapel *
                                </label>
                                <input 
                                    type="text" 
                                    wire:model="kode_mapel"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white text-base"
                                    placeholder="Contoh: MAT-10"
                                    required
                                >
                                @error('kode_mapel') 
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span> 
                                @enderror
                            </div>

                            <!-- Nama Mapel -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Nama Mapel *
                                </label>
                                <input 
                                    type="text" 
                                    wire:model="nama_mapel"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white text-base"
                                    placeholder="Contoh: Matematika"
                                    required
                                >
                                @error('nama_mapel') 
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span> 
                                @enderror
                            </div>

                            <!-- Tingkat & Jurusan Grid -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Tingkat -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Tingkat *
                                    </label>
                                    <select 
                                        wire:model="tingkat"
                                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white text-base"
                                        required
                                    >
                                        <option value="">Pilih Tingkat</option>
                                        @foreach($tingkatList as $tingkat)
                                            <option value="{{ $tingkat }}">Kelas {{ $tingkat }}</option>
                                        @endforeach
                                    </select>
                                    @error('tingkat') 
                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span> 
                                    @enderror
                                </div>

                                <!-- Jurusan -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Jurusan
                                    </label>
                                    <select 
                                        wire:model="jurusan"
                                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white text-base"
                                    >
                                        <option value="">Umum</option>
                                        @foreach($jurusanList as $jurusan)
                                            @if($jurusan)
                                                <option value="{{ $jurusan }}">{{ $jurusan }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @error('jurusan') 
                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span> 
                                    @enderror
                                </div>
                            </div>

                            <!-- KKM -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    KKM (Kriteria Ketuntasan Minimal) *
                                </label>
                                <input 
                                    type="number" 
                                    wire:model="kkm"
                                    min="0" 
                                    max="100"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white text-base"
                                    required
                                >
                                @error('kkm') 
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span> 
                                @enderror
                            </div>

                            <!-- Deskripsi -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Deskripsi
                                </label>
                                <textarea 
                                    wire:model="deskripsi"
                                    rows="3"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-3 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white text-base"
                                    placeholder="Deskripsi singkat mata pelajaran..."
                                ></textarea>
                                @error('deskripsi') 
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span> 
                                @enderror
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <button 
                                type="button" 
                                wire:click="closeForm"
                                wire:loading.attr="disabled"
                                class="w-full sm:w-auto px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors disabled:opacity-50 order-2 sm:order-1"
                            >
                                Batal
                            </button>
                            <button 
                                type="submit"
                                wire:loading.attr="disabled"
                                class="w-full sm:w-auto px-4 py-3 text-sm font-medium text-white bg-primary-600 border border-transparent rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors disabled:opacity-50 flex items-center justify-center gap-2 order-1 sm:order-2"
                            >
                                <span wire:loading.remove wire:target="saveMapel">
                                    {{ $formType === 'create' ? 'Simpan Mapel' : 'Update Mapel' }}
                                </span>
                                <span wire:loading wire:target="saveMapel">
                                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Menyimpan...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Loading Indicator -->
    <div wire:loading.flex class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-4 sm:p-6 flex items-center gap-3 m-4">
            <svg class="w-6 h-6 animate-spin text-primary-600" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="text-gray-700 dark:text-gray-300 text-sm sm:text-base">Memproses...</span>
        </div>
    </div>
</div>