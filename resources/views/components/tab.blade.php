<div x-data="{ selectedTab :  'train' }">
    <div
        class="text-sm font-medium text-center text-gray-500 border-b border-gray-200 dark:text-gray-400 dark:border-gray-700">
        <ul class="flex flex-wrap -mb-px">
            {{ $slot }}
        </ul>
    </div>
    <div>
        {{ $content }}
    </div>
</div>