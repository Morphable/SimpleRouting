<?php

namespace Morphable\SimpleRouting;

class Dispatcher
{
    /**
     * @var array
     */
    private $routes;

    public function __construct(array $routes = [])
    {
        $this->routes = $routes;
    }

    public function execute()
    {
        $request = Request::fromGlobals();
        $response = new Response();

        foreach ($this->routes as $name => $route) {
            $route->execute($request, $response);
        }
    }
}
