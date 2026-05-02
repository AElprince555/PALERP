<?php

namespace App\Livewire;

use App\Models\Module;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Illuminate\Database\Eloquent\Collection;

class Home extends Component
{
    // خاصية للتحكم في التحميل المؤجل (Deferred Loading)
    public bool $readyToLoad = false;

    public function loadModules(): void
    {
        $this->readyToLoad = true;
    }

    /**
     * جلب الأنظمة المفعلة
     * Financial Integrity: نحدد الأعمدة بدقة لتوفير الذاكرة
     */
    #[Computed]
    public function modules(): Collection
    {
        if (!$this->readyToLoad) {
            return new Collection();
        }

        return Module::query()
            ->whereNull('parent_id') // جلب الموديولات الرئيسية فقط
            ->select(['id', 'name', 'description', 'icon', 'route'])
            ->get();
    }

    // تحديد الـ Layout المستخدم صراحةً لحل مشكلة View [app] not found
    #[Layout('components.layouts.app')]
    public function render(): string
    {
        return <<<'blade'
            <div wire:init="loadModules">
                <x-mary-header title="نظام PALERP" subtitle="مرحباً بك، اختر النظام للبدء" separator progress-indicator />

                @if(!$readyToLoad)
                    <div class="flex justify-center items-center h-64">
                        <x-mary-loading class="text-primary loading-lg" />
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        @forelse($this->modules as $module)
                            <x-mary-card title="{{ $module->name }}" class="shadow-sm border-t-4 border-primary transition-all hover:shadow-md">
                                <x-slot:figure>
                                    <div class="p-6 bg-base-200 flex justify-center w-full">
                                        <x-mary-icon name="{{ $module->icon ?? 'o-squares-2x2' }}" class="w-12 h-12 text-primary" />
                                    </div>
                                </x-slot:figure>

                                <div class="text-sm opacity-70">{{ \Illuminate\Support\Str::limit($module->description, 60) }}</div>

                                <x-slot:actions>
                                    <x-mary-button
                                        label="دخول"
                                        :link="$module->route ? route($module->route) : '#'"
                                        icon="o-arrow-right"
                                        class="btn-primary btn-sm"
                                    />
                                </x-slot:actions>
                            </x-mary-card>
                        @empty
                            <x-mary-alert icon="o-exclamation-triangle" title="لا توجد أنظمة" class="alert-warning" />
                        @endforelse
                    </div>
                @endif
            </div>
        blade;
    }
}
