<?php

namespace Morphable\SimpleRouting;

/**
 * Sets response parameters
 */
class Response
{
    /**
     * @var string
     */
    private $response = '';

    /**
     * @var int
     */
    private $code = 200;

    /**
     * @var array
     */
    private $headers = [];

    /**
     * @var array
     */
    private $cookies = [];

    /**
     * @param string key
     * @param string value
     * @param int expiry
     * @param string path
     * @return self
     */
    public function setCookie(string $key, string $value, int $expiry = 0, string $path = '/')
    {
        $this->cookies[] = [
            'key' => $key,
            'value' => $value,
            'expiry' => $expiry,
            'path' => $path
        ];

        return $this;
    }

    /**
     * @param string response
     * @return self
     */
    public function setResponse(string $response)
    {
        $this->response = $response;
        return $this;
    }

    /**
     * @param int code
     * @return self
     */
    public function setCode(int $code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @param string response
     * @param int code
     * @return void
     */
    public function sendResponse(string $response = null, int $code = null)
    {
        if (!$code) {
            http_response_code($this->code);
        } else {
            http_response_code($code);
        }

        foreach ($this->headers as $key => $value) {
            header("$key: $value");
        }

        foreach ($this->cookies as $cookie) {
            setcookie($cookie['key'], $cookie['value'], $cookie['expiry'], $cookie['path']);
        }

        if (!$response) {
            echo $this->response;
        } else {
            echo $response;
        }
        die;
    }
}
