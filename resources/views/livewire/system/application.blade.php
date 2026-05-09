<div class="p-6 space-y-8 animate-fade-in" wire:key="application-view-{{ $this->moduleId }}">
    {{-- Header Section --}}
    <x-mary-header
        title="{{ $this->module->name ?? $this->module->code }}"
        subtitle="{{ $this->module->description ?? 'تطبيق النظام - استعراض وإدارة البيانات' }}"
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
            <x-mary-button label="إضافة جديد" icon="o-plus" class="btn-primary btn-sm" />
        </x-slot:actions>
    </x-mary-header>

    {{-- Empty Application State with MaryUI Table --}}
    <x-mary-card shadow class="bg-base-100 border-t-4 border-primary">
        <x-mary-table 
            :headers="[
                ['key' => 'id', 'label' => '#', 'class' => 'w-1'],
                ['key' => 'name', 'label' => 'الاسم'],
                ['key' => 'status', 'label' => 'الحالة'],
                ['key' => 'created_at', 'label' => 'تاريخ الإضافة'],
            ]"
            :rows="[]"
        >
            <x-slot:empty>
                <div class="flex flex-col items-center justify-center py-20 opacity-50">
                    <x-mary-icon name="o-circle-stack" class="w-20 h-20 mb-4" />
                    <h3 class="text-xl font-bold italic">لا توجد بيانات متاحة حالياً</h3>
                    <p class="text-sm">ابدأ بإضافة سجلات جديدة لهذا التطبيق عبر زر "إضافة جديد" في الأعلى.</p>
                </div>
            </x-slot:empty>
        </x-mary-table>
    </x-mary-card>
</div>
