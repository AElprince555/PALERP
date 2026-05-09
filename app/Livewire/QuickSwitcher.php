<?php

namespace App\Livewire;

use App\Models\Module;
use Illuminate\Support\Facades\App;
use Livewire\Component;

class QuickSwitcher extends Component
{
    public $query = '';

    public function render()
    {
        $results = [];
        $locale = App::getLocale();

        if (strlen($this->query) > 1) {
            $searchTerm = strtolower($this->query);

            $results = Module::query()
                ->with('parent')
                ->where('is_active', true) // نبحث فقط في الموديولات النشطة
                ->where(function ($q) use ($searchTerm, $locale) {
                    $q->whereRaw('LOWER(code) LIKE ?', ["%{$searchTerm}%"])
                        ->orWhereRaw("LOWER(JSON_EXTRACT(name, '$.{$locale}')) LIKE ?", ["%{$searchTerm}%"]);
                })
                ->take(8)
                ->get();
        }

        return view('livewire.quick-switcher', [
            'results' => $results,
        ]);
    }
}
