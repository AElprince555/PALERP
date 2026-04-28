<?php

use App\Models\Module;
use Livewire\Volt\Component;

new class extends Component {
    public Module $module;

    public function mount(Module $module)
    {
        $this->module = $module;
    }
};
?>

<x-mary-header title="{{ $module->name }}" icon="{{ $module->icon }}" separator>
    <x-slot:actions>
        @if($module->getResolvedForm())
            <x-mary-button icon="o-plus" label="إضافة جديد" class="btn-primary btn-sm" />
        @endif
    </x-slot:actions>
</x-mary-header>

    {{-- حاضنة البيانات (DataGrid أو Custom Component) --}}
    <div class="bg-base-100 rounded-2xl shadow-sm border border-base-200 p-2 min-h-[400px]">
        @php
            $customComponent = $module->metadata['livewire_component'] ?? null;
        @endphp

        @if($customComponent)
            {{-- حقن المكون المخصص الخاص بهذا التطبيق --}}
            <livewire:is :component="$customComponent" :module="$module" />
        @else
            {{-- رسالة الـ Placeholder للتطبيقات التي لم تُبرمج بعد --}}
            <div class="flex flex-col items-center justify-center h-80 text-base-content/40">
                <x-mary-icon name="o-cube-transparent" class="w-20 h-20 mb-6 opacity-30" />
                <h3 class="text-xl font-bold mb-2">منطقة العمليات: {{ $module->getTranslation('name', app()->getLocale()) }}</h3>
                <p class="text-sm">لم يتم ربط شاشة البيانات (DataGrid) بهذا التطبيق حتى الآن.</p>
                <div class="mt-6 flex gap-2">
                    <kbd class="kbd kbd-sm">{{ $module->code }}</kbd>
                </div>
            </div>
        @endif
    </div>
</div>
