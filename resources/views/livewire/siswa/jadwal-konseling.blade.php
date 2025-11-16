<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Jadwal Konseling</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Kelola jadwal konseling Anda</p>
            </div>
            <div class="mt-4 md:mt-0">
    <button 
        wire:click="openCreateModal"
        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white text-sm font-medium rounded-lg transition-all shadow-md hover:shadow-lg"
    >
        <i class="fas fa-calendar-plus mr-2"></i>Buat Janji Baru
    </button>
</div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                        <i class="fas fa-calendar-alt text-blue-600 dark:text-blue-400 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Konseling</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalKonseling }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Hari Ini</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $konselingHariIni }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900 rounded-lg flex items-center justify-center">
                        <i class="fas fa-clock text-orange-600 dark:text-orange-400 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Akan Datang</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $konselingAkanDatang }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center">
                        <i class="fas fa-clipboard-check text-purple-600 dark:text-purple-400 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Selesai</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $konselingSelesai }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <!-- Search -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cari Konseling</label>
                <div class="relative">
                    <input 
                        type="text" 
                        wire:model.live="search"
                        placeholder="Cari permasalahan, tempat, guru..." 
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                    >
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                </div>
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                <select 
                    wire:model.live="filterStatus"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                >
                    <option value="">Semua Status</option>
                    @foreach($statusOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Tanggal Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal</label>
                <select 
                    wire:model.live="filterTanggal"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                >
                    @foreach($tanggalOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Layanan Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Layanan</label>
                <select 
                    wire:model.live="filterLayanan"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                >
                    <option value="">Semua Layanan</option>
                    @foreach($layananOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Items Per Page -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Items per Page</label>
                <select 
                    wire:model.live="perPage"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                >
                    <option value="5">5 Items</option>
                    <option value="10">10 Items</option>
                    <option value="15">15 Items</option>
                    <option value="20">20 Items</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Content -->
    @if($konseling->count() > 0)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal & Waktu</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Layanan & Guru</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tempat</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                        @foreach($konseling as $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-4 py-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $item->tanggal_konseling->format('d M Y') }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ \Carbon\Carbon::parse($item->waktu_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($item->waktu_selesai)->format('H:i') }}
                                    </div>
                                </td>
                                <td class="px-4 py-4">
    <div class="text-sm font-medium text-gray-900 dark:text-white">
        {{ $item->layananBk->nama_layanan ?? '-' }}
    </div>
    <div class="text-sm text-gray-500 dark:text-gray-400">
        {{ $item->guruBk->nama_lengkap ?? '-' }} <!-- PERBAIKAN: nama_lengkap -->
    </div>
</td>
                                <td class="px-4 py-4 text-sm text-gray-900 dark:text-white">
                                    {{ $item->tempat }}
                                </td>
                                <td class="px-4 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        @if($item->status == 'terjadwal') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                        @elseif($item->status == 'selesai') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                        @elseif($item->status == 'dibatalkan') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                        @endif">
                                        {{ $item->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex space-x-2">
                                        <button 
                                            wire:click="showDetail({{ $item->id }})"
                                            class="inline-flex items-center px-3 py-1.5 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors"
                                        >
                                            <i class="fas fa-eye mr-1"></i> Detail
                                        </button>
                                        @if($item->status == 'terjadwal')
                                            <button 
                                                wire:click="batalkanKonseling({{ $item->id }})"
                                                wire:confirm="Apakah Anda yakin ingin membatalkan konseling ini?"
                                                class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-lg text-white bg-red-600 hover:bg-red-700 transition-colors"
                                            >
                                                <i class="fas fa-times mr-1"></i> Batalkan
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="flex items-center justify-between">
            <p class="text-sm text-gray-700 dark:text-gray-300">
                Menampilkan {{ $konseling->firstItem() }} hingga {{ $konseling->lastItem() }} dari {{ $konseling->total() }} konseling
            </p>
            <div>
                {{ $konseling->links() }}
            </div>
        </div>
    @else
        <!-- Empty State -->
        <div class="text-center py-12">
            <div class="mx-auto w-24 h-24 mb-4 text-gray-400">
                <i class="fas fa-calendar-times text-6xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Belum ada jadwal konseling</h3>
            <p class="text-gray-500 dark:text-gray-400 mb-4">Mulai buat janji konseling pertama Anda.</p>
            <button 
                wire:click="openCreateModal"
                class="btn btn-primary"
            >
                <i class="fas fa-calendar-plus mr-2"></i>Buat Janji Konseling
            </button>
            @if($search || $filterStatus || $filterTanggal || $filterLayanan)
                <button 
                    wire:click="$set(['search' => '', 'filterStatus' => '', 'filterTanggal' => '', 'filterLayanan' => ''])"
                    class="btn btn-outline-primary ml-2"
                >
                    Tampilkan Semua
                </button>
            @endif
        </div>
    @endif

    <!-- Detail Modal -->
    @if($showDetailModal && $selectedKonseling)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeDetail"></div>

                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">
                                    Detail Konseling
                                </h3>
                                
                                <div class="space-y-4">
                                    <!-- Informasi Dasar -->
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Tanggal</p>
                                            <p class="font-medium text-gray-900 dark:text-white">
                                                {{ $selectedKonseling->tanggal_konseling->format('d F Y') }}
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Waktu</p>
                                            <p class="font-medium text-gray-900 dark:text-white">
                                                {{ \Carbon\Carbon::parse($selectedKonseling->waktu_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($selectedKonseling->waktu_selesai)->format('H:i') }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Layanan</p>
                                            <p class="font-medium text-gray-900 dark:text-white">
                                                {{ $selectedKonseling->layananBk->nama_layanan ?? '-' }}
                                            </p>
                                        </div>
                                        <div>
    <p class="text-sm text-gray-500 dark:text-gray-400">Guru BK</p>
    <p class="font-medium text-gray-900 dark:text-white">
        {{ $selectedKonseling->guruBk->nama_lengkap ?? '-' }} <!-- PERBAIKAN: nama_lengkap -->
    </p>
</div>

                                    </div>

                                    <div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Tempat</p>
                                        <p class="font-medium text-gray-900 dark:text-white">{{ $selectedKonseling->tempat }}</p>
                                    </div>

                                    <div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Status</p>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            @if($selectedKonseling->status == 'terjadwal') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                            @elseif($selectedKonseling->status == 'selesai') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                            @elseif($selectedKonseling->status == 'dibatalkan') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                            @endif">
                                            {{ $selectedKonseling->status }}
                                        </span>
                                    </div>

                                    <!-- Permasalahan -->
                                    <div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Permasalahan</p>
                                        <p class="font-medium text-gray-900 dark:text-white mt-1 bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                                            {{ $selectedKonseling->permasalahan }}
                                        </p>
                                    </div>

                                    <!-- Hasil dan Tindakan (jika selesai) -->
                                    @if($selectedKonseling->status == 'selesai')
                                        @if($selectedKonseling->tindakan)
                                            <div>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">Tindakan</p>
                                                <p class="font-medium text-gray-900 dark:text-white mt-1 bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                                                    {{ $selectedKonseling->tindakan }}
                                                </p>
                                            </div>
                                        @endif

                                        @if($selectedKonseling->hasil)
                                            <div>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">Hasil</p>
                                                <p class="font-medium text-gray-900 dark:text-white mt-1 bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                                                    {{ $selectedKonseling->hasil }}
                                                </p>
                                            </div>
                                        @endif
                                    @endif

                                    <!-- Catatan -->
                                    @if($selectedKonseling->catatan)
                                        <div>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Catatan</p>
                                            <p class="font-medium text-gray-900 dark:text-white mt-1 bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                                                {{ $selectedKonseling->catatan }}
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button 
                            wire:click="closeDetail" 
                            type="button" 
                            class="w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Create Modal -->
    @if($showCreateModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeCreateModal"></div>

                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">
                                    Buat Janji Konseling Baru
                                </h3>
                                
                                <form wire:submit="buatJanji" class="space-y-4">
                                    <!-- Layanan BK -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            Layanan BK <span class="text-red-500">*</span>
                                        </label>
                                        <select 
                                            wire:model="form.layanan_bk_id"
                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                            required
                                        >
                                            <option value="">Pilih Layanan</option>
                                            @foreach($layananOptions as $value => $label)
                                                <option value="{{ $value }}">{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        @error('form.layanan_bk_id')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Guru BK -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            Guru BK <span class="text-red-500">*</span>
                                        </label>
                                        <select 
    wire:model="form.guru_id"
    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
    required
>
    <option value="">Pilih Guru BK</option>
    @foreach($guruOptions as $value => $label)
        <option value="{{ $value }}">{{ $label }}</option> <!-- Sudah menggunakan nama_lengkap -->
    @endforeach
</select>

                                        @error('form.guru_id')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Tanggal & Waktu -->
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                Tanggal <span class="text-red-500">*</span>
                                            </label>
                                            <input 
                                                type="date" 
                                                wire:model="form.tanggal_konseling"
                                                min="{{ now()->format('Y-m-d') }}"
                                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                                required
                                            >
                                            @error('form.tanggal_konseling')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                Waktu Mulai <span class="text-red-500">*</span>
                                            </label>
                                            <input 
                                                type="time" 
                                                wire:model="form.waktu_mulai"
                                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                                required
                                            >
                                            @error('form.waktu_mulai')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                Waktu Selesai <span class="text-red-500">*</span>
                                            </label>
                                            <input 
                                                type="time" 
                                                wire:model="form.waktu_selesai"
                                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                                required
                                            >
                                            @error('form.waktu_selesai')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Tempat -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            Tempat <span class="text-red-500">*</span>
                                        </label>
                                        <input 
                                            type="text" 
                                            wire:model="form.tempat"
                                            placeholder="Contoh: Ruang BK, Ruang Guru, dll."
                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                            required
                                        >
                                        @error('form.tempat')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Permasalahan -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            Permasalahan <span class="text-red-500">*</span>
                                        </label>
                                        <textarea 
                                            wire:model="form.permasalahan"
                                            rows="4"
                                            placeholder="Jelaskan permasalahan yang ingin dikonsultasikan..."
                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                            required
                                        ></textarea>
                                        @error('form.permasalahan')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button 
                            wire:click="buatJanji"
                            type="button" 
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            <i class="fas fa-calendar-plus mr-2"></i>Buat Janji
                        </button>
                        <button 
                            wire:click="closeCreateModal" 
                            type="button" 
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="fixed top-4 right-4 z-50">
            <div class="bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="fixed top-4 right-4 z-50">
            <div class="bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    {{ session('error') }}
                </div>
            </div>
        </div>
    @endif
</div>