<?php

namespace App\Providers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->registerModuleLivewireComponents();
    }
    private function registerModuleLivewireComponents(): void
    {
        $modulesPath = base_path('Modules');

        if (!File::exists($modulesPath)) {
            return;
        }

        // المرور على كل الموديولات (Finance, HR, etc.)
        foreach (File::directories($modulesPath) as $modulePath) {
            $moduleName = basename($modulePath);
            $livewireDir = $modulePath . '/Livewire';

            if (!File::exists($livewireDir)) {
                continue;
            }

            // جلب كافة ملفات Livewire داخل الموديول
            $files = File::allFiles($livewireDir);

            foreach ($files as $file) {
                // 1. استخراج مسار الـ Class
                $relativePath = $file->getRelativePathname();
                $classPath = str_replace(['/', '.php'], ['\\', ''], $relativePath);
                $class = "Modules\\{$moduleName}\\Livewire\\{$classPath}";

                // 2. بناء الـ Alias ليتم استدعاؤه في الـ Blade
                // مثال: finance::gl.journal-form
                $aliasPrefix = strtolower($moduleName) . '::';

                $aliasPath = collect(explode('\\', $classPath))
                    ->map(fn ($part) => Str::kebab($part))
                    ->implode('.');

                $alias = $aliasPrefix . $aliasPath;

                // 3. التسجيل الرسمي في Livewire
                if (class_exists($class)) {
                    Livewire::component($alias, $class);
                }
            }
        }
    }
}
