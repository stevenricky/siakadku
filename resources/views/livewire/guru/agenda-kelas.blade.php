<div class="p-3 sm:p-4 md:p-6">
    <!-- Header -->
    <div class="flex flex-col gap-4 mb-6">
        <div>
            <h1 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900 dark:text-white">Agenda Kelas</h1>
            <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mt-1">
                Jadwal kegiatan dan agenda sekolah
                @if($guru->kelasWali)
                    <span class="text-blue-600 dark:text-blue-400 font-medium block sm:inline mt-1 sm:mt-0">
                        (Kelas: {{ $guru->kelasWali->nama_kelas }})
                    </span>
                @else
                    <span class="text-green-600 dark:text-green-400 font-medium block sm:inline mt-1 sm:mt-0">
                        (Semua Kelas)
                    </span>
                @endif
            </p>
        </div>
        <div class="flex flex-wrap gap-2">
            <button 
                wire:click="toggleViewMode"
                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition flex items-center justify-center flex-1 sm:flex-none"
            >
                <i class="fas fa-{{ $viewMode === 'list' ? 'calendar' : 'list' }} mr-2 text-sm"></i>
                <span class="text-sm">{{ $viewMode === 'list' ? 'Kalender' : 'Daftar' }}</span>
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-3 gap-2 sm:gap-3 md:gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3">
            <div class="flex flex-col items-center text-center">
                <div class="p-2 bg-blue-100 rounded-lg mb-2">
                    <i class="fas fa-clock text-blue-600 text-base sm:text-lg"></i>
                </div>
                <p class="text-xs font-medium text-gray-600 dark:text-gray-400">Mendatang</p>
                <p class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white">{{ $totalMendatang }}</p>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3">
            <div class="flex flex-col items-center text-center">
                <div class="p-2 bg-green-100 rounded-lg mb-2">
                    <i class="fas fa-play-circle text-green-600 text-base sm:text-lg"></i>
                </div>
                <p class="text-xs font-medium text-gray-600 dark:text-gray-400">Berlangsung</p>
                <p class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white">{{ $totalBerlangsung }}</p>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3">
            <div class="flex flex-col items-center text-center">
                <div class="p-2 bg-gray-100 rounded-lg mb-2">
                    <i class="fas fa-check-circle text-gray-600 text-base sm:text-lg"></i>
                </div>
                <p class="text-xs font-medium text-gray-600 dark:text-gray-400">Selesai</p>
                <p class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white">{{ $totalSelesai }}</p>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
        <div class="p-3 sm:p-4 border-b border-gray-200 dark:border-gray-700">
            <div class="space-y-3">
                <input 
                    type="text" 
                    wire:model.live="search"
                    placeholder="Cari judul/deskripsi/tempat..." 
                    class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                >
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                    <select 
                        wire:model.live="jenisFilter"
                        class="px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                    >
                        <option value="">Semua Jenis</option>
                        <option value="akademik">Akademik</option>
                        <option value="non_akademik">Non-Akademik</option>
                        <option value="sosial">Sosial</option>
                        <option value="lainnya">Lainnya</option>
                    </select>
                    <select 
                        wire:model.live="statusFilter"
                        class="px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                    >
                        <option value="">Semua Status</option>
                        <option value="terjadwal">Terjadwal</option>
                        <option value="berlangsung">Berlangsung</option>
                        <option value="selesai">Selesai</option>
                        <option value="dibatalkan">Dibatalkan</option>
                    </select>
                    <button 
                        wire:click="resetFilters"
                        class="bg-gray-500 text-white px-3 py-2 text-sm rounded-lg hover:bg-gray-600 transition col-span-2 sm:col-span-1"
                    >
                        Reset Filter
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Calendar View -->
    @if($viewMode === 'calendar')
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
        <div class="p-3 sm:p-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
                <div class="flex items-center space-x-2 sm:space-x-4">
                    <button wire:click="previousMonth" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                        <i class="fas fa-chevron-left text-gray-600 dark:text-gray-400"></i>
                    </button>
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white">
                        {{ \Carbon\Carbon::create($currentYear, $currentMonth, 1)->translatedFormat('F Y') }}
                    </h2>
                    <button wire:click="nextMonth" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                        <i class="fas fa-chevron-right text-gray-600 dark:text-gray-400"></i>
                    </button>
                </div>
                <button wire:click="goToToday" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm w-full sm:w-auto">
                    Hari Ini
                </button>
            </div>
        </div>
        <div class="p-2 sm:p-4 overflow-x-auto">
            <!-- Simple Calendar Grid -->
            <div class="min-w-[320px]">
                <div class="grid grid-cols-7 gap-1 mb-2">
                    @foreach(['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'] as $day)
                        <div class="text-center text-xs font-medium text-gray-500 dark:text-gray-400 py-2">
                            {{ $day }}
                        </div>
                    @endforeach
                </div>
                <div class="grid grid-cols-7 gap-1">
                    @php
                        $firstDay = \Carbon\Carbon::create($currentYear, $currentMonth, 1);
                        $daysInMonth = $firstDay->daysInMonth;
                        $startDay = $firstDay->dayOfWeek;
                        
                        // Fill empty days
                        for($i = 0; $i < $startDay; $i++) {
                            echo '<div class="h-16 sm:h-20 bg-gray-50 dark:bg-gray-700 rounded"></div>';
                        }
                        
                        // Fill days
                        for($day = 1; $day <= $daysInMonth; $day++) {
                            $currentDate = \Carbon\Carbon::create($currentYear, $currentMonth, $day);
                            $isToday = $currentDate->isToday();
                            $events = collect($calendarEvents)->filter(function($event) use ($currentDate) {
                                return $currentDate->between($event['start'], $event['end']);
                            });
                    @endphp
                    <div class="h-16 sm:h-20 border border-gray-200 dark:border-gray-600 rounded p-1 {{ $isToday ? 'bg-blue-50 dark:bg-blue-900/20' : 'bg-white dark:bg-gray-800' }}">
                        <div class="flex justify-between items-start">
                            <span class="text-xs sm:text-sm font-medium {{ $isToday ? 'text-blue-600 dark:text-blue-400' : 'text-gray-900 dark:text-white' }}">
                                {{ $day }}
                            </span>
                            @if($events->count() > 0)
                                <span class="text-xs bg-blue-100 text-blue-800 rounded-full px-1">
                                    {{ $events->count() }}
                                </span>
                            @endif
                        </div>
                        <div class="mt-1 space-y-1 max-h-8 sm:max-h-12 overflow-y-auto">
                            @foreach($events->take(1) as $event)
                                <div class="text-xs p-1 rounded truncate" style="background-color: {{ $event['color'] }}; color: white;">
                                    {{ $event['title'] }}
                                </div>
                            @endforeach
                            @if($events->count() > 1)
                                <div class="text-xs text-gray-500 text-center">
                                    +{{ $events->count() - 1 }} lagi
                                </div>
                            @endif
                        </div>
                    </div>
                    @php } @endphp
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- List View -->
    @if($viewMode === 'list')
    <!-- Mobile Card View -->
    <div class="sm:hidden space-y-3 mb-6">
        @forelse($agenda as $item)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <div class="flex items-start justify-between mb-2">
                    <div class="flex items-center">
                        <div class="h-8 w-8 flex-shrink-0 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-calendar text-blue-600 text-xs"></i>
                        </div>
                        <div class="ml-3">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $item->judul_agenda }}
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $item->jenis_agenda_formatted }} • {{ $item->sasaran_formatted }}
                            </div>
                        </div>
                    </div>
                    @php
                        $statusBadge = match($item->status) {
                            'terjadwal' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                            'berlangsung' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                            'selesai' => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200',
                            'dibatalkan' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                            default => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200'
                        };
                        $statusText = match($item->status) {
                            'terjadwal' => 'Terjadwal',
                            'berlangsung' => 'Berlangsung',
                            'selesai' => 'Selesai',
                            'dibatalkan' => 'Dibatalkan',
                            default => $item->status
                        };
                    @endphp
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusBadge }}">
                        {{ $statusText }}
                    </span>
                </div>
                
                <div class="space-y-2 text-sm">
                    <div class="flex items-center text-gray-600 dark:text-gray-400">
                        <i class="fas fa-clock mr-2 text-xs"></i>
                        <span>{{ \Carbon\Carbon::parse($item->tanggal_mulai)->translatedFormat('d M Y') }}</span>
                        @if($item->waktu_mulai)
                            <span class="ml-1">{{ $item->waktu_mulai }} - {{ $item->waktu_selesai }}</span>
                        @else
                            <span class="ml-1">Sepanjang Hari</span>
                        @endif
                    </div>
                    <div class="flex items-center text-gray-600 dark:text-gray-400">
                        <i class="fas fa-map-marker-alt mr-2 text-xs"></i>
                        <span>{{ $item->tempat }}</span>
                    </div>
                    <div class="flex items-center text-gray-600 dark:text-gray-400">
                        <i class="fas fa-user mr-2 text-xs"></i>
                        <span>{{ $item->penanggung_jawab }}</span>
                    </div>
                </div>
                
                <div class="mt-3 flex justify-end">
                    <button 
                        wire:click="showDetail({{ $item->id }})"
                        class="text-blue-600 hover:text-blue-900 text-xs px-3 py-1 bg-blue-50 rounded"
                        title="Detail"
                    >
                        <i class="fas fa-eye mr-1"></i> Lihat Detail
                    </button>
                </div>
            </div>
        @empty
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 text-center">
                <div class="text-gray-500 dark:text-gray-400">
                    <i class="fas fa-calendar text-4xl mb-4"></i>
                    <p class="text-lg font-medium">Tidak ada agenda</p>
                    <p class="text-sm mt-2">Tidak ada agenda yang sesuai dengan filter</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Desktop Table View -->
    <div class="hidden sm:block bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-3 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Agenda</th>
                        <th class="px-3 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Waktu</th>
                        <th class="px-3 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tempat</th>
                        <th class="px-3 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-3 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($agenda as $item)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-3 py-2 sm:px-6 sm:py-4">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 flex-shrink-0 bg-blue-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-calendar text-blue-600 text-xs"></i>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $item->judul_agenda }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $item->jenis_agenda_formatted }} • {{ $item->sasaran_formatted }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-3 py-2 sm:px-6 sm:py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">
                                    {{ \Carbon\Carbon::parse($item->tanggal_mulai)->translatedFormat('d M Y') }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    @if($item->waktu_mulai)
                                        {{ $item->waktu_mulai }} - {{ $item->waktu_selesai }}
                                    @else
                                        Sepanjang Hari
                                    @endif
                                </div>
                            </td>
                            <td class="px-3 py-2 sm:px-6 sm:py-4">
                                <div class="text-sm text-gray-900 dark:text-white">
                                    {{ $item->tempat }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $item->penanggung_jawab }}
                                </div>
                            </td>
                            <td class="px-3 py-2 sm:px-6 sm:py-4 whitespace-nowrap">
                                @php
                                    $statusBadge = match($item->status) {
                                        'terjadwal' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                        'berlangsung' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                        'selesai' => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200',
                                        'dibatalkan' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                        default => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200'
                                    };
                                    $statusText = match($item->status) {
                                        'terjadwal' => 'Terjadwal',
                                        'berlangsung' => 'Berlangsung',
                                        'selesai' => 'Selesai',
                                        'dibatalkan' => 'Dibatalkan',
                                        default => $item->status
                                    };
                                @endphp
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusBadge }}">
                                    {{ $statusText }}
                                </span>
                            </td>
                            <td class="px-3 py-2 sm:px-6 sm:py-4 whitespace-nowrap">
                                <button 
                                    wire:click="showDetail({{ $item->id }})"
                                    class="text-blue-600 hover:text-blue-900 text-xs px-2 py-1 bg-blue-50 rounded"
                                    title="Detail"
                                >
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center">
                                <div class="text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-calendar text-4xl mb-4"></i>
                                    <p class="text-lg font-medium">Tidak ada agenda</p>
                                    <p class="text-sm mt-2">Tidak ada agenda yang sesuai dengan filter</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-3 sm:px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $agenda->links() }}
        </div>
    </div>
    @endif

    <!-- Modal Detail Agenda -->
    @if($showDetailModal && $selectedAgenda)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-4 sm:p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Detail Agenda</h3>
                    <button wire:click="closeDetail" class="text-gray-500 hover:text-gray-700 p-1">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Judul Agenda</p>
                        <p class="text-gray-900 dark:text-white text-lg font-semibold">{{ $selectedAgenda->judul_agenda }}</p>
                    </div>

                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Deskripsi</p>
                        <p class="text-gray-900 dark:text-white">{{ $selectedAgenda->deskripsi }}</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Mulai</p>
                            <p class="text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($selectedAgenda->tanggal_mulai)->translatedFormat('d F Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Selesai</p>
                            <p class="text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($selectedAgenda->tanggal_selesai)->translatedFormat('d F Y') }}</p>
                        </div>
                    </div>

                    @if($selectedAgenda->waktu_mulai)
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Waktu Mulai</p>
                            <p class="text-gray-900 dark:text-white">{{ $selectedAgenda->waktu_mulai }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Waktu Selesai</p>
                            <p class="text-gray-900 dark:text-white">{{ $selectedAgenda->waktu_selesai }}</p>
                        </div>
                    </div>
                    @endif

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Tempat</p>
                            <p class="text-gray-900 dark:text-white">{{ $selectedAgenda->tempat }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Penanggung Jawab</p>
                            <p class="text-gray-900 dark:text-white">{{ $selectedAgenda->penanggung_jawab }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Jenis Agenda</p>
                            <p class="text-gray-900 dark:text-white">{{ $selectedAgenda->jenis_agenda_formatted }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Sasaran</p>
                            <p class="text-gray-900 dark:text-white">{{ $selectedAgenda->sasaran_formatted }}</p>
                        </div>
                    </div>

                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</p>
                        @php
                            $statusBadge = match($selectedAgenda->status) {
                                'terjadwal' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                'berlangsung' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                'selesai' => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200',
                                'dibatalkan' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                default => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200'
                            };
                            $statusText = match($selectedAgenda->status) {
                                'terjadwal' => 'Terjadwal',
                                'berlangsung' => 'Berlangsung',
                                'selesai' => 'Selesai',
                                'dibatalkan' => 'Dibatalkan',
                                default => $selectedAgenda->status
                            };
                        @endphp
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusBadge }}">
                            {{ $statusText }}
                        </span>
                    </div>

                    @if($selectedAgenda->dokumentasi)
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Dokumentasi</p>
                        <p class="text-gray-900 dark:text-white">{{ $selectedAgenda->dokumentasi }}</p>
                    </div>
                    @endif
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button 
                        type="button" 
                        wire:click="closeDetail" 
                        class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200"
                    >
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Success/Error Messages -->
    @if (session()->has('success'))
    <div class="fixed top-4 right-4 z-50 animate-fade-in max-w-[90%] sm:max-w-none">
        <div class="bg-green-500 text-white px-4 py-3 rounded-lg shadow-lg flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            <span class="text-sm">{{ session('success') }}</span>
        </div>
    </div>
    @endif

    @if (session()->has('error'))
    <div class="fixed top-4 right-4 z-50 animate-fade-in max-w-[90%] sm:max-w-none">
        <div class="bg-red-500 text-white px-4 py-3 rounded-lg shadow-lg flex items-center">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            <span class="text-sm">{{ session('error') }}</span>
        </div>
    </div>
    @endif

    <!-- Loading Indicator -->
    <div wire:loading class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-4 sm:p-6 flex items-center max-w-[90%]">
            <i class="fas fa-spinner fa-spin text-blue-600 mr-3"></i>
            <span class="text-gray-700 dark:text-gray-300 text-sm">Memproses...</span>
        </div>
    </div>
</div>