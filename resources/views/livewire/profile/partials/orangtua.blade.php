@if($activeTab === 'orangtua' && auth()->user()->isSiswa())
<!-- Data Orang Tua -->
<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden"
     x-data="orangTuaForm()"
     x-show="activeTab === 'orangtua'"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 transform scale-95"
     x-transition:enter-end="opacity-100 transform scale-100">
    <div class="bg-gradient-to-r from-blue-600 to-cyan-600 px-6 py-4">
        <h3 class="text-lg font-semibold text-white flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            Data Orang Tua
        </h3>
    </div>
    
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Data Ayah -->
            <div class="space-y-6">
                <h4 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Data Ayah
                </h4>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Nama Ayah
                        </label>
                        <input 
                            type="text" 
                            value="{{ $roleData->nama_ayah ?? 'Belum diisi' }}" 
                            readonly
                            class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl bg-slate-50 dark:bg-slate-600 dark:text-white cursor-not-allowed"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Pekerjaan Ayah
                        </label>
                        <input 
                            type="text" 
                            value="{{ $roleData->pekerjaan_ayah ?? 'Belum diisi' }}" 
                            readonly
                            class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl bg-slate-50 dark:bg-slate-600 dark:text-white cursor-not-allowed"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            No. Telepon Ayah
                        </label>
                        <input 
                            type="text" 
                            value="{{ $roleData->telp_ayah ?? 'Belum diisi' }}" 
                            readonly
                            class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl bg-slate-50 dark:bg-slate-600 dark:text-white cursor-not-allowed"
                        >
                    </div>
                </div>
            </div>

            <!-- Data Ibu -->
            <div class="space-y-6">
                <h4 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                    <svg class="w-5 h-5 mr-2 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Data Ibu
                </h4>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Nama Ibu
                        </label>
                        <input 
                            type="text" 
                            value="{{ $roleData->nama_ibu ?? 'Belum diisi' }}" 
                            readonly
                            class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl bg-slate-50 dark:bg-slate-600 dark:text-white cursor-not-allowed"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Pekerjaan Ibu
                        </label>
                        <input 
                            type="text" 
                            value="{{ $roleData->pekerjaan_ibu ?? 'Belum diisi' }}" 
                            readonly
                            class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl bg-slate-50 dark:bg-slate-600 dark:text-white cursor-not-allowed"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            No. Telepon Ibu
                        </label>
                        <input 
                            type="text" 
                            value="{{ $roleData->telp_ibu ?? 'Belum diisi' }}" 
                            readonly
                            class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl bg-slate-50 dark:bg-slate-600 dark:text-white cursor-not-allowed"
                        >
                    </div>
                </div>
            </div>
        </div>

        <!-- Alamat Orang Tua -->
        <div class="mt-6 pt-6 border-t border-slate-200 dark:border-slate-700">
            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Alamat Orang Tua</h4>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Alamat Lengkap
                </label>
                <textarea 
                    readonly
                    class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl bg-slate-50 dark:bg-slate-600 dark:text-white cursor-not-allowed resize-none"
                    rows="3"
                >{{ $roleData->alamat_ortu ?? 'Belum diisi' }}</textarea>
            </div>
        </div>

        <!-- Info -->
        <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-200 dark:border-blue-800">
            <div class="flex items-start space-x-3">
                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="text-sm text-blue-700 dark:text-blue-300">
                    <p class="font-medium">Informasi Data Orang Tua</p>
                    <p class="mt-1">Data orang tua dapat diubah melalui administrator sekolah. Silakan hubungi pihak sekolah untuk perubahan data.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function orangTuaForm() {
    return {
        activeTab: @entangle('activeTab')
    }
}
</script>
@endif