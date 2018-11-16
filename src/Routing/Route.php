<?php

namespace Morphable\SimpleRouting;

/**
 * Single route
 */
class Route
{
    private $method;

    private $route;

    private $pattern;

    private $callback;

    private $middleware = [];

    public function __construct(string $method, string $route, callable $callback, array $middlewares = [])
    {
        $this->method = $method;
        $this->route = $route;
    }

    private function getPattern()
    {
        return $this->pattern;
    }

    public function execute(Request $req, Response $res)
    {
        foreach ($this->middleware as $mw) {
            $mw($req, $res);
        }

        ($this->callback)($req, $res);
        $res->respond();
    }
}
