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

        // جلب المجلدات الفرعية التابعة للقطاع
        $this->items = Cache::remember("sector_items_{$module->id}_{$locale}", 86400, function () use ($module) {
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

<div class="space-y-8 animate-fade-in-up" wire:key="sector-{{ $module->id }}">
    {{-- Header --}}
    <x-mary-header
        title="{{ $module->getTranslation('name', app()->getLocale()) }}"
        subtitle="{{ $module->getTranslation('description', app()->getLocale()) ?? 'إدارة القطاع الرئيسي' }}"
        size="text-4xl font-black"
        separator
    >
        <x-slot:icon>
            <div class="p-3 bg-primary/10 rounded-2xl mr-4">
                <x-icon :name="$module->icon" class="w-10 h-10 text-primary" />
            </div>
        </x-slot:icon>
    </x-mary-header>

    {{-- Sub-Modules (Folders) Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-8">
        @forelse($items as $item)
            @php
                $url = $item['route'] ? route($item['route']) : url('/erp/' . $item['code']);
            @endphp
            <a href="{{ $url }}" wire:navigate class="card bg-base-100 shadow-sm border border-base-200 hover:border-primary/50 hover:shadow-md transition-all group overflow-hidden relative">
                <div class="absolute -right-6 -top-6 opacity-5 group-hover:scale-150 transition-transform duration-700 pointer-events-none">
                    <x-icon :name="$item['icon']" class="w-32 h-32" />
                </div>
                <div class="card-body items-center text-center z-10">
                    <div class="p-5 bg-base-200 text-primary rounded-full mb-4 group-hover:bg-primary group-hover:text-white transition-colors duration-300">
                        <x-icon :name="$item['icon']" class="w-10 h-10" />
                    </div>
                    <h2 class="card-title font-bold text-lg">{{ $item['name'][app()->getLocale()] ?? $item['code'] }}</h2>
                    <p class="text-xs opacity-60 mt-2">{{ $item['description'][app()->getLocale()] ?? 'عرض محتويات الموديول' }}</p>
                </div>
            </a>
        @empty
            <div class="col-span-full p-12 text-center opacity-40 border-2 border-dashed border-base-300 rounded-[2rem]">
                لا توجد موديولات فرعية مفعلة في هذا القطاع.
            </div>
        @endforelse
    </div>
</div>
