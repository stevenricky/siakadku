<div>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Verifikasi Pembayaran SPP</h2>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Kelola verifikasi bukti pembayaran SPP dari siswa</p>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Perlu Verifikasi -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-yellow-100 dark:bg-yellow-900 text-yellow-600 dark:text-yellow-400">
                            <i class="fas fa-clock text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Perlu Verifikasi</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalPerluVerifikasi }}</p>
                        </div>
                    </div>
                </div>

                <!-- Menunggu -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400">
                            <i class="fas fa-hourglass-half text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Menunggu</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalMenunggu }}</p>
                        </div>
                    </div>
                </div>

                <!-- Diterima -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400">
                            <i class="fas fa-check-circle text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Diterima</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalDiterima }}</p>
                        </div>
                    </div>
                </div>

                <!-- Ditolak -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-red-100 dark:bg-red-900 text-red-600 dark:text-red-400">
                            <i class="fas fa-times-circle text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Ditolak</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalDitolak }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 mb-6">
                <div class="flex flex-col md:flex-row gap-4">
                    <!-- Search -->
                    <div class="flex-1">
                        <input 
                            type="text" 
                            wire:model.live="search"
                            placeholder="Cari berdasarkan nama siswa, NIS, atau kode referensi..."
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                        >
                    </div>

                    <!-- Filter Status -->
                    <div>
                        <select 
                            wire:model.live="statusFilter"
                            class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                        >
                            <option value="">Semua Status</option>
                            <option value="pending">Menunggu</option>
                            <option value="diterima">Diterima</option>
                            <option value="ditolak">Ditolak</option>
                        </select>
                    </div>

                    <!-- Items Per Page -->
                    <div>
                        <select 
                            wire:model.live="perPage"
                            class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                        >
                            <option value="10">10 per halaman</option>
                            <option value="25">25 per halaman</option>
                            <option value="50">50 per halaman</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Pembayaran List -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                @if($pembayaranList->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Siswa
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Periode
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Jumlah Bayar
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Metode
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Bukti
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($pembayaranList as $pembayaran)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $pembayaran->siswa->nama }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $pembayaran->siswa->nis }} - {{ $pembayaran->siswa->kelas->nama_kelas ?? '-' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 dark:text-white">
                                                {{ $pembayaran->tagihanSpp->bulan }} {{ $pembayaran->tagihanSpp->tahun }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                Rp {{ number_format($pembayaran->jumlah_bayar, 0, ',', '.') }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 dark:text-white">
                                                {{ $pembayaran->metode_bayar_text }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($pembayaran->has_bukti_upload)
                                                <button 
                                                    wire:click="openDetail({{ $pembayaran->id }})"
                                                    class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 text-sm"
                                                >
                                                    <i class="fas fa-eye mr-1"></i> Lihat Bukti
                                                </button>
                                            @else
                                                <span class="text-gray-400 text-sm">Tidak ada</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $pembayaran->status_badge }}">
                                                {{ $pembayaran->status_text }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex items-center space-x-2">
                                                <button 
                                                    wire:click="openDetail({{ $pembayaran->id }})"
                                                    class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                                                >
                                                    <i class="fas fa-search"></i>
                                                </button>
                                                
                                                @if($pembayaran->status_verifikasi === 'pending' && $pembayaran->has_bukti_upload)
                                                    <button 
                                                        wire:click="openDetail({{ $pembayaran->id }})"
                                                        class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300"
                                                        title="Verifikasi"
                                                    >
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                @endif
                                                
                                                @if($pembayaran->status_verifikasi === 'pending')
                                                    <button 
                                                        wire:click="hapusBukti({{ $pembayaran->id }})"
                                                        wire:confirm="Hapus bukti pembayaran ini?"
                                                        class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                                    >
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="bg-white dark:bg-gray-800 px-4 py-3 border-t border-gray-200 dark:border-gray-700 sm:px-6">
                        {{ $pembayaranList->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="text-gray-400 dark:text-gray-500">
                            <i class="fas fa-receipt text-6xl mb-4"></i>
                        </div>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Tidak ada data pembayaran</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            @if($search || $statusFilter)
                                Coba ubah pencarian atau filter Anda.
                            @else
                                Belum ada pembayaran yang perlu diverifikasi.
                            @endif
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal Detail Pembayaran -->
    @if($showDetailModal && $selectedPembayaran)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-4 border w-full max-w-2xl shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3">
                <!-- Header -->
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                        Detail Pembayaran
                    </h3>
                    <button wire:click="closeDetail" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <!-- Content -->
                <div class="space-y-4">
                    <!-- Info Siswa -->
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <h4 class="font-medium text-gray-900 dark:text-white mb-2">Informasi Siswa</h4>
                        <div class="grid grid-cols-2 gap-2 text-sm">
                            <div>
                                <span class="text-gray-600 dark:text-gray-400">Nama:</span>
                                <span class="font-medium">{{ $selectedPembayaran->siswa->nama }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600 dark:text-gray-400">NIS:</span>
                                <span class="font-medium">{{ $selectedPembayaran->siswa->nis }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600 dark:text-gray-400">Kelas:</span>
                                <span class="font-medium">{{ $selectedPembayaran->siswa->kelas->nama_kelas ?? '-' }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600 dark:text-gray-400">Periode:</span>
                                <span class="font-medium">{{ $selectedPembayaran->tagihanSpp->bulan }} {{ $selectedPembayaran->tagihanSpp->tahun }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Info Pembayaran -->
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <h4 class="font-medium text-gray-900 dark:text-white mb-2">Informasi Pembayaran</h4>
                        <div class="grid grid-cols-2 gap-2 text-sm">
                            <div>
                                <span class="text-gray-600 dark:text-gray-400">Jumlah Bayar:</span>
                                <span class="font-medium">Rp {{ number_format($selectedPembayaran->jumlah_bayar, 0, ',', '.') }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600 dark:text-gray-400">Metode:</span>
                                <span class="font-medium">{{ $selectedPembayaran->metode_bayar_text }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600 dark:text-gray-400">Tanggal Bayar:</span>
                                <span class="font-medium">{{ \Carbon\Carbon::parse($selectedPembayaran->tanggal_bayar)->format('d/m/Y') }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600 dark:text-gray-400">Status:</span>
                                <span class="font-medium {{ $selectedPembayaran->status_badge }} px-2 py-1 rounded-full text-xs">
                                    {{ $selectedPembayaran->status_text }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Bukti Pembayaran -->
                    @if($selectedPembayaran->has_bukti_upload)
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <h4 class="font-medium text-gray-900 dark:text-white mb-2">Bukti Pembayaran</h4>
                        <div class="flex justify-center">
                            <img 
                                src="{{ $selectedPembayaran->bukti_upload_url }}" 
                                alt="Bukti Pembayaran" 
                                class="max-w-full h-64 object-contain rounded-lg border"
                            >
                        </div>
                        <div class="mt-2 text-center">
                            <a 
                                href="{{ $selectedPembayaran->bukti_upload_url }}" 
                                target="_blank"
                                class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm"
                            >
                                <i class="fas fa-external-link-alt mr-1"></i> Buka di tab baru
                            </a>
                        </div>
                    </div>
                    @endif

                    <!-- Form Verifikasi -->
                    @if($selectedPembayaran->status_verifikasi === 'pending' && $selectedPembayaran->has_bukti_upload)
                    <div class="bg-yellow-50 dark:bg-yellow-900 rounded-lg p-4">
                        <h4 class="font-medium text-yellow-800 dark:text-yellow-200 mb-2">Verifikasi Pembayaran</h4>
                        <textarea 
                            wire:model="catatanVerifikasi"
                            placeholder="Berikan catatan verifikasi (opsional)..."
                            rows="3"
                            class="w-full px-3 py-2 border border-yellow-300 rounded-md focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 dark:bg-yellow-800 dark:text-yellow-200 dark:border-yellow-600"
                        ></textarea>
                        <div class="flex space-x-3 mt-3">
                            <button 
                                wire:click="verifikasiPembayaran('ditolak')"
                                class="flex-1 px-4 py-2 border border-red-300 text-red-700 rounded-lg hover:bg-red-50 dark:border-red-600 dark:text-red-400 dark:hover:bg-red-800 transition-colors"
                            >
                                <i class="fas fa-times mr-2"></i> Tolak
                            </button>
                            <button 
                                wire:click="verifikasiPembayaran('diterima')"
                                class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors"
                            >
                                <i class="fas fa-check mr-2"></i> Terima
                            </button>
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
        <div class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ session('error') }}
            </div>
        </div>
    @endif
</div>