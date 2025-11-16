<!-- Alpine.js Notification Panel Component -->
<div class="relative" 
     x-data="notificationPanel()" 
     x-init="init()">
    
    <!-- Notification Bell -->
    <button @click="toggleDropdown()" 
            :class="{'text-blue-600': open, 'text-gray-600 dark:text-gray-300': !open}"
            class="relative p-2 hover:text-primary-500 dark:hover:text-primary-400 transition-colors rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
        <i class="fas fa-bell text-lg"></i>
        
        <template x-if="unreadCount > 0">
            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center animate-pulse"
                  x-text="unreadCount > 9 ? '9+' : unreadCount"></span>
        </template>
    </button>

    <!-- Notification Dropdown -->
    <div x-show="open" 
         x-cloak
         @click.away="open = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-95"
         class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-50 max-h-96 overflow-hidden">
        
        <!-- Header -->
        <div class="p-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
            <div class="flex justify-between items-center">
                <h3 class="font-semibold text-gray-900 dark:text-white">Notifikasi</h3>
                <template x-if="unreadCount > 0">
                    <button @click="markAllAsRead()" 
                            class="text-xs text-primary-600 dark:text-primary-400 hover:text-primary-700 transition-colors">
                        Tandai semua dibaca
                    </button>
                </template>
            </div>
        </div>

        <!-- Notifications List -->
        <div class="overflow-y-auto max-h-64">
            <template x-if="isLoading">
                <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                    <i class="fas fa-spinner fa-spin text-2xl mb-2"></i>
                    <p class="text-sm">Memuat notifikasi...</p>
                </div>
            </template>

            <template x-if="!isLoading && notifications.length === 0">
                <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                    <i class="fas fa-bell-slash text-2xl mb-2"></i>
                    <p class="text-sm">Tidak ada notifikasi</p>
                </div>
            </template>

            <template x-if="!isLoading" x-for="notification in notifications" :key="notification.id">
                <div class="p-4 border-b border-gray-100 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                     :class="{'bg-blue-50 dark:bg-blue-900/20': !notification.is_read}">
                    <div class="flex items-start space-x-3">
                        <!-- Icon -->
                        <div class="flex-shrink-0 mt-1">
                            <i :class="getNotificationIcon(notification.type)"></i>
                        </div>
                        
                        <!-- Content -->
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white" 
                               x-text="notification.title"></p>
                            <p class="text-sm text-gray-600 dark:text-gray-300 mt-1" 
                               x-text="notification.message"></p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2" 
                               x-text="notification.created_at"></p>
                        </div>
                        
                        <!-- Mark as Read Button -->
                        <template x-if="!notification.is_read">
                            <button @click="markAsRead(notification.id)" 
                                    class="flex-shrink-0 text-xs text-primary-600 hover:text-primary-700 transition-colors"
                                    title="Tandai sebagai dibaca">
                                <i class="fas fa-check"></i>
                            </button>
                        </template>

                        <template x-if="notification.is_read">
                            <div class="flex-shrink-0 text-xs text-gray-400">
                                <i class="fas fa-check-double"></i>
                            </div>
                        </template>
                    </div>
                </div>
            </template>
        </div>

        <!-- Footer -->
        <div class="p-3 border-t border-gray-200 dark:border-gray-700 text-center bg-gray-50 dark:bg-gray-900">
            <div class="flex justify-between items-center text-xs text-gray-500 dark:text-gray-400">
                <span x-text="`${unreadCount} belum dibaca`"></span>
                <button @click="loadNotifications()" 
                        class="text-primary-600 dark:text-primary-400 hover:text-primary-700 transition-colors"
                        :disabled="isLoading">
                    <i class="fas fa-sync-alt mr-1" :class="{'fa-spin': isLoading}"></i> 
                    <span x-text="isLoading ? 'Loading...' : 'Refresh'"></span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function notificationPanel() {
    return {
        open: false,
        notifications: [],
        unreadCount: 0,
        isLoading: false,

        async init() {
            await this.loadNotifications();
            this.startPolling();
        },

        async loadNotifications() {
            if (this.isLoading) return;
            
            this.isLoading = true;
            try {
                const response = await fetch('/api/notifications');
                const data = await response.json();
                
                if (data.success) {
                    this.notifications = data.notifications || [];
                    this.unreadCount = data.unread_count || 0;
                } else {
                    console.error('Failed to load notifications:', data.error);
                }
            } catch (error) {
                console.error('Error loading notifications:', error);
                this.notifications = [];
                this.unreadCount = 0;
            } finally {
                this.isLoading = false;
            }
        },

        async markAsRead(notificationId) {
            try {
                const response = await fetch(`/api/notifications/${notificationId}/read`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
                    }
                });

                const data = await response.json();
                
                if (data.success) {
                    await this.loadNotifications(); // Reload untuk update count
                } else {
                    console.error('Failed to mark as read:', data.error);
                }
            } catch (error) {
                console.error('Error marking notification as read:', error);
            }
        },

        async markAllAsRead() {
            try {
                const response = await fetch('/api/notifications/mark-all-read', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
                    }
                });

                const data = await response.json();
                
                if (data.success) {
                    await this.loadNotifications(); // Reload untuk update count
                } else {
                    console.error('Failed to mark all as read:', data.error);
                }
            } catch (error) {
                console.error('Error marking all notifications as read:', error);
            }
        },

        toggleDropdown() {
            this.open = !this.open;
            if (this.open) {
                this.loadNotifications();
            }
        },

        getNotificationIcon(type) {
            const icons = {
                'agenda': 'fas fa-calendar text-blue-500',
                'pengumuman': 'fas fa-bullhorn text-green-500',
                'nilai': 'fas fa-chart-bar text-yellow-500',
                'tugas': 'fas fa-tasks text-purple-500',
                'ekskul': 'fas fa-futbol text-red-500',
                'info': 'fas fa-info-circle text-blue-500',
                'success': 'fas fa-check-circle text-green-500',
                'warning': 'fas fa-exclamation-triangle text-yellow-500',
                'error': 'fas fa-times-circle text-red-500'
            };
            return icons[type] || 'fas fa-bell text-gray-500';
        },

        startPolling() {
            // Poll every 30 seconds, but only if dropdown is closed
            setInterval(() => {
                if (!this.open && !this.isLoading) {
                    this.loadNotifications();
                }
            }, 30000);
        }
    }
}
</script>