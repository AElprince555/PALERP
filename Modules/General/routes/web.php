<?php

use App\Livewire\System\Application;
use App\Livewire\System\Sector;
use App\Livewire\System\SubModule;
use Illuminate\Support\Facades\Route;
use Modules\General\Http\Controllers\GeneralController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('gen')->group(function () {
        Route::get('/', Sector::class)->name('general');
        Route::get('/wrd', SubModule::class)->name('general.world');
        Route::get('/set', SubModule::class)->name('general.settings');
        Route::get('/sec', SubModule::class)->name('general.security');
        Route::get('/aud', SubModule::class)->name('general.audit');
        Route::get('/not', SubModule::class)->name('general.notifications');
        Route::get('/fil', SubModule::class)->name('general.files');
        Route::get('/app', SubModule::class)->name('general.apps');

        // Applications
        Route::get('/app/cal', Application::class)->name('general.apps.calendar');
        Route::get('/app/cht', Application::class)->name('general.apps.chat');
        Route::get('/app/ibx', Application::class)->name('general.apps.inbox');
        Route::get('/app/not', Application::class)->name('general.apps.notes');
        Route::get('/app/tsk', Application::class)->name('general.apps.tasks');
        Route::get('/aud/hst', Application::class)->name('general.audit.history');
        Route::get('/aud/set', Application::class)->name('general.audit.settings');
        Route::get('/aud/sys', Application::class)->name('general.audit.system-logs');
        Route::get('/aud/trk', Application::class)->name('general.audit.tracking');
        Route::get('/fil/arc', Application::class)->name('general.files.archive');
        Route::get('/fil/lib', Application::class)->name('general.files.library');
        Route::get('/fil/sec', Application::class)->name('general.files.security');
        Route::get('/fil/set', Application::class)->name('general.files.settings');
        Route::get('/not/evt', Application::class)->name('general.notifications.events');
        Route::get('/not/myn', Application::class)->name('general.notifications.mine');
        Route::get('/not/set', Application::class)->name('general.notifications.settings');
        Route::get('/not/tmp', Application::class)->name('general.notifications.templates');
        Route::get('/sec/bch', Application::class)->name('general.security.branches');
        Route::get('/sec/frm', Application::class)->name('general.security.fields');
        Route::get('/sec/log', Application::class)->name('general.security.logs');
        Route::get('/sec/pol', Application::class)->name('general.security.policies');
        Route::get('/sec/rol', Application::class)->name('general.security.roles');
        Route::get('/sec/usr', Application::class)->name('general.security.users');
        Route::get('/set/api', Application::class)->name('general.settings.api');
        Route::get('/set/bnd', Application::class)->name('general.settings.branding');
        Route::get('/set/def', Application::class)->name('general.settings.defaults');
        Route::get('/set/mod', Application::class)->name('general.settings.modules');
        Route::get('/set/seq', Application::class)->name('general.settings.sequences');
        Route::get('/wrd/cnt', Application::class)->name('general.world.countries');
        Route::get('/wrd/com', Application::class)->name('general.world.companies');
        Route::get('/wrd/cty', Application::class)->name('general.world.cities');
        Route::get('/wrd/cur', Application::class)->name('general.world.currencies');
        Route::get('/wrd/ppl', Application::class)->name('general.world.people');
        Route::get('/wrd/sta', Application::class)->name('general.world.states');
        Route::get('/wrd/tmz', Application::class)->name('general.world.timezones');
    });

    Route::resource('generals', GeneralController::class)->names('general.index');
});
