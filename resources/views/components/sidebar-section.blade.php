@props(['title', 'collapsed' => false])

<div class="pt-4">
    <!-- Section Title -->
    <div class="px-3 mb-2">
<p x-show="!collapsed" 
   class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider truncate transition-opacity duration-300"
   :class="{ 'opacity-0 h-0': collapsed, 'opacity-100 h-auto': !collapsed }">
    {{ $title }}
</p>
        <div x-show="$root.collapsed" class="w-full border-t border-gray-200 dark:border-gray-600"></div>
    </div>
    
    <!-- Section Items -->
    <div class="space-y-1">
        {{ $slot }}
    </div>
</div>