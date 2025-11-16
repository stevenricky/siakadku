<div class="p-4 sm:p-6">
    <!-- Header dan Tombol -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 space-y-4 sm:space-y-0">
        <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">Manajemen Pesan</h1>
        <div class="flex space-x-3">
            <button wire:click="toggleKirimPesan" 
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition flex items-center justify-center w-full sm:w-auto">
                <i class="fas fa-paper-plane mr-2"></i>
                {{ $showKirimPesan ? 'Batal' : 'Kirim Pesan' }}
            </button>
        </div>
    </div>

    <!-- Form Kirim Pesan -->
    @if($showKirimPesan)
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
        <div class="p-4 sm:p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Kirim Pesan Baru</h3>
            
            <form wire:submit="kirimPesan">
                <div class="grid grid-cols-1 gap-4 mb-4">
                    <!-- Tipe Penerima -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Tipe Penerima
                        </label>
                        <select wire:model="tipePenerima" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="guru">Semua Guru</option>
                            <option value="siswa">Semua Siswa</option>
                            <option value="semua">Semua Pengguna</option>
                            <option value="individu">Individu</option>
                        </select>
                    </div>

                    <!-- Penerima Individu -->
                    @if($tipePenerima === 'individu')
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Pilih Penerima
                        </label>
                        <select wire:model="penerima_id" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Pilih Penerima</option>
                            @foreach($users as $user)
                            <option value="{{ $user->id }}">
                                {{ $user->name }} ({{ $user->role }})
                            </option>
                            @endforeach
                        </select>
                        @error('penerima_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    @endif
                </div>

                <!-- Subjek -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Subjek Pesan
                    </label>
                    <input type="text" wire:model="subjek" 
                           placeholder="Masukkan subjek pesan..."
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('subjek') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Isi Pesan -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Isi Pesan
                    </label>
                    <textarea wire:model="pesan" rows="4"
                              placeholder="Tulis isi pesan di sini..."
                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                    @error('pesan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Tombol Aksi -->
                <div class="flex flex-col sm:flex-row sm:justify-end space-y-2 sm:space-y-0 sm:space-x-3">
                    <button type="button" wire:click="toggleKirimPesan" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-paper-plane mr-2"></i>Kirim Pesan
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- Statistik Pesan -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                    <i class="fas fa-comments text-blue-600 dark:text-blue-400"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Pesan</p>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">{{ $pesanList->total() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 dark:bg-green-900 rounded-lg">
                    <i class="fas fa-check text-green-600 dark:text-green-400"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Sudah Dibaca</p>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">
                        {{ \App\Models\Pesan::where('dibaca', true)->count() }}
                    </p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 dark:bg-yellow-900 rounded-lg">
                    <i class="fas fa-clock text-yellow-600 dark:text-yellow-400"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Belum Dibaca</p>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">
                        {{ \App\Models\Pesan::where('dibaca', false)->count() }}
                    </p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 dark:bg-purple-900 rounded-lg">
                    <i class="fas fa-users text-purple-600 dark:text-purple-400"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Pengguna</p>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">{{ $users->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search dan Filter -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex flex-col gap-4">
                <div>
                    <input type="text" 
                           wire:model.live="search"
                           placeholder="Cari pesan, subjek, atau nama pengirim/penerima..." 
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                </div>
                <select wire:model.live="perPage" 
                        class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                    <option value="5">5 per halaman</option>
                    <option value="10">10 per halaman</option>
                    <option value="25">25 per halaman</option>
                    <option value="50">50 per halaman</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Tabel Pesan - Desktop View -->
    <div class="hidden sm:block bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Pengirim
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Penerima
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Subjek
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Pesan
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Tanggal
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($pesanList as $pesan)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                    {{ substr($pesan->pengirim->name ?? '?', 0, 1) }}
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $pesan->pengirim->name ?? 'Unknown' }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $pesan->pengirim->role ?? '-' }}
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8 bg-green-500 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                    {{ substr($pesan->penerima->name ?? '?', 0, 1) }}
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $pesan->penerima->name ?? 'Unknown' }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $pesan->penerima->role ?? '-' }}
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="max-w-xs">
                                <p class="text-sm font-medium text-gray-900 dark:text-white truncate" title="{{ $pesan->subjek ?? 'Tidak ada subjek' }}">
                                    {{ $pesan->subjek ?? 'Tidak ada subjek' }}
                                </p>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="max-w-xs">
                                <p class="text-sm text-gray-900 dark:text-white truncate" title="{{ $pesan->pesan }}">
                                    {{ Str::limit($pesan->pesan, 50) }}
                                </p>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($pesan->dibaca)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                <i class="fas fa-check mr-1"></i>
                                Dibaca
                                @if($pesan->dibaca_pada)
                                <br>
                                <small class="text-xs">{{ $pesan->dibaca_pada->format('d/m H:i') }}</small>
                                @endif
                            </span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                <i class="fas fa-clock mr-1"></i>
                                Belum Dibaca
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                            <div>
                                <p class="font-medium">{{ $pesan->created_at->format('d/m/Y') }}</p>
                                <p class="text-xs">{{ $pesan->created_at->format('H:i') }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                @if(!$pesan->dibaca)
                                <button wire:click="markAsRead({{ $pesan->id }})" 
                                        class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 inline-flex items-center"
                                        title="Tandai sebagai sudah dibaca">
                                    <i class="fas fa-check mr-1"></i>
                                    Dibaca
                                </button>
                                @else
                                <button wire:click="markAsUnread({{ $pesan->id }})" 
                                        class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300 inline-flex items-center"
                                        title="Tandai sebagai belum dibaca">
                                    <i class="fas fa-clock mr-1"></i>
                                    Belum
                                </button>
                                @endif
                                <button wire:click="deletePesan({{ $pesan->id }})" 
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus pesan ini?')"
                                        class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 inline-flex items-center"
                                        title="Hapus pesan">
                                    <i class="fas fa-trash mr-1"></i>
                                    Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                            <div class="flex flex-col items-center justify-center py-8">
                                <i class="fas fa-comments text-gray-300 dark:text-gray-600 text-4xl mb-2"></i>
                                <p class="text-gray-500 dark:text-gray-400">Belum ada pesan dalam sistem</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($pesanList->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $pesanList->links() }}
        </div>
        @endif
    </div>

    <!-- Mobile Card View -->
    <div class="sm:hidden space-y-4">
        @forelse($pesanList as $pesan)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="flex justify-between items-start mb-3">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-8 w-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-xs font-bold">
                        {{ substr($pesan->pengirim->name ?? '?', 0, 1) }}
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $pesan->pengirim->name ?? 'Unknown' }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            {{ $pesan->pengirim->role ?? '-' }}
                        </p>
                    </div>
                </div>
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-8 w-8 bg-green-500 rounded-full flex items-center justify-center text-white text-xs font-bold">
                        {{ substr($pesan->penerima->name ?? '?', 0, 1) }}
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $pesan->penerima->name ?? 'Unknown' }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            {{ $pesan->penerima->role ?? '-' }}
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-1">
                    {{ $pesan->subjek ?? 'Tidak ada subjek' }}
                </h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    {{ Str::limit($pesan->pesan, 100) }}
                </p>
            </div>
            
            <div class="flex items-center justify-between mb-3">
                <div>
                    @if($pesan->dibaca)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                        <i class="fas fa-check mr-1"></i>
                        Dibaca
                    </span>
                    @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                        <i class="fas fa-clock mr-1"></i>
                        Belum Dibaca
                    </span>
                    @endif
                </div>
                <div class="text-xs text-gray-500 dark:text-gray-400">
                    {{ $pesan->created_at->format('d/m/Y H:i') }}
                </div>
            </div>
            
            <div class="flex justify-between items-center pt-3 border-t border-gray-200 dark:border-gray-700">
                <div class="flex space-x-2">
                    @if(!$pesan->dibaca)
                    <button wire:click="markAsRead({{ $pesan->id }})" 
                            class="px-3 py-1 text-xs bg-green-600 text-white rounded hover:bg-green-700 transition">
                        <i class="fas fa-check mr-1"></i>
                        Dibaca
                    </button>
                    @else
                    <button wire:click="markAsUnread({{ $pesan->id }})" 
                            class="px-3 py-1 text-xs bg-yellow-600 text-white rounded hover:bg-yellow-700 transition">
                        <i class="fas fa-clock mr-1"></i>
                        Belum
                    </button>
                    @endif
                    <button wire:click="deletePesan({{ $pesan->id }})" 
                            onclick="return confirm('Apakah Anda yakin ingin menghapus pesan ini?')"
                            class="px-3 py-1 text-xs bg-red-600 text-white rounded hover:bg-red-700 transition">
                        <i class="fas fa-trash mr-1"></i>
                        Hapus
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="flex flex-col items-center justify-center py-8">
                <i class="fas fa-comments text-gray-300 dark:text-gray-600 text-4xl mb-2"></i>
                <p class="text-gray-500 dark:text-gray-400">Belum ada pesan dalam sistem</p>
            </div>
        </div>
        @endforelse
        
        <!-- Pagination for Mobile -->
        @if($pesanList->hasPages())
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            {{ $pesanList->links() }}
        </div>
        @endif
    </div>

    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
             class="fixed top-4 right-4 bg-green-500 text-white px-4 sm:px-6 py-3 rounded-lg shadow-lg z-50 max-w-xs sm:max-w-md">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <span class="text-sm">{{ session('message') }}</span>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
             class="fixed top-4 right-4 bg-red-500 text-white px-4 sm:px-6 py-3 rounded-lg shadow-lg z-50 max-w-xs sm:max-w-md">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <span class="text-sm">{{ session('error') }}</span>
            </div>
        </div>
    @endif
</div>