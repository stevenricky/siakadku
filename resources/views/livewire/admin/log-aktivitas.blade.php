<div class="p-4 sm:p-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">Log Aktivitas</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Riwayat aktivitas sistem</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-2">
            <button wire:click="exportLogs" 
                    class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition flex items-center justify-center">
                <i class="fas fa-download mr-2"></i>Export Log
            </button>
            <button wire:click="clearLogs" 
                    onclick="return confirm('Yakin ingin membersihkan semua log?')"
                    class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition flex items-center justify-center">
                <i class="fas fa-trash mr-2"></i>Bersihkan Log
            </button>
        </div>
    </div>

   <!-- Di bagian filter -->
<div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
    <div class="p-4 border-b border-gray-200 dark:border-gray-700">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="lg:col-span-2">
                <input type="text" 
                       wire:model.live="search"
                       placeholder="Cari user, aksi, deskripsi, atau IP..." 
                       class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
            </div>
            <div>
                <select wire:model.live="userFilter" 
                        class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    <option value="">Semua User</option>
                    @foreach($users as $id => $name)
                    <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <select wire:model.live="actionFilter" 
                        class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    <option value="">Semua Aksi</option>
                    @foreach($actions as $key => $label)
                    <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
            <div>
                <input type="date" 
                       wire:model.live="dateFilter"
                       class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
            </div>
            <div>
                <select wire:model.live="perPage" 
                        class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    <option value="10">10 per halaman</option>
                    <option value="20">20 per halaman</option>
                    <option value="50">50 per halaman</option>
                    <option value="100">100 per halaman</option>
                </select>
            </div>
            <div class="flex items-center">
                <button wire:click="refreshData" 
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center justify-center">
                    <i class="fas fa-sync-alt mr-2"></i>Refresh
                </button>
            </div>
        </div>
    </div>
</div>

    <!-- Tabel Log -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            User
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Aksi
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Deskripsi
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            IP Address
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Waktu
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                    @forelse($logs as $log)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-4 py-4">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $log['user'] }}
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                @if($log['action'] === 'Login') bg-green-100 text-green-800
                                @elseif($log['action'] === 'Input Nilai') bg-blue-100 text-blue-800
                                @elseif($log['action'] === 'Update Data') bg-yellow-100 text-yellow-800
                                @elseif($log['action'] === 'Hapus Data') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ $log['action'] }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-sm text-gray-900 dark:text-white">
                            {{ $log['description'] }}
                        </td>
                        <td class="px-4 py-4 text-sm text-gray-900 dark:text-white">
                            <code class="text-xs bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">
                                {{ $log['ip_address'] }}
                            </code>
                        </td>
                        <td class="px-4 py-4 text-sm text-gray-900 dark:text-white">
                            <div class="flex flex-col">
                                <span>{{ $log['timestamp']->format('d M Y') }}</span>
                                <span class="text-xs text-gray-500">{{ $log['timestamp']->format('H:i:s') }}</span>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-clipboard-list text-2xl sm:text-3xl mb-2"></i>
                                <p class="text-sm sm:text-base">Tidak ada data log aktivitas</p>
                                <p class="text-xs sm:text-sm mt-1">Data akan muncul ketika ada aktivitas sistem</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($logs->hasPages())
        <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
            {{ $logs->links() }}
        </div>
        @endif
    </div>

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
             class="fixed top-4 right-4 bg-green-500 text-white px-4 py-3 rounded-lg shadow-lg z-50 max-w-sm">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <span class="text-sm">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if (session()->has('info'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
             class="fixed top-4 right-4 bg-blue-500 text-white px-4 py-3 rounded-lg shadow-lg z-50 max-w-sm">
            <div class="flex items-center">
                <i class="fas fa-info-circle mr-2"></i>
                <span class="text-sm">{{ session('info') }}</span>
            </div>
        </div>
    @endif
</div>