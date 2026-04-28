<?php

use Illuminate\Support\Facades\Route;
use Modules\Purchasing\Http\Controllers\PurchasingController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('purchasings', PurchasingController::class)->names('purchasing');
});
