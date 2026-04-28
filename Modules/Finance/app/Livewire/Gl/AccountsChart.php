<?php

namespace Modules\Finance\Livewire\Gl;

use App\Models\Module;
use Livewire\Component;

class AccountsChart extends Component
{
    public $module;

    public function mount(): void
    {
        // نجلب بيانات موديول شجرة الحسابات من الداتابيز
        // هذا الكود يضمن أن الـ Generic App سيحصل على الأيقونة والاسم الصحيح
        $this->module = Module::where('code', 'FIN-GL-COA')->firstOrFail();
    }

    public function render()
    {
        return view('finance::livewire.gl.accounts-chart')->layout('layouts.app');
    }
}
