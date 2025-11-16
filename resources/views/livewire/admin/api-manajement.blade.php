    <div class="p-4 sm:p-6">
        <!-- Header dengan Stats -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">Manajemen API</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Kelola API keys dan monitor penggunaan</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="bg-white dark:bg-gray-800 rounded-lg p-3 shadow">
                    <div class="text-2xl font-bold text-blue-600">{{ $stats['today_requests'] }}</div>
                    <div class="text-sm text-gray-500">Request Hari Ini</div>
                </div>
                <button 
                    wire:click="$set('showCreateModal', true)"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center transition-colors">
                    <i class="fas fa-plus mr-2"></i>Buat API Key
                </button>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cari</label>
                    <input 
                        type="text" 
                        wire:model.live="search"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                        placeholder="Cari API key...">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                    <select wire:model.live="statusFilter" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">
                        <option value="">Semua Status</option>
                        <option value="active">Aktif</option>
                        <option value="inactive">Nonaktif</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Dari Tanggal</label>
                    <input type="date" wire:model="dateFrom" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sampai Tanggal</label>
                    <input type="date" wire:model="dateTo" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg">
                </div>
            </div>
        </div>

        <!-- API Keys List dengan Enhanced Features -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="p-4 sm:p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Daftar API Keys</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                            Total {{ $stats['total'] }} API key ({{ $stats['active'] }} aktif)
                        </p>
                    </div>
                    <button 
                        wire:click="exportLogs"
                        class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-lg flex items-center text-sm">
                        <i class="fas fa-download mr-2"></i>Export Logs
                    </button>
                </div>
            </div>
            
            <div class="p-4 sm:p-6">
                @if(count($apiKeys) > 0)
                    <div class="space-y-4">
                        @foreach($apiKeys as $key)
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3">
                                        <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                                            <i class="fas fa-key text-blue-600 dark:text-blue-400"></i>
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3">
                                                <h4 class="font-semibold text-gray-900 dark:text-white">{{ $key['name'] }}</h4>
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                                    @if($key['status'] === 'active') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                                    @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300 @endif">
                                                    {{ $key['status'] === 'active' ? 'Aktif' : 'Nonaktif' }}
                                                </span>
                                            </div>
                                            
                                            <div class="mt-2 space-y-2">
    <!-- Full API Key dengan Toggle Visibility -->
    <div x-data="{ 
        showKey: false, 
        copied: false,
        apiKey: '{{ $key->api_key }}'
    }">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            API Key:
        </label>
        
        <div class="flex items-center gap-2">
            <!-- Input Field dengan Toggle -->
            <div class="flex-1 relative">
                <input 
                    :type="showKey ? 'text' : 'password'"
                    :value="apiKey" 
                    readonly
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-sm font-mono pr-20">
                
                <!-- Toggle Visibility Button -->
                <button 
                    type="button"
                    @click="showKey = !showKey"
                    class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 p-1"
                    :title="showKey ? 'Sembunyikan key' : 'Tampilkan key'">
                    <i class="fas text-sm" :class="showKey ? 'fa-eye-slash' : 'fa-eye'"></i>
                </button>
            </div>
            
            <!-- Copy Button dengan Feedback -->
            <button 
                @click="
                    navigator.clipboard.writeText(apiKey);
                    copied = true;
                    setTimeout(() => copied = false, 2000);
                "
                :class="copied ? 'bg-green-600' : 'bg-blue-600 hover:bg-blue-700'"
                class="px-3 py-2 text-white rounded text-sm flex items-center whitespace-nowrap transition-colors">
                <i class="fas mr-2" :class="copied ? 'fa-check' : 'fa-copy'"></i>
                <span x-text="copied ? 'Disalin!' : 'Salin'"></span>
            </button>
        </div>
    </div>
    
    <!-- Info lainnya -->
    <p class="text-sm text-gray-500 dark:text-gray-400">
        Rate Limit: {{ $key->rate_limit }}/minute
        @if($key->expires_at)
        • Expires: {{ \Carbon\Carbon::parse($key->expires_at)->translatedFormat('d M Y') }}
        @endif
    </p>
    <p class="text-sm text-gray-500 dark:text-gray-400">
        Terakhir digunakan: 
        @if($key->last_used_at)
            {{ \Carbon\Carbon::parse($key->last_used_at)->translatedFormat('d M Y H:i') }}
        @else
            Belum pernah digunakan
        @endif
    </p>
