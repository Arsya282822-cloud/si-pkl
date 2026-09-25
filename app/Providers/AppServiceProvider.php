<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::useBootstrapFive();

        // Enforce HTTPS Scheme jika diakses via HTTPS, Reverse Proxy, Cloudflare, Ngrok, atau Server Production
        if (
            request()->server('HTTP_X_FORWARDED_PROTO') === 'https' ||
            request()->header('X-Forwarded-Proto') === 'https' ||
            request()->isSecure() ||
            str_contains(config('app.url'), 'https://') ||
            $this->app->environment('production')
        ) {
            URL::forceScheme('https');
        }
    }
}
