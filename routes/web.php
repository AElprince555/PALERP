<?php

// مسارات عامة (قبل الدخول)
Route::middleware(['guest'])->group(function () {
    Route::view('/login', 'layouts.login')->name('login');
});

// مسارات النظام (بعد الدخول)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('/',function (){
       return redirect()->route('dashboard');
    });

});

Route::fallback(function () {
    return redirect('login');
});
