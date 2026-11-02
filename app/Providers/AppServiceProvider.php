<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
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
        URL::forceScheme('https');

        // Register Gmail API transport (bypasses Railway SMTP port blocks)
        \Illuminate\Support\Facades\Mail::extend('gmail-api', function () {
            return new \App\Mail\Transport\GmailApiTransport(
                clientId:     env('GOOGLE_MAILER_CLIENT_ID', ''),
                clientSecret: env('GOOGLE_MAILER_CLIENT_SECRET', ''),
                refreshToken: env('GOOGLE_MAILER_REFRESH_TOKEN', ''),
            );
        });

        $version = env('APP_VERSION', '5.0.0');
        View::share('appVersion', $version);
    }
}
