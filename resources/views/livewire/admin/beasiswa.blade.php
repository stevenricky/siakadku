<div class="p-4 sm:p-6">
    <!-- Header dan Tombol -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">Manajemen Beasiswa</h1>
        <button wire:click="create" class="w-full sm:w-auto bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition text-sm sm:text-base">
            <i class="fas fa-plus mr-2"></i>Tambah Beasiswa
        </button>
    </div>

    <!-- Statistik -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-6 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm sm:text-lg font-semibold text-gray-900 dark:text-white">Beasiswa Aktif</h3>
                    <p class="text-xl sm:text-2xl font-bold text-green-600 dark:text-green-400">{{ $beasiswaAktif }}</p>
                </div>
                <i class="fas fa-award text-green-600 dark:text-green-400 text-lg sm:text-2xl"></i>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm sm:text-lg font-semibold text-gray-900 dark:text-white">Total Beasiswa</h3>
                    <p class="text-xl sm:text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $totalBeasiswa }}</p>
                </div>
                <i class="fas fa-graduation-cap text-blue-600 dark:text-blue-400 text-lg sm:text-2xl"></i>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm sm:text-lg font-semibold text-gray-900 dark:text-white">Beasiswa Tutup</h3>
                    <p class="text-xl sm:text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $beasiswaTutup }}</p>
                </div>
                <i class="fas fa-archive text-purple-600 dark:text-purple-400 text-lg sm:text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Search dan Filter -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex flex-col gap-3">
                <div class="flex-1">
                    <input 
                        type="text" 
                        wire:model.live="search"
                        placeholder="Cari beasiswa, penyelenggara, atau deskripsi..." 
                        class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                </div>
                <div class="w-full sm:w-48">
                    <select 
                        wire:model.live="perPage"
                        class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                        <option value="5">5 per halaman</option>
                        <option value="10">10 per halaman</option>
                        <option value="25">25 per halaman</option>
                        <option value="50">50 per halaman</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Card Beasiswa - Desktop -->
    <div class="hidden sm:grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
        @forelse($beasiswa as $item)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden hover:shadow-lg transition-shadow">
            <div class="h-32 bg-gradient-to-r from-blue-500 to-purple-600 relative">
                <div class="absolute inset-0 bg-black bg-opacity-20"></div>
                <div class="absolute top-3 right-3">
                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $item->status === 'buka' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $statusOptions[$item->status] }}
                    </span>
                </div>
                <div class="absolute bottom-3 left-3">
                    <h3 class="text-lg font-bold text-white">{{ $item->nama_beasiswa }}</h3>
                </div>
            </div>
            <div class="p-4">
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-3 line-clamp-2">
                    {{ $item->deskripsi }}
                </p>
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Penyelenggara</span>
                    <span class="text-sm text-gray-900 dark:text-white">{{ Str::limit($item->penyelenggara, 20) }}</span>
                </div>
                <div class="flex justify-between items-center mb-3">
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Nilai</span>
                    <span class="text-sm text-gray-900 dark:text-white">
                        {{ $item->nilai_beasiswa ? 'Rp ' . number_format($item->nilai_beasiswa, 0, ',', '.') : 'Tidak ditentukan' }}
                    </span>
                </div>
                <div class="flex justify-between items-center text-xs text-gray-500 dark:text-gray-400 mb-3">
                    <span>{{ $item->tanggal_buka->translatedFormat('d M') }} - {{ $item->tanggal_tutup->translatedFormat('d M Y') }}</span>
                </div>
                <div class="flex justify-between pt-3 border-t border-gray-200 dark:border-gray-700">
                    <button wire:click="showDetail({{ $item->id }})" class="text-blue-600 hover:text-blue-900 text-sm">
                        <i class="fas fa-eye mr-1"></i>Detail
                    </button>
                    <button wire:click="edit({{ $item->id }})" class="text-green-600 hover:text-green-900 text-sm">
                        <i class="fas fa-edit mr-1"></i>Edit
                    </button>
                    <button wire:click="updateStatus({{ $item->id }}, '{{ $item->status === 'buka' ? 'tutup' : 'buka' }}')" class="text-orange-600 hover:text-orange-900 text-sm">
                        <i class="fas fa-toggle-{{ $item->status === 'buka' ? 'on' : 'off' }} mr-1"></i>
                        {{ $item->status === 'buka' ? 'Tutup' : 'Buka' }}
                    </button>
                    <button wire:click="delete({{ $item->id }})" onclick="return confirm('Apakah Anda yakin ingin menghapus beasiswa ini?')" class="text-red-600 hover:text-red-900 text-sm">
                        <i class="fas fa-trash mr-1"></i>Hapus
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-3 bg-white dark:bg-gray-800 rounded-lg shadow p-8 text-center">
            <div class="text-gray-500 dark:text-gray-400">
                <i class="fas fa-award text-4xl mb-4"></i>
                <p class="text-lg">Tidak ada data beasiswa</p>
                <p class="text-sm mt-2">Klik tombol "Tambah Beasiswa" untuk menambahkan beasiswa baru</p>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Tabel Data - Mobile -->
    <div class="sm:hidden space-y-4">
        @forelse($beasiswa as $item)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="space-y-3">
                <!-- Header -->
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $item->nama_beasiswa }}
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            {{ Str::limit($item->deskripsi, 60) }}
                        </div>
                    </div>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $item->status === 'buka' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $statusOptions[$item->status] }}
                    </span>
                </div>

                <!-- Detail Info -->
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div>
                        <div class="text-gray-500 dark:text-gray-400 text-xs">Penyelenggara</div>
                        <div class="text-gray-900 dark:text-white">
                            {{ Str::limit($item->penyelenggara, 25) }}
                        </div>
                    </div>
                    <div>
                        <div class="text-gray-500 dark:text-gray-400 text-xs">Nilai</div>
                        <div class="text-gray-900 dark:text-white">
                            {{ $item->nilai_beasiswa ? 'Rp ' . number_format($item->nilai_beasiswa, 0, ',', '.') : '-' }}
                        </div>
                    </div>
                    <div class="col-span-2">
                        <div class="text-gray-500 dark:text-gray-400 text-xs">Periode</div>
                        <div class="text-gray-900 dark:text-white">
                            {{ $item->tanggal_buka->translatedFormat('d M') }} - {{ $item->tanggal_tutup->translatedFormat('d M Y') }}
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-between pt-2 border-t border-gray-200 dark:border-gray-700">
                    <button wire:click="showDetail({{ $item->id }})" class="text-blue-600 hover:text-blue-900 text-sm">
                        <i class="fas fa-eye mr-1"></i>Detail
                    </button>
                    <button wire:click="edit({{ $item->id }})" class="text-green-600 hover:text-green-900 text-sm">
                        <i class="fas fa-edit mr-1"></i>Edit
                    </button>
                    <button wire:click="updateStatus({{ $item->id }}, '{{ $item->status === 'buka' ? 'tutup' : 'buka' }}')" class="text-orange-600 hover:text-orange-900 text-sm">
                        <i class="fas fa-toggle-{{ $item->status === 'buka' ? 'on' : 'off' }} mr-1"></i>
                        {{ $item->status === 'buka' ? 'Tutup' : 'Buka' }}
                    </button>
                    <button wire:click="delete({{ $item->id }})" onclick="return confirm('Apakah Anda yakin ingin menghapus beasiswa ini?')" class="text-red-600 hover:text-red-900 text-sm">
                        <i class="fas fa-trash mr-1"></i>Hapus
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 text-center">
            <div class="text-gray-500 dark:text-gray-400">
                <i class="fas fa-award text-3xl mb-3"></i>
                <p>Tidak ada data beasiswa</p>
            </div>
        </div>
        @endforelse

        <!-- Pagination Mobile -->
        @if($beasiswa->hasPages())
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            {{ $beasiswa->links() }}
        </div>
        @endif
    </div>

    <!-- Pagination Desktop -->
    @if($beasiswa->hasPages())
    <div class="hidden sm:block mt-6">
        {{ $beasiswa->links() }}
    </div>
    @endif

    <!-- Modal Form -->
    @if($showModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="p-4 sm:p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ $modalTitle }}</h3>
                
                <form wire:submit="save">
                    <div class="grid grid-cols-1 gap-4">
                        <!-- Nama Beasiswa dan Penyelenggara -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Beasiswa *</label>
                                <input type="text" wire:model="nama_beasiswa" placeholder="Nama beasiswa..." class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('nama_beasiswa') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Penyelenggara *</label>
                                <input type="text" wire:model="penyelenggara" placeholder="Nama penyelenggara..." class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('penyelenggara') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deskripsi *</label>
                            <textarea wire:model="deskripsi" rows="3" placeholder="Deskripsi lengkap tentang beasiswa..." class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                            @error('deskripsi') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Persyaratan -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Persyaratan *</label>
                            <textarea wire:model="persyaratan" rows="4" placeholder="Persyaratan yang harus dipenuhi..." class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                            @error('persyaratan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Nilai Beasiswa dan Status -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nilai Beasiswa</label>
                                <input type="number" wire:model="nilai_beasiswa" placeholder="Jumlah beasiswa..." class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('nilai_beasiswa') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status *</label>
                                <select wire:model="status" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    @foreach($statusOptions as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Tanggal Buka dan Tutup -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Buka *</label>
                                <input type="date" wire:model="tanggal_buka" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('tanggal_buka') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Tutup *</label>
                                <input type="date" wire:model="tanggal_tutup" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('tanggal_tutup') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Kontak dan Website -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kontak *</label>
                                <input type="text" wire:model="kontak" placeholder="Email/Telepon kontak..." class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('kontak') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Website</label>
                                <input type="url" wire:model="website" placeholder="https://example.com" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('website') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Dokumen -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Dokumen (PDF/DOC)</label>
                            <input type="file" wire:model="dokumen" accept=".pdf,.doc,.docx" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('dokumen') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            @if($dokumen)
                                <p class="text-xs text-green-600 mt-1">File terpilih: {{ $dokumen->getClientOriginalName() }}</p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="mt-6 flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-3">
                        <button type="button" wire:click="$set('showModal', false)" class="w-full sm:w-auto px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">Batal</button>
                        <button type="submit" class="w-full sm:w-auto px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal Detail -->
    @if($showDetailModal && $selectedBeasiswa)
    <!-- Lanjutan dari modal detail -->
<div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="p-4 sm:p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Detail Beasiswa</h3>
                <button wire:click="$set('showDetailModal', false)" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="space-y-4">
                <!-- Header -->
                <div class="p-4 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg text-white">
                    <h2 class="text-xl font-bold">{{ $selectedBeasiswa->nama_beasiswa }}</h2>
                    <p class="text-blue-100">{{ $selectedBeasiswa->penyelenggara }}</p>
                </div>

                <!-- Status dan Nilai -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Status</div>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $selectedBeasiswa->status === 'buka' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $statusOptions[$selectedBeasiswa->status] }}
                        </span>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Nilai Beasiswa</div>
                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $selectedBeasiswa->nilai_beasiswa ? 'Rp ' . number_format($selectedBeasiswa->nilai_beasiswa, 0, ',', '.') : 'Tidak ditentukan' }}
                        </div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Periode</div>
                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $selectedBeasiswa->tanggal_buka->translatedFormat('d M Y') }} - {{ $selectedBeasiswa->tanggal_tutup->translatedFormat('d M Y') }}
                        </div>
                    </div>
                </div>

                <!-- Deskripsi -->
                <div>
                    <div class="text-sm text-gray-500 dark:text-gray-400 mb-2">Deskripsi</div>
                    <div class="text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                        {{ $selectedBeasiswa->deskripsi }}
                    </div>
                </div>

                <!-- Persyaratan -->
                <div>
                    <div class="text-sm text-gray-500 dark:text-gray-400 mb-2">Persyaratan</div>
                    <div class="text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 p-3 rounded-lg whitespace-pre-line">
                        {{ $selectedBeasiswa->persyaratan }}
                    </div>
                </div>

                <!-- Kontak dan Website -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mb-2">Kontak</div>
                        <div class="text-sm text-gray-900 dark:text-white">
                            {{ $selectedBeasiswa->kontak }}
                        </div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mb-2">Website</div>
                        <div class="text-sm text-gray-900 dark:text-white">
                            @if($selectedBeasiswa->website)
                                <a href="{{ $selectedBeasiswa->website }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                    {{ $selectedBeasiswa->website }}
                                </a>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Dokumen -->
                @if($selectedBeasiswa->dokumen)
                <div>
                    <div class="text-sm text-gray-500 dark:text-gray-400 mb-2">Dokumen</div>
                    <div class="text-sm text-gray-900 dark:text-white">
                        <a href="{{ asset('storage/' . $selectedBeasiswa->dokumen) }}" target="_blank" class="text-blue-600 hover:text-blue-800 flex items-center">
                            <i class="fas fa-file-download mr-2"></i>
                            Download Dokumen
                        </a>
                    </div>
                </div>
                @endif

                <!-- Created & Updated -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Dibuat</div>
                        <div class="text-sm text-gray-900 dark:text-white">
                            {{ $selectedBeasiswa->created_at->translatedFormat('d F Y H:i') }}
                        </div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Diupdate</div>
                        <div class="text-sm text-gray-900 dark:text-white">
                            {{ $selectedBeasiswa->updated_at->translatedFormat('d F Y H:i') }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <button wire:click="$set('showDetailModal', false)" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                    Tutup
                </button>
                <button wire:click="edit({{ $selectedBeasiswa->id }})" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-edit mr-1"></i>Edit
                </button>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Flash Messages -->
@if (session()->has('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
         class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
        <div class="flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            <span>{{ session('success') }}</span>
        </div>
    </div>
@endif

@if (session()->has('error'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
         class="fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
        <div class="flex items-center">
            <i class="fas fa-exclamation-circle mr-2"></i>
            <span>{{ session('error') }}</span>
        </div>
    </div>
@endif
</div>


<script>
document.addEventListener('livewire:initialized', () => {
    // Konfirmasi delete dengan SweetAlert
    window.confirmDelete = function(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data beasiswa akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('delete', { id: id });
            }
        });
    }

    // Event listener untuk delete buttons
    document.addEventListener('click', function(e) {
        if (e.target.closest('[wire\\:click="delete"]')) {
            e.preventDefault();
            const id = e.target.closest('[wire\\:click="delete"]').getAttribute('wire:click').match(/\d+/)[0];
            confirmDelete(id);
        }
    });
});
</script>