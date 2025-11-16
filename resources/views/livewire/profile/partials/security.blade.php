@if($activeTab === 'security')
<!-- Keamanan Akun -->
<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden"
     x-data="securitySettings()"
     x-show="activeTab === 'security'"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 transform scale-95"
     x-transition:enter-end="opacity-100 transform scale-100">
    <div class="bg-gradient-to-r from-amber-500 to-orange-500 px-6 py-4">
        <h3 class="text-lg font-semibold text-white flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
            Keamanan Akun
        </h3>
    </div>
    
    <div class="p-6">
        <!-- Ubah Password -->
        <div class="mb-8">
            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Ubah Password</h4>
            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                @method('put')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Current Password -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Password Saat Ini *
                        </label>
                        <div class="relative">
                            <input 
                                name="current_password" 
                                x-bind:type="showCurrentPassword ? 'text' : 'password'"
                                required
                                class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 dark:bg-slate-700 dark:text-white transition-all duration-300 pr-10"
                            >
                            <button type="button" 
                                    x-on:click="showCurrentPassword = !showCurrentPassword"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                                <svg x-show="!showCurrentPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg x-show="showCurrentPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L7.59 7.59m9.653 9.654l-2.828 2.828m-9.9-2.829l2.828-2.827m0 0l2.828-2.828"/>
                                </svg>
                            </button>
                        </div>
                        @error('current_password')
                            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- New Password -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Password Baru *
                        </label>
                        <div class="relative">
                            <input 
                                name="password" 
                                x-bind:type="showNewPassword ? 'text' : 'password'"
                                required
                                x-on:input="checkPasswordStrength"
                                class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 dark:bg-slate-700 dark:text-white transition-all duration-300 pr-10"
                            >
                            <button type="button" 
                                    x-on:click="showNewPassword = !showNewPassword"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                                <svg x-show="!showNewPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg x-show="showNewPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L7.59 7.59m9.653 9.654l-2.828 2.828m-9.9-2.829l2.828-2.827m0 0l2.828-2.828"/>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Strength Meter -->
                    <div x-show="passwordStrength > 0" class="md:col-span-2">
                        <div class="flex items-center space-x-2 mb-2">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Kekuatan Password:</span>
                            <span x-text="strengthText" 
                                  x-bind:class="{
                                      'text-rose-600': passwordStrength === 1,
                                      'text-amber-600': passwordStrength === 2,
                                      'text-emerald-600': passwordStrength >= 3
                                  }"
                                  class="text-sm font-semibold"></span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            <div x-bind:class="{
                                'bg-rose-500 w-1/4': passwordStrength === 1,
                                'bg-amber-500 w-2/4': passwordStrength === 2,
                                'bg-emerald-500 w-3/4': passwordStrength === 3,
                                'bg-emerald-600 w-full': passwordStrength === 4
                            }" 
                            class="h-2 rounded-full transition-all duration-300"></div>
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Konfirmasi Password Baru *
                        </label>
                        <div class="relative">
                            <input 
                                name="password_confirmation" 
                                x-bind:type="showConfirmPassword ? 'text' : 'password'"
                                required
                                class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 dark:bg-slate-700 dark:text-white transition-all duration-300 pr-10"
                            >
                            <button type="button" 
                                    x-on:click="showConfirmPassword = !showConfirmPassword"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                                <svg x-show="!showConfirmPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg x-show="showConfirmPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L7.59 7.59m9.653 9.654l-2.828 2.828m-9.9-2.829l2.828-2.827m0 0l2.828-2.828"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Save Password Button -->
                <div class="flex items-center justify-between mt-8 pt-6 border-t border-slate-200 dark:border-slate-700">
                    <div>
                        @if (session('status') === 'password-updated')
                            <p class="text-sm text-emerald-600 font-medium animate-pulse">
                                ✓ Password berhasil diubah
                            </p>
                        @endif
                    </div>
                    <button type="submit" 
                            class="bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white px-8 py-3 rounded-xl font-semibold shadow-lg transition-all duration-300 transform hover:scale-105">
                        Ubah Password
                    </button>
                </div>
            </form>
        </div>

        <!-- Sesi Aktif -->
        <div class="border-t border-slate-200 dark:border-slate-700 pt-6">
            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Sesi Aktif</h4>
            <div class="bg-slate-50 dark:bg-slate-700 rounded-xl p-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-3 h-3 bg-emerald-500 rounded-full animate-pulse"></div>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Sesi Saat Ini</span>
                    </div>
                    <span class="text-sm text-gray-500 dark:text-gray-400">
                        {{ \Carbon\Carbon::parse(auth()->user()->last_seen)->diffForHumans() }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function securitySettings() {
    return {
        activeTab: @entangle('activeTab'),
        showCurrentPassword: false,
        showNewPassword: false,
        showConfirmPassword: false,
        passwordStrength: 0,
        strengthText: '',

        checkPasswordStrength(event) {
            const password = event.target.value;
            let strength = 0;
            
            if (password.length >= 8) strength++;
            if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
            if (password.match(/\d/)) strength++;
            if (password.match(/[^a-zA-Z\d]/)) strength++;
            
            this.passwordStrength = strength;
            
            // Update strength text
            const texts = ['Sangat Lemah', 'Lemah', 'Cukup', 'Kuat', 'Sangat Kuat'];
            this.strengthText = texts[strength] || '';
        }
    }
}
</script>
@endif