</div>
                                        </div>
                                    </div>
                                </div>
<!-- Desktop Actions -->
<div class="hidden sm:flex items-center gap-2">
    <button 
        wire:click="showLogs({{ $key->id }})"
        class="inline-flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition-colors">
        <i class="fas fa-history mr-1"></i>Logs
    </button>
    <button 
        wire:click="toggleKeyStatus({{ $key->id }})"
        class="inline-flex items-center px-3 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm rounded-lg transition-colors">
        <i class="fas fa-power-off mr-1"></i>
        {{ $key->status === 'active' ? 'Nonaktifkan' : 'Aktifkan' }}
    </button>
    <button 
        wire:click="regenerateKey({{ $key->id }})"
        wire:confirm="Yakin ingin regenerasi API key ini?"
        class="inline-flex items-center px-3 py-2 bg-orange-600 hover:bg-orange-700 text-white text-sm rounded-lg transition-colors">
        <i class="fas fa-sync-alt mr-1"></i>Regenerasi
    </button>
    <button 
        wire:click="deleteKey({{ $key->id }})"
        wire:confirm="Yakin ingin menghapus API key ini?"
        class="inline-flex items-center px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-sm rounded-lg transition-colors">
        <i class="fas fa-trash mr-1"></i>Hapus
    </button>
</div>

<!-- Mobile Actions - Stack Vertical -->
<div class="sm:hidden flex flex-col w-full gap-2">
    <button 
        wire:click="showLogs({{ $key->id }})"
        class="w-full flex items-center justify-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition-colors">
        <i class="fas fa-history mr-2"></i>Lihat Logs
    </button>
    <div class="grid grid-cols-3 gap-2">
        <button 
            wire:click="toggleKeyStatus({{ $key->id }})"
            class="flex items-center justify-center px-2 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-xs rounded-lg transition-colors"
            title="{{ $key->status === 'active' ? 'Nonaktifkan' : 'Aktifkan' }}">
            <i class="fas fa-power-off"></i>
        </button>
        <button 
            wire:click="regenerateKey({{ $key->id }})"
            wire:confirm="Yakin ingin regenerasi API key ini?"
            class="flex items-center justify-center px-2 py-2 bg-orange-600 hover:bg-orange-700 text-white text-xs rounded-lg transition-colors"
            title="Regenerasi">
            <i class="fas fa-sync-alt"></i>
        </button>
        <button 
            wire:click="deleteKey({{ $key->id }})"
            wire:confirm="Yakin ingin menghapus API key ini?"
            class="flex items-center justify-center px-2 py-2 bg-red-600 hover:bg-red-700 text-white text-xs rounded-lg transition-colors"
            title="Hapus">
            <i class="fas fa-trash"></i>
        </button>
    </div>
