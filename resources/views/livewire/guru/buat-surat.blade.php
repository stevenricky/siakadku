<div>
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Manajemen Surat</h1>
        <p class="text-gray-600 dark:text-gray-400">Kelola surat masuk dan keluar Anda</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="bi bi-envelope text-2xl text-blue-500"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Surat</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['total'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="bi bi-download text-2xl text-green-500"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Surat Masuk</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['masuk'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-purple-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="bi bi-upload text-2xl text-purple-500"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Surat Keluar</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['keluar'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-yellow-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="bi bi-clock text-2xl text-yellow-500"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Status Baru</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['baru'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Cari Surat</label>
                <input type="text" wire:model.live="search" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                       placeholder="Cari nomor surat, perihal...">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Jenis Surat</label>
                <select wire:model.live="jenisFilter" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">Semua Jenis</option>
                    <option value="masuk">Surat Masuk</option>
                    <option value="keluar">Surat Keluar</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                <select wire:model.live="statusFilter" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">Semua Status</option>
                    <option value="baru">Baru</option>
                    <option value="diproses">Diproses</option>
                    <option value="selesai">Selesai</option>
                    <option value="arsip">Arsip</option>
                </select>
            </div>
            <div class="flex items-end">
                <button wire:click="$toggle('editingSurat')" 
                        class="w-full px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-colors">
                    {{ $editingSurat ? 'Batal Edit' : 'Buat Surat' }}
                </button>
            </div>
        </div>
    </div>

    <!-- Form Buat/Edit Surat -->
    @if($editingSurat || !$viewingSurat)
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
            {{ $editingSurat ? 'Edit Surat' : 'Buat Surat Baru' }}
        </h2>

        <form wire:submit.prevent="{{ $editingSurat ? 'updateSurat' : 'simpanSurat' }}">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Jenis & Nomor Surat -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Jenis Surat <span class="text-red-500">*</span>
                    </label>
                    <select wire:model="jenis_surat" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">Pilih Jenis Surat</option>
                        <option value="masuk">Surat Masuk</option>
                        <option value="keluar">Surat Keluar</option>
                    </select>
                    @error('jenis_surat') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Nomor Surat <span class="text-red-500">*</span>
                    </label>
                    <input type="text" wire:model="nomor_surat" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                           placeholder="Contoh: 001/SMA/VI/2024">
                    @error('nomor_surat') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Perihal -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Perihal <span class="text-red-500">*</span>
                    </label>
                    <input type="text" wire:model="perihal" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                           placeholder="Perihal surat">
                    @error('perihal') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Isi Singkat -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Isi Singkat <span class="text-red-500">*</span>
                    </label>
                    <textarea wire:model="isi_singkat" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                              placeholder="Ringkasan isi surat"></textarea>
                    @error('isi_singkat') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Pengirim & Penerima -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Pengirim <span class="text-red-500">*</span>
                    </label>
                    <input type="text" wire:model="pengirim" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                           placeholder="Nama pengirim">
                    @error('pengirim') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Penerima</label>
                    <input type="text" wire:model="penerima" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                           placeholder="Nama penerima (opsional)">
                    @error('penerima') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Tanggal -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Tanggal Surat <span class="text-red-500">*</span>
                    </label>
                    <input type="date" wire:model="tanggal_surat" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('tanggal_surat') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tanggal Terima</label>
                    <input type="date" wire:model="tanggal_terima" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('tanggal_terima') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- File Surat -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">File Surat</label>
                    
                    @if($existing_file)
                        <div class="mb-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <i class="bi bi-file-earmark-text text-blue-500 mr-3"></i>
                                    <span class="text-sm text-gray-700 dark:text-gray-300">{{ basename($existing_file) }}</span>
                                </div>
                                <a href="{{ Storage::disk('public')->url($existing_file) }}" 
                                   target="_blank" class="text-primary-600 hover:text-primary-700 text-sm">
                                    Download
                                </a>
                            </div>
                        </div>
                    @endif
                    
                    <input type="file" wire:model="file_surat" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        Format: PDF, DOC, DOCX, JPG, JPEG, PNG (Maksimal: 2MB)
                    </p>
                    @error('file_surat') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Disposisi & Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Disposisi Ke</label>
                    <select wire:model="disposisi_ke" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">Pilih Tujuan Disposisi</option>
                        <option value="kepala_sekolah">Kepala Sekolah</option>
                        <option value="wakil_kepala_sekolah">Wakil Kepala Sekolah</option>
                        <option value="guru">Guru</option>
                        <option value="tata_usaha">Tata Usaha</option>
                        <option value="lainnya">Lainnya</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                    <select wire:model="status" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="baru">Baru</option>
                        <option value="diproses">Diproses</option>
                        <option value="selesai">Selesai</option>
                        <option value="arsip">Arsip</option>
                    </select>
                </div>

                <!-- Catatan Disposisi -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Catatan Disposisi</label>
                    <textarea wire:model="catatan_disposisi" rows="2"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                              placeholder="Catatan untuk disposisi (opsional)"></textarea>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3 mt-6">
                @if($editingSurat)
                    <button type="button" wire:click="cancelEdit" 
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                        Batal
                    </button>
                @endif
                <button type="submit" 
                        class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-colors">
                    {{ $editingSurat ? 'Update Surat' : 'Simpan Surat' }}
                </button>
            </div>
        </form>
    </div>
    @endif

    <!-- Detail View Modal -->
    @if($viewingSurat)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Detail Surat</h2>
                    <button wire:click="closeView" class="text-gray-400 hover:text-gray-600">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Nomor Surat</label>
                            <p class="text-sm text-gray-900 dark:text-white">{{ $viewingSurat->nomor_surat }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Jenis Surat</label>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $viewingSurat->jenis_surat == 'masuk' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                {{ $viewingSurat->jenis_surat == 'masuk' ? 'Surat Masuk' : 'Surat Keluar' }}
                            </span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Perihal</label>
                        <p class="text-sm text-gray-900 dark:text-white">{{ $viewingSurat->perihal }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Isi Singkat</label>
                        <p class="text-sm text-gray-900 dark:text-white">{{ $viewingSurat->isi_singkat }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Pengirim</label>
                            <p class="text-sm text-gray-900 dark:text-white">{{ $viewingSurat->pengirim }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Penerima</label>
                            <p class="text-sm text-gray-900 dark:text-white">{{ $viewingSurat->penerima ?? '-' }}</p>
                        </div>
                    </div>

                    @if($viewingSurat->file_surat)
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">File Surat</label>
                        <a href="{{ Storage::disk('public')->url($viewingSurat->file_surat) }}" 
                           target="_blank" class="inline-flex items-center text-primary-600 hover:text-primary-700">
                            <i class="bi bi-download mr-2"></i>
                            Download File
                        </a>
                    </div>
                    @endif
                </div>

                <div class="flex justify-end mt-6">
                    <button wire:click="closeView" 
                            class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- List Surat -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Daftar Surat</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nomor Surat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jenis</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Perihal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($surat as $item)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $item->nomor_surat }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $item->tanggal_surat->format('d/m/Y') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $item->jenis_surat == 'masuk' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $item->jenis_surat == 'masuk' ? 'Surat Masuk' : 'Surat Keluar' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ Str::limit($item->perihal, 50) }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ Str::limit($item->isi_singkat, 70) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusColors = [
                                        'baru' => 'bg-blue-100 text-blue-800',
                                        'diproses' => 'bg-yellow-100 text-yellow-800',
                                        'selesai' => 'bg-green-100 text-green-800',
                                        'arsip' => 'bg-gray-100 text-gray-800'
                                    ];
                                    $statusLabels = [
                                        'baru' => 'Baru',
                                        'diproses' => 'Diproses',
                                        'selesai' => 'Selesai',
                                        'arsip' => 'Arsip'
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$item->status] }}">
                                    {{ $statusLabels[$item->status] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <button wire:click="viewSurat({{ $item->id }})" 
                                            class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button wire:click="editSurat({{ $item->id }})" 
                                            class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button wire:click="deleteSurat({{ $item->id }})" 
                                            wire:confirm="Apakah Anda yakin ingin menghapus surat ini?"
                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center">
                                <div class="text-gray-500 dark:text-gray-400">
                                    <i class="bi bi-envelope-x text-4xl mb-3 block"></i>
                                    <p>Tidak ada data surat ditemukan</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $surat->links() }}
        </div>
    </div>

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