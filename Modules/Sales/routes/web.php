<?php

use App\Livewire\System\Application;
use App\Livewire\System\Sector;
use App\Livewire\System\SubModule;
use Illuminate\Support\Facades\Route;
use Modules\Sales\Http\Controllers\SalesController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('sal')->group(function () {
        Route::get('/', Sector::class)->name('sales');
        Route::get('/con', SubModule::class)->name('sales.contracts');
        Route::get('/quo', SubModule::class)->name('sales.quotations');
        Route::get('/ord', SubModule::class)->name('sales.orders');
        Route::get('/inv', SubModule::class)->name('sales.invoices');
        Route::get('/col', SubModule::class)->name('sales.collections');
        Route::get('/pos', SubModule::class)->name('sales.pos');

        // Applications
        Route::get('/col/agn', Application::class)->name('sales.collections.aging');
        Route::get('/col/rcp', Application::class)->name('sales.collections.receipts');
        Route::get('/col/stmt', Application::class)->name('sales.collections.statement');
        Route::get('/con/mng', Application::class)->name('sales.contracts.manage');
        Route::get('/con/prc', Application::class)->name('sales.contracts.prices');
        Route::get('/inv/einv', Application::class)->name('sales.invoices.electronic');
        Route::get('/inv/gen', Application::class)->name('sales.invoices.index');
        Route::get('/ord/app', Application::class)->name('sales.orders.credit-check');
        Route::get('/ord/crt', Application::class)->name('sales.orders.create');
        Route::get('/ord/shp', Application::class)->name('sales.orders.shipping');
        Route::get('/pos/reg', Application::class)->name('sales.pos.register');
        Route::get('/pos/ses', Application::class)->name('sales.pos.shifts');
        Route::get('/pos/xrc', Application::class)->name('sales.pos.reports');
        Route::get('/quo/app', Application::class)->name('sales.quotes.approvals');
        Route::get('/quo/crt', Application::class)->name('sales.quotes.create');
        Route::get('/quo/stt', Application::class)->name('sales.quotes.status');
    });

    Route::resource('sales', SalesController::class)->names('sales.index');
});
