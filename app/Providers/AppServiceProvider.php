<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind('path.public', function () {
            return base_path('../public_html');
        });
    }

    public function boot(): void
    {
        $locale = session('app_locale', 'ar');
        app()->setLocale($locale);
        config(['app.locale' => $locale]);
    }
}
