<?php

use App\Models\Module;
use Illuminate\Support\Facades\Cache;
use Livewire\Volt\Component;

new class extends Component {
    public Module $module;
    public array $items = [];

    public function mount(Module $module): void
    {
        $this->module = $module;
        $locale = app()->getLocale();

        // جلب التطبيقات المرتبطة بهذا المجلد
        $this->items = Cache::remember("folder_items_{$module->id}_{$locale}", 86400, function () use ($module) {
            return Module::where('parent_id', $module->id)
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->select(['id', 'code', 'name', 'icon', 'type', 'description', 'route'])
                ->get()
                ->toArray();
        });
    }
};
?>

<div class="space-y-8 animate-fade-in-up" wire:key="folder-{{ $module->id }}">
    @php $isRtl = app()->getLocale() == 'ar'; @endphp

    {{-- Banner Section --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-8 bg-base-100 p-10 rounded-[3rem] border border-base-200 shadow-sm relative overflow-hidden group">
        <div class="absolute top-[-20%] {{ $isRtl ? 'left-[-5%]' : 'right-[-5%]' }} opacity-[0.03] pointer-events-none group-hover:scale-110 transition-transform duration-1000">
            <x-icon :name="$module->icon" class="w-64 h-64" />
        </div>
        <div class="relative z-10 flex items-center gap-8 text-start">
            <div class="p-6 bg-primary/10 rounded-[2rem] text-primary shadow-inner group-hover:rotate-12 transition-transform duration-500">
                <x-icon :name="$module->icon" class="w-14 h-14" />
            </div>
            <div>
                <h1 class="text-4xl font-black text-base-content">{{ $module->getTranslation('name', app()->getLocale()) }}</h1>
                <p class="text-base-content/50 mt-2 font-medium text-lg">{{ $module->getTranslation('description', app()->getLocale()) }}</p>
            </div>
        </div>
    </div>

    {{-- Quick Actions (Applications) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($items as $item)
            @php
                $url = $item['route'] ? route($item['route']) : url('/erp/' . $item['code']);
            @endphp
            <a href="{{ $url }}" wire:navigate class="group flex items-center p-5 bg-base-100 hover:bg-primary border border-base-200 rounded-3xl transition-all duration-300 shadow-sm hover:shadow-md hover:-translate-y-1">
                <div class="w-12 h-12 rounded-xl bg-base-200 group-hover:bg-white/20 flex items-center justify-center transition-colors ml-4 mr-4 rtl:ml-4 rtl:mr-0">
                    <x-icon :name="$item['icon']" class="w-6 h-6 group-hover:text-white" />
                </div>
                <div class="flex flex-col text-start flex-1">
                    <span class="font-bold text-sm group-hover:text-white transition-colors">
                        {{ $item['name'][app()->getLocale()] ?? $item['code'] }}
                    </span>
                    <span class="text-[10px] opacity-50 group-hover:opacity-80 group-hover:text-white uppercase transition-colors line-clamp-1 mt-1">
                        {{ $item['description'][app()->getLocale()] ?? 'فتح التطبيق' }}
                    </span>
                </div>
                <x-icon name="o-arrow-left" class="w-5 h-5 opacity-0 group-hover:opacity-100 transition-all group-hover:text-white rtl:rotate-180" />
            </a>
        @empty
            <div class="col-span-full p-10 text-center opacity-40 border-2 border-dashed border-base-300 rounded-[2rem]">
                لا توجد تطبيقات متاحة حالياً داخل هذا المجلد.
            </div>
        @endforelse
    </div>
</div>
