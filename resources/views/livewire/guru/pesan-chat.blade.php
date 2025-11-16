<div>
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Pesan & Chat</h1>
        <p class="text-gray-600 dark:text-gray-400">Kelola pesan dengan siswa</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="bi bi-chat-dots text-2xl text-blue-500"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Pesan</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                        {{ $totalPesan }}
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="bi bi-people text-2xl text-green-500"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Siswa Diampu</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $totalSiswa }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-yellow-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="bi bi-envelope text-2xl text-yellow-500"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pesan Belum Dibaca</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                        {{ $pesanBelumDibaca }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Daftar Siswa -->
        <div class="lg:col-span-1 bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex justify-between items-center mb-2">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Daftar Siswa</h2>
                    <span class="text-sm text-gray-500 dark:text-gray-400">
                        {{ $siswaList->count() }} siswa
                    </span>
                </div>
                
                <!-- Pencarian -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="bi bi-search text-gray-400"></i>
                    </div>
                    <input 
                        type="text" 
                        wire:model.live="search"
                        placeholder="Cari nama siswa atau kelas..."
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    >
                    @if($search)
                        <button 
                            wire:click="$set('search', '')"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600"
                        >
                            <i class="bi bi-x-lg text-sm"></i>
                        </button>
                    @endif
                </div>
            </div>
            
            <div class="max-h-96 overflow-y-auto">
                @forelse($siswaList as $siswa)
                    <button 
                        wire:click="pilihPenerima({{ $siswa->id }})"
                        class="w-full p-4 text-left border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors {{ $penerimaId == $siswa->id ? 'bg-blue-50 dark:bg-blue-900' : '' }}"
                    >
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0 w-10 h-10 bg-primary-100 dark:bg-primary-900 rounded-full flex items-center justify-center">
                                <span class="text-primary-600 dark:text-primary-300 font-medium">
                                    {{ substr($siswa->name, 0, 1) }}
                                </span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                    {{ $siswa->name }}
                                </p>
                                <p class="text-sm text-gray-500 dark:text-gray-400 truncate">
                                    {{ $siswa->siswa->kelas->nama_kelas ?? 'Kelas' }}
                                </p>
                            </div>
                            @php
                                $unreadCount = $this->hitungPesanBelumDibaca($siswa->id);
                            @endphp
                            @if($unreadCount > 0)
                                <span class="flex-shrink-0 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">
                                    {{ $unreadCount }}
                                </span>
                            @endif
                        </div>
                    </button>
                @empty
                    <div class="p-4 text-center text-gray-500 dark:text-gray-400">
                        @if($search)
                            <i class="bi bi-search text-2xl mb-2 block"></i>
                            <p>Tidak ada siswa ditemukan</p>
                            <p class="text-sm">Untuk pencarian "{{ $search }}"</p>
                            <button 
                                wire:click="$set('search', '')"
                                class="mt-2 text-primary-600 hover:text-primary-700 text-sm"
                            >
                                Tampilkan semua siswa
                            </button>
                        @else
                            <i class="bi bi-people text-2xl mb-2 block"></i>
                            <p>Tidak ada siswa ditemukan</p>
                        @endif
                    </div>
                @endforelse
            </div>
            
            <!-- Info Pencarian -->
            @if($search && $siswaList->count() > 0)
                <div class="p-3 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
                    <p class="text-xs text-gray-500 dark:text-gray-400 text-center">
                        Menampilkan {{ $siswaList->count() }} hasil untuk "{{ $search }}"
                        <button 
                            wire:click="$set('search', '')"
                            class="text-primary-600 hover:text-primary-700 ml-1"
                        >
                            Tampilkan semua
                        </button>
                    </p>
                </div>
            @endif
        </div>

        <!-- Area Pesan -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-lg shadow">
            @if($penerimaId)
                @php
                    $penerima = $siswaList->firstWhere('id', $penerimaId);
                @endphp
                
                <!-- Header Pesan -->
                <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0 w-10 h-10 bg-primary-100 dark:bg-primary-900 rounded-full flex items-center justify-center">
                            <span class="text-primary-600 dark:text-primary-300 font-medium">
                                {{ substr($penerima->name, 0, 1) }}
                            </span>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                {{ $penerima->name }}
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $penerima->siswa->kelas->nama_kelas ?? 'Kelas' }}
                            </p>
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        @if($search)
                            <button 
                                wire:click="$set('search', '')"
                                class="px-3 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700 text-sm"
                            >
                                <i class="bi bi-arrow-left mr-1"></i>Kembali
                            </button>
                        @endif
                        <button 
                            wire:click="bukaModalPesan"
                            class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-colors"
                        >
                            <i class="bi bi-pencil mr-2"></i>Pesan Baru
                        </button>
                    </div>
                </div>

                <!-- Daftar Pesan -->
                <div class="h-96 overflow-y-auto p-4 space-y-4">
                    @forelse($pesanList as $pesan)
                        <div class="flex {{ $pesan->pengirim_id == auth()->id() ? 'justify-end' : 'justify-start' }}">
                            <div class="max-w-xs lg:max-w-md {{ $pesan->pengirim_id == auth()->id() ? 'bg-primary-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white' }} rounded-lg p-3">
                                @if($pesan->subjek)
                                    <p class="font-semibold text-sm mb-1 {{ $pesan->pengirim_id == auth()->id() ? 'text-primary-100' : 'text-gray-700 dark:text-gray-300' }}">
                                        {{ $pesan->subjek }}
                                    </p>
                                @endif
                                <p class="text-sm">{{ $pesan->pesan }}</p>
                                <div class="flex justify-between items-center mt-2">
                                    <p class="text-xs opacity-70 {{ $pesan->pengirim_id == auth()->id() ? 'text-primary-100' : 'text-gray-500' }}">
                                        {{ $pesan->created_at->format('H:i') }}
                                    </p>
                                    @if($pesan->pengirim_id == auth()->id())
                                        <button 
                                            wire:click="hapusPesan({{ $pesan->id }})"
                                            wire:confirm="Apakah Anda yakin ingin menghapus pesan ini?"
                                            class="text-xs opacity-70 hover:opacity-100"
                                        >
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-gray-500 dark:text-gray-400 py-8">
                            <i class="bi bi-chat-dots text-2xl mb-2 block"></i>
                            <p>Belum ada pesan</p>
                            <p class="text-sm">Kirim pesan pertama untuk memulai percakapan</p>
                        </div>
                    @endforelse
                </div>
            @else
                <!-- Placeholder ketika belum memilih siswa -->
                <div class="h-96 flex items-center justify-center text-center">
                    <div class="text-gray-500 dark:text-gray-400">
                        @if($search)
                            <i class="bi bi-search text-4xl mb-4 block"></i>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Pilih Siswa</h3>
                            <p>Pilih siswa dari hasil pencarian untuk melihat pesan</p>
                        @else
                            <i class="bi bi-chat-left-text text-4xl mb-4 block"></i>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Pilih Siswa</h3>
                            <p>Pilih siswa dari daftar untuk melihat pesan</p>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Kirim Pesan Baru -->
    @if($showModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Pesan Baru</h3>
                    <button wire:click="$set('showModal', false)" class="text-gray-400 hover:text-gray-600">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                <form wire:submit.prevent="kirimPesan">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Subjek <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                wire:model="subjek"
                                placeholder="Masukkan subjek pesan"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            >
                            @error('subjek') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Isi Pesan <span class="text-red-500">*</span>
                            </label>
                            <textarea 
                                wire:model="pesanTeks"
                                rows="4"
                                placeholder="Tulis isi pesan di sini..."
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            ></textarea>
                            @error('pesanTeks') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 mt-6">
                        <button 
                            type="button" 
                            wire:click="$set('showModal', false)"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700"
                        >
                            Batal
                        </button>
                        <button 
                            type="submit"
                            class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-colors"
                        >
                            Kirim Pesan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal Detail Pesan -->
    @if($pesanTerpilih)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Detail Pesan</h3>
                    <button wire:click="$set('pesanTerpilih', null)" class="text-gray-400 hover:text-gray-600">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Pengirim</label>
                        <p class="text-sm text-gray-900 dark:text-white">{{ $pesanTerpilih->pengirim->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Penerima</label>
                        <p class="text-sm text-gray-900 dark:text-white">{{ $pesanTerpilih->penerima->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Subjek</label>
                        <p class="text-sm text-gray-900 dark:text-white">{{ $pesanTerpilih->subjek }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Isi Pesan</label>
                        <p class="text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 p-3 rounded">{{ $pesanTerpilih->pesan }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Waktu</label>
                        <p class="text-sm text-gray-900 dark:text-white">{{ $pesanTerpilih->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Status</label>
                        <p class="text-sm text-gray-900 dark:text-white">
                            @if($pesanTerpilih->dibaca)
                                <span class="text-green-600">Sudah dibaca</span>
                            @else
                                <span class="text-yellow-600">Belum dibaca</span>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="flex justify-end mt-6">
                    <button wire:click="$set('pesanTerpilih', null)" 
                            class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="fixed top-4 right-4 z-50">
            <div class="bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-2">
                <i class="bi bi-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="fixed top-4 right-4 z-50">
            <div class="bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-2">
                <i class="bi bi-exclamation-circle"></i>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif
</div>