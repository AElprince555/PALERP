<?php

use App\Models\Module;
use Illuminate\Support\Facades\Cache;
use Livewire\Volt\Component;

new class extends Component {
    public Module $module;
    public array $items = [];

    public function mount(Module $module)
    {
        $this->module = $module;
        $locale = app()->getLocale();

        // جلب الأبناء (الـ Sub-modules أو الـ Apps المرتبطة) إذا وجدت
        $this->items = Cache::remember("mix_items_{$module->id}_{$locale}", 86400, function () use ($module) {
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

<div class="space-y-8 animate-fade-in-up" wire:key="mix-{{ $module->id }}">
    {{-- Header --}}
    <x-mary-header title="{{ $module->getTranslation('name', app()->getLocale()) }}" separator>
        <x-slot:icon>
            <x-icon :name="$module->icon" class="w-8 h-8 text-primary mr-3" />
        </x-slot:icon>
        <x-slot:actions>
            <x-mary-button icon="o-plus" label="إجراء جديد" class="btn-primary btn-sm" />
        </x-slot:actions>
    </x-mary-header>

    {{-- Quick Actions / Sub-items (إن وجدت) --}}
    @if(count($items) > 0)
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            @foreach($items as $item)
                @php
                    $url = $item['route'] ? route($item['route']) : url('/erp/' . $item['code']);
                @endphp
                <a href="{{ $url }}" wire:navigate class="flex items-center p-4 bg-base-100 hover:bg-primary/10 border border-base-200 rounded-2xl transition-all shadow-sm">
                    <x-icon :name="$item['icon']" class="w-6 h-6 text-primary ml-3" />
                    <span class="font-bold text-sm">{{ $item['name'][app()->getLocale()] ?? $item['code'] }}</span>
                </a>
            @endforeach
        </div>
    @endif

    {{-- Main App Area (مساحة العمل الرئيسية) --}}
    <div class="bg-base-100 rounded-2xl shadow-sm border border-base-200 p-6 min-h-[400px]">
        @if(isset($module->metadata['livewire_component']))
            <livewire:is :component="$module->metadata['livewire_component']" :module="$module" />
        @else
            <div class="flex flex-col items-center justify-center h-64 text-base-content/40">
                <x-icon name="o-square-3-stack-3d" class="w-16 h-16 mb-4 opacity-50" />
                <p>منطقة العمليات المهجنة: ({{ $module->getTranslation('name', app()->getLocale()) }})</p>
                <p class="text-xs mt-2 opacity-50">قريباً سيتم ربط الجداول والتقارير هنا</p>
            </div>
        @endif
    </div>
</div>
