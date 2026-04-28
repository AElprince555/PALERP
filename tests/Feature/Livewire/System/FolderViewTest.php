<?php
// tests/Feature/Livewire/FolderViewTest.php

use App\Models\Module;
use App\Livewire\System\FolderView;
use Livewire\Livewire;

it('loads folder module and displays its active children', function () {
    // 1. إنشاء موديول رئيسي (Folder)
    $parentFolder = Module::factory()->create([
        'code' => 'FIN-GL',
        'type' => 'folder',
        'name' => ['en' => 'General Ledger'],
        'is_active' => true,
    ]);

    // 2. إنشاء موديول فرعي مفعل
    Module::factory()->create([
        'parent_id' => $parentFolder->id,
        'code' => 'FIN-GL-ACC',
        'type' => 'app',
        'name' => ['en' => 'Accounts'],
        'is_active' => true,
    ]);

    // 3. إنشاء موديول فرعي غير مفعل (يجب ألا يظهر)
    Module::factory()->create([
        'parent_id' => $parentFolder->id,
        'code' => 'FIN-GL-OLD',
        'type' => 'app',
        'name' => ['en' => 'Old Accounts'],
        'is_active' => false,
    ]);

    // 4. اختبار مكون Livewire
    Livewire::test(FolderView::class, ['module' => $parentFolder])
        ->assertSee('General Ledger')
        ->assertSee('Accounts')
        ->assertDontSee('Old Accounts');
});
