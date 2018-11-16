<?php

namespace Morphable\SimpleRouting;

/**
 * Single route
 */
class Route
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
     * @var string
     */
    private $pattern;

    /**
     * @var callable
     */
    private $callback;

    /**
     * @var array
     */
    private $params = [];

    /**
     * @var array
     */
    private $middleware;

    /**
     * @param string request method
     * @param string route
     * @param callable callback
     * @param array middleware
     * @return self
     */
    public function __construct(string $method, string $route, callable $callback, array $middleware = [])
    {
        $this->method = strtoupper($method);
        $this->route = Path::normalize(strtolower($route));
        $this->callback = $callback;
        $this->middleware = $middleware;
    }

    /**
     * @return string method
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * :param<>
     * @return string pattern
     */
    public function getPattern()
    {
        if (is_string($this->pattern)) {
            return $this->pattern;
        }

        $pattern = "";
        $params = explode('/', trim($this->route, '/'));
        foreach ($params as $index => $param) {
            if ($param[0] == ':') {
                $pattern .= "\/(.*?)";
                $this->params[substr($param, 1)] = $index;
            } else {
                $pattern .= "\/$param";
            }
        }
        $pattern .= "\/";

        $this->pattern = "/$pattern/";
        return $this->pattern;
    }

    /**
     * @param string request url
     * @return bool
     */
    public function match($url)
    {
        $url = Path::normalize($url) . '/';
        return (bool) preg_match($this->getPattern(), $url);
    }

    /**
     * @param string request url
     */
    public function fillParams($url)
    {
        $params = explode('/', trim($url, '/'));

        foreach ($this->params as $key => $value) {
            $this->params[$key] = $params[$value];
        }

        return $this->params;
    }

    /**
     * @param Request req
     * @param Response
     * @return void
     */
    public function execute(Request $req, Response $res)
    {
        if (!$this->getMethod() === $req->getMethod()) {
            return;
        }

        if (!$this->match($req->getPath())) {
            return;
        }

        $params = $this->fillParams($req->getPath());
        $req->setParams($params);

        foreach ($this->middleware as $mw) {
            $mw($req, $res);
        }

        ($this->callback)($req, $res);
        $res->respond();
    }
}
