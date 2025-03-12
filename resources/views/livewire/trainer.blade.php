<form class="p-4" wire:submit='save'>
    <div class="grid grid-cols-2">
        <div class="p-4 space-y-3">
            <div>
                <x-label for="file_input">Upload file</x-label>
                <input wire:model='file'
                    class="block w-full text-sm text-gray-900 border border-gray-300 shadow-sm rounded-lg cursor-pointer bg-gray-50 dark:bg-neutral-700 dark:text-neutral-400 focus:z-10 focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none file:bg-gray-800 file:text-white file:border-0 file:me-4 file:py-3 file:px-4 dark:file:bg-neutral-700 dark:file:text-neutral-400"
                    id="file_input" type="file" accept="image/*">
                <x-input-error for="file" />
            </div>
            <div>
                <x-label for="name">Name</x-label>
                <input type="text" id="name" wire:model='name'
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="John" />
                <x-input-error for="name" />
            </div>
            <button type="submit"
                class="text-white bg-[#4285F4] hover:bg-[#4285F4]/90 focus:ring-4 focus:outline-none focus:ring-[#4285F4]/50 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:focus:ring-[#4285F4]/55 me-2 mb-2">
                <x-icon.disk />
                Save
            </button>
        </div>
        <div class="p-4">
            @if ($file)
                {{-- <img class="h-72" src="{{ $file->temporaryUrl() }}"> --}}
                <img class="h-72" src="{{ asset('storage/' . $fileFace . '.jpg') }}">
            @endif
        </div>
    </div>
</form>
