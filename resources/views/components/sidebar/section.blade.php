@props(['title'])

<div class="mt-6">
    <h4 class="px-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
        {{ $title }}
    </h4>
    <div class="mt-2 space-y-1">
        {{ $slot }}
    </div>
</div>