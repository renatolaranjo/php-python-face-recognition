@props(['identifier'])
<template x-if="selectedTab == '{{ $identifier }}'">
    <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-800">
        {{ $slot }}
    </div>
</template>