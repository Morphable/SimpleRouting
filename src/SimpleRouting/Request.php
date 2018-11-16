<?php

namespace Morphable\SimpleRouting;

/**
 * Yields normalized request params
 */
class Request
{
    public $headers;

    public $method;

    public $path;

    public $params;

    public static function fromGlobals()
    {
        $req = new Self();
        $req->headers = self::getAllHeaders();
        $req->path = Path::normalize($_SERVER['REQUEST_URI']);
        $req->method = strtoupper($_SERVER['REQUEST_METHOD']);

        return $req;
    }

    public function getPath()
    {
        return Path::normalize($this->path);
    }

    public function getMethod()
    {
        return strtoupper($this->method);
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function getHeader(string $name)
    {
        return $this->headers[$name];
    }

    public function setParams(array $params)
    {
        $this->params = $params;
        return $this;
    }

    public function getParam(string $name)
    {
        return $this->params[strtolower($name)];
    }

    public static function getAllHeaders()
    {
        if (function_exists('getallheaders')) {
            return getallheaders();
        }

        $headers = [];
        foreach ($_SERVER as $name => $value)
        {
            if (substr($name, 0, 5) == 'HTTP_')
            {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }

        return $headers;
    }
}