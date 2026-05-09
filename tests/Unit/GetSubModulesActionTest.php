<?php

use App\Actions\System\GetSubModulesAction;
use App\Models\Module;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('GetSubModulesAction returns active children with count', function () {
    $parent = Module::factory()->create();

    // Active child
    Module::factory()->create(['parent_id' => $parent->id, 'is_active' => true]);

    // Inactive child
    Module::factory()->create(['parent_id' => $parent->id, 'is_active' => false]);

    // Grandchild (to check withCount('children'))
    $child = Module::factory()->create(['parent_id' => $parent->id, 'is_active' => true]);
    Module::factory()->create(['parent_id' => $child->id]);

    $action = new GetSubModulesAction;
    $results = $action->execute($parent->id);

    expect($results)->toHaveCount(2); // Only active ones
    expect($results->where('id', $child->id)->first()->children_count)->toBe(1);
});
