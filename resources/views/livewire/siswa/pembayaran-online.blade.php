<div>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Pembayaran Online</h2>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Bayar SPP secara online dengan berbagai metode pembayaran</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Daftar Tagihan -->
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                            Tagihan Belum Lunas
                        </h3>

                        @if($tagihanBelumLunas->count() > 0)
                            <div class="space-y-4">
                                @foreach($tagihanBelumLunas as $tagihan)
                                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                        <div class="flex items-center justify-between">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-2 mb-2">
                                                    <h4 class="font-medium text-gray-900 dark:text-white">
                                                        {{ $tagihan->bulan }} {{ $tagihan->tahun }}
                                                    </h4>
                                                    @if($tagihan->has_pending_online_payment)
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                            <i class="fas fa-clock mr-1"></i>
                                                            Pembayaran Pending
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="flex items-center gap-4 mt-2 text-sm text-gray-600 dark:text-gray-400">
                                                    <span>Tagihan: {{ $tagihan->jumlah_tagihan_formatted }}</span>
                                                    @if($tagihan->denda > 0)
                                                        <span>Denda: {{ $tagihan->denda_formatted }}</span>
                                                    @endif
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                        {{ $tagihan->status_lengkap }}
                                                    </span>
                                                </div>
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                    Jatuh tempo: {{ \Carbon\Carbon::parse($tagihan->tanggal_jatuh_tempo)->format('d M Y') }}
                                                </p>
                                            </div>
                                            <div>
                                                @if($tagihan->has_pending_online_payment)
                                                    <button 
                                                        wire:click="lihatDetailPembayaran({{ $tagihan->latest_pending_payment->id }})"
                                                        class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 focus:bg-yellow-700 active:bg-yellow-800 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                                    >
                                                        Lihat Status
                                                    </button>
                                                @else
                                                    <button 
                                                        wire:click="pilihTagihan({{ $tagihan->id }})"
                                                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                                    >
                                                        Bayar Sekarang
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="text-gray-400 dark:text-gray-500">
                                    <i class="fas fa-check-circle text-4xl mb-4"></i>
                                </div>
                                <h3 class="text-sm font-medium text-gray-900 dark:text-white">Semua tagihan sudah lunas</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    Tidak ada tagihan yang perlu dibayar saat ini.
                                </p>
                            </div>
                        @endif
                    </div>

                    <!-- Riwayat Pembayaran Online -->
                    <div class="mt-6 bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                            Riwayat Pembayaran Online
                        </h3>

                        @if($riwayatPembayaran->count() > 0)
                            <div class="space-y-3">
                                @foreach($riwayatPembayaran as $pembayaran)
                                    <div class="flex items-center justify-between p-3 border border-gray-200 dark:border-gray-700 rounded-lg">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2">
                                                <span class="text-sm font-medium text-gray-900 dark:text-white">
                                                    {{ $pembayaran->tagihanSpp->bulan }} {{ $pembayaran->tagihanSpp->tahun }}
                                                </span>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $pembayaran->status_pembayaran_badge }}">
                                                    {{ $pembayaran->status_pembayaran_text }}
                                                </span>
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                {{ $pembayaran->metode_bayar_text }} • 
                                                {{ \Carbon\Carbon::parse($pembayaran->tanggal_bayar)->format('d M Y H:i') }}
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $pembayaran->jumlah_bayar_formatted }}
                                            </span>
                                            @if($pembayaran->is_pending)
                                                <button 
                                                    wire:click="lihatDetailPembayaran({{ $pembayaran->id }})"
                                                    class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 text-sm"
                                                >
                                                    Lihat Instruksi
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Pagination -->
                            <div class="mt-4">
                                {{ $riwayatPembayaran->links() }}
                            </div>
                        @else
                            <div class="text-center py-4">
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Belum ada riwayat pembayaran online.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Form Pembayaran & Info Panel -->
                @if($showFormPembayaran && $tagihanTerpilih)
                    <div class="lg:col-span-1">
                        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 sticky top-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                                Pembayaran {{ $tagihanTerpilih->bulan }} {{ $tagihanTerpilih->tahun }}
                            </h3>

                            <!-- Detail Tagihan -->
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-4">
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Tagihan SPP:</span>
                                        <span class="font-medium">{{ $tagihanTerpilih->jumlah_tagihan_formatted }}</span>
                                    </div>
                                    @if($tagihanTerpilih->denda > 0)
                                        <div class="flex justify-between">
                                            <span class="text-gray-600 dark:text-gray-400">Denda:</span>
                                            <span class="font-medium text-red-600">{{ $tagihanTerpilih->denda_formatted }}</span>
                                        </div>
                                    @endif
                                    <div class="flex justify-between border-t border-gray-200 dark:border-gray-600 pt-2">
                                        <span class="text-gray-600 dark:text-gray-400">Total Tagihan:</span>
                                        <span class="font-medium text-green-600">{{ $tagihanTerpilih->total_pembayaran_formatted }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Pilih Metode Pembayaran -->
                            @if(!$metodePembayaran)
                                <div class="mb-4">
                                    <h4 class="font-medium text-gray-900 dark:text-white mb-3">Pilih Metode Pembayaran</h4>
                                    <div class="grid grid-cols-1 gap-3">
                                        @foreach($channels as $key => $channel)
                                            <button 
                                                wire:click="pilihMetode('{{ $key }}')"
                                                class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                                            >
                                                <div class="flex items-center">
                                                    <div class="w-10 h-10 rounded-full bg-{{ $channel['color'] }}-100 flex items-center justify-center mr-3">
                                                        <i class="{{ $channel['icon'] }} text-{{ $channel['color'] }}-600"></i>
                                                    </div>
                                                    <div>
                                                        <div class="font-medium text-gray-900 dark:text-white text-left">{{ $channel['name'] }}</div>
                                                        <div class="text-xs text-gray-500 text-left">
                                                            @if($key === 'virtual_account')
                                                                BCA, BRI, BNI, Mandiri
                                                            @elseif($key === 'ewallet')
                                                                GoPay, OVO, DANA, ShopeePay
                                                            @else
                                                                QR Code Indonesia Standard
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <i class="fas fa-chevron-right text-gray-400"></i>
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Pilih Channel Pembayaran -->
                            @if($metodePembayaran && !$channelPembayaran)
                                <div class="mb-4">
                                    <div class="flex items-center mb-3">
                                        <button 
                                            wire:click="pilihMetode('')"
                                            class="mr-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
                                        >
                                            <i class="fas fa-arrow-left"></i>
                                        </button>
                                        <h4 class="font-medium text-gray-900 dark:text-white">Pilih {{ $channels[$metodePembayaran]['name'] }}</h4>
                                    </div>

                                    @if($metodePembayaran === 'virtual_account')
                                        <div class="grid grid-cols-2 gap-3">
                                            @foreach($channels['virtual_account']['banks'] as $key => $bank)
                                                <button 
                                                    wire:click="pilihChannel('{{ $key }}')"
                                                    class="flex flex-col items-center p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                                                >
                                                    <!-- Logo Bank -->
                                                    <div class="w-12 h-12 mb-2 bg-white rounded-lg border flex items-center justify-center">
                                                        @if(isset($bank['logo']))
                                                            <img src="{{ $this->getLogoUrl($bank['logo']) }}" alt="{{ $bank['name'] }}" class="w-8 h-8 object-contain">
                                                        @else
                                                            <div class="w-8 h-8 bg-{{ $bank['color'] }}-100 rounded-full flex items-center justify-center">
                                                                <i class="{{ $bank['icon'] }} text-{{ $bank['color'] }}-600"></i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $bank['name'] }}</span>
                                                    <span class="text-xs text-gray-500">Biaya: Rp {{ number_format($bank['fee'], 0, ',', '.') }}</span>
                                                </button>
                                            @endforeach
                                        </div>
                                    @elseif($metodePembayaran === 'ewallet')
                                        <div class="grid grid-cols-2 gap-3">
                                            @foreach($channels['ewallet']['wallets'] as $key => $wallet)
                                                <button 
                                                    wire:click="pilihChannel('{{ $key }}')"
                                                    class="flex flex-col items-center p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                                                >
                                                    <!-- Logo E-Wallet -->
                                                    <div class="w-12 h-12 mb-2 bg-white rounded-lg border flex items-center justify-center">
                                                        @if(isset($wallet['logo']))
                                                            <img src="{{ $this->getLogoUrl($wallet['logo']) }}" alt="{{ $wallet['name'] }}" class="w-8 h-8 object-contain">
                                                        @else
                                                            <div class="w-8 h-8 bg-{{ $wallet['color'] }}-100 rounded-full flex items-center justify-center">
                                                                <i class="{{ $wallet['icon'] }} text-{{ $wallet['color'] }}-600"></i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $wallet['name'] }}</span>
                                                    <span class="text-xs text-gray-500">Biaya: Rp {{ number_format($wallet['fee'], 0, ',', '.') }}</span>
                                                </button>
                                            @endforeach
                                        </div>
                                    @elseif($metodePembayaran === 'qris')
                                        <button 
                                            wire:click="pilihChannel('qris')"
                                            class="w-full flex items-center justify-between p-4 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                                        >
                                            <div class="flex items-center">
                                                <!-- Logo QRIS -->
                                                <div class="w-12 h-12 mr-3 bg-white rounded-lg border flex items-center justify-center">
                                                    @if(isset($channels['qris']['logo']))
                                                        <img src="{{ $this->getLogoUrl($channels['qris']['logo']) }}" alt="QRIS" class="w-8 h-8 object-contain">
                                                    @else
                                                        <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                                            <i class="fas fa-qrcode text-purple-600"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <div class="font-medium text-gray-900 dark:text-white">QRIS</div>
                                                    <div class="text-xs text-gray-500">Biaya: Rp {{ number_format($channels['qris']['fee'], 0, ',', '.') }}</div>
                                                </div>
                                            </div>
                                            <i class="fas fa-chevron-right text-gray-400"></i>
                                        </button>
                                    @endif
                                </div>
                            @endif

                            <!-- Konfirmasi Pembayaran -->
                            @if($channelPembayaran)
                                <div class="mb-4">
                                    <div class="flex items-center mb-3">
                                        <button 
                                            wire:click="pilihChannel('')"
                                            class="mr-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
                                        >
                                            <i class="fas fa-arrow-left"></i>
                                        </button>
                                        <h4 class="font-medium text-gray-900 dark:text-white">Konfirmasi Pembayaran</h4>
                                    </div>

                                    <!-- Logo Channel Terpilih -->
                                    <div class="flex justify-center mb-4">
                                        <div class="w-16 h-16 bg-white rounded-lg border flex items-center justify-center">
                                            @if($metodePembayaran === 'virtual_account')
                                                @if(isset($channels['virtual_account']['banks'][$channelPembayaran]['logo']))
                                                    <img src="{{ $this->getLogoUrl($channels['virtual_account']['banks'][$channelPembayaran]['logo']) }}" 
                                                         alt="{{ $channels['virtual_account']['banks'][$channelPembayaran]['name'] }}" 
                                                         class="w-12 h-12 object-contain">
                                                @else
                                                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                                        <i class="fab fa-bank text-blue-600 text-xl"></i>
                                                    </div>
                                                @endif
                                            @elseif($metodePembayaran === 'ewallet')
                                                @if(isset($channels['ewallet']['wallets'][$channelPembayaran]['logo']))
                                                    <img src="{{ $this->getLogoUrl($channels['ewallet']['wallets'][$channelPembayaran]['logo']) }}" 
                                                         alt="{{ $channels['ewallet']['wallets'][$channelPembayaran]['name'] }}" 
                                                         class="w-12 h-12 object-contain">
                                                @else
                                                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                                        <i class="fas fa-mobile-alt text-green-600 text-xl"></i>
                                                    </div>
                                                @endif
                                            @elseif($metodePembayaran === 'qris')
                                                @if(isset($channels['qris']['logo']))
                                                    <img src="{{ $this->getLogoUrl($channels['qris']['logo']) }}" 
                                                         alt="QRIS" 
                                                         class="w-12 h-12 object-contain">
                                                @else
                                                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                                                        <i class="fas fa-qrcode text-purple-600 text-xl"></i>
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                    </div>

                                    @php
                                        $biayaAdmin = $this->hitungBiayaAdmin();
                                        $totalBayar = $tagihanTerpilih->total_pembayaran + $biayaAdmin;
                                    @endphp

                                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 space-y-2 text-sm">
                                        <div class="flex justify-between">
                                            <span>Metode Pembayaran:</span>
                                            <span class="font-medium">{{ $this->getNamaChannel() }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span>Total Tagihan:</span>
                                            <span>{{ $tagihanTerpilih->total_pembayaran_formatted }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span>Biaya Admin:</span>
                                            <span>Rp {{ number_format($biayaAdmin, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="flex justify-between border-t border-gray-200 dark:border-gray-600 pt-2 font-medium">
                                            <span>Total Bayar:</span>
                                            <span class="text-green-600">Rp {{ number_format($totalBayar, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tombol Aksi -->
                                <div class="flex space-x-3">
                                    <button 
                                        wire:click="batalkanPembayaran"
                                        class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                                    >
                                        Batal
                                    </button>
                                    <button 
                                        wire:click="prosesPembayaran"
                                        class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors"
                                    >
                                        Bayar Sekarang
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    <!-- Info Panel ketika tidak ada tagihan terpilih -->
                    <div class="lg:col-span-1">
                        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                            <div class="text-center">
                                <div class="text-blue-400 dark:text-blue-300 mb-4">
                                    <i class="fas fa-credit-card text-4xl"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                                    Metode Pembayaran
                                </h3>
                                
                                <!-- Transfer Bank -->
                                <div class="mb-6">
                                    <h4 class="font-medium text-gray-900 dark:text-white mb-3 text-left">Transfer Bank</h4>
                                    <div class="grid grid-cols-2 gap-2">
                                        @foreach($channels['virtual_account']['banks'] as $bank)
                                        <div class="flex items-center p-2 border border-gray-200 dark:border-gray-600 rounded-lg">
                                            <div class="w-8 h-8 mr-2 bg-white rounded flex items-center justify-center">
                                                @if(isset($bank['logo']))
                                                    <img src="{{ $this->getLogoUrl($bank['logo']) }}" alt="{{ $bank['name'] }}" class="w-6 h-6 object-contain">
                                                @else
                                                    <i class="{{ $bank['icon'] }} text-gray-600 text-sm"></i>
                                                @endif
                                            </div>
                                            <span class="text-sm text-gray-700 dark:text-gray-300">{{ $bank['name'] }}</span>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- E-Wallet -->
                                <div class="mb-6">
                                    <h4 class="font-medium text-gray-900 dark:text-white mb-3 text-left">E-Wallet</h4>
                                    <div class="grid grid-cols-2 gap-2">
                                        @foreach($channels['ewallet']['wallets'] as $wallet)
                                        <div class="flex items-center p-2 border border-gray-200 dark:border-gray-600 rounded-lg">
                                            <div class="w-8 h-8 mr-2 bg-white rounded flex items-center justify-center">
                                                @if(isset($wallet['logo']))
                                                    <img src="{{ $this->getLogoUrl($wallet['logo']) }}" alt="{{ $wallet['name'] }}" class="w-6 h-6 object-contain">
                                                @else
                                                    <i class="{{ $wallet['icon'] }} text-gray-600 text-sm"></i>
                                                @endif
                                            </div>
                                            <span class="text-sm text-gray-700 dark:text-gray-300">{{ $wallet['name'] }}</span>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- QRIS -->
                                <div>
                                    <h4 class="font-medium text-gray-900 dark:text-white mb-3 text-left">QRIS</h4>
                                    <div class="flex items-center p-3 border border-gray-200 dark:border-gray-600 rounded-lg">
                                        <div class="w-10 h-10 mr-3 bg-white rounded flex items-center justify-center">
                                            @if(isset($channels['qris']['logo']))
                                                <img src="{{ $this->getLogoUrl($channels['qris']['logo']) }}" alt="QRIS" class="w-8 h-8 object-contain">
                                            @else
                                                <i class="fas fa-qrcode text-purple-600"></i>
                                            @endif
                                        </div>
                                        <span class="text-sm text-gray-700 dark:text-gray-300">QR Code Indonesia Standard</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal Detail Pembayaran -->
@if($showDetailPembayaran && $pembayaranDetail)
<div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-4 mx-auto p-3 border w-11/12 max-w-md shadow-lg rounded-md bg-white dark:bg-gray-800 sm:top-20 sm:p-4">
        <div class="mt-2 sm:mt-3">
            <div class="flex items-center justify-between mb-3 sm:mb-4">
                <h3 class="text-base sm:text-lg font-medium text-gray-900 dark:text-white">
                    Detail Pembayaran
                </h3>
                <button wire:click="tutupDetailPembayaran" class="text-gray-400 hover:text-gray-600 p-1">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>

            <div class="space-y-3 sm:space-y-4">
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3 sm:p-4">
                    <div class="space-y-2 text-xs sm:text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Kode Referensi:</span>
                            <span class="font-mono font-medium">{{ $pembayaranDetail->kode_referensi }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Periode:</span>
                            <span>{{ $pembayaranDetail->tagihanSpp->periode_lengkap }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Metode:</span>
                            <span>{{ $pembayaranDetail->metode_bayar_text }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Status:</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $pembayaranDetail->status_pembayaran_badge }}">
                                {{ $pembayaranDetail->status_pembayaran_text }}
                            </span>
                        </div>
                        <div class="flex justify-between border-t border-gray-200 dark:border-gray-600 pt-2">
                            <span class="text-gray-600 dark:text-gray-400 font-medium">Total:</span>
                            <span class="font-medium text-green-600">{{ $pembayaranDetail->total_bayar_formatted }}</span>
                        </div>
                        @if($pembayaranDetail->waktu_kadaluarsa)
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Batas Waktu:</span>
                            <span class="text-sm">{{ $pembayaranDetail->waktu_kadaluarsa_formatted }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Dibuat:</span>
                            <span class="text-sm">{{ $pembayaranDetail->created_at_formatted }}</span>
                        </div>
                    </div>
                </div>

                @if($pembayaranDetail->is_pending)
                <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 sm:space-x-3">
                    <button 
                        wire:click="batalkanPembayaranOnline({{ $pembayaranDetail->id }})"
                        class="flex-1 px-3 py-2 sm:px-4 sm:py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors text-sm sm:text-base"
                    >
                        Batalkan
                    </button>
                    
                    <!-- ✅ TAMBAH TOMBOL UPLOAD BUKTI DI SINI -->
                    <button 
                        wire:click="openUploadModal({{ $pembayaranDetail->id }})"
                        class="flex-1 px-3 py-2 sm:px-4 sm:py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm sm:text-base"
                    >
                        <i class="fas fa-upload mr-2"></i>
                        Upload Bukti
                    </button>

                    <!-- Tombol simulasi (untuk testing) -->
                    <button 
                        wire:click="simulasiPembayaranBerhasil({{ $pembayaranDetail->id }})"
                        class="flex-1 px-3 py-2 sm:px-4 sm:py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm sm:text-base"
                    >
                        Simulasi Berhasil
                    </button>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endif



<!-- Modal Upload Bukti Pembayaran -->
@if($showUploadModal && $pembayaranDetail)
<div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-4 mx-auto p-3 border w-11/12 max-w-md shadow-lg rounded-md bg-white dark:bg-gray-800 sm:top-20 sm:p-4">
        <div class="mt-2 sm:mt-3">
            <div class="flex items-center justify-between mb-3 sm:mb-4">
                <h3 class="text-base sm:text-lg font-medium text-gray-900 dark:text-white">
                    Upload Bukti Pembayaran
                </h3>
                <button wire:click="closeUploadModal" class="text-gray-400 hover:text-gray-600 p-1">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>

            <form wire:submit="prosesUploadBukti">
                <div class="space-y-4">
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            <strong>Kode Referensi:</strong> {{ $pembayaranDetail->kode_referensi }}<br>
                            <strong>Total Bayar:</strong> {{ $pembayaranDetail->total_bayar_formatted }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Upload Bukti Pembayaran *
                        </label>
                        <input 
                            type="file" 
                            wire:model="buktiUpload"
                            accept="image/*,.pdf"
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg p-2 dark:bg-gray-700 dark:text-white"
                        >
                        @error('buktiUpload') 
                            <span class="text-red-500 text-xs">{{ $message }}</span> 
                        @enderror
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            Format: JPG, PNG, JPEG, PDF (Maks. 2MB)
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Catatan (Opsional)
                        </label>
                        <textarea 
                            wire:model="catatanUpload"
                            rows="3"
                            placeholder="Tambahkan catatan jika diperlukan..."
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg p-2 dark:bg-gray-700 dark:text-white"
                        ></textarea>
                        @error('catatanUpload') 
                            <span class="text-red-500 text-xs">{{ $message }}</span> 
                        @enderror
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <button 
                        type="button"
                        wire:click="closeUploadModal"
                        class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700"
                    >
                        Batal
                    </button>
                    <button 
                        type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                    >
                        Upload Bukti
                    </button>
                </div>
            </form>
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

    @if (session()->has('info'))
        <div class="fixed bottom-4 right-4 bg-blue-500 text-white px-6 py-3 rounded-lg shadow-lg">
            <div class="flex items-center">
                <i class="fas fa-info-circle mr-2"></i>
                {{ session('info') }}
            </div>
        </div>z
    @endif
</div>