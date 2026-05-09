<?php

use App\Livewire\System\Application;
use App\Livewire\System\Sector;
use App\Livewire\System\SubModule;
use Illuminate\Support\Facades\Route;
use Modules\HumanResources\Http\Controllers\HumanResourcesController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('hr')->group(function () {
        Route::get('/', Sector::class)->name('hr');
        Route::get('/org', SubModule::class)->name('hr.org');
        Route::get('/emp', SubModule::class)->name('hr.employees');
        Route::get('/att', SubModule::class)->name('hr.attendance');
        Route::get('/pay', SubModule::class)->name('hr.payroll');
        Route::get('/rec', SubModule::class)->name('hr.recruitment');
        Route::get('/per', SubModule::class)->name('hr.performance');
        Route::get('/ess', SubModule::class)->name('hr.portal');

        // Applications
        Route::get('/att/lev', Application::class)->name('hr.attendance.leaves');
        Route::get('/att/log', Application::class)->name('hr.attendance.logs');
        Route::get('/att/pol', Application::class)->name('hr.attendance.policies');
        Route::get('/att/prc', Application::class)->name('hr.attendance.processing');
        Route::get('/att/set', Application::class)->name('hr.attendance.settings');
        Route::get('/att/shf', Application::class)->name('hr.attendance.shifts');
        Route::get('/emp/alc', Application::class)->name('hr.employees.allocation');
        Route::get('/emp/con', Application::class)->name('hr.employees.contracts');
        Route::get('/emp/cus', Application::class)->name('hr.employees.custody');
        Route::get('/emp/doc', Application::class)->name('hr.employees.documents');
        Route::get('/emp/reg', Application::class)->name('hr.employees.register');
        Route::get('/emp/set', Application::class)->name('hr.employees.settings');
        Route::get('/ess/adv', Application::class)->name('hr.ess.advances');
        Route::get('/ess/att', Application::class)->name('hr.ess.attendance');
        Route::get('/ess/let', Application::class)->name('hr.ess.letters');
        Route::get('/ess/lev', Application::class)->name('hr.ess.leaves');
        Route::get('/ess/pay', Application::class)->name('hr.ess.payslips');
        Route::get('/ess/set', Application::class)->name('hr.ess.settings');
        Route::get('/org/brn', Application::class)->name('hr.org.branches');
        Route::get('/org/cmp', Application::class)->name('hr.org.companies');
        Route::get('/org/dep', Application::class)->name('hr.org.departments');
        Route::get('/org/grd', Application::class)->name('hr.org.grades');
        Route::get('/org/job', Application::class)->name('hr.org.jobs');
        Route::get('/org/set', Application::class)->name('hr.org.settings');
        Route::get('/org/str', Application::class)->name('hr.org.chart');
        Route::get('/pay/adv', Application::class)->name('hr.payroll.advances');
        Route::get('/pay/ded', Application::class)->name('hr.payroll.deductions');
        Route::get('/pay/eos', Application::class)->name('hr.payroll.eos');
        Route::get('/pay/inc', Application::class)->name('hr.payroll.income');
        Route::get('/pay/run', Application::class)->name('hr.payroll.run');
        Route::get('/pay/set', Application::class)->name('hr.payroll.settings');
        Route::get('/pay/str', Application::class)->name('hr.payroll.structures');
        Route::get('/per/app', Application::class)->name('hr.performance.appraisals');
        Route::get('/per/inc', Application::class)->name('hr.performance.incentives');
        Route::get('/per/kpi', Application::class)->name('hr.performance.kpis');
        Route::get('/per/set', Application::class)->name('hr.performance.settings');
        Route::get('/per/trn', Application::class)->name('hr.performance.training');
        Route::get('/rec/can', Application::class)->name('hr.recruitment.candidates');
        Route::get('/rec/int', Application::class)->name('hr.recruitment.interviews');
        Route::get('/rec/ofr', Application::class)->name('hr.recruitment.offers');
        Route::get('/rec/req', Application::class)->name('hr.recruitment.requisitions');
        Route::get('/rec/set', Application::class)->name('hr.recruitment.settings');
    });

    Route::resource('humanresources', HumanResourcesController::class)->names('humanresources.index');
});
