<div class="p-3 sm:p-4 md:p-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">Pengumuman Kelas</h1>
        <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400">Buat dan kelola pengumuman untuk kelas yang Anda ajar</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 sm:gap-4 md:gap-6 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 sm:p-6 border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-bullhorn text-xl sm:text-2xl text-blue-500"></i>
                </div>
                <div class="ml-3 sm:ml-4">
                    <p class="text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-400">Total Pengumuman</p>
                    <p class="text-xl sm:text-2xl font-semibold text-gray-900 dark:text-white">{{ $pengumumanList->total() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 sm:p-6 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-building text-xl sm:text-2xl text-green-500"></i>
                </div>
                <div class="ml-3 sm:ml-4">
                    <p class="text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-400">Kelas Diampu</p>
                    <p class="text-xl sm:text-2xl font-semibold text-gray-900 dark:text-white">{{ $kelasDiajar->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 sm:p-6 border-l-4 border-yellow-500 sm:col-span-2 md:col-span-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-xl sm:text-2xl text-yellow-500"></i>
                </div>
                <div class="ml-3 sm:ml-4">
                    <p class="text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-400">Pengumuman Mendesak</p>
                    <p class="text-xl sm:text-2xl font-semibold text-gray-900 dark:text-white">
                        {{ $pengumumanList->where('is_urgent', true)->count() }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters & Actions -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 sm:p-6 mb-6">
        <div class="flex flex-col gap-4">
            <div>
                <input type="text" wire:model.live="search" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                       placeholder="Cari pengumuman...">
            </div>
            <div>
                <button wire:click="openModal" 
                        class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                    <i class="fas fa-plus-circle mr-2"></i>Buat Pengumuman
                </button>
            </div>
        </div>
    </div>

    <!-- Pengumuman List -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Daftar Pengumuman</h2>
        </div>

        <!-- Mobile Card View -->
        <div class="sm:hidden p-4 space-y-4">
            @forelse($pengumumanList as $pengumuman)
                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                    <div class="flex justify-between items-start mb-2">
                        <div class="flex items-center">
                            @if($pengumuman->is_urgent)
                                <i class="fas fa-exclamation-triangle text-yellow-500 mr-2"></i>
                            @endif
                            <h3 class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $pengumuman->judul }}
                            </h3>
                        </div>
                        @if($pengumuman->is_urgent)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                Mendesak
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                Normal
                            </span>
                        @endif
                    </div>
                    
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                        {{ Str::limit($pengumuman->isi, 100) }}
                    </p>
                    
                    <div class="flex flex-wrap gap-2 mb-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                            {{ $pengumuman->kelas->nama_kelas ?? 'Semua Kelas' }}
                        </span>
                        <span class="text-xs text-gray-500 dark:text-gray-400">
                            {{ $pengumuman->created_at->format('d/m/Y H:i') }}
                        </span>
                    </div>
                    
                    <div class="flex justify-end space-x-2">
                        <button wire:click="edit({{ $pengumuman->id }})" 
                                class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg dark:text-blue-400 dark:hover:bg-blue-900/20"
                                title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button wire:click="toggleUrgent({{ $pengumuman->id }})" 
                                class="p-2 text-yellow-600 hover:bg-yellow-50 rounded-lg dark:text-yellow-400 dark:hover:bg-yellow-900/20"
                                title="{{ $pengumuman->is_urgent ? 'Hapus status mendesak' : 'Tandai mendesak' }}">
                            <i class="fas fa-exclamation-triangle"></i>
                        </button>
                        <button wire:click="delete({{ $pengumuman->id }})" 
                                wire:confirm="Apakah Anda yakin ingin menghapus pengumuman ini?"
                                class="p-2 text-red-600 hover:bg-red-50 rounded-lg dark:text-red-400 dark:hover:bg-red-900/20"
                                title="Hapus">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            @empty
                <div class="text-center py-8">
                    <div class="text-gray-500 dark:text-gray-400">
                        <i class="fas fa-bullhorn text-4xl mb-3 block"></i>
                        <p>Belum ada pengumuman</p>
                        <button wire:click="openModal" 
                                class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Buat Pengumuman Pertama
                        </button>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Desktop Table View -->
        <div class="hidden sm:block overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Judul</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kelas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($pengumumanList as $pengumuman)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    @if($pengumuman->is_urgent)
                                        <i class="fas fa-exclamation-triangle text-yellow-500 mr-2"></i>
                                    @endif
                                    <div>
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $pengumuman->judul }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                            {{ Str::limit($pengumuman->isi, 100) }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    {{ $pengumuman->kelas->nama_kelas ?? 'Semua Kelas' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($pengumuman->is_urgent)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                        Mendesak
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        Normal
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $pengumuman->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <button wire:click="edit({{ $pengumuman->id }})" 
                                            class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                                            title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button wire:click="toggleUrgent({{ $pengumuman->id }})" 
                                            class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300"
                                            title="{{ $pengumuman->is_urgent ? 'Hapus status mendesak' : 'Tandai mendesak' }}">
                                        <i class="fas fa-exclamation-triangle"></i>
                                    </button>
                                    <button wire:click="delete({{ $pengumuman->id }})" 
                                            wire:confirm="Apakah Anda yakin ingin menghapus pengumuman ini?"
                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                            title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center">
                                <div class="text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-bullhorn text-4xl mb-3 block"></i>
                                    <p>Belum ada pengumuman</p>
                                    <button wire:click="openModal" 
                                            class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                        Buat Pengumuman Pertama
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-4 sm:px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $pengumumanList->links() }}
        </div>
    </div>

    <!-- Create/Edit Modal -->
    @if($showModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-4 sm:p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg sm:text-xl font-semibold text-gray-900 dark:text-white">
                        {{ $editId ? 'Edit Pengumuman' : 'Buat Pengumuman Baru' }}
                    </h2>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 p-1">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <form wire:submit.prevent="save">
                    <div class="space-y-4">
                        <!-- Kelas -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Kelas Tujuan <span class="text-red-500">*</span>
                            </label>
                            <select wire:model="kelasId" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="">Pilih Kelas</option>
                                @foreach($kelasDiajar as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama_kelas }}</option>
                                @endforeach
                            </select>
                            @error('kelasId') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Judul -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Judul Pengumuman <span class="text-red-500">*</span>
                            </label>
                            <input type="text" wire:model="judul" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                   placeholder="Masukkan judul pengumuman">
                            @error('judul') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Isi -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Isi Pengumuman <span class="text-red-500">*</span>
                            </label>
                            <textarea wire:model="isi" rows="4"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                      placeholder="Tulis isi pengumuman di sini..."></textarea>
                            @error('isi') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Urgent -->
                        <div class="flex items-center">
                            <input type="checkbox" wire:model="isUrgent" 
                                   id="isUrgent"
                                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="isUrgent" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                Tandai sebagai pengumuman mendesak
                            </label>
                        </div>

                        <!-- Tanggal Berlaku -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Tanggal Berlaku (Opsional)
                            </label>
                            <input type="date" wire:model="tanggalBerlaku" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            @error('tanggalBerlaku') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row justify-end gap-3 mt-6">
                        <button type="button" wire:click="closeModal" 
                                class="w-full sm:w-auto px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                            Batal
                        </button>
                        <button type="submit" 
                                class="w-full sm:w-auto px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                            {{ $editId ? 'Update' : 'Simpan' }} Pengumuman
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="fixed top-4 right-4 z-50 max-w-[90%] sm:max-w-none">
            <div class="bg-green-500 text-white px-4 sm:px-6 py-3 rounded-lg shadow-lg flex items-center space-x-2">
                <i class="fas fa-check-circle"></i>
                <span class="text-sm">{{ session('success') }}</span>
            </div>
        </div>
    @endif
</div>