<div>
    <div class="py-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:text-3xl">
                    Kirim Pesan Baru
                </h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Kirim pesan internal ke guru, siswa, atau semua pengguna
                </p>
            </div>

            <!-- Flash Messages -->
            @if (session()->has('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative dark:bg-green-900 dark:border-green-600 dark:text-green-200">
                    {{ session('success') }}
                </div>
            @endif

            @if (session()->has('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative dark:bg-red-900 dark:border-red-600 dark:text-red-200">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Form -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <form wire:submit="kirimPesan">
                    <!-- Tipe Penerima -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Kirim Kepada
                        </label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <label class="flex items-center">
                                <input type="radio" wire:model="tipePenerima" value="guru" class="mr-2">
                                <span class="text-sm text-gray-700 dark:text-gray-300">Semua Guru</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" wire:model="tipePenerima" value="siswa" class="mr-2">
                                <span class="text-sm text-gray-700 dark:text-gray-300">Semua Siswa</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" wire:model="tipePenerima" value="semua" class="mr-2">
                                <span class="text-sm text-gray-700 dark:text-gray-300">Semua User</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" wire:model="tipePenerima" value="individu" class="mr-2">
                                <span class="text-sm text-gray-700 dark:text-gray-300">Individu</span>
                            </label>
                        </div>
                    </div>

                    <!-- Penerima Individu -->
                    @if($tipePenerima === 'individu')
                    <div class="mb-6">
                        <label for="penerima_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Pilih Penerima
                        </label>
                        <select wire:model="penerima_id" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            <option value="">Pilih Penerima</option>
                            <optgroup label="Guru">
                                @foreach($gurus as $guru)
                                <option value="{{ $guru->id }}">{{ $guru->name }} - {{ $guru->email }}</option>
                                @endforeach
                            </optgroup>
                            <optgroup label="Siswa">
                                @foreach($siswas as $siswa)
                                <option value="{{ $siswa->id }}">{{ $siswa->name }} - {{ $siswa->email }}</option>
                                @endforeach
                            </optgroup>
                        </select>
                        @error('penerima_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    @endif

                    <!-- Subjek -->
                    <div class="mb-6">
                        <label for="subjek" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Subjek Pesan
                        </label>
                        <input type="text" 
                               wire:model="subjek"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                               placeholder="Masukkan subjek pesan">
                        @error('subjek') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Pesan -->
                    <div class="mb-6">
                        <label for="pesan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Isi Pesan
                        </label>
                        <textarea wire:model="pesan" 
                                  rows="6"
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                  placeholder="Tulis isi pesan di sini..."></textarea>
                        @error('pesan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('admin.pesan.index') }}" 
                           class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Kembali
                        </a>
                        <button type="submit"
                                class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Kirim Pesan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>