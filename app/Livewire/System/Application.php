<?php

namespace App\Livewire\System;

use App\Models\Module;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Application extends Component
{
    #[Locked]
    public ?int $moduleId = null;

    #[Locked]
    public ?string $moduleCode = null;

    public function mount(?Module $module = null): void
    {
        if ($module?->exists) {
            $this->moduleId = $module->id;
        } else {
            // /fin/gl/grp -> segments: ['fin', 'gl', 'grp'] -> join with - and uppercase -> FIN-GL-GRP
            $segments = request()->segments();
            $this->moduleCode = strtoupper(implode('-', $segments));
        }
    }

    #[Computed(persist: true)]
    public function module(): Module
    {
        $query = Module::query()->select(['id', 'code', 'name', 'icon', 'description', 'route', 'parent_id']);

        if ($this->moduleId) {
            return $query->findOrFail($this->moduleId);
        }

        $module = $query->where('code', $this->moduleCode)->firstOrFail();
        $this->moduleId = $module->id;

        return $module;
    }

    public function render(): View
    {
        return view('livewire.system.application')
            ->title($this->module->name ?? $this->module->code)
            ->layout('layouts.app', [
                'breadcrumbs' => $this->module->getBreadcrumbs(),
            ]);
    }
}
