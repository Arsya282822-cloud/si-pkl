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

        // Di server local (localhost / 127.0.0.1), selalu gunakan HTTP biasa.
        // Hanya paksa HTTPS jika di server production atau jika URL terkonfigurasi dengan https://
        $isLocalHost = in_array(request()->getHost(), ['localhost', '127.0.0.1', '::1', '']) || 
                       $this->app->environment(['local', 'development', 'testing']);

        if (!$isLocalHost) {
            if (
                request()->server('HTTP_X_FORWARDED_PROTO') === 'https' ||
                request()->header('X-Forwarded-Proto') === 'https' ||
                str_starts_with(config('app.url'), 'https://') ||
                $this->app->environment('production')
            ) {
                URL::forceScheme('https');
            }
        }
    }
}
