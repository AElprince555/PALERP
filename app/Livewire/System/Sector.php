<?php

namespace App\Livewire\System;

use App\Actions\System\GetSubModulesAction;
use App\Models\Module;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Sector extends Component
{
    /**
     * Financial Security: حماية المعرفات من التلاعب الخارجي.
     */
    #[Locked]
    public ?int $moduleId = null;

    #[Locked]
    public ?string $moduleCode = null;

    /**
     * Infrastructure: الـ mount الآن لا يتصل بقاعدة البيانات إطلاقاً!
     * مهمته فقط التقاط الـ Input وتخزينه.
     */
    public function mount(?Module $module = null): void
    {
        if ($module?->exists) {
            $this->moduleId = $module->id;
        } else {
            // التقاط أول مقطع من المسار (مثال: /fin -> fin)
            $this->moduleCode = request()->segment(1);
        }
    }

    /**
     * Performance: استعلام واحد فقط يتم تنفيذه (Single Source of Truth).
     * مع الالتزام الصارم بتحديد الأعمدة لمنع استهلاك الذاكرة.
     */
    #[Computed(persist: true)]
    public function module(): Module
    {
        $query = Module::query()->select(['id', 'code', 'name', 'icon', 'description', 'route', 'parent_id']);

        // إذا كان لدينا الـ ID (في الطلبات اللاحقة عبر الـ Livewire Hydration)
        if ($this->moduleId) {
            return $query->findOrFail($this->moduleId);
        }

        // في أول تحميل للصفحة (Initial Load)
        // نقوم بتحويل الكود للأحرف الكبيرة لمطابقة قاعدة البيانات (FIN, HR, etc.)
        $module = $query->where('code', strtoupper($this->moduleCode))->firstOrFail();

        // نحفظ الـ ID للطلبات القادمة (لضمان سرعة الاستعلام)
        $this->moduleId = $module->id;

        return $module;
    }

    /**
     * استخدام Action Class لمنع مشكلة N+1.
     */
    #[Computed]
    public function subModules()
    {
        // نعتمد على الكائن المحسوب مباشرة لضمان ترابط البيانات
        return app(GetSubModulesAction::class)->execute($this->module->id);
    }

    public function render(): View
    {
        return view('livewire.system.sector')
            ->title($this->module->name ?? $this->module->code)
            ->layout('layouts.app', [
                'breadcrumbs' => $this->module->getBreadcrumbs(),
            ]);
    }
}
