<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Website\SiteSetting;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $settings = SiteSetting::select('key', 'value', 'type', 'label')->get()->keyBy('key');
            $view->with('siteSettings', $settings);
        });
    }
}
