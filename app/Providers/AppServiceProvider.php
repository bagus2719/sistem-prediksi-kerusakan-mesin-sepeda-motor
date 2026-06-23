<?php

namespace App\Providers;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use App\Http\Responses\LoginResponse as CustomLoginResponse;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
         $this->app->singleton(
            LoginResponseContract::class,
            CustomLoginResponse::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Jika request memiliki header X-Forwarded-Proto (selalu dikirim oleh Ngrok) atau diakses via HTTPS
        if (request()->header('x-forwarded-proto') === 'https' || str_contains(request()->getHost(), 'ngrok')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
    }
}