</div>
                            
                            <!-- Permissions Summary -->
    @if(!empty($key['permissions']))
    <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-600">
        <h5 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Permissions:</h5>
        <div class="flex flex-wrap gap-1">
            @php
                $permissions = is_string($key['permissions']) ? json_decode($key['permissions'], true) : $key['permissions'];
            @endphp
            
            @if(is_array($permissions))
                @foreach($permissions as $endpoint => $actions)
                    @if(is_array($actions))
                        @foreach($actions as $action => $allowed)
                            @if($allowed)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                    {{ $endpoint }}:{{ $action }}
                                </span>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            @endif
        </div>
    </div>
    @endif
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="bg-gray-100 dark:bg-gray-700 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-key text-gray-400 text-2xl"></i>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400">Belum ada API key</p>
                        <p class="text-sm text-gray-400 mt-1">Buat API key pertama untuk memulai integrasi</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Enhanced Documentation Section -->
        <div class="mt-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- API Endpoints -->
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-blue-800 dark:text-blue-400 mb-4 flex items-center">
                    <i class="fas fa-code mr-2"></i>Dokumentasi Endpoint
                </h3>
                <div class="space-y-3">
                    @foreach($availableEndpoints as $endpoint => $actions)
                    <div class="bg-white dark:bg-blue-800/30 rounded-lg p-3">
                        <h4 class="font-medium text-blue-700 dark:text-blue-300 mb-2">
                            /api/{{ $endpoint }}
                        </h4>
                        <div class="flex flex-wrap gap-1">
                            @foreach($actions as $action)
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs 
                                @if($action === 'read') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                @elseif($action === 'write') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                                @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300 @endif">
                                {{ strtoupper($action) }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Usage Statistics & Quick Actions -->
            <div class="space-y-6">
                <!-- Quick Stats -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Statistik Penggunaan</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="text-2xl font-bold text-blue-600">{{ $stats['total'] }}</div>
                            <div class="text-sm text-gray-500">Total API Keys</div>
                        </div>
                        <div class="text-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="text-2xl font-bold text-green-600">{{ $stats['active'] }}</div>
                            <div class="text-sm text-gray-500">Aktif</div>
                        </div>
                        <div class="text-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="text-2xl font-bold text-orange-600">{{ $stats['today_requests'] }}</div>
                            <div class="text-sm text-gray-500">Request Hari Ini</div>
                        </div>
                        <div class="text-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="text-2xl font-bold text-purple-600">{{ $stats['inactive'] }}</div>
                            <div class="text-sm text-gray-500">Nonaktif</div>
                        </div>
                    </div>
                </div>

                <!-- Security Tips -->
                <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-yellow-800 dark:text-yellow-400 mb-3 flex items-center">
                        <i class="fas fa-shield-alt mr-2"></i>Tips Keamanan
                    </h3>
                    <ul class="space-y-2 text-yellow-700 dark:text-yellow-300 text-sm">
                        <li class="flex items-start">
                            <i class="fas fa-exclamation-triangle mr-2 mt-1 text-yellow-500"></i>
                            <span>Gunakan IP whitelist untuk production keys</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-exclamation-triangle mr-2 mt-1 text-yellow-500"></i>
                            <span>Set expiration date untuk keys sementara</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-exclamation-triangle mr-2 mt-1 text-yellow-500"></i>
                            <span>Monitor suspicious activity melalui logs</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

<!-- Enhanced Create API Key Modal -->
@if($showCreateModal)
<div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <!-- HEADER -->
        <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Buat API Key Baru</h3>
            <button 
                type="button"
                wire:click="$set('showCreateModal', false)"
                class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>
        
        <form wire:submit="createApiKey" class="p-6">
            <!-- Basic Information -->
            <div class="mb-6">
                <h4 class="font-medium text-gray-900 dark:text-white mb-4">Informasi Dasar</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Nama API Key *
                        </label>
                        <input 
                            type="text" 
                            wire:model="newKeyName"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                            placeholder="Contoh: Mobile App Production"
                            required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Rate Limit *
                        </label>
                        <input 
                            type="number" 
                            wire:model="rateLimit"
                            min="1" max="1000"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700"
                            value="100"
                            required>
                    </div>
                </div>
            </div>

            <!-- Permissions - Sederhana tanpa Alpine.js -->
            <div class="mb-6">
                <h4 class="font-medium text-gray-900 dark:text-white mb-4">Permissions</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($availableEndpoints as $endpoint => $actions)
                    <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-3">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-medium text-gray-900 dark:text-white capitalize">{{ $endpoint }}</span>
                            <label class="flex items-center text-sm">
                                <input 
                                    type="checkbox" 
                                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 mr-1"
                                    onclick="
                                        const checkboxes = this.closest('.border').querySelectorAll('input[type=\'checkbox\']:not([onclick])');
                                        checkboxes.forEach(cb => cb.checked = this.checked);
                                    ">
                                Pilih Semua
                            </label>
                        </div>
                        <div class="space-y-1">
                            @foreach($actions as $action => $description)
                            <label class="flex items-center">
                                <input 
                                    type="checkbox" 
                                    wire:model="permissions.{{ $endpoint }}.{{ $action }}"
                                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 mr-2">
                                <span class="text-sm text-gray-700 dark:text-gray-300">{{ $description }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Security Settings -->
            <div class="mb-6">
                <h4 class="font-medium text-gray-900 dark:text-white mb-4">Pengaturan Keamanan</h4>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            IP Whitelist (opsional)
                        </label>
                        <input 
                            type="text" 
                            wire:model="allowedIps"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700"
                            placeholder="Contoh: 192.168.1.1, 10.0.0.1">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Tanggal Kedaluwarsa
                            </label>
                            <input 
                                type="date" 
                                wire:model="expiresAt"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Webhook URL (opsional)
                            </label>
                            <input 
                                type="url" 
                                wire:model="webhookUrl"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700"
                                placeholder="https://example.com/webhook">
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                <button 
                    type="button"
                    wire:click="$set('showCreateModal', false)"
                    class="px-4 py-2 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    Batal
                </button>
                <button 
                    type="submit"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                    Buat API Key
                </button>
            </div>
        </form>
    </div>
</div>
@endif

        

    <!-- API Logs Modal -->
    @if($showLogsModal && $selectedApiKey)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Logs - {{ $selectedApiKey->name }}
                    </h3>
                    <button wire:click="closeLogsModal" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="p-6">
@if($selectedApiKey->logs->count() > 0)
<!-- Desktop Table -->
<div class="hidden md:block overflow-x-auto">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 dark:bg-gray-700">
            <tr>
                <th class="px-4 py-2">Timestamp</th>
                <th class="px-4 py-2">Endpoint</th>
                <th class="px-4 py-2">Method</th>
                <th class="px-4 py-2">Status</th>
                <th class="px-4 py-2">Response Time</th>
                <th class="px-4 py-2">IP Address</th>
            </tr>
        </thead>
        <tbody>
            @foreach($selectedApiKey->logs as $log)
            <tr class="border-b dark:border-gray-600">
                <td class="px-4 py-2">{{ $log->requested_at->format('d M Y H:i:s') }}</td>
                <td class="px-4 py-2">{{ $log->endpoint }}</td>
                <td class="px-4 py-2">
                    <span class="px-2 py-1 rounded text-xs 
                        @if($log->method === 'GET') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300
                        @elseif($log->method === 'POST') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                        @elseif($log->method === 'PUT') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                        @elseif($log->method === 'DELETE') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                        @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 @endif">
                        {{ $log->method }}
                    </span>
                </td>
                <td class="px-4 py-2">
                    <span class="px-2 py-1 rounded text-xs 
                        @if($log->response_code >= 200 && $log->response_code < 300) bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                        @elseif($log->response_code >= 400) bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                        @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300 @endif">
                        {{ $log->response_code }}
                    </span>
                </td>
                <td class="px-4 py-2">{{ number_format($log->response_time, 2) }}s</td>
                <td class="px-4 py-2">{{ $log->ip_address }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Mobile Cards -->
<div class="md:hidden space-y-3">
    @foreach($selectedApiKey->logs as $log)
    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
        <div class="grid grid-cols-2 gap-2 text-sm">
            <div class="font-medium text-gray-700 dark:text-gray-300">Timestamp:</div>
            <div class="text-gray-900 dark:text-white">{{ $log->requested_at->format('d M Y H:i:s') }}</div>
            
            <div class="font-medium text-gray-700 dark:text-gray-300">Endpoint:</div>
            <div class="text-gray-900 dark:text-white break-all">{{ $log->endpoint }}</div>
            
            <div class="font-medium text-gray-700 dark:text-gray-300">Method:</div>
            <div>
                <span class="px-2 py-1 rounded text-xs 
                    @if($log->method === 'GET') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300
                    @elseif($log->method === 'POST') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                    @elseif($log->method === 'PUT') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                    @elseif($log->method === 'DELETE') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                    @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 @endif">
                    {{ $log->method }}
                </span>
            </div>
            
            <div class="font-medium text-gray-700 dark:text-gray-300">Status:</div>
            <div>
                <span class="px-2 py-1 rounded text-xs 
                    @if($log->response_code >= 200 && $log->response_code < 300) bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                    @elseif($log->response_code >= 400) bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                    @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300 @endif">
                    {{ $log->response_code }}
                </span>
            </div>
            
            <div class="font-medium text-gray-700 dark:text-gray-300">Response Time:</div>
            <div class="text-gray-900 dark:text-white">{{ number_format($log->response_time, 2) }}s</div>
            
            <div class="font-medium text-gray-700 dark:text-gray-300">IP Address:</div>
            <div class="text-gray-900 dark:text-white">{{ $log->ip_address }}</div>
        </div>
    </div>
    @endforeach
</div>
@else
<div class="text-center py-8">
    <div class="bg-gray-100 dark:bg-gray-700 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
        <i class="fas fa-history text-gray-400 text-2xl"></i>
    </div>
    <p class="text-gray-500 dark:text-gray-400">Belum ada logs</p>
    <p class="text-sm text-gray-400 mt-1">Tidak ada aktivitas API untuk key ini</p>
</div>
@endif
            </div>
        </div>
    </div>
    @endif