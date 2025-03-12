@props(['identifier'])
<template x-if="selectedTab == '{{ $identifier }}'">
    <div class="p-4 rounded-lg bg-white dark:bg-gray-800">
        {{ $slot }}
    </div>
</template>