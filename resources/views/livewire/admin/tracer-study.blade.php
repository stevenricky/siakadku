<div class="p-4 sm:p-6">
    <!-- Header dan Tombol -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">Tracer Study</h1>
        <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
            <button class="w-full sm:w-auto bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition text-sm sm:text-base">
                <i class="fas fa-download mr-2"></i>Export
            </button>
            <button wire:click="create" class="w-full sm:w-auto bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition text-sm sm:text-base">
                <i class="fas fa-plus mr-2"></i>Survey Baru
            </button>
        </div>
    </div>

    <!-- Statistik -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-6 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm sm:text-lg font-semibold text-gray-900 dark:text-white">Survey Terkirim</h3>
                    <p class="text-xl sm:text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $surveyTerkirim }}</p>
                </div>
                <i class="fas fa-paper-plane text-blue-600 dark:text-blue-400 text-lg sm:text-2xl"></i>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm sm:text-lg font-semibold text-gray-900 dark:text-white">Telah Dijawab</h3>
                    <p class="text-xl sm:text-2xl font-bold text-green-600 dark:text-green-400">{{ $surveyDijawab }}</p>
                </div>
                <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-lg sm:text-2xl"></i>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm sm:text-lg font-semibold text-gray-900 dark:text-white">Response Rate</h3>
                    <p class="text-xl sm:text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $responseRate }}%</p>
                </div>
                <i class="fas fa-chart-pie text-purple-600 dark:text-purple-400 text-lg sm:text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Status Alumni -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 sm:p-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Status Alumni</h3>
            <div class="space-y-3">
                @php
                    $totalStatus = $bekerja + $kuliah + $wirausaha + $belumBekerja;
                    $bekerjaPercent = $totalStatus > 0 ? round(($bekerja / $totalStatus) * 100) : 0;
                    $kuliahPercent = $totalStatus > 0 ? round(($kuliah / $totalStatus) * 100) : 0;
                    $wirausahaPercent = $totalStatus > 0 ? round(($wirausaha / $totalStatus) * 100) : 0;
                    $belumBekerjaPercent = $totalStatus > 0 ? round(($belumBekerja / $totalStatus) * 100) : 0;
                @endphp
                
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Bekerja</span>
                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $bekerjaPercent }}%</span>
                </div>
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $bekerjaPercent }}%"></div>
                </div>

                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Kuliah</span>
                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $kuliahPercent }}%</span>
                </div>
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                    <div class="bg-green-600 h-2 rounded-full" style="width: {{ $kuliahPercent }}%"></div>
                </div>

                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Wirausaha</span>
                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $wirausahaPercent }}%</span>
                </div>
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                    <div class="bg-purple-600 h-2 rounded-full" style="width: {{ $wirausahaPercent }}%"></div>
                </div>

                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Belum Bekerja</span>
                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $belumBekerjaPercent }}%</span>
                </div>
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                    <div class="bg-orange-600 h-2 rounded-full" style="width: {{ $belumBekerjaPercent }}%"></div>
                </div>
            </div>
        </div>

        <!-- Relevansi Pendidikan -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 sm:p-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Relevansi Pendidikan</h3>
            <div class="space-y-4">
                @php
                    $relevansiData = [
                        'sangat_relevan' => ['count' => 65, 'color' => 'bg-green-600', 'label' => 'Sangat Relevan'],
                        'cukup_relevan' => ['count' => 25, 'color' => 'bg-yellow-600', 'label' => 'Cukup Relevan'],
                        'kurang_relevan' => ['count' => 8, 'color' => 'bg-orange-600', 'label' => 'Kurang Relevan'],
                        'tidak_relevan' => ['count' => 2, 'color' => 'bg-red-600', 'label' => 'Tidak Relevan']
                    ];
                @endphp
                
                @foreach($relevansiData as $key => $data)
                <div>
                    <div class="flex justify-between mb-1">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $data['label'] }}</span>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $data['count'] }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                        <div class="{{ $data['color'] }} h-2 rounded-full" style="width: {{ $data['count'] }}%"></div>
                    </div>
                </div>
                @endforeach
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
                        placeholder="Cari alumni, perusahaan, atau kampus..." 
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

    <!-- Tabel Data - Desktop -->
    <div class="hidden sm:block bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Hasil Survey Terbaru</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Alumni</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tahun Survey</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Instansi</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status Survey</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($tracerStudy as $item)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $item->alumni->siswa->nama }}
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                Lulus: {{ $item->alumni->tahun_lulus }}
                            </div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            {{ $item->tahun_survey }}
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            @php
                                $statusColors = [
                                    'bekerja' => 'bg-blue-100 text-blue-800',
                                    'wirausaha' => 'bg-purple-100 text-purple-800',
                                    'melanjutkan' => 'bg-green-100 text-green-800',
                                    'belum_bekerja' => 'bg-orange-100 text-orange-800'
                                ];
                            @endphp
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$item->status_pekerjaan] }}">
                                {{ $statusPekerjaanOptions[$item->status_pekerjaan] }}
                            </span>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            {{ $item->nama_perusahaan ?? $item->nama_kampus ?? '-' }}
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $item->status_survey === 'dijawab' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $statusSurveyOptions[$item->status_survey] }}
                            </span>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <button wire:click="showDetail({{ $item->id }})" class="text-blue-600 hover:text-blue-900" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button wire:click="edit({{ $item->id }})" class="text-green-600 hover:text-green-900" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                @if($item->status_survey === 'terkirim')
                                    <button wire:click="updateStatus({{ $item->id }}, 'dijawab')" class="text-orange-600 hover:text-orange-900" title="Tandai Dijawab">
                                        <i class="fas fa-check"></i>
                                    </button>
                                @else
                                    <button wire:click="updateStatus({{ $item->id }}, 'terkirim')" class="text-gray-600 hover:text-gray-900" title="Tandai Terkirim">
                                        <i class="fas fa-undo"></i>
                                    </button>
                                @endif
                                <button wire:click="delete({{ $item->id }})" onclick="return confirm('Apakah Anda yakin ingin menghapus survey ini?')" class="text-red-600 hover:text-red-900" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                            Tidak ada data survey tracer study
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($tracerStudy->hasPages())
        <div class="p-4 border-t border-gray-200 dark:border-gray-700">
            {{ $tracerStudy->links() }}
        </div>
        @endif
    </div>

    <!-- Tabel Data - Mobile -->
    <div class="sm:hidden space-y-4">
        @forelse($tracerStudy as $item)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="space-y-3">
                <!-- Header -->
                <div class="flex justify-between items-start">
                    <div>
                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $item->alumni->siswa->nama }}
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">
                            Survey: {{ $item->tahun_survey }} | Lulus: {{ $item->alumni->tahun_lulus }}
                        </div>
                    </div>
                    <div class="flex flex-col items-end space-y-1">
                        @php
                            $statusColors = [
                                'bekerja' => 'bg-blue-100 text-blue-800',
                                'wirausaha' => 'bg-purple-100 text-purple-800',
                                'melanjutkan' => 'bg-green-100 text-green-800',
                                'belum_bekerja' => 'bg-orange-100 text-orange-800'
                            ];
                        @endphp
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$item->status_pekerjaan] }}">
                            {{ $statusPekerjaanOptions[$item->status_pekerjaan] }}
                        </span>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $item->status_survey === 'dijawab' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $statusSurveyOptions[$item->status_survey] }}
                        </span>
                    </div>
                </div>

                <!-- Detail Info -->
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div class="col-span-2">
                        <div class="text-gray-500 dark:text-gray-400 text-xs">Instansi</div>
                        <div class="text-gray-900 dark:text-white">
                            {{ $item->nama_perusahaan ?? $item->nama_kampus ?? '-' }}
                        </div>
                    </div>
                    @if($item->bidang_pekerjaan)
                    <div>
                        <div class="text-gray-500 dark:text-gray-400 text-xs">Bidang</div>
                        <div class="text-gray-900 dark:text-white">
                            {{ $item->bidang_pekerjaan }}
                        </div>
                    </div>
                    @endif
                    @if($item->jabatan)
                    <div>
                        <div class="text-gray-500 dark:text-gray-400 text-xs">Jabatan</div>
                        <div class="text-gray-900 dark:text-white">
                            {{ $item->jabatan }}
                        </div>
                    </div>
                    @endif
                    @if($item->jurusan_kuliah)
                    <div class="col-span-2">
                        <div class="text-gray-500 dark:text-gray-400 text-xs">Jurusan</div>
                        <div class="text-gray-900 dark:text-white">
                            {{ $item->jurusan_kuliah }}
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Actions -->
                <div class="flex justify-between pt-2 border-t border-gray-200 dark:border-gray-700">
                    <button wire:click="showDetail({{ $item->id }})" class="text-blue-600 hover:text-blue-900 text-sm">
                        <i class="fas fa-eye mr-1"></i>Detail
                    </button>
                    <button wire:click="edit({{ $item->id }})" class="text-green-600 hover:text-green-900 text-sm">
                        <i class="fas fa-edit mr-1"></i>Edit
                    </button>
                    @if($item->status_survey === 'terkirim')
                        <button wire:click="updateStatus({{ $item->id }}, 'dijawab')" class="text-orange-600 hover:text-orange-900 text-sm">
                            <i class="fas fa-check mr-1"></i>Dijawab
                        </button>
                    @else
                        <button wire:click="updateStatus({{ $item->id }}, 'terkirim')" class="text-gray-600 hover:text-gray-900 text-sm">
                            <i class="fas fa-undo mr-1"></i>Terkirim
                        </button>
                    @endif
                    <button wire:click="delete({{ $item->id }})" onclick="return confirm('Apakah Anda yakin ingin menghapus survey ini?')" class="text-red-600 hover:text-red-900 text-sm">
                        <i class="fas fa-trash mr-1"></i>Hapus
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 text-center">
            <div class="text-gray-500 dark:text-gray-400">
                <i class="fas fa-chart-line text-3xl mb-3"></i>
                <p>Tidak ada data survey tracer study</p>
            </div>
        </div>
        @endforelse

        <!-- Pagination Mobile -->
        @if($tracerStudy->hasPages())
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            {{ $tracerStudy->links() }}
        </div>
        @endif
    </div>

    <!-- Modal Form -->
    @if($showModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="p-4 sm:p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ $modalTitle }}</h3>
                
                <form wire:submit="save">
                    <div class="grid grid-cols-1 gap-4">
                        <!-- Alumni -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Alumni *</label>
                            <select wire:model="alumni_id" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Pilih Alumni</option>
                                @foreach($alumni as $a)
                                <option value="{{ $a->id }}">{{ $a->siswa->nama }} - Lulus: {{ $a->tahun_lulus }}</option>
                                @endforeach
                            </select>
                            @error('alumni_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Tahun Survey dan Status -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tahun Survey *</label>
                                <input type="number" wire:model="tahun_survey" min="2000" max="{{ date('Y') + 1 }}" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('tahun_survey') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status Pekerjaan *</label>
                                <select wire:model="status_pekerjaan" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    @foreach($statusPekerjaanOptions as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('status_pekerjaan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Data Pekerjaan (hanya tampil jika status bekerja/wirausaha) -->
                        @if(in_array($status_pekerjaan, ['bekerja', 'wirausaha']))
                        <div class="grid grid-cols-1 gap-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white">Data Pekerjaan</h4>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Perusahaan/Usaha</label>
                                <input type="text" wire:model="nama_perusahaan" placeholder="Nama perusahaan atau usaha..." class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('nama_perusahaan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Bidang Pekerjaan</label>
                                    <input type="text" wire:model="bidang_pekerjaan" placeholder="Bidang pekerjaan..." class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    @error('bidang_pekerjaan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jabatan</label>
                                    <input type="text" wire:model="jabatan" placeholder="Jabatan..." class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    @error('jabatan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Gaji (Opsional)</label>
                                <input type="number" wire:model="gaji" placeholder="Gaji per bulan..." class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('gaji') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        @endif

                        <!-- Data Kuliah (hanya tampil jika status melanjutkan) -->
                        @if($status_pekerjaan === 'melanjutkan')
                        <div class="grid grid-cols-1 gap-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white">Data Pendidikan Lanjut</h4>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Kampus</label>
                                <input type="text" wire:model="nama_kampus" placeholder="Nama kampus/universitas..." class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('nama_kampus') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jurusan</label>
                                    <input type="text" wire:model="jurusan_kuliah" placeholder="Jurusan kuliah..." class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    @error('jurusan_kuliah') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tahun Masuk</label>
                                    <input type="number" wire:model="tahun_masuk_kuliah" min="2000" max="{{ date('Y') + 1 }}" placeholder="Tahun masuk kuliah..." class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    @error('tahun_masuk_kuliah') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Relevansi dan Saran -->
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Relevansi Pendidikan</label>
                                <textarea wire:model="relevansi_pendidikan" rows="3" placeholder="Bagaimana relevansi pendidikan di sekolah dengan pekerjaan/kuliah saat ini..." class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                                @error('relevansi_pendidikan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Saran untuk Sekolah</label>
                                <textarea wire:model="saran_untuk_sekolah" rows="3" placeholder="Saran dan masukan untuk pengembangan sekolah..." class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                                @error('saran_untuk_sekolah') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Status Survey -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status Survey *</label>
                            <select wire:model="status_survey" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @foreach($statusSurveyOptions as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('status_survey') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
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
    @if($showDetailModal && $selectedTracer)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="p-4 sm:p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Detail Survey Tracer Study</h3>
                    <button wire:click="$set('showDetailModal', false)" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="space-y-4">
                    <!-- Info Alumni -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Alumni</div>
                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $selectedTracer->alumni->siswa->nama }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                Lulus: {{ $selectedTracer->alumni->tahun_lulus }}
                            </div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Tahun Survey</div>
                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $selectedTracer->tahun_survey }}</div>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Status Pekerjaan</div>
                            @php
                                $statusColors = [
                                    'bekerja' => 'bg-blue-100 text-blue-800',
                                    'wirausaha' => 'bg-purple-100 text-purple-800',
                                    'melanjutkan' => 'bg-green-100 text-green-800',
                                    'belum_bekerja' => 'bg-orange-100 text-orange-800'
                                ];
                            @endphp
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$selectedTracer->status_pekerjaan] }}">
                                {{ $statusPekerjaanOptions[$selectedTracer->status_pekerjaan] }}
                            </span>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Status Survey</div>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $selectedTracer->status_survey === 'dijawab' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $statusSurveyOptions[$selectedTracer->status_survey] }}
                            </span>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Tanggal Update</div>
                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $selectedTracer->updated_at->translatedFormat('d M Y') }}</div>
                        </div>
                    </div>

                    <!-- Detail Berdasarkan Status -->
                    @if(in_array($selectedTracer->status_pekerjaan, ['bekerja', 'wirausaha']))
                    <div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mb-2">Data Pekerjaan</div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                            <div>
                                <div class="text-gray-500 dark:text-gray-400">Perusahaan/Usaha</div>
                                <div class="text-gray-900 dark:text-white">{{ $selectedTracer->nama_perusahaan ?? '-' }}</div>
                            </div>
                            <div>
                                <div class="text-gray-500 dark:text-gray-400">Bidang</div>
                                <div class="text-gray-900 dark:text-white">{{ $selectedTracer->bidang_pekerjaan ?? '-' }}</div>
                            </div>
                            <div>
                                <div class="text-gray-500 dark:text-gray-400">Jabatan</div>
                                <div class="text-gray-900 dark:text-white">{{ $selectedTracer->jabatan ?? '-' }}</div>
                            </div>
                            @if($selectedTracer->gaji)
                            <div>
                                <div class="text-gray-500 dark:text-gray-400">Gaji</div>
                                <div class="text-gray-900 dark:text-white">Rp {{ number_format($selectedTracer->gaji, 0, ',', '.') }}</div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    @if($selectedTracer->status_pekerjaan === 'melanjutkan')
                    <div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mb-2">Data Pendidikan Lanjut</div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                            <div>
                                <div class="text-gray-500 dark:text-gray-400">Kampus</div>
                                <div class="text-gray-900 dark:text-white">{{ $selectedTracer->nama_kampus ?? '-' }}</div>
                            </div>
                            <div>
                                <div class="text-gray-500 dark:text-gray-400">Jurusan</div>
                                <div class="text-gray-900 dark:text-white">{{ $selectedTracer->jurusan_kuliah ?? '-' }}</div>
                            </div>
                            <div>
                                <div class="text-gray-500 dark:text-gray-400">Tahun Masuk</div>
                                <div class="text-gray-900 dark:text-white">{{ $selectedTracer->tahun_masuk_kuliah ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Relevansi -->
                    @if($selectedTracer->relevansi_pendidikan)
                    <div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mb-2">Relevansi Pendidikan</div>
                        <div class="text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                            {{ $selectedTracer->relevansi_pendidikan }}
                        </div>
                    </div>
                    @endif

                    <!-- Saran -->
                    @if($selectedTracer->saran_untuk_sekolah)
                    <div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mb-2">Saran untuk Sekolah</div>
                        <div class="text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                            {{ $selectedTracer->saran_untuk_sekolah }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Flash Messages -->
    @if (session()->has('success'))
    <div class="fixed top-4 right-4 z-50 max-w-sm w-full sm:max-w-md">
        <div class="bg-green-500 text-white px-4 py-3 rounded-lg shadow-lg text-sm">
            {{ session('success') }}
        </div>
    </div>
    @endif

    @if (session()->has('error'))
    <div class="fixed top-4 right-4 z-50 max-w-sm w-full sm:max-w-md">
        <div class="bg-red-500 text-white px-4 py-3 rounded-lg shadow-lg text-sm">
            {{ session('error') }}
        </div>
    </div>
    @endif
</div>