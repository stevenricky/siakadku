<div class="p-4 sm:p-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">Pemeliharaan Barang</h1>
            <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400 mt-1">Kelola laporan pemeliharaan barang</p>
        </div>
        <button wire:click="openCreateForm" class="bg-blue-600 text-white px-3 py-2 sm:px-4 sm:py-2 rounded-lg hover:bg-blue-700 transition flex items-center justify-center w-full sm:w-auto">
            <i class="fas fa-plus mr-2 text-xs sm:text-sm"></i>
            <span class="text-xs sm:text-sm">Laporkan Kerusakan</span>
        </button>
    </div>

    <!-- Flash Message -->
    @if (session()->has('success'))
        <div class="mb-4 p-3 sm:p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 p-3 sm:p-4 bg-red-100 border border-red-400 text-red-700 rounded">
            {{ session('error') }}
        </div>
    @endif

    <!-- Statistics -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 sm:p-4">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                    <i class="fas fa-clipboard-list text-blue-600 dark:text-blue-400 text-sm sm:text-base"></i>
                </div>
                <div class="ml-3">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400">Dilaporkan</p>
                    <p class="text-lg sm:text-2xl font-bold text-gray-900 dark:text-white">{{ $dilaporkanCount }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 sm:p-4">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 dark:bg-yellow-900 rounded-lg">
                    <i class="fas fa-tools text-yellow-600 dark:text-yellow-400 text-sm sm:text-base"></i>
                </div>
                <div class="ml-3">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400">Diproses</p>
                    <p class="text-lg sm:text-2xl font-bold text-gray-900 dark:text-white">{{ $diprosesCount }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 sm:p-4">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 dark:bg-green-900 rounded-lg">
                    <i class="fas fa-check text-green-600 dark:text-green-400 text-sm sm:text-base"></i>
                </div>
                <div class="ml-3">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400">Selesai</p>
                    <p class="text-lg sm:text-2xl font-bold text-gray-900 dark:text-white">{{ $selesaiCount }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 sm:p-4">
            <div class="flex items-center">
                <div class="p-2 bg-red-100 dark:bg-red-900 rounded-lg">
                    <i class="fas fa-times text-red-600 dark:text-red-400 text-sm sm:text-base"></i>
                </div>
                <div class="ml-3">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400">Batal</p>
                    <p class="text-lg sm:text-2xl font-bold text-gray-900 dark:text-white">{{ $batalCount }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
        <div class="p-3 sm:p-4 border-b border-gray-200 dark:border-gray-700">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div class="sm:col-span-1">
                    <input 
                        type="text" 
                        wire:model.live="search"
                        placeholder="Cari laporan..." 
                        class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                    >
                </div>
                <select wire:model.live="statusFilter" class="px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                    <option value="">Semua Status</option>
                    <option value="dilaporkan">Dilaporkan</option>
                    <option value="diproses">Diproses</option>
                    <option value="selesai">Selesai</option>
                    <option value="batal">Batal</option>
                </select>
                <input 
                    type="month" 
                    wire:model.live="monthFilter"
                    class="px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                >
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <!-- Mobile Card View -->
            <div class="sm:hidden">
                @forelse($pemeliharaan as $item)
                    <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $item->barang->nama_barang }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    {{ $item->barang->kategori->nama_kategori ?? '-' }} - {{ $item->barang->ruangan->nama_ruangan ?? '-' }}
                                </div>
                            </div>
                            @php
                                $badgeClasses = [
                                    'dilaporkan' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                                    'diproses' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                    'selesai' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                    'batal' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300'
                                ];
                                $badgeClass = $badgeClasses[$item->status] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $badgeClass }}">
                                {{ ucfirst($item->status) }}
                            </span>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-2 mb-3 text-xs">
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Tanggal:</span>
                                <div class="font-medium text-gray-900 dark:text-white">
                                    {{ $item->tanggal_pemeliharaan->format('d M Y') }}
                                </div>
                            </div>
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Jenis:</span>
                                <div class="font-medium text-gray-900 dark:text-white">
                                    {{ ucfirst(str_replace('_', ' ', $item->jenis_pemeliharaan)) }}
                                </div>
                            </div>
                            <div class="col-span-2">
                                <span class="text-gray-500 dark:text-gray-400">Teknisi:</span>
                                <div class="font-medium text-gray-900 dark:text-white">
                                    {{ $item->teknisi ?? '-' }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex justify-end space-x-2">
                            <button 
                                wire:click="openEditForm({{ $item->id }})" 
                                class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 text-xs px-2 py-1 bg-blue-50 rounded"
                                title="Detail"
                            >
                                <i class="fas fa-eye"></i>
                            </button>
                            @if($item->status !== 'selesai' && $item->status !== 'batal')
                                <button 
                                    wire:click="updateStatus({{ $item->id }}, 'selesai')" 
                                    class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 text-xs px-2 py-1 bg-green-50 rounded"
                                    title="Selesai"
                                >
                                    <i class="fas fa-check"></i>
                                </button>
                            @endif
                            <!-- Tombol delete dinonaktifkan -->
                            <button 
                                disabled
                                class="text-gray-400 text-xs px-2 py-1 bg-gray-100 rounded cursor-not-allowed"
                                title="Hapus dinonaktifkan"
                            >
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="p-4 text-center">
                        <div class="text-gray-500 dark:text-gray-400">
                            <i class="fas fa-clipboard-list text-4xl mb-4"></i>
                            <p class="text-lg font-medium">Tidak ada data pemeliharaan</p>
                            <p class="text-sm mt-2">Mulai dengan melaporkan kerusakan barang</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Desktop Table View -->
            <div class="hidden sm:block overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-3 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal</th>
                            <th class="px-3 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Barang</th>
                            <th class="px-3 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jenis</th>
                            <th class="px-3 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Teknisi</th>
                            <th class="px-3 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            <th class="px-3 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($pemeliharaan as $item)
                        <tr>
                            <td class="px-3 py-2 sm:px-6 sm:py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ $item->tanggal_pemeliharaan->format('d M Y') }}
                            </td>
                            <td class="px-3 py-2 sm:px-6 sm:py-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $item->barang->nama_barang }}
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $item->barang->kategori->nama_kategori ?? '-' }} - {{ $item->barang->ruangan->nama_ruangan ?? '-' }}
                                </div>
                            </td>
                            <td class="px-3 py-2 sm:px-6 sm:py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ ucfirst(str_replace('_', ' ', $item->jenis_pemeliharaan)) }}
                            </td>
                            <td class="px-3 py-2 sm:px-6 sm:py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ $item->teknisi ?? '-' }}
                            </td>
                            <td class="px-3 py-2 sm:px-6 sm:py-4 whitespace-nowrap">
                                @php
                                    $badgeClasses = [
                                        'dilaporkan' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                                        'diproses' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                        'selesai' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                        'batal' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300'
                                    ];
                                    $badgeClass = $badgeClasses[$item->status] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $badgeClass }}">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>
                            <td class="px-3 py-2 sm:px-6 sm:py-4 whitespace-nowrap text-sm font-medium">
                                <button 
                                    wire:click="openEditForm({{ $item->id }})" 
                                    class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 mr-3"
                                    title="Detail"
                                >
                                    <i class="fas fa-eye"></i>
                                </button>
                                @if($item->status !== 'selesai' && $item->status !== 'batal')
                                    <button 
                                        wire:click="updateStatus({{ $item->id }}, 'selesai')" 
                                        class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 mr-3"
                                        title="Selesai"
                                    >
                                        <i class="fas fa-check"></i>
                                    </button>
                                @endif
                                <!-- Tombol delete dinonaktifkan -->
                                <button 
                                    disabled
                                    class="text-gray-400 cursor-not-allowed"
                                    title="Hapus dinonaktifkan"
                                >
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                <i class="fas fa-clipboard-list mr-2"></i>Tidak ada data pemeliharaan
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-3 sm:px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $pemeliharaan->links() }}
            </div>
        </div>
    </div>

    <!-- Form Modal -->
    @if($showForm)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-4xl max-h-[90vh] overflow-y-auto">
            <div class="p-4 sm:p-6">
                <h2 class="text-lg sm:text-xl font-bold mb-4 text-gray-900 dark:text-white">
                    {{ $formType === 'create' ? 'Laporkan Pemeliharaan' : 'Edit Data Pemeliharaan' }}
                </h2>

                <form wire:submit="savePemeliharaan">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <!-- Barang -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Barang *
                            </label>
                            <select 
                                wire:model="barang_id"
                                class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                                required
                            >
                                <option value="">Pilih Barang</option>
                                @foreach($barangList as $barang)
                                    <option value="{{ $barang->id }}">
                                        {{ $barang->kode_barang }} - {{ $barang->nama_barang }} 
                                        ({{ $barang->ruangan->nama_ruangan ?? '-' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('barang_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Tanggal Pemeliharaan -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Tanggal Pemeliharaan *
                            </label>
                            <input 
                                type="date" 
                                wire:model="tanggal_pemeliharaan"
                                class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                                required
                            >
                            @error('tanggal_pemeliharaan') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Jenis Pemeliharaan -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Jenis Pemeliharaan *
                            </label>
                            <select 
                                wire:model="jenis_pemeliharaan"
                                class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                                required
                            >
                                @foreach($jenisList as $jenis)
                                    <option value="{{ $jenis }}">{{ ucfirst(str_replace('_', ' ', $jenis)) }}</option>
                                @endforeach
                            </select>
                            @error('jenis_pemeliharaan') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Teknisi -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Teknisi
                            </label>
                            <select 
                                wire:model="teknisi"
                                class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                            >
                                <option value="">Pilih Teknisi</option>
                                @foreach($teknisiList as $teknisiOption)
                                    <option value="{{ $teknisiOption }}">{{ $teknisiOption }}</option>
                                @endforeach
                            </select>
                            @error('teknisi') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Biaya -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Biaya (Rp) *
                            </label>
                            <input 
                                type="number" 
                                wire:model="biaya"
                                min="0"
                                step="0.01"
                                class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                                required
                            >
                            @error('biaya') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Status *
                            </label>
                            <select 
                                wire:model="status"
                                class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                                required
                            >
                                @foreach($statusList as $statusOption)
                                    <option value="{{ $statusOption }}">{{ ucfirst($statusOption) }}</option>
                                @endforeach
                            </select>
                            @error('status') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Deskripsi Kerusakan -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Deskripsi Kerusakan
                        </label>
                        <textarea 
                            wire:model="deskripsi_kerusakan"
                            rows="3"
                            class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                            placeholder="Jelaskan kondisi kerusakan yang terjadi..."
                        ></textarea>
                        @error('deskripsi_kerusakan') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Tindakan -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Tindakan *
                        </label>
                        <textarea 
                            wire:model="tindakan"
                            rows="3"
                            class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                            placeholder="Jelaskan tindakan yang dilakukan..."
                            required
                        ></textarea>
                        @error('tindakan') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Catatan -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Catatan
                        </label>
                        <textarea 
                            wire:model="catatan"
                            rows="2"
                            class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                            placeholder="Catatan tambahan..."
                        ></textarea>
                        @error('catatan') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Actions -->
                    <div class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-3">
                        <button 
                            type="button" 
                            wire:click="$set('showForm', false)"
                            class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 w-full sm:w-auto"
                        >
                            Batal
                        </button>
                        <button 
                            type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 w-full sm:w-auto"
                        >
                            {{ $formType === 'create' ? 'Simpan Laporan' : 'Update Data' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>