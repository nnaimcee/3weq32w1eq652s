<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        // ถ้าอยู่บน Production (Render) ให้บังคับใช้ HTTPS
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
