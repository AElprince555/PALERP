<?php

// مسارات عامة (قبل الدخول)
Route::middleware(['guest'])->group(function () {
    Route::view('/login', 'layouts.login')->name('login');
});

// مسارات النظام (بعد الدخول)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::livewire('/','home');

});

Route::fallback(function () {
    return redirect('login');
});
