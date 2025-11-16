<div>
    <div class="py-4 sm:py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6 sm:mb-8">
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">Riwayat Pembayaran</h2>
                <p class="mt-1 sm:mt-2 text-sm sm:text-base text-gray-600 dark:text-gray-400">Lihat riwayat pembayaran SPP Anda</p>
            </div>

            <!-- Statistics Cards - Improved Responsive Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-3 sm:gap-4 md:gap-6 mb-6 sm:mb-8">
                <!-- Total Pembayaran -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 sm:p-4 md:p-6">
                    <div class="flex items-center">
                        <div class="p-2 sm:p-3 rounded-full bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400">
                            <i class="fas fa-receipt text-lg sm:text-xl"></i>
                        </div>
                        <div class="ml-3 sm:ml-4">
                            <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400">Total Pembayaran</p>
                            <p class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900 dark:text-white">{{ $totalPembayaran }}</p>
                        </div>
                    </div>
                </div>

                <!-- Diterima -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 sm:p-4 md:p-6">
                    <div class="flex items-center">
                        <div class="p-2 sm:p-3 rounded-full bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400">
                            <i class="fas fa-check-circle text-lg sm:text-xl"></i>
                        </div>
                        <div class="ml-3 sm:ml-4">
                            <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400">Diterima</p>
                            <p class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900 dark:text-white">{{ $totalDiterima }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                Rp {{ number_format($totalNominalDiterima, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Menunggu -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 sm:p-4 md:p-6">
                    <div class="flex items-center">
                        <div class="p-2 sm:p-3 rounded-full bg-yellow-100 dark:bg-yellow-900 text-yellow-600 dark:text-yellow-400">
                            <i class="fas fa-clock text-lg sm:text-xl"></i>
                        </div>
                        <div class="ml-3 sm:ml-4">
                            <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400">Menunggu</p>
                            <p class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900 dark:text-white">{{ $totalPending }}</p>
                        </div>
                    </div>
                </div>

                <!-- Ditolak -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 sm:p-4 md:p-6">
                    <div class="flex items-center">
                        <div class="p-2 sm:p-3 rounded-full bg-red-100 dark:bg-red-900 text-red-600 dark:text-red-400">
                            <i class="fas fa-times-circle text-lg sm:text-xl"></i>
                        </div>
                        <div class="ml-3 sm:ml-4">
                            <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400">Ditolak</p>
                            <p class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900 dark:text-white">{{ $totalDitolak }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Section - Improved Responsive Layout -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-3 sm:p-4 md:p-6 mb-4 sm:mb-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                    <!-- Search -->
                    <div class="sm:col-span-2 lg:col-span-1">
                        <div class="relative">
                            <input 
                                type="text" 
                                wire:model.live="search"
                                placeholder="Cari berdasarkan bulan, catatan, atau kode referensi..."
                                class="w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white text-sm"
                            >
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Filter Status -->
                    <div>
                        <select 
                            wire:model.live="filterStatus"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white text-sm"
                        >
                            <option value="semua">Semua Status</option>
                            <option value="pending">Menunggu</option>
                            <option value="diterima">Diterima</option>
                            <option value="ditolak">Ditolak</option>
                        </select>
                    </div>

                    <!-- Filter Bulan -->
                    <div>
                        <select 
                            wire:model.live="filterBulan"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white text-sm"
                        >
                            <option value="">Semua Bulan</option>
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}">{{ DateTime::createFromFormat('!m', $i)->format('F') }}</option>
                            @endfor
                        </select>
                    </div>

                    <!-- Filter Tahun -->
                    <div>
                        <select 
                            wire:model.live="filterTahun"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white text-sm"
                        >
                            <option value="2024">2024</option>
                            <option value="2023">2023</option>
                            <option value="2022">2022</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Pembayaran List - Improved Table Responsiveness -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                @if($pembayaranList->count() > 0)
                    <!-- Desktop Table View -->
                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full min-w-full">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">
                                        Tanggal
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">
                                        Periode
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">
                                        Jumlah
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">
                                        Metode
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">
                                        Bukti
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($pembayaranList as $pembayaran)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ \Carbon\Carbon::parse($pembayaran->tanggal_bayar)->format('d M Y') }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 dark:text-white">
                                                @if($pembayaran->tagihanSpp)
                                                    {{ $pembayaran->tagihanSpp->bulan }} {{ $pembayaran->tagihanSpp->tahun }}
                                                @else
                                                    -
                                                @endif
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
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $pembayaran->status_badge }}">
                                                <i class="{{ $pembayaran->status_icon }} mr-1"></i>
                                                {{ $pembayaran->status_text }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($pembayaran->has_bukti_upload)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <i class="fas fa-check mr-1"></i> Terupload
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    <i class="fas fa-times mr-1"></i> Belum
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <button 
                                                    wire:click="lihatDetail({{ $pembayaran->id }})"
                                                    class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                                                    title="Lihat Detail"
                                                >
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                
                                                @if($pembayaran->status_verifikasi === 'pending' && !$pembayaran->has_bukti_upload)
                                                    <button 
                                                        wire:click="openUploadModal({{ $pembayaran->id }})"
                                                        class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300"
                                                        title="Upload Bukti"
                                                    >
                                                        <i class="fas fa-upload"></i>
                                                    </button>
                                                @endif

                                                @if($pembayaran->has_bukti_upload)
                                                    <button 
                                                        wire:click="downloadBukti({{ $pembayaran->id }})"
                                                        class="text-purple-600 hover:text-purple-900 dark:text-purple-400 dark:hover:text-purple-300"
                                                        title="Download Bukti"
                                                    >
                                                        <i class="fas fa-download"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Card View -->
                    <div class="md:hidden divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($pembayaranList as $pembayaran)
                            <div class="p-4">
                                <!-- Header Card -->
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <h3 class="text-base font-medium text-gray-900 dark:text-white">
                                            {{ \Carbon\Carbon::parse($pembayaran->tanggal_bayar)->format('d M Y') }}
                                        </h3>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            @if($pembayaran->tagihanSpp)
                                                {{ $pembayaran->tagihanSpp->bulan }} {{ $pembayaran->tagihanSpp->tahun }}
                                            @else
                                                -
                                            @endif
                                        </p>
                                    </div>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $pembayaran->status_badge }}">
                                        <i class="{{ $pembayaran->status_icon }} mr-1 text-xs"></i>
                                        {{ $pembayaran->status_text }}
                                    </span>
                                </div>

                                <!-- Detail Info -->
                                <div class="space-y-2 mb-3">
                                    <div class="flex justify-between items-center">
                                        <span class="text-xs text-gray-500 dark:text-gray-400">Jumlah</span>
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">
                                            Rp {{ number_format($pembayaran->jumlah_bayar, 0, ',', '.') }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-xs text-gray-500 dark:text-gray-400">Metode</span>
                                        <span class="text-sm text-gray-900 dark:text-white">
                                            {{ $pembayaran->metode_bayar_text }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-xs text-gray-500 dark:text-gray-400">Bukti</span>
                                        @if($pembayaran->has_bukti_upload)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check mr-1 text-xs"></i> Terupload
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                <i class="fas fa-times mr-1 text-xs"></i> Belum
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex gap-2">
                                    <button 
                                        wire:click="lihatDetail({{ $pembayaran->id }})"
                                        class="flex-1 text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 text-sm px-3 py-2 rounded border border-blue-200 dark:border-blue-800 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors flex items-center justify-center"
                                    >
                                        <i class="fas fa-eye mr-1"></i>
                                        Detail
                                    </button>
                                    
                                    @if($pembayaran->status_verifikasi === 'pending' && !$pembayaran->has_bukti_upload)
                                        <button 
                                            wire:click="openUploadModal({{ $pembayaran->id }})"
                                            class="flex-1 text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 text-sm px-3 py-2 rounded border border-green-200 dark:border-green-800 hover:bg-green-50 dark:hover:bg-green-900/20 transition-colors flex items-center justify-center"
                                        >
                                            <i class="fas fa-upload mr-1"></i>
                                            Upload
                                        </button>
                                    @endif

                                    @if($pembayaran->has_bukti_upload)
                                        <button 
                                            wire:click="downloadBukti({{ $pembayaran->id }})"
                                            class="flex-1 text-purple-600 hover:text-purple-900 dark:text-purple-400 dark:hover:text-purple-300 text-sm px-3 py-2 rounded border border-purple-200 dark:border-purple-800 hover:bg-purple-50 dark:hover:bg-purple-900/20 transition-colors flex items-center justify-center"
                                        >
                                            <i class="fas fa-download mr-1"></i>
                                            Download
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="bg-white dark:bg-gray-800 px-4 py-3 border-t border-gray-200 dark:border-gray-700 sm:px-6">
                        {{ $pembayaranList->links() }}
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="text-center py-8 sm:py-12">
                        <div class="text-gray-400 dark:text-gray-500">
                            <i class="fas fa-receipt text-4xl sm:text-6xl mb-4"></i>
                        </div>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Tidak ada riwayat pembayaran</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 px-4">
                            @if($search || $filterStatus !== 'semua' || $filterBulan || $filterTahun)
                                Coba ubah pencarian atau filter Anda.
                            @else
                                Belum ada riwayat pembayaran SPP.
                            @endif
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal Upload Bukti - Improved Responsiveness -->
    @if($showUploadModal)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-start justify-center p-4">
        <div class="relative top-4 md:top-20 mx-auto p-4 border w-full max-w-md shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3">
                <!-- Header -->
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                        Upload Bukti Pembayaran
                    </h3>
                    <button wire:click="closeUploadModal" class="text-gray-400 hover:text-gray-600 p-1">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <!-- Content -->
                <div class="space-y-4">
                    <!-- Info -->
                    <div class="bg-blue-50 dark:bg-blue-900 rounded-lg p-4">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-blue-500 dark:text-blue-300 mr-2 mt-0.5"></i>
                            <p class="text-sm text-blue-700 dark:text-blue-300">
                                Upload bukti transfer/pembayaran untuk verifikasi oleh admin.
                            </p>
                        </div>
                    </div>

                    <!-- Form Upload -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Bukti Pembayaran *
                        </label>
                        <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-4 text-center">
                            @if($buktiUpload)
                                <div class="mb-3">
                                    <img 
                                        src="{{ $buktiUpload->temporaryUrl() }}" 
                                        alt="Preview Bukti" 
                                        class="max-w-full h-32 object-contain rounded-lg mx-auto"
                                    >
                                </div>
                                <button 
                                    type="button"
                                    wire:click="$set('buktiUpload', null)"
                                    class="text-red-600 hover:text-red-800 text-sm"
                                >
                                    <i class="fas fa-trash mr-1"></i> Hapus
                                </button>
                            @else
                                <div>
                                    <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-3"></i>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                        Klik atau seret file ke sini
                                    </p>
                                    <input 
                                        type="file" 
                                        wire:model="buktiUpload"
                                        accept="image/*"
                                        class="hidden"
                                        id="bukti-upload"
                                    >
                                    <label for="bukti-upload" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 cursor-pointer">
                                        Pilih File
                                    </label>
                                </div>
                            @endif
                        </div>
                        @error('buktiUpload') 
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> 
                        @enderror
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            Format: JPG, PNG (Maksimal 2MB)
                        </p>
                    </div>

                    <!-- Catatan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Catatan (Opsional)
                        </label>
                        <textarea 
                            wire:model="catatanUpload"
                            rows="3"
                            placeholder="Tambahkan catatan untuk admin (misal: nomor referensi transfer, nama pengirim, dll)..."
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white text-sm"
                        ></textarea>
                    </div>

                    <!-- Actions -->
                    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3 pt-4">
                        <button 
                            wire:click="closeUploadModal"
                            class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors text-sm"
                        >
                            Batal
                        </button>
                        <button 
                            wire:click="uploadBukti"
                            wire:loading.attr="disabled"
                            class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:bg-blue-400 transition-colors text-sm"
                        >
                            <span wire:loading.remove>Upload Bukti</span>
                            <span wire:loading>
                                <i class="fas fa-spinner fa-spin mr-2"></i> Mengupload...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Detail Modal - Improved -->
    @isset($selectedPembayaran)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-4 sm:p-6">
                <!-- Modal Header -->
                <div class="flex justify-between items-center mb-4 sm:mb-6">
                    <h2 class="text-lg sm:text-xl font-semibold text-gray-900 dark:text-white">Detail Pembayaran</h2>
                    <button wire:click="closeDetail" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 p-1">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <!-- Payment Info Section -->
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-6">
                    <h3 class="text-base font-medium text-gray-900 dark:text-white mb-3">Informasi Pembayaran</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Tanggal Bayar</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ \Carbon\Carbon::parse($selectedPembayaran->tanggal_bayar)->format('d F Y') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Jumlah Bayar</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                Rp {{ number_format($selectedPembayaran->jumlah_bayar, 0, ',', '.') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Metode Bayar</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $selectedPembayaran->metode_bayar_text }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Status</p>
                            <div class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $selectedPembayaran->status_badge }}">
                                    <i class="{{ $selectedPembayaran->status_icon }} mr-1"></i>
                                    {{ $selectedPembayaran->status_text }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @if($selectedPembayaran->tagihanSpp)
                        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-600">
                            <p class="text-xs text-gray-500 dark:text-gray-400">Periode SPP</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $selectedPembayaran->tagihanSpp->bulan }} {{ $selectedPembayaran->tagihanSpp->tahun }}
                            </p>
                        </div>
                    @endif
                </div>

                <!-- Verification Info Section -->
                @if($selectedPembayaran->status_verifikasi !== 'pending')
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-6">
                        <h3 class="text-base font-medium text-gray-900 dark:text-white mb-3">Informasi Verifikasi</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @if($selectedPembayaran->verified_at)
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Tanggal Verifikasi</p>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ \Carbon\Carbon::parse($selectedPembayaran->verified_at)->format('d F Y') }}
                                    </p>
                                </div>
                            @endif
                            @if($selectedPembayaran->nama_verifikator)
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Diverifikasi Oleh</p>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $selectedPembayaran->nama_verifikator }}
                                    </p>
                                </div>
                            @endif
                            @if($selectedPembayaran->alasan_penolakan)
                                <div class="col-span-2">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Alasan Penolakan</p>
                                    <p class="text-sm font-medium text-red-600 dark:text-red-400">
                                        {{ $selectedPembayaran->alasan_penolakan }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Bukti Pembayaran Section -->
                @if($selectedPembayaran->has_bukti_upload)
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-6">
                        <h3 class="text-base font-medium text-gray-900 dark:text-white mb-3">Bukti Pembayaran</h3>
                        <div class="flex justify-center">
                            <img 
                                src="{{ asset('storage/' . $selectedPembayaran->bukti_pembayaran) }}" 
                                alt="Bukti Pembayaran" 
                                class="max-w-full h-auto rounded-lg border border-gray-200 dark:border-gray-600"
                            >
                        </div>
                    </div>
                @endif

                <!-- Catatan Section -->
                @if($selectedPembayaran->catatan)
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-6">
                        <h3 class="text-base font-medium text-gray-900 dark:text-white mb-3">Catatan</h3>
                        <p class="text-sm text-gray-900 dark:text-white">
                            {{ $selectedPembayaran->catatan }}
                        </p>
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-3">
                    <button wire:click="closeDetail" 
                            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-600">
                        Tutup
                    </button>
                    @if($selectedPembayaran->has_bukti_upload)
                        <button wire:click="downloadBukti({{ $selectedPembayaran->id }})" 
                                class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                            <i class="fas fa-download mr-2"></i> Download Bukti
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endisset

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="fixed bottom-4 right-4 bg-green-500 text-white px-4 py-3 rounded-lg shadow-lg max-w-xs sm:max-w-sm z-50">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <span class="text-sm">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="fixed bottom-4 right-4 bg-red-500 text-white px-4 py-3 rounded-lg shadow-lg max-w-xs sm:max-w-sm z-50">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <span class="text-sm">{{ session('error') }}</span>
            </div>
        </div>
    @endif
</div>

@script
<script>
    // Event listener untuk detail pembayaran
    Livewire.on('show-pembayaran-detail', (event) => {
        const pembayaran = event.pembayaran;
        
        // Create modal element
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50';
        modal.innerHTML = `
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <div class="p-4 sm:p-6">
                    <!-- Modal Header -->
                    <div class="flex justify-between items-center mb-4 sm:mb-6">
                        <h2 class="text-lg sm:text-xl font-semibold text-gray-900 dark:text-white">Detail Pembayaran</h2>
                        <button onclick="this.closest('.fixed').remove()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 p-1">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>

                    <!-- Payment Info Section -->
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-6">
                        <h3 class="text-base font-medium text-gray-900 dark:text-white mb-3">Informasi Pembayaran</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Tanggal Bayar</p>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    ${new Date(pembayaran.tanggal_bayar).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })}
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Jumlah Bayar</p>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    Rp ${new Intl.NumberFormat('id-ID').format(pembayaran.jumlah_bayar)}
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Metode Bayar</p>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    ${pembayaran.metode_bayar_text}
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Status</p>
                                <div class="mt-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${pembayaran.status_badge}">
                                        <i class="${pembayaran.status_icon} mr-1"></i>
                                        ${pembayaran.status_text}
                                    </span>
                                </div>
                            </div>
                        </div>
                        ${pembayaran.tagihan_spp ? `
                        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-600">
                            <p class="text-xs text-gray-500 dark:text-gray-400">Periode SPP</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                ${pembayaran.tagihan_spp.bulan} ${pembayaran.tagihan_spp.tahun}
                            </p>
                        </div>
                        ` : ''}
                    </div>

                    <!-- Verification Info Section -->
                    ${pembayaran.status_verifikasi !== 'pending' ? `
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-6">
                        <h3 class="text-base font-medium text-gray-900 dark:text-white mb-3">Informasi Verifikasi</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            ${pembayaran.verified_at ? `
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Tanggal Verifikasi</p>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    ${new Date(pembayaran.verified_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })}
                                </p>
                            </div>
                            ` : ''}
                            ${pembayaran.nama_verifikator ? `
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Diverifikasi Oleh</p>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    ${pembayaran.nama_verifikator}
                                </p>
                            </div>
                            ` : ''}
                            ${pembayaran.alasan_penolakan ? `
                            <div class="col-span-2">
                                <p class="text-xs text-gray-500 dark:text-gray-400">Alasan Penolakan</p>
                                <p class="text-sm font-medium text-red-600 dark:text-red-400">
                                    ${pembayaran.alasan_penolakan}
                                </p>
                            </div>
                            ` : ''}
                        </div>
                    </div>
                    ` : ''}

                    <!-- Bukti Pembayaran Section -->
                    ${pembayaran.has_bukti_upload ? `
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-6">
                        <h3 class="text-base font-medium text-gray-900 dark:text-white mb-3">Bukti Pembayaran</h3>
                        <div class="flex justify-center">
                            <img 
                                src="${pembayaran.bukti_pembayaran}" 
                                alt="Bukti Pembayaran" 
                                class="max-w-full h-auto rounded-lg border border-gray-200 dark:border-gray-600"
                            >
                        </div>
                    </div>
                    ` : ''}

                    <!-- Catatan Section -->
                    ${pembayaran.catatan ? `
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-6">
                        <h3 class="text-base font-medium text-gray-900 dark:text-white mb-3">Catatan</h3>
                        <p class="text-sm text-gray-900 dark:text-white">
                            ${pembayaran.catatan}
                        </p>
                    </div>
                    ` : ''}

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-3">
                        <button onclick="this.closest('.fixed').remove()" 
                                class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-600">
                            Tutup
                        </button>
                        ${pembayaran.has_bukti_upload ? `
                        <button onclick="window.open('${pembayaran.bukti_pembayaran}', '_blank')" 
                                class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                            <i class="fas fa-download mr-2"></i> Download Bukti
                        </button>
                        ` : ''}
                    </div>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        
        // Close modal on background click
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.remove();
            }
        });
    });
</script>
@endscript