<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Blade;
use Jenssegers\Agent\Agent;
use Illuminate\Support\ServiceProvider;

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
        if (app()->isProduction() || str_starts_with((string) config('app.url'), 'https://')) {
            URL::forceScheme('https');
        }

        Blade::if('mobile', function () {
            $agent = new Agent();
            return $agent->isMobile();
        });
    }
}
