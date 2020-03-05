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
        AttributeSchema::observe(AttributeSchemaObserver::class);
        DataView::observe(DataViewObserver::class);
    }

}
