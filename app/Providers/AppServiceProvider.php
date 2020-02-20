<?php

namespace App\Providers;

use App\Compass\RouteResolver;
use Davidhsianturi\Compass\Contracts\RouteResolverContract;
use Illuminate\Support\ServiceProvider;
use Amethyst\Models\DataSchema;
use App\Observers\DataSchemaObserver;
use Amethyst\Models\RelationSchema;
use App\Observers\RelationSchemaObserver;
use Amethyst\Models\Attribute;
use App\Observers\AttributeObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        app('amethyst.data-schema')->boot();
        app('amethyst.attributable')->boot();
        app('amethyst.relation-schema')->boot();
        app('eloquent.mapper')->boot();

        DataSchema::observe(DataSchemaObserver::class);
        RelationSchema::observe(RelationSchemaObserver::class);
        Attribute::observe(AttributeObserver::class);
    }

}
