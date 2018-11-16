<?php

namespace Morphable;

use Morphable\SimpleRouting\Dispatcher;
use Morphable\SimpleRouting\Route;

/**
 * Main object to call and initialize process
 */
class SimpleRouting
{
    /**
     * @var array
     */
    private static $routes = [];

    /**
     * @param string route name
     * @param Route
     * @return void
     */
    public static function add(string $name, Route $route)
    {
        self::$routes[$name] = $route;
    }

    /**
     * @param string name
     */
    public static function remove(string $name)
    {
        unset(self::$routes[$name]);
    }

    public static function execute()
    {
        (new Dispatcher(self::$routes))->execute();
    }
}
