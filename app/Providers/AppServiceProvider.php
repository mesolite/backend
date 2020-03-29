<?php

namespace App\Providers;

use App\Compass\RouteResolver;
use Davidhsianturi\Compass\Contracts\RouteResolverContract;
use Illuminate\Support\ServiceProvider;
use Amethyst\Models\DataSchema;
use Amethyst\Models\RelationSchema;
use Amethyst\Models\AttributeSchema;
use Amethyst\Models\DataView;
use App\Observers\DataSchemaObserver;
use App\Observers\RelationSchemaObserver;
use App\Observers\AttributeSchemaObserver;
use App\Observers\DataViewObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        // ...
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        // ...    
    }

}
