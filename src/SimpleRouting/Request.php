<?php

namespace Morphable\SimpleRouting;

/**
 * Yields normalized request params
 */
class Request
{
    /**
     * @var array
     */
    public $headers;

    /**
     * @var string
     */
    public $method;

    /**
     * @var string
     */
    public $path;

    /**
     * @var array
     */
    public $params;

    /**
     * @return self
     */
    public static function fromGlobals()
    {
        $req = new Self();
        $req->headers = self::getAllHeaders();
        $req->path = Path::normalize($_SERVER['REQUEST_URI']);
        $req->method = strtoupper($_SERVER['REQUEST_METHOD']);

        return $req;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return Path::normalize($this->path);
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return strtoupper($this->method);
    }

    /**
     * @param string
     * @return string
     */
    public function getHeader(string $name)
    {
        return $this->headers[$name];
    }

    /**
     * @param array
     * @return self
     */
    public function setParams(array $params)
    {
        $this->params = $params;
        return $this;
    }

    /**
     * @param string
     * @return string
     */
    public function getParam(string $name)
    {
        return $this->params[strtolower($name)];
    }

    /**
     * @return array
     */
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
