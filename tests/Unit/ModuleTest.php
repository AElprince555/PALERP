<?php

use App\Models\Module;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('module can have children and a parent', function () {
    $parent = Module::factory()->create(['name' => ['en' => 'Parent']]);
    $child = Module::factory()->create([
        'parent_id' => $parent->id,
        'name' => ['en' => 'Child'],
    ]);

    expect($child->parent->id)->toBe($parent->id);
    expect($parent->children->first()->id)->toBe($child->id);
});

test('module handles translations correctly', function () {
    $module = Module::factory()->create([
        'name' => [
            'ar' => 'اختبار',
            'en' => 'Test',
        ],
    ]);

    app()->setLocale('ar');
    expect($module->name)->toBe('اختبار');

    app()->setLocale('en');
    expect($module->name)->toBe('Test');
});

test('module generates correct breadcrumbs', function () {
    $grandParent = Module::factory()->create(['name' => ['en' => 'GrandParent'], 'code' => 'GP']);
    $parent = Module::factory()->create(['parent_id' => $grandParent->id, 'name' => ['en' => 'Parent'], 'code' => 'P']);
    $child = Module::factory()->create(['parent_id' => $parent->id, 'name' => ['en' => 'Child'], 'code' => 'C']);

    $breadcrumbs = $child->getBreadcrumbs();

    expect($breadcrumbs)->toHaveCount(3);
    expect($breadcrumbs[0]['name'])->toBe('GrandParent');
    expect($breadcrumbs[1]['name'])->toBe('Parent');
    expect($breadcrumbs[2]['name'])->toBe('Child');
});
