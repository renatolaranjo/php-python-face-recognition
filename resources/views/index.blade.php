<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <title>{{ env('APP_NAME') }}</title>


    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="bg-gradient-to-t from-gray-200 to-white  h-screen">

    <x-wrapper>
        <x-page>
            <x-tab>
                <x-tab.link id='train'>Train</x-tab.link>
                <x-tab.link id='recon'>Recognize</x-tab.link>
                @slot('content')
                <x-tab.content identifier="train">
                    <livewire-trainer />
                </x-tab.content>
                <x-tab.content identifier="recon">
                    Recognize
                </x-tab.content>
                @endslot
            </x-tab>
            <livewire:faces />

        </x-page>
    </x-wrapper>
    @livewireScripts
</body>

</html>