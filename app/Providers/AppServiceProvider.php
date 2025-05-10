<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Config;
use App\Models\Restaurant;
use App\Observers\RestaurantObserver;

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
        // if (config('app.env') !== 'local') {
        //     URL::forceScheme('https');
        // }

        // Get the server's timezone
        $serverTimezone = date_default_timezone_get();

        // Set the application's timezone to the server's timezone
        Config::set('app.timezone', $serverTimezone);

        Restaurant::observe(RestaurantObserver::class);
    }
}
