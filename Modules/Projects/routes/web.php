<?php

use App\Livewire\System\Application;
use App\Livewire\System\Sector;
use App\Livewire\System\SubModule;
use Illuminate\Support\Facades\Route;
use Modules\Projects\Http\Controllers\ProjectsController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('prj')->group(function () {
        Route::get('/', Sector::class)->name('projects');
        Route::get('/mst', SubModule::class)->name('projects.master');
        Route::get('/pln', SubModule::class)->name('projects.planning');
        Route::get('/res', SubModule::class)->name('projects.resources');
        Route::get('/cst', SubModule::class)->name('projects.costs');
        Route::get('/tsk', SubModule::class)->name('projects.tasks');
        Route::get('/doc', SubModule::class)->name('projects.documents');

        // Applications
        Route::get('/cst/act', Application::class)->name('projects.costs.actual');
        Route::get('/cst/bgt', Application::class)->name('projects.costs.budget');
        Route::get('/doc/con', Application::class)->name('projects.documents.contracts');
        Route::get('/doc/dwg', Application::class)->name('projects.documents.drawings');
        Route::get('/mst/cat', Application::class)->name('projects.master.categories');
        Route::get('/mst/mst', Application::class)->name('projects.master.index');
        Route::get('/pln/gnt', Application::class)->name('projects.planning.gantt');
        Route::get('/pln/wbs', Application::class)->name('projects.planning.wbs');
        Route::get('/res/eqp', Application::class)->name('projects.resources.equipment');
        Route::get('/res/stf', Application::class)->name('projects.resources.staff');
        Route::get('/tsk/lst', Application::class)->name('projects.tasks.index');
        Route::get('/tsk/tms', Application::class)->name('projects.tasks.timesheets');
    });

    Route::resource('projects', ProjectsController::class)->names('projects.index');
});
