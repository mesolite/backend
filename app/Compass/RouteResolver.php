<?php

namespace App\Compass;

use Davidhsianturi\Compass\RouteResolver as BaseRouteResolver;
use Illuminate\Routing\Route;

class RouteResolver extends BaseRouteResolver
{
    /**
     * Retrieve title from the route.
     *
     * @return string
     */
    public function getTitle(Route $route)
    {
        return ucfirst(last(explode('.', $route->getName())));
    }

    /**
     * Retrieve description from the route.
     *
     * @return string
     */
    public function getDescription(Route $route)
    {
        return null;
    }
}
