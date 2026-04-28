<?php

use Illuminate\Support\Facades\Route;
use Modules\Finance\Http\Controllers\FinanceController;
use Modules\Finance\Livewire\Gl\AccountsChart;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('finance', AccountsChart::class)->name('finance');
    Route::get('/fin/gl/coa', AccountsChart::class)->name('finance.gl.tree');
    Route::get('/fin/gl', AccountsChart::class)->name('finance.gl');
});
