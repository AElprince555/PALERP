<!DOCTYPE html>
{{-- تعديل: أضفنا data-theme والـ dir للعربي --}}
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="palestine" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ isset($title) ? $title.' - '.config('app.name') : config('app.name') }}</title>

        {{-- إضافة خط تجول للعربي --}}
        <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700;900&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles

        <style>
            body { font-family: 'Tajawal', sans-serif; }
        </style>
    </head>
    <body class="min-h-screen antialiased bg-base-200">

        {{-- NAVBAR mobile only --}}
        <x-mary-nav sticky class="lg:hidden bg-primary text-primary-content">
            <x-slot:brand>
                <div class="font-black">PAL<span class="italic opacity-70">ERP</span></div>
            </x-slot:brand>
            <x-slot:actions>
                <label for="main-drawer" class="lg:hidden me-3">
                    <x-mary-icon name="o-bars-3" class="cursor-pointer" />
                </label>
            </x-slot:actions>
        </x-mary-nav>

        {{-- MAIN --}}
        <x-mary-main>
            {{-- SIDEBAR: تعديل بسيط للون --}}
            <x-slot:sidebar drawer="main-drawer" collapsible class="bg-secondary text-secondary-content lg:bg-secondary">

                {{-- BRAND --}}
                <div class="flex items-center justify-center lg:justify-start p-5 pt-4 text-2xl font-black text-white tracking-tighter">
                    P<span class="mary-hideable !inline-flex">AL<span class="text-primary italic">ERP</span></span>
                </div>
                {{-- MENU --}}
                <x-mary-menu activate-by-route>
                    @if($user = auth()->user())
                        <x-mary-menu-separator />
                        <x-mary-list-item :item="$user" value="name" sub-value="email" no-separator no-hover class="-mx-2 -my-2! rounded text-white">
                            <x-slot:actions>
                                {{-- زرار الخروج --}}
                                <x-mary-button
                                    icon="o-power"
                                    class="btn-circle btn-ghost btn-xs text-red-500"
                                    tooltip-left="تسجيل الخروج"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                />

                                {{-- فورم الخروج المخفية --}}
                                <form id="logout-form" action="/logout" method="POST" class="hidden">
                                    @csrf
                                </form>
                            </x-slot:actions>
                        </x-mary-list-item>
                        <x-mary-menu-separator />
                    @endif

                    <x-mary-menu-item title="الرئيسية" icon="o-sparkles" link="/" />

                    <x-mary-menu-sub title="الإعدادات" icon="o-cog-6-tooth">
                        <x-mary-menu-item title="الشبكة" icon="o-wifi" link="####" />
                        <x-mary-menu-item title="الأرشيف" icon="o-archive-box" link="####" />
                    </x-mary-menu-sub>
                </x-mary-menu>
            </x-slot:sidebar>

            <x-slot:content>
                {{ $slot }}
            </x-slot:content>
        </x-mary-main>

        <x-mary-toast />
        @livewireScripts
        @stack('scripts')
    </body>
</html>
