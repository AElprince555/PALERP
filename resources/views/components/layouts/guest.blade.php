@props(['title' => null])
    <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      data-theme="palestine"
      dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ $title ?? 'PalERP' }}</title>

        <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700;900&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            body { font-family: 'Tajawal', sans-serif; }
            .bg-palestine-pattern {
                background-color: oklch(98% 0 0);
                background-image: radial-gradient(oklch(35% 0.15 150 / 0.05) 1px, transparent 1px);
                background-size: 20px 20px;
            }
        </style>
    </head>
    <body class="min-h-screen antialiased bg-palestine-pattern flex flex-col justify-center items-center p-4">

        {{-- Logo / Brand --}}
        <div class="mb-8 text-center animate-fade-in-up">
            <div class="text-4xl font-black tracking-tighter uppercase">
                PAL<span class="text-primary italic">ERP</span>
            </div>
        </div>

        {{-- الحاوية المركزية --}}
        <div class="w-full max-w-md bg-base-100 shadow-2xl rounded-[2rem] border border-base-300 p-8 relative overflow-hidden">
            {{-- لمسة جمالية: خط أخضر في الأعلى --}}
            <div class="absolute top-0 left-0 right-0 h-1.5 bg-primary"></div>

            {{ $slot }}
        </div>

        {{-- Footer بسيط --}}
        <div class="mt-8 text-xs text-base-content/40 font-medium tracking-widest uppercase text-center">
            &copy; {{ date('Y') }} PALERP - Free & Proud
        </div>

    </body>
</html>
