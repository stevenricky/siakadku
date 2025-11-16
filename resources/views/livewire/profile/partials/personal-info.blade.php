@if($activeTab === 'personal')
<!-- Informasi Pribadi -->
<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden"
     x-data="personalInfo()"
     x-show="activeTab === 'personal'"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 transform scale-95"
     x-transition:enter-end="opacity-100 transform scale-100">
    <div class="bg-gradient-to-r from-violet-600 to-indigo-600 px-6 py-4">
        <h3 class="text-lg font-semibold text-white flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            Informasi Pribadi
        </h3>
    </div>
    
    <div class="p-6">
        <form wire:submit="updateProfile">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Lengkap -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Nama Lengkap *
                    </label>
                    <input 
                        wire:model.live="name"
                        type="text" 
                        required
                        class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-slate-700 dark:text-white transition-all duration-300"
                        x-bind:class="{ 'border-rose-500': errors.name }"
                    >
                    <template x-if="errors.name">
                        <p class="mt-1 text-sm text-rose-600 animate-pulse" x-text="errors.name[0]"></p>
                    </template>
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Email *
                    </label>
                    <input 
                        wire:model.live="email"
                        type="email" 
                        required
                        class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-slate-700 dark:text-white transition-all duration-300"
                        x-bind:class="{ 'border-rose-500': errors.email }"
                    >
                    <template x-if="errors.email">
                        <p class="mt-1 text-sm text-rose-600 animate-pulse" x-text="errors.email[0]"></p>
                    </template>
                </div>

                <!-- Data Spesifik Berdasarkan Role -->
                @if(auth()->user()->isSiswa() && $roleData)
                    <!-- NIS -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            NIS
                        </label>
                        <input 
                            type="text" 
                            value="{{ $roleData->nis }}" 
                            readonly
                            class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl bg-slate-50 dark:bg-slate-600 dark:text-white cursor-not-allowed"
                        >
                    </div>

                    <!-- NISN -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            NISN
                        </label>
                        <input 
                            type="text" 
                            value="{{ $roleData->nisn }}" 
                            readonly
                            class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl bg-slate-50 dark:bg-slate-600 dark:text-white cursor-not-allowed"
                        >
                    </div>

                @elseif(auth()->user()->isGuru() && $roleData)
                    <!-- NIP -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            NIP
                        </label>
                        <input 
                            type="text" 
                            value="{{ $roleData->nip }}" 
                            readonly
                            class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl bg-slate-50 dark:bg-slate-600 dark:text-white cursor-not-allowed"
                        >
                    </div>

                    <!-- Status Guru -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Status
                        </label>
                        <input 
                            type="text" 
                            value="{{ ucfirst($roleData->status) }}" 
                            readonly
                            class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl bg-slate-50 dark:bg-slate-600 dark:text-white cursor-not-allowed"
                        >
                    </div>
                @endif

                <!-- Role -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Role
                    </label>
                    <input 
                        type="text" 
                        value="{{ ucfirst(auth()->user()->role) }}" 
                        readonly
                        class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl bg-slate-50 dark:bg-slate-600 dark:text-white cursor-not-allowed"
                    >
                </div>

                <!-- Bergabung Sejak -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Bergabung Sejak
                    </label>
                    <input 
                        type="text" 
                        value="{{ auth()->user()->created_at->format('d F Y') }}" 
                        readonly
                        class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl bg-slate-50 dark:bg-slate-600 dark:text-white cursor-not-allowed"
                    >
                </div>
            </div>

            <!-- Save Button -->
            <div class="flex items-center justify-between mt-8 pt-6 border-t border-slate-200 dark:border-slate-700">
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    <span x-show="isChanged" class="text-amber-600 animate-pulse">
                        ⚠ Ada perubahan yang belum disimpan
                    </span>
                </div>
                <button type="submit" 
                        x-bind:disabled="!isChanged"
                        class="bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-700 hover:to-indigo-700 text-white px-8 py-3 rounded-xl font-semibold shadow-lg transition-all duration-300 transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                        x-bind:class="{ 'opacity-50 cursor-not-allowed': !isChanged }">
                    <span wire:loading.remove wire:target="updateProfile">Simpan Perubahan</span>
                    <span wire:loading wire:target="updateProfile" class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Menyimpan...
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function personalInfo() {
    return {
        activeTab: @entangle('activeTab'),
        isChanged: false,
        errors: {},
        
        init() {
            // Listen untuk perubahan data
            Livewire.on('profileDataRefreshed', () => {
                this.isChanged = false;
            });
            
            // Listen untuk validation errors
            Livewire.on('validationErrors', (errors) => {
                this.errors = errors;
            });
            
            // Cek perubahan form
            const inputs = this.$el.querySelectorAll('input:not([readonly])');
            inputs.forEach(input => {
                input.addEventListener('input', () => {
                    this.isChanged = true;
                });
            });
        }
    }
}
</script>
@endif