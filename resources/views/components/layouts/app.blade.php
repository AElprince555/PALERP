<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      data-theme="palestine"
      dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ isset($title) ? $title.' - '.config('app.name') : config('app.name') }}</title>

        {{-- استيراد الخطوط --}}
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;900&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles

        <style>
            body { font-family: 'Tajawal', sans-serif; }
            [x-cloak] { display: none !important; }
        </style>
    </head>
    <body class="min-h-screen antialiased bg-base-200">

        {{-- NAVBAR للهواتف --}}
        <x-mary-nav sticky class="lg:hidden bg-primary text-primary-content">
            <x-slot:brand>
                <div class="font-black text-xl">PAL<span class="italic opacity-70">ERP</span></div>
            </x-slot:brand>
            <x-slot:actions>
                <label for="main-drawer" class="lg:hidden me-3">
                    <x-mary-icon name="o-bars-3" class="cursor-pointer" />
                </label>
            </x-slot:actions>
        </x-mary-nav>

        <x-mary-main full-width>

            <x-system.sidebar />
            {{-- المحتوى الرئيسي --}}
            <x-slot:content>
                {{-- حقن الـ Breadcrumb تلقائياً --}}
                {{-- إذا قمت بتمرير مصفوفة breadcrumbs من الكلاس ستظهر هنا --}}
                @if(isset($breadcrumbs))
                    <x-system.breadcrumb :breadcrumbItems="$breadcrumbs" />
                @endif

                {{-- حاوية المحتوى لضمان وجود مسافات متناسقة (Padding) --}}
                <div class="p-4 lg:p-6">
                    {{ $slot }}
                </div>
            </x-slot:content>
        </x-mary-main>
        <livewire:quick-switcher />
        {{-- مكونات النظام الإضافية --}}
        <x-mary-toast />

        @livewireScripts
        @stack('scripts')
    </body>
</html>
