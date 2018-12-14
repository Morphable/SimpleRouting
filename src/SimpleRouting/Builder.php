<?php

namespace Morphable\SimpleRouting;

class Builder
{
    /**
     * @var string
     */
    private $method;

    /**
     * @var string
     */
    private $route;

    /**
     * @var callable
     */
    private $callback;

    /**
     * @var array
     */
    private $middleware = [];

    /**
     * @param string method (GET, POST, PUT, PATCH, DELETE)
     * @return self
     */
    public function setMethod(string $method)
    {
        $this->method = strtoupper($method);

        return $this;
    }

    /**
     * @param string route
     * @return self
     */
    public function setRoute(string $route)
    {
        $this->route = $route;

        return $this;
    }

    /**
     * @param callable|array|string callback
     * @return self
     */
    public function setCallback($callback)
    {
        $this->callback = $callback;

        return $this;
    }

    /**
     * @param callable|array middlewares
     * @return self
     */
    public function setMiddleware($middlewares)
    {
        if (is_array($middlewares)) {
            $this->middleware = array_merge($this->middleware, $middlewares);
            return $this;
        }

        $this->middleware[] = $middlewares;

        return $this;
    }

    /**
     * Build new object
     *
     * @return Morphable\SimpleRouting\Route
     */
    public function build()
    {
        $this->instance = new Route($this->method, $this->route, $this->callback, $this->middleware);
        return $this->instance;
    }

    /**
     * Build route from array
     *
     * @param array
     * @return \Morphable\SimpleRouting\Route
     */
    public static function fromArray(array $data)
    {
        if (!is_array($data['middleware'])) {
            $data['middleware'] = [$data['middleware']];
        }

        return new Route($data['method'], $data['route'], $data['callback'], $data['middleware']);
    }
}
