<?php

namespace App\Providers;

use App\Compass\RouteResolver;
use Davidhsianturi\Compass\Contracts\RouteResolverContract;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
    }

    /**
     * Register any application services.
     */
    public function register()
    {
        /*$this->app->singleton(
            RouteResolverContract::class, RouteResolver::class
        );*/
    }
}
