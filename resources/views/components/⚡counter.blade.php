<?php

use Livewire\Component;

new class extends Component
{
    public int $count = 0;

    public function increment()
    {
        $this->count++;
    }
};
?>

<div class="p-10">
    {{-- استخدام مكونات MaryUI --}}
    <x-mary-card title="لوحة تحكم العداد" subtitle="تجربة مكونات MaryUI في نظام الـ ERP" shadow separator>
        <div class="text-4xl font-bold mb-5">
            {{ $count }}
        </div>

        <x-slot:actions>
            <x-mary-button label="إعادة ضبط" wire:click="$set('count', 0)" class="btn-ghost" />
            <x-mary-button label="زيادة العداد" wire:click="increment" icon="o-plus" class="btn-primary" />
        </x-slot:actions>
    </x-mary-card>
</div>
