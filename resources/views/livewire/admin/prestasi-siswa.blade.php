<div class="p-4 md:p-6">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
                    Prestasi Siswa
                </h1>
                <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">
                    Kelola data prestasi dan pencapaian siswa
                </p>
            </div>
            <button 
                wire:click="openCreateForm" 
                class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg transition flex items-center justify-center gap-2"
            >
                <i class="fas fa-plus"></i>
                <span>Tambah Prestasi</span>
            </button>
        </div>

        <!-- Flash Messages -->
        @if (session()->has('success'))
            <div class="p-4 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-200 rounded-lg flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.style.display='none'" class="text-lg">&times;</button>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="p-4 bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-200 rounded-lg flex items-center justify-between">
                <span>{{ session('error') }}</span>
                <button onclick="this.parentElement.style.display='none'" class="text-lg">&times;</button>
            </div>
        @endif

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 md:p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 dark:text-gray-400 text-sm md:text-base">Total Prestasi</p>
                        <p class="text-3xl md:text-4xl font-bold text-blue-600 dark:text-blue-400 mt-1">
                            {{ $totalPrestasi }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                        <i class="fas fa-trophy text-blue-600 dark:text-blue-400 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 md:p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 dark:text-gray-400 text-sm md:text-base">Nasional</p>
                        <p class="text-3xl md:text-4xl font-bold text-green-600 dark:text-green-400 mt-1">
                            {{ $nasionalCount }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                        <i class="fas fa-medal text-green-600 dark:text-green-400 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 md:p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 dark:text-gray-400 text-sm md:text-base">Provinsi</p>
                        <p class="text-3xl md:text-4xl font-bold text-purple-600 dark:text-purple-400 mt-1">
                            {{ $provinsiCount }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900 rounded-full flex items-center justify-center">
                        <i class="fas fa-award text-purple-600 dark:text-purple-400 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 md:p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 dark:text-gray-400 text-sm md:text-base">Kabupaten</p>
                        <p class="text-3xl md:text-4xl font-bold text-orange-600 dark:text-orange-400 mt-1">
                            {{ $kabupatenCount }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900 rounded-full flex items-center justify-center">
                        <i class="fas fa-star text-orange-600 dark:text-orange-400 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="p-4 md:p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="space-y-3 md:space-y-0 md:flex md:gap-4">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Cari Prestasi
                        </label>
                        <input 
                            type="text" 
                            wire:model.live.debounce.300ms="search"
                            placeholder="Nama prestasi, penyelenggara, atau siswa..." 
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                        >
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Jenis Prestasi
                        </label>
                        <select 
                            wire:model.live="jenisFilter" 
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                        >
                            <option value="">Semua Jenis</option>
                            <option value="akademik">Akademik</option>
                            <option value="olahraga">Olahraga</option>
                            <option value="seni">Seni</option>
                            <option value="non-akademik">Non-Akademik</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Tingkat
                        </label>
                        <select 
                            wire:model.live="tingkatFilter" 
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                        >
                            <option value="">Semua Tingkat</option>
                            <option value="sekolah">Sekolah</option>
                            <option value="kecamatan">Kecamatan</option>
                            <option value="kabupaten">Kabupaten</option>
                            <option value="provinsi">Provinsi</option>
                            <option value="nasional">Nasional</option>
                            <option value="internasional">Internasional</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr class="text-xs md:text-sm">
                            <th class="px-4 md:px-6 py-3 text-left font-semibold text-gray-700 dark:text-gray-300">Siswa</th>
                            <th class="px-4 md:px-6 py-3 text-left font-semibold text-gray-700 dark:text-gray-300 hidden sm:table-cell">Prestasi</th>
                            <th class="px-4 md:px-6 py-3 text-left font-semibold text-gray-700 dark:text-gray-300 hidden md:table-cell">Jenis</th>
                            <th class="px-4 md:px-6 py-3 text-left font-semibold text-gray-700 dark:text-gray-300">Tingkat</th>
                            <th class="px-4 md:px-6 py-3 text-left font-semibold text-gray-700 dark:text-gray-300 hidden lg:table-cell">Tanggal</th>
                            <th class="px-4 md:px-6 py-3 text-center font-semibold text-gray-700 dark:text-gray-300">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($prestasi as $item)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 text-xs md:text-sm transition">
                            <!-- Siswa -->
                            <td class="px-4 md:px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center flex-shrink-0">
                                        <span class="text-white font-bold text-xs md:text-sm">
                                            {{ substr($item->siswa->nama, 0, 1) }}
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="font-medium text-gray-900 dark:text-white truncate">
                                            {{ $item->siswa->nama }}
                                        </p>
                                        <p class="text-gray-500 dark:text-gray-400 text-xs truncate">
                                            {{ $item->siswa->kelas->nama_kelas ?? 'N/A' }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            <!-- Prestasi (hidden on mobile) -->
                            <td class="px-4 md:px-6 py-4 hidden sm:table-cell">
                                <p class="font-medium text-gray-900 dark:text-white truncate">
                                    {{ $item->nama_prestasi }}
                                </p>
                                <p class="text-gray-500 dark:text-gray-400 text-xs truncate">
                                    {{ $item->penyelenggara }}
                                </p>
                                @if($item->peringkat)
                                    <p class="text-xs text-green-600 dark:text-green-400 font-medium mt-1">
                                        {{ $item->peringkat }}
                                    </p>
                                @endif
                            </td>

                            <!-- Jenis (hidden on mobile) -->
                            <td class="px-4 md:px-6 py-4 hidden md:table-cell">
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                    {{ ucfirst($item->jenis_prestasi) }}
                                </span>
                            </td>

                            <!-- Tingkat -->
                            <td class="px-4 md:px-6 py-4">
                                @php
                                    $tingkatConfig = [
                                        'sekolah' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'darkBg' => 'dark:bg-gray-700', 'darkText' => 'dark:text-gray-300'],
                                        'kecamatan' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'darkBg' => 'dark:bg-blue-900', 'darkText' => 'dark:text-blue-300'],
                                        'kabupaten' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-800', 'darkBg' => 'dark:bg-orange-900', 'darkText' => 'dark:text-orange-300'],
                                        'provinsi' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-800', 'darkBg' => 'dark:bg-purple-900', 'darkText' => 'dark:text-purple-300'],
                                        'nasional' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'darkBg' => 'dark:bg-green-900', 'darkText' => 'dark:text-green-300'],
                                        'internasional' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'darkBg' => 'dark:bg-red-900', 'darkText' => 'dark:text-red-300']
                                    ];
                                    $config = $tingkatConfig[$item->tingkat] ?? $tingkatConfig['sekolah'];
                                @endphp
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-semibold {{ $config['bg'] }} {{ $config['text'] }} {{ $config['darkBg'] }} {{ $config['darkText'] }}">
                                    {{ ucfirst($item->tingkat) }}
                                </span>
                            </td>

                            <!-- Tanggal (hidden on mobile) -->
                            <td class="px-4 md:px-6 py-4 hidden lg:table-cell text-gray-600 dark:text-gray-400">
                                {{ $item->tanggal_prestasi->format('d M Y') }}
                            </td>

                            <!-- Actions -->
                            <td class="px-4 md:px-6 py-4">
                                <div class="flex flex-wrap gap-1 justify-center">
                                    <button 
                                        wire:click="openEditForm({{ $item->id }})"
                                        class="p-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg text-xs transition"
                                        title="Edit Prestasi"
                                    >
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button 
                                        wire:click="toggleStatus({{ $item->id }})"
                                        class="p-2 {{ $item->status ? 'bg-green-500 hover:bg-green-600' : 'bg-yellow-500 hover:bg-yellow-600' }} text-white rounded-lg text-xs transition"
                                        title="{{ $item->status ? 'Nonaktifkan' : 'Aktifkan' }}"
                                    >
                                        <i class="fas {{ $item->status ? 'fa-eye' : 'fa-eye-slash' }}"></i>
                                    </button>
                                    <button 
                                        wire:click="deletePrestasi({{ $item->id }})"
                                        wire:confirm="Apakah Anda yakin ingin menghapus prestasi ini?"
                                        class="p-2 bg-red-500 hover:bg-red-600 text-white rounded-lg text-xs transition"
                                        title="Hapus Prestasi"
                                    >
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-4 md:px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-400 dark:text-gray-500">
                                    <i class="fas fa-trophy text-4xl mb-3"></i>
                                    <p class="text-sm font-medium">Tidak ada data prestasi</p>
                                    <p class="text-xs mt-1">Saat ini belum ada prestasi yang tercatat</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination & Settings -->
            <div class="p-4 md:p-6 border-t border-gray-200 dark:border-gray-700">
                <div class="flex flex-col sm:flex-row gap-4 items-center justify-between">
                    <div class="flex items-center gap-2">
                        <label class="text-sm text-gray-600 dark:text-gray-400">Per halaman:</label>
                        <select 
                            wire:model.live="perPage"
                            class="px-3 py-1 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white text-sm focus:ring-2 focus:ring-blue-500 outline-none"
                        >
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                    </div>
                    <div class="w-full sm:w-auto">
                        {{ $prestasi->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Modal -->
    @if($showForm)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-4xl max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-4 md:px-6 py-4 flex justify-between items-center">
                <h2 class="text-lg md:text-xl font-bold text-gray-900 dark:text-white">
                    {{ $formType === 'create' ? 'Tambah Prestasi Siswa' : 'Edit Prestasi Siswa' }}
                </h2>
                <button 
                    wire:click="closeForm"
                    class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 transition"
                >
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form wire:submit="savePrestasi" class="p-4 md:p-6 space-y-4">
                @if($errors->any())
                    <div class="p-3 bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-200 rounded-lg">
                        <ul class="list-disc list-inside text-sm">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <!-- Siswa -->
                    <div class="lg:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Siswa <span class="text-red-500">*</span>
                        </label>
                        <select 
                            wire:model="siswa_id"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                            required
                        >
                            <option value="">Pilih Siswa</option>
                            @foreach($siswaList as $siswa)
                                <option value="{{ $siswa->id }}">
                                    {{ $siswa->nis }} - {{ $siswa->nama }} ({{ $siswa->kelas->nama_kelas ?? '-' }})
                                </option>
                            @endforeach
                        </select>
                        @error('siswa_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Jenis Prestasi -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Jenis Prestasi <span class="text-red-500">*</span>
                        </label>
                        <select 
                            wire:model="jenis_prestasi"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                            required
                        >
                            @foreach($jenisList as $jenis)
                                <option value="{{ $jenis }}">{{ ucfirst($jenis) }}</option>
                            @endforeach
                        </select>
                        @error('jenis_prestasi') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Tingkat -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Tingkat <span class="text-red-500">*</span>
                        </label>
                        <select 
                            wire:model="tingkat"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                            required
                        >
                            @foreach($tingkatList as $tingkatOption)
                                <option value="{{ $tingkatOption }}">{{ ucfirst($tingkatOption) }}</option>
                            @endforeach
                        </select>
                        @error('tingkat') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Nama Prestasi -->
                    <div class="lg:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Nama Prestasi <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            wire:model="nama_prestasi"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                            placeholder="Contoh: Juara 1 Olimpiade Matematika"
                            required
                        >
                        @error('nama_prestasi') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Penyelenggara -->
                    <div class="lg:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Penyelenggara <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            wire:model="penyelenggara"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                            placeholder="Contoh: Dinas Pendidikan Kota"
                            required
                        >
                        @error('penyelenggara') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Tanggal Prestasi -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Tanggal Prestasi <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="date" 
                            wire:model="tanggal_prestasi"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                            required
                        >
                        @error('tanggal_prestasi') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Peringkat -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Peringkat
                        </label>
                        <select 
                            wire:model="peringkat"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                        >
                            <option value="">Pilih Peringkat</option>
                            @foreach($peringkatList as $peringkatOption)
                                <option value="{{ $peringkatOption }}">{{ $peringkatOption }}</option>
                            @endforeach
                        </select>
                        @error('peringkat') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Sertifikat -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Sertifikat
                        </label>
                        <input 
                            type="file" 
                            wire:model="sertifikat"
                            accept=".pdf,.jpg,.jpeg,.png"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                        >
                        @error('sertifikat') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Foto -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Foto
                        </label>
                        <input 
                            type="file" 
                            wire:model="foto"
                            accept="image/*"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                        >
                        @error('foto') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Status -->
                    <div class="lg:col-span-2">
                        <label class="flex items-center">
                            <input 
                                type="checkbox" 
                                wire:model="status"
                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                            >
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Aktif</span>
                        </label>
                    </div>
                </div>

                <!-- Deskripsi -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Deskripsi
                    </label>
                    <textarea 
                        wire:model="deskripsi"
                        rows="4"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                        placeholder="Deskripsi detail tentang prestasi yang diraih..."
                    ></textarea>
                    @error('deskripsi') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button 
                        type="button" 
                        wire:click="closeForm"
                        class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 font-medium transition"
                    >
                        Batal
                    </button>
                    <button 
                        type="submit"
                        class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition"
                    >
                        {{ $formType === 'create' ? 'Simpan' : 'Update' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>