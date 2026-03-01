<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Helper\ColorHelper;
use App\Helper\Helper;
use App\Models\Module;
use App\Models\User;
use Illuminate\Support\Facades\View;

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
        View::composer('*', function ($view) {
            $module = null;
            $colorKey = $module && $module->primary_color ? $module->primary_color : 'orange';
            $moduleColorClasses = ColorHelper::getColorClasses($colorKey);
            $moduleColorClasses['dangerhover'] = 'hover:bg-red-50 dark:hover:bg-gray-700 hover:text-red-600 dark:hover:text-red-400';
            $view->with('modulecolor', $moduleColorClasses);
        });
    }
}
