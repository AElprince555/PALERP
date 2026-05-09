<div class="p-6 space-y-8 animate-fade-in" wire:key="submodule-view-{{ $this->moduleId }}">
    {{-- Header Section --}}
    <x-mary-header
        title="{{ $this->module->name ?? $this->module->code }}"
        subtitle="{{ $this->module->description ?? 'استعراض التطبيقات والوظائف المتاحة' }}"
        separator
    >
        <div class="p-3 bg-primary/10 rounded-2xl text-primary shadow-sm border border-primary/5">
            @if(str_contains($this->module->icon, '<svg'))
                <div class="w-10 h-10 flex items-center justify-center">{!! $this->module->icon !!}</div>
            @else
                <x-mary-icon :name="$this->module->icon" class="w-10 h-10" />
            @endif
        </div>

        <x-slot:actions>
            <x-mary-button label="تحديث" icon="o-arrow-path" wire:click="$refresh" class="btn-ghost btn-sm" />
        </x-slot:actions>
    </x-mary-header>

    {{-- Grid Layout for Applications --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($this->applications as $item)
            <x-mary-card
                shadow
                hover
                class="border-t-4 border-t-secondary bg-base-100 transition-all duration-300 hover:-translate-y-2"
                wire:key="application-{{ $item->id }}"
            >
                <div class="flex items-start justify-between">
                    <div class="p-4 bg-base-200 rounded-xl">
                        @if(str_contains($item->icon, '<svg'))
                            <div class="w-8 h-8 text-secondary flex items-center justify-center">{!! $item->icon !!}</div>
                        @else
                            <x-mary-icon :name="$item->icon ?? 'o-cpu-chip'" class="w-8 h-8 text-secondary" />
                        @endif
                    </div>
                    <div class="badge badge-secondary badge-outline badge-sm uppercase font-mono tracking-tighter">{{ $item->code }}</div>
                </div>

                <div class="mt-4">
                    <h2 class="text-xl font-bold line-clamp-1">{{ $item->name ?? $item->code }}</h2>
                    <p class="text-sm opacity-60 line-clamp-2 mt-2 h-10">
                        {{ $item->description ?? 'تشغيل التطبيق وإدارة البيانات الخاصة به.' }}
                    </p>
                </div>

                <x-slot:actions>
                    {{-- ملاحظة: التطبيقات (Level 3) قد لا تحتوي على مسارات معرفة بعد، سنترك الرابط مؤقتاً --}}
                    <x-mary-button
                        label="تشغيل"
                        icon-right="o-play"
                        link="{{ $item->route ? route($item->route) : '#' }}"
                        class="btn-secondary btn-sm w-full"
                        wire:navigate
                    />
                </x-slot:actions>
            </x-mary-card>
        @empty
            <div class="col-span-full flex flex-col items-center justify-center p-20 border-2 border-dashed border-base-300 rounded-4xl opacity-50">
                <x-mary-icon name="o-cpu-chip" class="w-20 h-20 mb-4" />
                <p class="text-xl font-medium italic">لا توجد تطبيقات متوفرة في هذا الموديول حالياً.</p>
            </div>
        @endforelse
    </div>
</div>
