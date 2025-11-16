<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Tugas & Kuis</h2>
            <p class="mt-2 text-gray-600 dark:text-gray-400">Lihat dan kerjakan tugas serta kuis dari guru</p>
        </div>

        <!-- Filters & Search -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 mb-6">
            <div class="flex flex-col md:flex-row gap-4">
                <!-- Search -->
                <div class="flex-1">
                    <input type="text" 
                           wire:model.live="search"
                           placeholder="Cari tugas atau mata pelajaran..."
                           class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                </div>
                
                <!-- Status Filter -->
                <div class="flex gap-2">
                    <select wire:model.live="filterStatus" 
                            class="border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                        <option value="semua">Semua Tugas</option>
                        <option value="belum">Belum Dikerjakan</option>
                        <option value="selesai">Sudah Dikumpulkan</option>
                        <option value="terlambat">Terlambat</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Tugas List -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
            @if($tugasList->count() > 0)
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($tugasList as $tugas)
                        @php
                            $status = $this->getStatusPengumpulan($tugas, auth()->user()->siswa->id);
                            $isExpired = now()->gt($tugas->deadline);
                        @endphp
                        
                        <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                                <!-- Left Content -->
                                <div class="flex-1">
                                    <div class="flex items-start gap-3">
                                        <!-- Icon based on type -->
                                        <div class="flex-shrink-0">
                                            <div class="w-10 h-10 rounded-full flex items-center justify-center 
                                                @if($tugas->tipe === 'tugas') bg-blue-100 text-blue-600 dark:bg-blue-900 dark:text-blue-400
                                                @elseif($tugas->tipe === 'kuis') bg-purple-100 text-purple-600 dark:bg-purple-900 dark:text-purple-400
                                                @else bg-red-100 text-red-600 dark:bg-red-900 dark:text-red-400 @endif">
                                                @if($tugas->tipe === 'tugas')
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
                                                @elseif($tugas->tipe === 'kuis')
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                @else
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                                    </svg>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Task Info -->
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2 mb-2">
                                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white truncate">
                                                    {{ $tugas->judul }}
                                                </h3>
                                                <!-- Status Badge -->
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    @if($status === 'dinilai') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                                    @elseif($status === 'dikumpulkan') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300
                                                    @elseif($status === 'terlambat') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                                                    @elseif($isExpired) bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300
                                                    @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300 @endif">
                                                    @if($status === 'dinilai') Dinilai
                                                    @elseif($status === 'dikumpulkan') Dikumpulkan
                                                    @elseif($status === 'terlambat') Terlambat
                                                    @elseif($isExpired) Expired
                                                    @else Belum Dikerjakan
                                                    @endif
                                                </span>
                                            </div>
                                            
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                                {{ $tugas->mapel->nama_mapel }} • {{ $tugas->guru->nama_lengkap }}
                                            </p>
                                            
                                            <p class="text-sm text-gray-500 dark:text-gray-500">
                                                Deadline: {{ $tugas->deadline->format('d M Y H:i') }} 
                                                ({{ $tugas->deadline->diffForHumans() }})
                                            </p>
                                            
                                            @if($tugas->deskripsi)
                                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                                                    {{ \Illuminate\Support\Str::limit($tugas->deskripsi, 100) }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Right Actions -->
                                <div class="flex flex-col sm:flex-row gap-2">
                                    @if($tugas->file)
                                        <button wire:click="downloadMateri({{ $tugas->id }})"
                                                class="px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-medium transition-colors">
                                            Download Materi
                                        </button>
                                    @endif
                                    
                                    @if(!$isExpired && $status === 'belum')
                                        <button wire:click="bukaModal({{ $tugas->id }})"
                                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition-colors">
                                            Kerjakan
                                        </button>
                                    @elseif(in_array($status, ['dikumpulkan', 'dinilai', 'terlambat']))
                                        <button wire:click="bukaModal({{ $tugas->id }})"
                                                class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium transition-colors">
                                            Lihat Detail
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $tugasList->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">Tidak ada tugas</h3>
                    <p class="mt-2 text-gray-500 dark:text-gray-400">
                        @if($search || $filterStatus !== 'semua')
                            Coba ubah pencarian atau filter Anda.
                        @else
                            Belum ada tugas yang diberikan untuk kelas Anda.
                        @endif
                    </p>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal untuk mengerjakan tugas -->
    @if($showModal && $selectedTugas)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                <!-- Header -->
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $selectedTugas->judul }}
                        </h3>
                        <button wire:click="tutupModal" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        {{ $selectedTugas->mapel->nama_mapel }} • {{ $selectedTugas->guru->nama_lengkap }}
                    </p>
                </div>

                <!-- Content -->
                <div class="p-6 space-y-4">
                    @if($selectedTugas->deskripsi)
                        <div>
                            <h4 class="font-medium text-gray-900 dark:text-white mb-2">Deskripsi</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $selectedTugas->deskripsi }}</p>
                        </div>
                    @endif

                    @if($selectedTugas->instruksi)
                        <div>
                            <h4 class="font-medium text-gray-900 dark:text-white mb-2">Instruksi</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $selectedTugas->instruksi }}</p>
                        </div>
                    @endif

                    <!-- Form Jawaban -->
                    <div>
                        <h4 class="font-medium text-gray-900 dark:text-white mb-2">Jawaban Anda</h4>
                        <textarea wire:model="jawaban" 
                                  rows="6"
                                  placeholder="Ketik jawaban Anda di sini..."
                                  class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white resize-none"></textarea>
                    </div>

                    <!-- File Upload -->
                    <div>
                        <h4 class="font-medium text-gray-900 dark:text-white mb-2">Atau Upload File</h4>
                        <input type="file" wire:model="fileJawaban" 
                               class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                        @error('fileJawaban') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Deadline Info -->
                    <div class="bg-yellow-50 dark:bg-yellow-900 border border-yellow-200 dark:border-yellow-700 rounded-lg p-4">
                        <p class="text-sm text-yellow-800 dark:text-yellow-200">
                            <strong>Deadline:</strong> {{ $selectedTugas->deadline->format('d M Y H:i') }}
                            ({{ $selectedTugas->deadline->diffForHumans() }})
                        </p>
                    </div>
                </div>

                <!-- Footer -->
                <div class="p-6 border-t border-gray-200 dark:border-gray-700 flex justify-end gap-3">
                    <button wire:click="tutupModal" 
                            class="px-4 py-2 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        Batal
                    </button>
                    <button wire:click="kumpulkanTugas" 
                            wire:loading.attr="disabled"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors flex items-center gap-2 disabled:opacity-50">
                        <span wire:loading.remove>Kumpulkan</span>
                        <span wire:loading>Mengirim...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>