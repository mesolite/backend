<?php

namespace App\Compass;

use Illuminate\Support\Str;
use Illuminate\Routing\Route;
use Davidhsianturi\Compass\RouteResolver as BaseRouteResolver;

class RouteResolver extends BaseRouteResolver
{
    /**
     * Retrieve title from the route
     *
     * @param Route $route
     *
     * @return string
     */
    public function getTitle(Route $route)
    {
        return ucfirst(last(explode(".", $route->getName())));
    }

    /**
     * Retrieve description from the route
     *
     * @param Route $route
     *
     * @return string
     */
    public function getDescription(Route $route)
    {
        return null;
    }
}
