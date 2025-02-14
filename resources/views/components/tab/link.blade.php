@props(['id' => null])
<li class="me-2">
    <button @click="selectedTab = '{{ $id ?? $slot }}'" 
        class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300"
        :class="{ 'text-blue-600 border-blue-600 dark:text-blue-500 dark:border-blue-500': selectedTab == '{{ $id ?? $slot }}' }"
        >
        {{ $slot }}
    </button>
</li>