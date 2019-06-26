<?php

namespace Morphable\SimpleRouting;

class Dispatcher
{
    /**
     * @var array
     */
    private $routes;

    /**
     * @var mixed
     */
    private $container;

    /**
     * @var array
     * @var mixed
     * @return self
     */
    public function __construct(array $routes = [], $container = null)
    {
        $this->routes = $routes;
        $this->container = $container;
        return $this;
    }

    /**
     * @return void
     */
    public function execute()
    {
        $request = Request::incomming();
        $response = new Response();

        foreach ($this->routes as $name => $route) {
            $route->execute($request, $response, $this->container);
        }

        throw new Exception\RouteNotFound();
    }
}
