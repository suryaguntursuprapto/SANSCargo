<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;

class LocaleServiceProvider extends ServiceProvider
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
        // Set locale from session at application boot
        if (session()->has('locale')) {
            App::setLocale(session('locale'));
        }
        
        // Listen for session updates to locale
        $this->app['events']->listen('session.locale.changed', function ($locale) {
            App::setLocale($locale);
        });
    }
}