<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title.' - '.config('app.name') : config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen font-sans antialiased bg-base-200">

    {{-- NAVBAR mobile only --}}
    <x-mary-nav sticky class="lg:hidden">
        <x-slot:brand>
            <x-app-brand />
        </x-slot:brand>
        <x-slot:actions>
            <label for="main-drawer" class="lg:hidden me-3">
                <x-mary-icon name="o-bars-3" class="cursor-pointer" />
            </label>
        </x-slot:actions>
    </x-mary-nav>

    {{-- MAIN --}}
    <x-mary-main>
        {{-- SIDEBAR --}}
        <x-slot:sidebar drawer="main-drawer" collapsible class="bg-base-100 lg:bg-inherit">

            {{-- BRAND --}}
            <x-app-brand class="px-5 pt-4" />

            {{-- MENU --}}
            <x-mary-menu activate-by-route>

                {{-- User --}}
                @if($user = auth()->user())
                    <x-mary-menu-separator />

                    <x-mary-list-item :item="$user" value="name" sub-value="email" no-separator no-hover class="-mx-2 -my-2! rounded">
                        <x-slot:actions>
                            <x-mary-button icon="o-power" class="btn-circle btn-ghost btn-xs" tooltip-left="logoff" no-wire-navigate link="/logout" />
                        </x-slot:actions>
                    </x-mary-list-item>

                    <x-mary-menu-separator />
                @endif

                <x-mary-menu-item title="Hello" icon="o-sparkles" link="/" />

                <x-mary-menu-sub title="Settings" icon="o-cog-6-tooth">
                    <x-mary-menu-item title="Wifi" icon="o-wifi" link="####" />
                    <x-mary-menu-item title="Archives" icon="o-archive-box" link="####" />
                </x-mary-menu-sub>
            </x-mary-menu>
        </x-slot:sidebar>

        {{-- The `$slot` goes here --}}
        <x-slot:content>
            {{ $slot }}
        </x-slot:content>
    </x-mary-main>

    {{--  TOAST area --}}
    <x-mary-toast />
    @livewireScripts
    @stack('scripts')
</body>
</html>
