<div class="p-4 sm:p-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">Manajemen Inventaris</h1>
        <button wire:click="openCreateForm" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition w-full sm:w-auto">
            <i class="fas fa-plus mr-2"></i>Tambah Barang
        </button>
    </div>

    <!-- Flash Message -->
    @if (session()->has('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
            {{ session('error') }}
        </div>
    @endif

    <!-- Statistics -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 sm:p-4">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                    <i class="fas fa-boxes text-blue-600 dark:text-blue-400 text-sm sm:text-base"></i>
                </div>
                <div class="ml-3 sm:ml-4">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400">Total Barang</p>
                    <p class="text-lg sm:text-2xl font-bold text-gray-900 dark:text-white">{{ $totalBarang }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 sm:p-4">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 dark:bg-green-900 rounded-lg">
                    <i class="fas fa-check text-green-600 dark:text-green-400 text-sm sm:text-base"></i>
                </div>
                <div class="ml-3 sm:ml-4">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400">Baik</p>
                    <p class="text-lg sm:text-2xl font-bold text-gray-900 dark:text-white">{{ $baikCount }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 sm:p-4">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 dark:bg-yellow-900 rounded-lg">
                    <i class="fas fa-tools text-yellow-600 dark:text-yellow-400 text-sm sm:text-base"></i>
                </div>
                <div class="ml-3 sm:ml-4">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400">Rusak Ringan</p>
                    <p class="text-lg sm:text-2xl font-bold text-gray-900 dark:text-white">{{ $rusakRinganCount }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 sm:p-4">
            <div class="flex items-center">
                <div class="p-2 bg-orange-100 dark:bg-orange-900 rounded-lg">
                    <i class="fas fa-exclamation-triangle text-orange-600 dark:text-orange-400 text-sm sm:text-base"></i>
                </div>
                <div class="ml-3 sm:ml-4">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400">Rusak Berat</p>
                    <p class="text-lg sm:text-2xl font-bold text-gray-900 dark:text-white">{{ $rusakBeratCount }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 sm:p-4">
            <div class="flex items-center">
                <div class="p-2 bg-red-100 dark:bg-red-900 rounded-lg">
                    <i class="fas fa-times text-red-600 dark:text-red-400 text-sm sm:text-base"></i>
                </div>
                <div class="ml-3 sm:ml-4">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400">Hilang</p>
                    <p class="text-lg sm:text-2xl font-bold text-gray-900 dark:text-white">{{ $hilangCount }}</p>
                </div>
            </div>
        </div>
    </div>

    @if($totalNilai > 0)
    <div class="mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 sm:p-4">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 dark:bg-purple-900 rounded-lg">
                    <i class="fas fa-money-bill-wave text-purple-600 dark:text-purple-400 text-sm sm:text-base"></i>
                </div>
                <div class="ml-3 sm:ml-4">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400">Total Nilai Inventaris</p>
                    <p class="text-lg sm:text-2xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($totalNilai, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
        <div class="p-3 sm:p-4 border-b border-gray-200 dark:border-gray-700">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                <div class="sm:col-span-2 lg:col-span-1">
                    <input 
                        type="text" 
                        wire:model.live="search"
                        placeholder="Cari barang..." 
                        class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                    >
                </div>
                <select wire:model.live="kategoriFilter" class="px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoriList as $kategori)
                        <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                    @endforeach
                </select>
                <select wire:model.live="ruanganFilter" class="px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                    <option value="">Semua Ruangan</option>
                    @foreach($ruanganList as $ruangan)
                        <option value="{{ $ruangan->id }}">{{ $ruangan->nama_ruangan }}</option>
                    @endforeach
                </select>
                <select wire:model.live="kondisiFilter" class="px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                    <option value="">Semua Kondisi</option>
                    <option value="baik">Baik</option>
                    <option value="rusak_ringan">Rusak Ringan</option>
                    <option value="rusak_berat">Rusak Berat</option>
                    <option value="hilang">Hilang</option>
                </select>
            </div>
        </div>

        <!-- Card Layout for All Mobile Sizes -->
        <div class="block lg:hidden p-3 sm:p-4">
            @forelse($barang as $item)
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-4 shadow-sm">
                    <!-- Header with foto and basic info -->
                    <div class="flex flex-col sm:flex-row gap-3 mb-3">
                        @if($item->foto)
                            <img src="{{ asset('storage/' . $item->foto) }}" 
                                 alt="Foto Barang" 
                                 class="h-32 w-32 sm:h-36 sm:w-36 object-cover rounded-lg shadow-sm mx-auto sm:mx-0"
                                 onerror="this.src='https://via.placeholder.com/150x150?text=No+Image'">
                        @else
                            <div class="h-32 w-32 sm:h-36 sm:w-36 bg-gray-200 dark:bg-gray-600 rounded-lg flex items-center justify-center mx-auto sm:mx-0">
                                <i class="fas fa-image text-gray-400 dark:text-gray-500 text-3xl"></i>
                            </div>
                        @endif
                        
                        <div class="flex-1 text-center sm:text-left">
                            <h3 class="font-semibold text-gray-900 dark:text-white text-sm sm:text-base mb-1">{{ $item->nama_barang }}</h3>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">{{ $item->kode_barang }}</p>
                            @php
                                $badgeClasses = [
                                    'baik' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                    'rusak_ringan' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                    'rusak_berat' => 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300',
                                    'hilang' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300'
                                ];
                                $badgeClass = $badgeClasses[$item->kondisi] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300';
                            @endphp
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $badgeClass }}">
                                {{ ucfirst(str_replace('_', ' ', $item->kondisi)) }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Details Grid -->
                    <div class="grid grid-cols-2 gap-2 mb-3 text-xs">
                        <div>
                            <p class="text-gray-500 dark:text-gray-400">Jumlah</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $item->jumlah }} {{ $item->satuan }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 dark:text-gray-400">Kategori</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $item->kategori->nama_kategori ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 dark:text-gray-400">Ruangan</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $item->ruangan->nama_ruangan ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 dark:text-gray-400">Harga</p>
                            <p class="font-medium text-gray-900 dark:text-white">
                                @if($item->harga)Rp {{ number_format($item->harga, 0, ',', '.') }}@else -@endif
                            </p>
                        </div>
                        @if($item->merk || $item->tipe)
                        <div class="col-span-2">
                            <p class="text-gray-500 dark:text-gray-400">Merk/Tipe</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $item->merk }} {{ $item->tipe }}</p>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Actions -->
                    <div class="flex flex-col sm:flex-row gap-2">
                        <button 
                            wire:click="openEditForm({{ $item->id }})" 
                            class="flex-1 bg-blue-50 text-blue-600 hover:bg-blue-100 dark:bg-blue-900 dark:text-blue-400 dark:hover:bg-blue-800 px-3 py-2 rounded text-xs sm:text-sm font-medium text-center transition"
                        >
                            <i class="fas fa-edit mr-1"></i> Edit
                        </button>
                        <button 
                            disabled
                            class="flex-1 bg-gray-100 text-gray-400 px-3 py-2 rounded text-xs sm:text-sm font-medium text-center cursor-not-allowed"
                        >
                            <i class="fas fa-trash mr-1"></i> Hapus
                        </button>
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-sm text-gray-500 dark:text-gray-400">
                    <i class="fas fa-box-open text-4xl mb-3"></i>
                    <p>Tidak ada data barang inventaris</p>
                </div>
            @endforelse
        </div>

        <!-- Responsive Table for Desktop - Show All Items -->
        <div class="hidden lg:block">
            <div class="overflow-x-auto scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-600">
                <table class="w-full min-w-[800px]">
                    <thead class="bg-gray-50 dark:bg-gray-700 sticky top-0">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">Foto</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">Kode</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider min-w-[200px]">Nama Barang</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">Kategori</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">Ruangan</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">Jumlah</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">Harga</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">Kondisi</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($allBarang as $item)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <td class="px-4 py-3 whitespace-nowrap">
                                @if($item->foto)
                                    <img src="{{ asset('storage/' . $item->foto) }}" 
                                         alt="Foto Barang" 
                                         class="h-16 w-16 object-cover rounded-lg shadow-sm"
                                         onerror="this.src='https://via.placeholder.com/150x150?text=No+Image'">
                                @else
                                    <div class="h-16 w-16 bg-gray-200 dark:bg-gray-600 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-image text-gray-400 dark:text-gray-500"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="text-xs font-mono text-gray-900 dark:text-white">{{ $item->kode_barang }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-sm font-medium text-gray-900 dark:text-white truncate max-w-[200px]" title="{{ $item->nama_barang }}">
                                    {{ $item->nama_barang }}
                                </div>
                                @if($item->merk || $item->tipe)
                                    <div class="text-xs text-gray-500 dark:text-gray-400 truncate max-w-[200px]" title="{{ $item->merk }} {{ $item->tipe }}">
                                        {{ $item->merk }} {{ $item->tipe }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="text-sm text-gray-900 dark:text-white truncate max-w-[120px]" title="{{ $item->kategori->nama_kategori ?? '-' }}">
                                    {{ $item->kategori->nama_kategori ?? '-' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="text-sm text-gray-900 dark:text-white truncate max-w-[120px]" title="{{ $item->ruangan->nama_ruangan ?? '-' }}">
                                    {{ $item->ruangan->nama_ruangan ?? '-' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="text-sm text-gray-900 dark:text-white">{{ $item->jumlah }} {{ $item->satuan }}</span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="text-sm text-gray-900 dark:text-white">
                                    @if($item->harga)Rp {{ number_format($item->harga, 0, ',', '.') }}@else -@endif
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                @php
                                    $badgeClasses = [
                                        'baik' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                        'rusak_ringan' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                        'rusak_berat' => 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300',
                                        'hilang' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300'
                                    ];
                                    $badgeClass = $badgeClasses[$item->kondisi] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300';
                                @endphp
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $badgeClass }}">
                                    {{ ucfirst(str_replace('_', ' ', $item->kondisi)) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">
                                <button 
                                    wire:click="openEditForm({{ $item->id }})" 
                                    class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 mr-2 transition-colors"
                                    title="Edit"
                                >
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button 
                                    disabled
                                    class="text-gray-400 cursor-not-allowed"
                                    title="Hapus (dinonaktifkan)"
                                >
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                <i class="fas fa-box-open text-4xl mb-3"></i>
                                <p>Tidak ada data barang inventaris</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination (only for mobile) -->
        <div class="lg:hidden p-3 sm:p-4 border-t border-gray-200 dark:border-gray-700">
            {{ $barang->links() }}
        </div>
    </div>

    <!-- Form Modal -->
    @if($showForm)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-4xl max-h-[90vh] overflow-y-auto">
            <div class="p-4 sm:p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white">
                        {{ $formType === 'create' ? 'Tambah Barang Inventaris' : 'Edit Barang Inventaris' }}
                    </h2>
                    <button wire:click="$set('showForm', false)" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <form wire:submit="saveBarang">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <!-- Kode Barang -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Kode Barang *
                            </label>
                            <input 
                                type="text" 
                                wire:model="kode_barang"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white text-sm"
                                required
                            >
                            @error('kode_barang') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Nama Barang -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Nama Barang *
                            </label>
                            <input 
                                type="text" 
                                wire:model="nama_barang"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white text-sm"
                                required
                            >
                            @error('nama_barang') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Kategori -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Kategori *
                            </label>
                            <select 
                                wire:model="kategori_id"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white text-sm"
                                required
                            >
                                <option value="">Pilih Kategori</option>
                                @foreach($kategoriList as $kategori)
                                    <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                                @endforeach
                            </select>
                            @error('kategori_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Ruangan -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Ruangan *
                            </label>
                            <select 
                                wire:model="ruangan_id"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white text-sm"
                                required
                            >
                                <option value="">Pilih Ruangan</option>
                                @foreach($ruanganList as $ruangan)
                                    <option value="{{ $ruangan->id }}">{{ $ruangan->nama_ruangan }}</option>
                                @endforeach
                            </select>
                            @error('ruangan_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Merk -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Merk
                            </label>
                            <input 
                                type="text" 
                                wire:model="merk"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white text-sm"
                            >
                            @error('merk') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Tipe -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Tipe
                            </label>
                            <input 
                                type="text" 
                                wire:model="tipe"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white text-sm"
                            >
                            @error('tipe') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Jumlah -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Jumlah *
                            </label>
                            <input 
                                type="number" 
                                wire:model="jumlah"
                                min="1"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white text-sm"
                                required
                            >
                            @error('jumlah') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Satuan -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Satuan *
                            </label>
                            <select 
                                wire:model="satuan"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white text-sm"
                                required
                            >
                                @foreach($satuanList as $satuanOption)
                                    <option value="{{ $satuanOption }}">{{ ucfirst($satuanOption) }}</option>
                                @endforeach
                            </select>
                            @error('satuan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Harga -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Harga (Rp)
                            </label>
                            <input 
                                type="number" 
                                wire:model="harga"
                                min="0"
                                step="0.01"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white text-sm"
                            >
                            @error('harga') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Tanggal Pembelian -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Tanggal Pembelian
                            </label>
                            <input 
                                type="date" 
                                wire:model="tanggal_pembelian"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white text-sm"
                            >
                            @error('tanggal_pembelian') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Sumber Dana -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Sumber Dana
                            </label>
                            <input 
                                type="text" 
                                wire:model="sumber_dana"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white text-sm"
                            >
                            @error('sumber_dana') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Kondisi -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Kondisi *
                            </label>
                            <select 
                                wire:model="kondisi"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white text-sm"
                                required
                            >
                                @foreach($kondisiList as $kondisiOption)
                                    <option value="{{ $kondisiOption }}">{{ ucfirst(str_replace('_', ' ', $kondisiOption)) }}</option>
                                @endforeach
                            </select>
                            @error('kondisi') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Foto -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Foto Barang
                            </label>
                            <input 
                                type="file" 
                                wire:model="foto"
                                accept="image/*"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white text-sm"
                            >
                            @error('foto') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            
                            <!-- Tampilkan foto yang ada saat edit -->
                            @if($formType === 'edit' && $selectedBarang && $selectedBarang->foto)
                                <div class="mt-3">
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mb-2">Foto saat ini:</p>
                                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                                        <img src="{{ asset('storage/' . $selectedBarang->foto) }}" 
                                             alt="Foto Barang" 
                                             class="h-20 w-20 object-cover rounded-lg shadow-sm"
                                             onerror="this.src='https://via.placeholder.com/150x150?text=No+Image'">
                                        <div class="flex-1">
                                            <p class="text-sm text-gray-600 dark:text-gray-400 truncate">
                                                {{ basename($selectedBarang->foto) }}
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                                                Upload foto baru untuk mengganti
                                            </p>
                                            <button 
                                                type="button"
                                                wire:click="removePhoto"
                                                class="mt-2 text-xs text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300"
                                            >
                                                <i class="fas fa-trash mr-1"></i>Hapus foto
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                            <!-- Preview foto baru yang diupload -->
                            @if ($foto)
                                <div class="mt-3">
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mb-2">Preview foto baru:</p>
                                    <img src="{{ $foto->temporaryUrl() }}" 
                                         alt="Preview" 
                                         class="h-20 w-20 object-cover rounded-lg shadow-sm">
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Spesifikasi -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Spesifikasi
                        </label>
                        <textarea 
                            wire:model="spesifikasi"
                            rows="3"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white text-sm"
                            placeholder="Spesifikasi teknis barang..."
                        ></textarea>
                        @error('spesifikasi') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Keterangan -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Keterangan
                        </label>
                        <textarea 
                            wire:model="keterangan"
                            rows="3"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white text-sm"
                            placeholder="Keterangan tambahan..."
                        ></textarea>
                        @error('keterangan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Actions -->
                    <div class="flex flex-col sm:flex-row justify-end gap-3">
                        <button 
                            type="button" 
                            wire:click="$set('showForm', false)"
                            class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 w-full sm:w-auto text-sm"
                        >
                            Batal
                        </button>
                        <button 
                            type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 w-full sm:w-auto text-sm"
                        >
                            {{ $formType === 'create' ? 'Simpan' : 'Update' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>

