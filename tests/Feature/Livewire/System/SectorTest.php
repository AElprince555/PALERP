<?php

use App\Livewire\System\Sector;
use App\Models\Module;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('loads sector module and displays its active children', function () {
    $user = User::factory()->create();

    // 1. إنشاء موديول رئيسي (Folder/Sector)
    $parentFolder = Module::factory()->create([
        'code' => 'FIN',
        'type' => 'folder',
        'name' => ['en' => 'Finance'],
        'is_active' => true,
    ]);

    // 2. إنشاء موديول فرعي مفعل
    Module::factory()->create([
        'parent_id' => $parentFolder->id,
        'code' => 'FIN-GL',
        'type' => 'folder',
        'name' => ['en' => 'General Ledger'],
        'is_active' => true,
    ]);

    // 3. إنشاء موديول فرعي غير مفعل (يجب ألا يظهر)
    Module::factory()->create([
        'parent_id' => $parentFolder->id,
        'code' => 'FIN-OLD',
        'type' => 'folder',
        'name' => ['en' => 'Old Finance'],
        'is_active' => false,
    ]);

    // 4. اختبار مكون Livewire
    $this->actingAs($user);

    Livewire::test(Sector::class, ['module' => $parentFolder])
        ->assertSee('Finance')
        ->assertSee('General Ledger')
        ->assertDontSee('Old Finance');
});
