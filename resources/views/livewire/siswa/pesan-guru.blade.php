<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Pesan Guru</h1>
        <p class="text-gray-600 dark:text-gray-400">Kirim dan terima pesan dari guru Anda</p>
    </div>

    <!-- Flash Message -->
    @if (session()->has('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg dark:bg-green-900 dark:border-green-700 dark:text-green-200">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg dark:bg-red-900 dark:border-red-700 dark:text-red-200">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Daftar Guru -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="border-b border-gray-200 dark:border-gray-700 px-4 py-3">
                    <h3 class="font-semibold text-gray-900 dark:text-white">Daftar Guru</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Pilih guru untuk mengirim pesan</p>
                </div>

                <div class="max-h-96 overflow-y-auto">
                    @forelse($guruList as $guru)
                        <button 
                            wire:click="pilihPenerima({{ $guru->id }})"
                            class="w-full text-left p-4 border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200 {{ $penerimaId == $guru->id ? 'bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800' : '' }}"
                        >
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                            {{ substr($guru->name, 0, 1) }}
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-900 dark:text-white">
                                            {{ $guru->name }}
                                        </h4>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $guru->nama_mapel }}
                                        </p>
                                    </div>
                                </div>
                                @if(isset($unreadCounts[$guru->id]) && $unreadCounts[$guru->id] > 0)
                                    <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full">
                                        {{ $unreadCounts[$guru->id] }}
                                    </span>
                                @endif
                            </div>
                        </button>
                    @empty
                        <div class="p-4 text-center text-gray-500 dark:text-gray-400">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                            </svg>
                            <p class="mt-2">Tidak ada guru tersedia</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Area Chat -->
        <div class="lg:col-span-2">
            @if($penerimaId)
                @php $guruTerpilih = $guruList->firstWhere('id', $penerimaId); @endphp
                
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 mb-4">
                    <div class="border-b border-gray-200 dark:border-gray-700 px-4 py-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                    {{ substr($guruTerpilih->name, 0, 1) }}
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900 dark:text-white">
                                        {{ $guruTerpilih->name }}
                                    </h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $guruTerpilih->nama_mapel }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Area Pesan -->
                    <div class="h-96 overflow-y-auto p-4 space-y-4" id="chat-messages">
                        @forelse($pesanList as $pesan)
                            <div class="flex {{ $pesan->pengirim_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                                <div class="max-w-xs lg:max-w-md px-4 py-2 rounded-lg {{ $pesan->pengirim_id === auth()->id() ? 'bg-blue-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white' }}">
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-xs font-medium">
                                            {{ $pesan->pengirim_id === auth()->id() ? 'Anda' : $pesan->pengirim->name }}
                                        </span>
                                        <span class="text-xs opacity-75">
                                            {{ $pesan->created_at->format('H:i') }}
                                        </span>
                                    </div>
                                    <p class="text-sm whitespace-pre-wrap">{{ $pesan->pesan }}</p>
                                    @if($pesan->pengirim_id === auth()->id())
                                        <div class="flex justify-between items-center mt-1">
                                            <span class="text-xs {{ $pesan->dibaca ? 'text-green-400' : 'text-blue-300' }}">
                                                {{ $pesan->dibaca ? '✓✓' : '✓' }}
                                            </span>
                                            <button 
                                                wire:click="hapusPesan({{ $pesan->id }})"
                                                wire:confirm="Apakah Anda yakin ingin menghapus pesan ini?"
                                                class="text-xs opacity-75 hover:opacity-100 transition-opacity duration-200"
                                                title="Hapus pesan"
                                            >
                                                🗑️
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                </svg>
                                <p class="mt-2">Belum ada pesan</p>
                                <p class="text-sm">Mulai percakapan dengan mengirim pesan</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Form Kirim Pesan - VERSI SANGAT SEDERHANA -->
                    <div class="border-t border-gray-200 dark:border-gray-700 p-4">
                        <!-- Subjek Opsional -->
                        <div class="mb-3">
                            <input 
                                type="text" 
                                wire:model="subjek"
                                placeholder="Subjek (opsional)"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                            >
                        </div>

                        <div class="flex gap-3 items-end">
                            <!-- Textarea -->
                            <div class="flex-1">
<!-- Ganti textarea di view -->
<textarea 
    wire:model.live="pesanTeks"
    placeholder="Ketik pesan Anda..."
    rows="2"
    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"
    wire:keydown.enter.prevent="$dispatch('kirim-pesan')"
></textarea>                            </div>
                            
                            <!-- Tombol Kirim -->
                            <button 
                                type="button"
                                wire:click="kirimPesan"
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2 h-[42px]"
                                @if(empty($pesanTeks)) disabled @endif
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                </svg>
                                <span>Kirim</span>
                            </button>
                        </div>

                        <!-- Info -->
                        <div class="mt-2 flex justify-between items-center text-xs text-gray-500 dark:text-gray-400">
                            <span>
                                {{ strlen($pesanTeks) }}/1000 karakter
                            </span>
                            <span>
                                Tekan Enter untuk kirim
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Event listener untuk Enter key -->
                <script>
                    document.addEventListener('livewire:init', () => {
                        Livewire.on('kirim-pesan', () => {
                            @this.call('kirimPesan');
                        });

                        // Auto scroll
                        Livewire.hook('message.processed', (message) => {
                            setTimeout(() => {
                                const chatContainer = document.getElementById('chat-messages');
                                if (chatContainer) {
                                    chatContainer.scrollTop = chatContainer.scrollHeight;
                                }
                            }, 100);
                        });
                    });
                </script>
            @else
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-8 text-center">
                    <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">Pilih Guru</h3>
                    <p class="mt-2 text-gray-500 dark:text-gray-400">
                        Pilih guru dari daftar di sebelah kiri untuk memulai percakapan
                    </p>
                </div>
            @endif
        </div>
    </div>

    
</div>


