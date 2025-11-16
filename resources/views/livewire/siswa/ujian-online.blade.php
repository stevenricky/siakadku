<div>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Ujian Online</h2>
            <p class="mt-2 text-gray-600 dark:text-gray-400">Ikuti ujian online yang diselenggarakan guru</p>
            
            <!-- Filter dan Pencarian -->
            <div class="mt-6 bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <div class="flex flex-col md:flex-row gap-4 mb-6">
                    <!-- Search -->
                    <div class="flex-1">
                        <input 
                            type="text" 
                            wire:model.live="search"
                            placeholder="Cari ujian atau mata pelajaran..."
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                        >
                    </div>
                    
                    <!-- Filter Status -->
                    <div>
                        <select 
                            wire:model.live="filterStatus"
                            class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                        >
                            <option value="semua">Semua Status</option>
                            <option value="akan_datang">Akan Datang</option>
                            <option value="berlangsung">Berlangsung</option>
                            <option value="selesai">Selesai</option>
                        </select>
                    </div>
                </div>

                <!-- Daftar Ujian -->
                @if($ujianList->count() > 0)
                    <div class="space-y-4">
                        @foreach($ujianList as $ujian)
                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-6 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <div class="flex flex-col md:flex-row md:items-center justify-between">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                            {{ $ujian->judul }}
                                        </h3>
                                        <p class="text-gray-600 dark:text-gray-400 mt-1">
                                            Mata Pelajaran: {{ $ujian->mapel->nama_mapel }}
                                        </p>
                                        <p class="text-gray-600 dark:text-gray-400">
                                            Guru: {{ $ujian->guru->nama ?? 'N/A' }}
                                        </p>
                                        <div class="flex flex-wrap gap-4 mt-2 text-sm text-gray-500 dark:text-gray-400">
                                            <span>Mulai: {{ $ujian->waktu_mulai->format('d M Y H:i') }}</span>
                                            <span>Selesai: {{ $ujian->waktu_selesai->format('d M Y H:i') }}</span>
                                            <span>Durasi: {{ $ujian->durasi }} menit</span>
                                            <span>Soal: {{ $ujian->jumlah_soal }}</span>
                                        </div>
                                        @if($ujian->deskripsi)
                                            <p class="mt-2 text-gray-600 dark:text-gray-400 text-sm">
                                                {{ Str::limit($ujian->deskripsi, 100) }}
                                            </p>
                                        @endif
                                    </div>
                                    
                                    <div class="mt-4 md:mt-0 flex flex-col items-end gap-2">
                                        @php
                                            $now = now();
                                            if ($now < $ujian->waktu_mulai) {
                                                $status = 'akan_datang';
                                                $statusText = 'Akan Datang';
                                                $statusClass = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200';
                                            } elseif ($now >= $ujian->waktu_mulai && $now <= $ujian->waktu_selesai) {
                                                $status = 'berlangsung';
                                                $statusText = 'Berlangsung';
                                                $statusClass = 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200';
                                            } else {
                                                $status = 'selesai';
                                                $statusText = 'Selesai';
                                                $statusClass = 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200';
                                            }
                                        @endphp
                                        
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusClass }}">
                                            {{ $statusText }}
                                        </span>
                                        
                                        @if($status === 'berlangsung')
                                            <button 
                                                wire:click="mulaiUjian({{ $ujian->id }})"
                                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                            >
                                                Mulai Ujian
                                            </button>
                                        @elseif($status === 'selesai')
                                            <button 
                                                wire:click="lihatHasil({{ $ujian->id }})"
                                                class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                            >
                                                Lihat Hasil
                                            </button>
                                        @else
                                            <button 
                                                disabled
                                                class="inline-flex items-center px-4 py-2 bg-gray-400 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest cursor-not-allowed"
                                            >
                                                Belum Dimulai
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $ujianList->links() }}
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="text-gray-400 dark:text-gray-500">
                            <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Tidak ada ujian</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            @if($search || $filterStatus !== 'semua')
                                Coba ubah pencarian atau filter Anda.
                            @else
                                Belum ada ujian yang dijadwalkan untuk kelas Anda.
                            @endif
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>