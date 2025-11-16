<div>
    <div class="py-4 md:py-6">
        <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-8">
            <!-- Header -->
            <div class="mb-4 md:mb-6">
                <h2 class="text-xl md:text-2xl font-bold text-gray-900 dark:text-white">Rencana Belajar</h2>
                <p class="mt-1 md:mt-2 text-sm md:text-base text-gray-600 dark:text-gray-400">Buat dan kelola rencana belajar pribadi</p>
            </div>
            
            <div class="mt-4 md:mt-6 grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6">
                <!-- Form Tambah Rencana & Waktu Kosong -->
                <div class="lg:col-span-1 space-y-4 md:space-y-6">
                    <!-- Form Tambah Rencana -->
                    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4 md:p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 md:mb-4">
                            Tambah Rencana Belajar
                        </h3>
                        
                        <form wire:submit="tambahRencana">
                            <div class="space-y-3 md:space-y-4">
                                <!-- Mata Pelajaran -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Mata Pelajaran
                                    </label>
                                    <select 
                                        wire:model="mapelId"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white text-sm md:text-base"
                                        required
                                    >
                                        <option value="">Pilih Mata Pelajaran</option>
                                        @foreach($mapels as $mapel)
                                            <option value="{{ $mapel->id }}">{{ $mapel->nama_mapel }}</option>
                                        @endforeach
                                    </select>
                                    @error('mapelId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <!-- Topik -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Topik Belajar
                                    </label>
                                    <input 
                                        type="text" 
                                        wire:model="topik"
                                        placeholder="Contoh: Trigonometri Dasar"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white text-sm md:text-base"
                                        required
                                    >
                                    @error('topik') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <!-- Target Waktu -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Target Waktu
                                    </label>
                                    <input 
                                        type="datetime-local" 
                                        wire:model="targetWaktu"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white text-sm md:text-base"
                                        required
                                    >
                                    @error('targetWaktu') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <!-- Keterangan -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Keterangan (Opsional)
                                    </label>
                                    <textarea 
                                        wire:model="keterangan"
                                        rows="3"
                                        placeholder="Deskripsi detail yang akan dipelajari..."
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white text-sm md:text-base"
                                    ></textarea>
                                </div>

                                <!-- Submit Button -->
                                <button 
                                    type="submit"
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-200 text-sm md:text-base"
                                >
                                    Tambah Rencana
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Waktu Kosong -->
                    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4 md:p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 md:mb-4">
                            Waktu Kosong Rekomendasi
                        </h3>
                        <div class="space-y-3">
                            @foreach($jadwalKosong as $hari => $waktuKosong)
                                @if(count($waktuKosong) > 0)
                                    <div>
                                        <h4 class="font-medium text-gray-900 dark:text-white capitalize text-sm md:text-base">{{ $hari }}</h4>
                                        <div class="mt-1 flex flex-wrap gap-1">
                                            @foreach($waktuKosong as $waktu)
                                                <span class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded">
                                                    {{ $waktu }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Daftar Rencana Belajar -->
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4 md:p-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 md:mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2 sm:mb-0">
                                Daftar Rencana Belajar
                            </h3>
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                Total: {{ count($rencanaBelajar) }} rencana
                            </div>
                        </div>

                        @if(count($rencanaBelajar) > 0)
                            <div class="space-y-3 md:space-y-4">
                                @foreach($rencanaBelajar as $index => $rencana)
                                    @php
                                        $mapel = $mapels->firstWhere('id', $rencana['mapel_id']);
                                        $statusColors = [
                                            'belum' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                            'progress' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300', 
                                            'selesai' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300'
                                        ];
                                        $isOverdue = \Carbon\Carbon::parse($rencana['target_waktu'])->isPast() && $rencana['status'] !== 'selesai';
                                    @endphp
                                    
                                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-3 md:p-4 {{ $isOverdue ? 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800' : '' }}">
                                        <div class="flex flex-col sm:flex-row sm:items-start gap-3">
                                            <div class="flex-1 min-w-0">
                                                <!-- Header Info -->
                                                <div class="flex flex-wrap items-center gap-2 mb-2">
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$rencana['status']] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                                                        {{ ucfirst($rencana['status']) }}
                                                    </span>
                                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                                        {{ \Carbon\Carbon::parse($rencana['target_waktu'])->format('d M Y H:i') }}
                                                    </span>
                                                    @if($isOverdue)
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">
                                                            Terlambat
                                                        </span>
                                                    @endif
                                                </div>
                                                
                                                <!-- Content -->
                                                <div class="space-y-1 md:space-y-2">
                                                    <h4 class="font-medium text-gray-900 dark:text-white text-sm md:text-base line-clamp-2">
                                                        {{ $rencana['topik'] }}
                                                    </h4>
                                                    
                                                    @if($mapel)
                                                        <p class="text-xs md:text-sm text-gray-600 dark:text-gray-400">
                                                            <span class="font-medium">Mapel:</span> {{ $mapel->nama_mapel }}
                                                        </p>
                                                    @endif
                                                    
                                                    @if($rencana['keterangan'])
                                                        <p class="text-xs md:text-sm text-gray-600 dark:text-gray-400 line-clamp-2">
                                                            {{ $rencana['keterangan'] }}
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Actions -->
                                            <div class="flex items-center gap-2 sm:flex-col sm:items-end sm:gap-1">
                                                <!-- Status Dropdown -->
                                                <select 
                                                    wire:change="updateStatus({{ $index }}, $event.target.value)"
                                                    class="text-xs md:text-sm border border-gray-300 dark:border-gray-600 rounded px-2 py-1 dark:bg-gray-700 dark:text-white w-full sm:w-auto"
                                                >
                                                    <option value="belum" {{ $rencana['status'] == 'belum' ? 'selected' : '' }}>Belum</option>
                                                    <option value="progress" {{ $rencana['status'] == 'progress' ? 'selected' : '' }}>Progress</option>
                                                    <option value="selesai" {{ $rencana['status'] == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                                </select>

                                                <!-- Delete Button -->
                                                <button 
                                                    wire:click="hapusRencana({{ $index }})"
                                                    wire:confirm="Hapus rencana belajar ini?"
                                                    class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 p-1 flex-shrink-0"
                                                    title="Hapus rencana"
                                                >
                                                    <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <!-- Empty State -->
                            <div class="text-center py-8 md:py-12">
                                <div class="text-gray-400 dark:text-gray-500">
                                    <svg class="mx-auto h-10 w-10 md:h-12 md:w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Belum ada rencana belajar</h3>
                                <p class="mt-1 text-xs md:text-sm text-gray-500 dark:text-gray-400 max-w-sm mx-auto">
                                    Mulai buat rencana belajar pertama Anda dengan mengisi form di samping.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 md:px-6 md:py-3 rounded-lg shadow-lg text-sm md:text-base max-w-xs md:max-w-sm">
            {{ session('success') }}
        </div>
    @endif
</div>