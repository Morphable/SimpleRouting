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
     * @param callable|array|string callback
     * @param array middleware
     * @return self
     */
    public function __construct(string $method, string $route, $callback, array $middleware = [])
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
     * @return string pattern
     */
    public function getPattern()
    {
        $pattern = "^";
        $params = explode('/', trim($this->route, '/'));
        foreach ($params as $index => $param) {
            if (isset($param[0]) && $param[0] == ':') {
                $pattern .= "\/(\d|\w|-|_|\.)*?";
                $this->params[substr($param, 1)] = $index;
            } else {
                $pattern .= "\/$param";
            }
        }
        $pattern .= "\/$";

        $this->pattern = "/$pattern/";
        return $this->pattern;
    }

    /**
     * @param string request url
     * @return bool
     */
    public function match(string $url)
    {
        $url = Path::normalize($url) . '/';
        return (bool) preg_match($this->getPattern(), $url);
    }

    /**
     * @param string request url
     * @return array
     */
    public function fillParams(string $url)
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
        if ($this->getMethod() !== $req->getMethod()) {
            return;
        }

        if (!$this->match($req->getPath())) {
            return;
        }

        $params = $this->fillParams($req->getPath());
        $req->setParams($params);

        foreach ($this->middleware as $mw) {
            if (is_callable($mw)) {
                $mw($req, $res);
            } elseif (is_array($mw)) {
                \call_user_func($mw, [$req, $res]);
            }
        }

        $this->execCallback($req, $res);
        $res->sendResponse();
    }

    /**
     * @param Request req
     * @param Response res
     * @return void
     */
    public function execCallback(Request $req, Response $res)
    {
        if (is_callable($this->callback)) {
            return ($this->callback)($req, $res);
        }

        return \call_user_func($this->callback, [$req, $res]);
    }
}
