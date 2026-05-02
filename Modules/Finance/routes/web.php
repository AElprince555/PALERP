<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use Modules\Finance\Http\Controllers\FinanceController;
use App\Livewire\System\Sector;
use Modules\Finance\Livewire\Gl\AccountsChart;

Route::middleware(['auth', 'verified'])->group(function () {
    // Use the component class instead of a closure
    Route::get('/fin',Sector::class)->name('finance');

    Route::get('/fin/gl/coa', AccountsChart::class)->name('finance.gl.tree');
    Route::get('/fin/gl', AccountsChart::class)->name('finance.gl');
});
