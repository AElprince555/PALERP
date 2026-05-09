<?php

use App\Livewire\System\Application;
use App\Livewire\System\Sector;
use App\Livewire\System\SubModule;
use Illuminate\Support\Facades\Route;
use Modules\CRM\Http\Controllers\CRMController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('crm')->group(function () {
        Route::get('/', Sector::class)->name('crm');
        Route::get('/ld', SubModule::class)->name('crm.leads');
        Route::get('/opp', SubModule::class)->name('crm.opportunities');
        Route::get('/act', SubModule::class)->name('crm.activities');
        Route::get('/cmp', SubModule::class)->name('crm.campaigns');
        Route::get('/cst', SubModule::class)->name('crm.customers');

        // Applications
        Route::get('/act/cal', Application::class)->name('crm.activities.calendar');
        Route::get('/act/log', Application::class)->name('crm.activities.calls');
        Route::get('/cmp/crt', Application::class)->name('crm.marketing.campaigns');
        Route::get('/cst/dir', Application::class)->name('crm.customers.index');
        Route::get('/ld/flw', Application::class)->name('crm.leads.followup');
        Route::get('/ld/reg', Application::class)->name('crm.leads.register');
        Route::get('/opp/pip', Application::class)->name('crm.opportunities.pipeline');
        Route::get('/opp/won', Application::class)->name('crm.opportunities.won');
    });

    Route::resource('crms', CRMController::class)->names('crm.index');
});
