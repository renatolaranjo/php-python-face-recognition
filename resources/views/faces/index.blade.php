<div class="p-4">
    <x-table>
        <x-table.header>
            <x-table.head>Name</x-table.head>
        </x-table.header>
        <x-table.tbody>
            @forelse ($faces as $face)
                <x-table.column>
                    {{ $face->name }}
                </x-table.column>
            @empty
                <x-table.column>
                    Nothing
                </x-table.column>
            @endforelse
        </x-table.tbody>
    </x-table>
</div>