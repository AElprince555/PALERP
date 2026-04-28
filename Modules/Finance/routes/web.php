<?php

use Illuminate\Support\Facades\Route;
use Modules\Finance\Http\Controllers\FinanceController;
use Modules\Finance\Livewire\Gl\AccountsChart;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('finances', FinanceController::class)->names('finance');
    Route::get('/finance/gl/accounts', AccountsChart::class)->name('finance.gl.accounts');
});
