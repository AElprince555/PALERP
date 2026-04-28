@props(['breadcrumbItems' => []])

<div class="flex items-center flex-wrap gap-2 py-4 px-6 bg-base-100/50 border-b border-base-300 {{ app()->getLocale() == 'ar' ? 'font-arabic' : '' }}">
    {{-- زر الصفحة الرئيسية --}}
    <a href="/dashboard" wire:navigate class="btn btn-ghost btn-sm btn-circle hover:bg-primary/10 text-primary transition-all">
        <x-heroicon-o-home class="size-4" />
    </a>

    @foreach($breadcrumbItems as $item)
        {{-- الفاصل بين العناصر مع مراعاة الاتجاه --}}
        <div class="text-base-content/20 font-thin {{ app()->getLocale() == 'ar' ? 'rotate-180' : '' }}">/</div>

        <a href="{{ $item['url'] }}"
           wire:navigate
           class="flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-bold tracking-widest uppercase transition-all {{ $loop->last ? 'bg-primary/10 text-primary pointer-events-none' : 'text-base-content/60 hover:bg-base-200' }}">

            @php
                // تنظيف اسم الأيقونة لضمان التوافق مع مكونات Heroicons
                $iName = str_replace(['heroicon-', 'o-'], '', $item['icon'] ?? 'folder');
                $iName = str_replace(['magnifying-glascircle', 'bar3-bottom-left'], ['magnifying-glass-circle', 'bars-3-bottom-left'], $iName);
            @endphp

            <x-dynamic-component :component="'heroicon-o-' . $iName" class="size-4 opacity-50" />

            {{-- عرض الاسم المترجم القادم من الموديول --}}
            <span>{{ $item['name'] }}</span>
        </a>
    @endforeach
</div>
