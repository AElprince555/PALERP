<?php

use App\Livewire\System\Application;
use App\Livewire\System\Sector;
use App\Livewire\System\SubModule;
use Illuminate\Support\Facades\Route;
use Modules\Inventory\Http\Controllers\InventoryController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('inv')->group(function () {
        Route::get('/', Sector::class)->name('inventory');
        Route::get('/mst', SubModule::class)->name('inventory.master');
        Route::get('/ops', SubModule::class)->name('inventory.operations');
        Route::get('/stk', SubModule::class)->name('inventory.audit');
        Route::get('/val', SubModule::class)->name('inventory.valuation');

        // Applications
        Route::get('/mst/cat', Application::class)->name('inventory.master.categories');
        Route::get('/mst/itm', Application::class)->name('inventory.master.items');
        Route::get('/mst/loc', Application::class)->name('inventory.master.locations');
        Route::get('/mst/set', Application::class)->name('inventory.master.settings');
        Route::get('/mst/unt', Application::class)->name('inventory.master.units');
        Route::get('/ops/grn', Application::class)->name('inventory.operations.receipts');
        Route::get('/ops/iss', Application::class)->name('inventory.operations.issues');
        Route::get('/ops/ret', Application::class)->name('inventory.operations.returns');
        Route::get('/ops/set', Application::class)->name('inventory.operations.settings');
        Route::get('/ops/trf', Application::class)->name('inventory.operations.transfers');
        Route::get('/stk/adj', Application::class)->name('inventory.audit.adjustments');
        Route::get('/stk/ord', Application::class)->name('inventory.audit.orders');
        Route::get('/stk/set', Application::class)->name('inventory.audit.settings');
        Route::get('/stk/tly', Application::class)->name('inventory.audit.counting');
        Route::get('/val/cst', Application::class)->name('inventory.valuation.adjustments');
        Route::get('/val/mth', Application::class)->name('inventory.valuation.methods');
        Route::get('/val/set', Application::class)->name('inventory.valuation.settings');
        Route::get('/val/val', Application::class)->name('inventory.valuation.reports');
    });

    Route::resource('inventories', InventoryController::class)->names('inventory.index');
});
