<?php

namespace Morphable\SimpleRouting;

class Dispatcher
{
    /**
     * @var array
     */
    private $routes;

    /**
     * @var array
     * @return self
     */
    public function __construct(array $routes = [])
    {
        $this->routes = $routes;
        return $this;
    }

    /**
     * @return void
     */
    public function execute()
    {
        $request = Request::fromGlobals();
        $response = new Response();

        foreach ($this->routes as $name => $route) {
            $route->execute($request, $response);
        }
    }
}
