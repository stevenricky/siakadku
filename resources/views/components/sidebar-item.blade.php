@props(['href', 'icon', 'collapsed' => false, 'active' => false])

<a href="{{ $href }}" 
    @click="closeMobile()"
    {{ $attributes->class([
        'flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 group relative',
        'text-gray-900 dark:text-white bg-gray-100 dark:bg-gray-700 shadow-sm' => $active,
        'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700' => !$active
    ]) }}
    title="{{ $slot }}"
>
    <!-- Icon -->
    <i class="fas fa-{{ $icon }} w-5 h-5 flex-shrink-0 transition-all duration-200 {{ $active ? 'text-primary-600' : 'text-gray-400 group-hover:text-gray-600' }}"></i>
    
    <!-- Text -->
    <span class="ml-3 transition-all duration-200 whitespace-nowrap" 
          :class="{ 'opacity-0 w-0': $root.collapsed, 'opacity-100 w-auto': !$root.collapsed }">
        {{ $slot }}
    </span>

    <!-- Tooltip for collapsed state -->
    <div x-show="$root.collapsed" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute left-full ml-2 px-2 py-1 bg-gray-900 dark:bg-gray-700 text-white text-sm rounded-md shadow-lg z-50 whitespace-nowrap opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity duration-200">
        {{ $slot }}
    </div>

    <!-- Active indicator -->
    @if($active)
    <div class="absolute right-2 top-1/2 transform -translate-y-1/2">
        <div class="w-2 h-2 bg-primary-600 rounded-full"></div>
    </div>
    @endif
</a>