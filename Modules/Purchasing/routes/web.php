<?php

use App\Livewire\System\Application;
use App\Livewire\System\Sector;
use App\Livewire\System\SubModule;
use Illuminate\Support\Facades\Route;
use Modules\Purchasing\Http\Controllers\PurchasingController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('pur')->group(function () {
        Route::get('/', Sector::class)->name('purchasing');
        Route::get('/vnd', SubModule::class)->name('purchasing.vendors');
        Route::get('/con', SubModule::class)->name('purchasing.contracts');
        Route::get('/req', SubModule::class)->name('purchasing.requisitions');
        Route::get('/rfq', SubModule::class)->name('purchasing.rfq');
        Route::get('/ord', SubModule::class)->name('purchasing.orders');
        Route::get('/inv', SubModule::class)->name('purchasing.invoices');
        Route::get('/pay', SubModule::class)->name('purchasing.payments');
        Route::get('/ret', SubModule::class)->name('purchasing.returns');

        // Applications
        Route::get('/con/doc', Application::class)->name('purchasing.contracts.documents');
        Route::get('/con/itm', Application::class)->name('purchasing.contracts.items');
        Route::get('/con/mng', Application::class)->name('purchasing.contracts.manage');
        Route::get('/con/pay', Application::class)->name('purchasing.contracts.payments');
        Route::get('/con/prc', Application::class)->name('purchasing.contracts.prices');
        Route::get('/inv/app', Application::class)->name('purchasing.invoices.approvals');
        Route::get('/inv/ent', Application::class)->name('purchasing.invoices.entry');
        Route::get('/inv/exp', Application::class)->name('purchasing.invoices.landed-costs');
        Route::get('/inv/mtch', Application::class)->name('purchasing.invoices.match');
        Route::get('/ord/app', Application::class)->name('purchasing.orders.approvals');
        Route::get('/ord/crt', Application::class)->name('purchasing.orders.create');
        Route::get('/ord/mng', Application::class)->name('purchasing.orders.manage');
        Route::get('/ord/trk', Application::class)->name('purchasing.orders.tracking');
        Route::get('/pay/adv', Application::class)->name('purchasing.payments.advances');
        Route::get('/pay/app', Application::class)->name('purchasing.payments.approvals');
        Route::get('/pay/pym', Application::class)->name('purchasing.payments.process');
        Route::get('/pay/stmt', Application::class)->name('purchasing.payments.statement');
        Route::get('/req/app', Application::class)->name('purchasing.requisitions.approvals');
        Route::get('/req/cns', Application::class)->name('purchasing.requisitions.consolidate');
        Route::get('/req/crt', Application::class)->name('purchasing.requisitions.create');
        Route::get('/req/trk', Application::class)->name('purchasing.requisitions.tracking');
        Route::get('/ret/app', Application::class)->name('purchasing.returns.approvals');
        Route::get('/ret/crt', Application::class)->name('purchasing.returns.create');
        Route::get('/ret/dbn', Application::class)->name('purchasing.returns.debit-notes');
        Route::get('/rfq/cmp', Application::class)->name('purchasing.rfq.compare');
        Route::get('/rfq/crt', Application::class)->name('purchasing.rfq.create');
        Route::get('/rfq/rec', Application::class)->name('purchasing.rfq.quotes');
        Route::get('/rfq/sel', Application::class)->name('purchasing.rfq.award');
        Route::get('/vnd/cat', Application::class)->name('purchasing.vendors.categories');
        Route::get('/vnd/evl', Application::class)->name('purchasing.vendors.evaluation');
        Route::get('/vnd/mas', Application::class)->name('purchasing.vendors.master');
    });

    Route::resource('purchasings', PurchasingController::class)->names('purchasing.index');
});
