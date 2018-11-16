<?php

namespace Morphable;

use Morphable\SimpleRouting\Dispatcher;

/**
 * Main object to call and initialize process
 */
class SimpleRouting
{
    private static $routes = [];

    public static function add($name, $route)
    {
        self::$routes[$name] = $route;
    }

    public static function remove($name)
    {
        unset(self::$routes[$name]);
    }

    public static function execute()
    {
        (new Dispatcher(self::$routes))->execute();
    }
}
