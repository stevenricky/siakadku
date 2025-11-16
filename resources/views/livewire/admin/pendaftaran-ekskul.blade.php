<div class="p-4 md:p-6">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
                    Pendaftaran Ekstrakurikuler
                </h1>
                <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">
                    Kelola pendaftaran siswa untuk ekstrakurikuler
                </p>
            </div>
            <button 
                wire:click="openCreateForm" 
                class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg transition flex items-center justify-center gap-2"
            >
                <i class="fas fa-plus"></i>
                <span>Tambah Pendaftaran</span>
            </button>
        </div>

        <!-- Flash Messages -->
        @if (session()->has('success'))
            <div class="p-4 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-200 rounded-lg flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.style.display='none'" class="text-lg">&times;</button>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="p-4 bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-200 rounded-lg flex items-center justify-between">
                <span>{{ session('error') }}</span>
                <button onclick="this.parentElement.style.display='none'" class="text-lg">&times;</button>
            </div>
        @endif

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 md:p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 dark:text-gray-400 text-sm md:text-base">Pending</p>
                        <p class="text-3xl md:text-4xl font-bold text-yellow-600 dark:text-yellow-400 mt-1">
                            {{ $pendingCount }}
                        </p>
                    </div>
                    <i class="fas fa-clock text-yellow-600 dark:text-yellow-400 text-3xl md:text-4xl opacity-20"></i>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 md:p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 dark:text-gray-400 text-sm md:text-base">Diterima</p>
                        <p class="text-3xl md:text-4xl font-bold text-green-600 dark:text-green-400 mt-1">
                            {{ $diterimaCount }}
                        </p>
                    </div>
                    <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-3xl md:text-4xl opacity-20"></i>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 md:p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 dark:text-gray-400 text-sm md:text-base">Ditolak</p>
                        <p class="text-3xl md:text-4xl font-bold text-red-600 dark:text-red-400 mt-1">
                            {{ $ditolakCount }}
                        </p>
                    </div>
                    <i class="fas fa-times-circle text-red-600 dark:text-red-400 text-3xl md:text-4xl opacity-20"></i>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="p-4 md:p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="space-y-3 md:space-y-0 md:flex md:gap-4">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Cari Siswa
                        </label>
                        <input 
                            type="text" 
                            wire:model.live.debounce-300ms="search"
                            placeholder="Nama atau NIS..." 
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none"
                        >
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Ekstrakurikuler
                        </label>
                        <select 
                            wire:model.live="ekskulFilter" 
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none"
                        >
                            <option value="">Semua Ekskul</option>
                            @foreach($ekskulList as $ekskul)
                                <option value="{{ $ekskul->id }}">{{ $ekskul->nama_ekstra }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Status
                        </label>
                        <select 
                            wire:model.live="statusFilter" 
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none"
                        >
                            <option value="">Semua Status</option>
                            <option value="pending">Pending</option>
                            <option value="diterima">Diterima</option>
                            <option value="ditolak">Ditolak</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Mobile Card View -->
            <div class="sm:hidden">
                @forelse($pendaftaran as $item)
                    <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center flex-shrink-0">
                                    <span class="text-white font-bold text-sm">
                                        {{ substr($item->siswa->nama, 0, 1) }}
                                    </span>
                                </div>
                                <div class="min-w-0">
                                    <p class="font-medium text-gray-900 dark:text-white truncate">
                                        {{ $item->siswa->nama }}
                                    </p>
                                    <p class="text-gray-500 dark:text-gray-400 text-xs truncate">
                                        {{ $item->siswa->kelas->nama_kelas ?? 'N/A' }}
                                    </p>
                                </div>
                            </div>
                            @php
                                $statusConfig = [
                                    'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'darkBg' => 'dark:bg-yellow-900', 'darkText' => 'dark:text-yellow-300', 'label' => 'Pending'],
                                    'diterima' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'darkBg' => 'dark:bg-green-900', 'darkText' => 'dark:text-green-300', 'label' => 'Diterima'],
                                    'ditolak' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'darkBg' => 'dark:bg-red-900', 'darkText' => 'dark:text-red-300', 'label' => 'Ditolak']
                                ];
                                $config = $statusConfig[$item->status_pendaftaran] ?? $statusConfig['pending'];
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $config['bg'] }} {{ $config['text'] }} {{ $config['darkBg'] }} {{ $config['darkText'] }}">
                                {{ $config['label'] }}
                            </span>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-2 mb-3 text-xs">
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Ekskul:</span>
                                <div class="font-medium text-gray-900 dark:text-white truncate">
                                    {{ $item->ekstrakurikuler->nama_ekstra }}
                                </div>
                            </div>
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Tgl Daftar:</span>
                                <div class="font-medium text-gray-900 dark:text-white">
                                    {{ $item->created_at->format('d M Y') }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex flex-wrap gap-2 justify-end">
                            @if($item->status_pendaftaran === 'pending')
                                <button 
                                    wire:click="terimaPendaftaran({{ $item->id }})"
                                    class="px-3 py-1 bg-green-500 hover:bg-green-600 text-white rounded text-xs font-medium transition"
                                    title="Terima"
                                >
                                    <i class="fas fa-check"></i>
                                    <span class="ml-1">Terima</span>
                                </button>
                                <button 
                                    wire:click="openTolakModal({{ $item->id }})"
                                    class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white rounded text-xs font-medium transition"
                                    title="Tolak"
                                >
                                    <i class="fas fa-times"></i>
                                    <span class="ml-1">Tolak</span>
                                </button>
                            @endif
                            <button 
                                wire:click="openEditForm({{ $item->id }})"
                                class="px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white rounded text-xs font-medium transition"
                                title="Edit"
                            >
                                <i class="fas fa-edit"></i>
                                <span class="ml-1">Edit</span>
                            </button>
                            <button 
                                wire:click="deletePendaftaran({{ $item->id }})"
                                wire:confirm="Apakah Anda yakin ingin menghapus?"
                                class="px-3 py-1 bg-gray-500 hover:bg-gray-600 text-white rounded text-xs font-medium transition"
                                title="Hapus"
                            >
                                <i class="fas fa-trash"></i>
                                <span class="ml-1">Hapus</span>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="p-4 text-center">
                        <i class="fas fa-inbox text-gray-400 text-3xl md:text-4xl mb-3"></i>
                        <p class="text-gray-500 dark:text-gray-400 text-sm md:text-base">
                            Tidak ada data pendaftaran
                        </p>
                    </div>
                @endforelse
            </div>

            <!-- Desktop Table View -->
            <div class="hidden sm:block overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr class="text-xs md:text-sm">
                            <th class="px-4 md:px-6 py-3 text-left font-semibold text-gray-700 dark:text-gray-300">Siswa</th>
                            <th class="px-4 md:px-6 py-3 text-left font-semibold text-gray-700 dark:text-gray-300 hidden sm:table-cell">Ekskul</th>
                            <th class="px-4 md:px-6 py-3 text-left font-semibold text-gray-700 dark:text-gray-300 hidden md:table-cell">Tgl Daftar</th>
                            <th class="px-4 md:px-6 py-3 text-left font-semibold text-gray-700 dark:text-gray-300">Status</th>
                            <th class="px-4 md:px-6 py-3 text-center font-semibold text-gray-700 dark:text-gray-300">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($pendaftaran as $item)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 text-xs md:text-sm">
                            <!-- Siswa -->
                            <td class="px-4 md:px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center flex-shrink-0">
                                        <span class="text-white font-bold text-xs md:text-sm">
                                            {{ substr($item->siswa->nama, 0, 1) }}
                                        </span>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-medium text-gray-900 dark:text-white truncate">
                                            {{ $item->siswa->nama }}
                                        </p>
                                        <p class="text-gray-500 dark:text-gray-400 text-xs truncate">
                                            {{ $item->siswa->kelas->nama_kelas ?? 'N/A' }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            <!-- Ekskul (hidden on mobile) -->
                            <td class="px-4 md:px-6 py-4 hidden sm:table-cell">
                                <p class="font-medium text-gray-900 dark:text-white truncate">
                                    {{ $item->ekstrakurikuler->nama_ekstra }}
                                </p>
                                <p class="text-gray-500 dark:text-gray-400 text-xs truncate">
                                    {{ $item->ekstrakurikuler->pembina ?? 'N/A' }}
                                </p>
                            </td>

                            <!-- Tanggal (hidden on mobile) -->
                            <td class="px-4 md:px-6 py-4 hidden md:table-cell text-gray-600 dark:text-gray-400">
                                {{ $item->created_at->format('d M Y') }}
                            </td>

                            <!-- Status -->
                            <td class="px-4 md:px-6 py-4">
                                @php
                                    $statusConfig = [
                                        'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'darkBg' => 'dark:bg-yellow-900', 'darkText' => 'dark:text-yellow-300', 'label' => 'Pending'],
                                        'diterima' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'darkBg' => 'dark:bg-green-900', 'darkText' => 'dark:text-green-300', 'label' => 'Diterima'],
                                        'ditolak' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'darkBg' => 'dark:bg-red-900', 'darkText' => 'dark:text-red-300', 'label' => 'Ditolak']
                                    ];
                                    $config = $statusConfig[$item->status_pendaftaran] ?? $statusConfig['pending'];
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $config['bg'] }} {{ $config['text'] }} {{ $config['darkBg'] }} {{ $config['darkText'] }}">
                                    {{ $config['label'] }}
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="px-4 md:px-6 py-4">
                                <div class="flex flex-wrap gap-2 justify-center">
                                    @if($item->status_pendaftaran === 'pending')
                                        <button 
                                            wire:click="terimaPendaftaran({{ $item->id }})"
                                            class="px-3 py-1 bg-green-500 hover:bg-green-600 text-white rounded text-xs font-medium transition"
                                            title="Terima"
                                        >
                                            <i class="fas fa-check"></i>
                                            <span class="hidden sm:inline ml-1">Terima</span>
                                        </button>
                                        <button 
                                            wire:click="openTolakModal({{ $item->id }})"
                                            class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white rounded text-xs font-medium transition"
                                            title="Tolak"
                                        >
                                            <i class="fas fa-times"></i>
                                            <span class="hidden sm:inline ml-1">Tolak</span>
                                        </button>
                                    @endif
                                    <button 
                                        wire:click="openEditForm({{ $item->id }})"
                                        class="px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white rounded text-xs font-medium transition"
                                        title="Edit"
                                    >
                                        <i class="fas fa-edit"></i>
                                        <span class="hidden sm:inline ml-1">Edit</span>
                                    </button>
                                    <button 
                                        wire:click="deletePendaftaran({{ $item->id }})"
                                        wire:confirm="Apakah Anda yakin ingin menghapus?"
                                        class="px-3 py-1 bg-gray-500 hover:bg-gray-600 text-white rounded text-xs font-medium transition"
                                        title="Hapus"
                                    >
                                        <i class="fas fa-trash"></i>
                                        <span class="hidden sm:inline ml-1">Hapus</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-4 md:px-6 py-8 text-center">
                                <i class="fas fa-inbox text-gray-400 text-3xl md:text-4xl mb-3"></i>
                                <p class="text-gray-500 dark:text-gray-400 text-sm md:text-base">
                                    Tidak ada data pendaftaran
                                </p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination & Settings -->
            <div class="p-4 md:p-6 border-t border-gray-200 dark:border-gray-700">
                <div class="flex flex-col sm:flex-row gap-4 items-center justify-between">
                    <div class="flex items-center gap-2">
                        <label class="text-sm text-gray-600 dark:text-gray-400">Per halaman:</label>
                        <select 
                            wire:model.live="perPage"
                            class="px-3 py-1 border border-gray-300 dark:border-gray-600 rounded dark:bg-gray-700 dark:text-white text-sm"
                        >
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                    </div>
                    <div class="w-full sm:w-auto">
                        {{ $pendaftaran->links() }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Modal -->
        @if($showForm)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                <div class="sticky top-0 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-4 md:px-6 py-4 flex justify-between items-center">
                    <h2 class="text-lg md:text-xl font-bold text-gray-900 dark:text-white">
                        {{ $formType === 'create' ? 'Tambah Pendaftaran Ekskul' : 'Edit Pendaftaran Ekskul' }}
                    </h2>
                    <button 
                        wire:click="closeForm"
                        class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300"
                    >
                        <i class="fas fa-times text-2xl"></i>
                    </button>
                </div>

                <form wire:submit="savePendaftaran" class="p-4 md:p-6 space-y-4">
                    @if($errors->has('form'))
                        <div class="p-3 bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-200 rounded">
                            {{ $errors->first('form') }}
                        </div>
                    @endif

                    <!-- Siswa -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Siswa <span class="text-red-500">*</span>
                        </label>
                        <select 
                            wire:model="siswa_id"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none"
                            required
                        >
                            <option value="">Pilih Siswa</option>
                            @foreach($siswaList as $siswa)
                                <option value="{{ $siswa->id }}">
                                    {{ $siswa->nis }} - {{ $siswa->nama }} ({{ $siswa->kelas->nama_kelas ?? '-' }})
                                </option>
                            @endforeach
                        </select>
                        @error('siswa_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Ekstrakurikuler -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Ekstrakurikuler <span class="text-red-500">*</span>
                        </label>
                        <select 
                            wire:model="ekstrakurikuler_id"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none"
                            required
                        >
                            <option value="">Pilih Ekstrakurikuler</option>
                            @foreach($ekskulList as $ekskul)
                                <option value="{{ $ekskul->id }}">
                                    {{ $ekskul->nama_ekstra }} ({{ $ekskul->pembina ?? 'Tanpa pembina' }})
                                </option>
                            @endforeach
                        </select>
                        @error('ekstrakurikuler_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Tahun Ajaran -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Tahun Ajaran <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="number" 
                            wire:model="tahun_ajaran"
                            min="2020"
                            max="2030"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none"
                            required
                        >
                        @error('tahun_ajaran') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select 
                            wire:model.live="status_pendaftaran"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none"
                            required
                        >
                            @foreach($statusList as $status)
                                <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                            @endforeach
                        </select>
                        @error('status_pendaftaran') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Alasan Ditolak -->
                    @if($status_pendaftaran === 'ditolak')
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Alasan Ditolak <span class="text-red-500">*</span>
                        </label>
                        <textarea 
                            wire:model="alasan_ditolak"
                            rows="4"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none"
                            placeholder="Berikan alasan penolakan..."
                            required
                        ></textarea>
                        @error('alasan_ditolak') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    @endif

                    <!-- Actions -->
                    <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button 
                            type="button" 
                            wire:click="closeForm"
                            class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 font-medium transition"
                        >
                            Batal
                        </button>
                        <button 
                            type="submit"
                            class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition"
                        >
                            {{ $formType === 'create' ? 'Simpan' : 'Update' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endif

        <!-- Modal Tolak -->
        @if($showTolakModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-md">
                <div class="p-4 md:p-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg md:text-xl font-bold text-gray-900 dark:text-white">
                        Tolak Pendaftaran
                    </h2>
                </div>
                
                <div class="p-4 md:p-6 space-y-4">
                    <p class="text-gray-600 dark:text-gray-400 text-sm">
                        Silakan berikan alasan penolakan pendaftaran ini.
                    </p>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Alasan Penolakan <span class="text-red-500">*</span>
                        </label>
                        <textarea 
                            wire:model="tolakAlasan"
                            rows="4"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-red-500 outline-none"
                            placeholder="Jelaskan alasan penolakan..."
                        ></textarea>
                    </div>
                </div>

                <div class="p-4 md:p-6 border-t border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row gap-3">
                    <button 
                        wire:click="closeTolakModal"
                        class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 font-medium transition"
                    >
                        Batal
                    </button>
                    <button 
                        wire:click="confirmTolak"
                        :disabled="!tolakAlasan"
                        class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed text-white rounded-lg font-medium transition"
                    >
                        Tolak Pendaftaran
                    </button>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>