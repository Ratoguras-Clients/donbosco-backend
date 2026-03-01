<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ModulesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $modulesPath = base_path('Modules');

        if (!is_dir($modulesPath)) {
            return;
        }

        foreach (scandir($modulesPath) as $module) {
            if ($module === '.' || $module === '..') {
                continue;
            }

            $providerBase = "Modules\\{$module}\\App\\Providers";

            $providers = [
                "{$module}AppServiceProvider",
                "{$module}RouteServiceProvider",
                "{$module}EventServiceProvider",
                "{$module}BroadcastServiceProvider",
            ];

            foreach ($providers as $providerClass) {
                $fullClass = "{$providerBase}\\{$providerClass}";

                if (class_exists($fullClass)) {
                    $this->app->register($fullClass);
                }
            }
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
