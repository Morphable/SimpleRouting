<?php

use \PHPUnit\Framework\TestCase;
use \Morphable\SimpleRouting\Route;
use \Morphable\SimpleRouting\Request;
use \Morphable\SimpleRouting\Response;
use \Morphable\SimpleRouting\Builder;

class RouteTest extends TestCase
{
    private function getReqRes()
    {
        $req = new Request();
        $req->headers = [];
        $req->path = '/test/1';
        $req->method = 'GET';

        $res = new Response();

        return [$req, $res];
    }

    public function testGenerate()
    {
        list($req, $res) = $this->getReqRes();
        $route = new Route("GET", "/test/:param", function () {
        }, []);

        $should = "/^\/test\/(\d|\w|-|_|\.)*?\/$/";
        $is = $route->getPattern();

        $this->assertSame($is, $should);

        $match = $route->match($req->getPath());
        $this->assertTrue($match);

        $should = ['param' => '1'];
        $is = $route->fillParams($req->getPath());

        $this->assertSame($is, $should);
    }

    public function testBuilder()
    {
        list($req, $res) = $this->getReqRes();

        $callback = function ($req, $res, $container) {
            echo "test";
            die;
        };

        $builder = (new Builder())
            ->setMethod('GET')
            ->setRoute('/test/:param')
            ->setCallback($callback)
            ->setMiddleware([$callback]);

        $route = $builder->build();

        $should = "/^\/test\/(\d|\w|-|_|\.)*?\/$/";
        $is = $route->getPattern();

        $match = $route->match($req->getPath());
        $this->assertTrue($match);

        $route = Builder::fromArray([
            'method' => 'GET',
            'route' => '/test/:param',
            'callback' => $callback,
            'middleware' => $callback
        ]);

        $should = "/^\/test\/(\d|\w|-|_|\.)*?\/$/";
        $is = $route->getPattern();

        $match = $route->match($req->getPath());
        $this->assertTrue($match);
    }

    public function testCallback()
    {
        list($req, $res) = $this->getReqRes();

        $callback = function ($req, $res, $container) {
            return true;
        };

        $route = Builder::fromArray([
            'method' => 'GET',
            'route' => '/test/:param',
            'callback' => $callback,
            'middleware' => $callback
        ]);

        $should = true;
        $is = $route->execCallback($req, $res);
        $this->assertSame($is, $should);

        $testCallback = new TestCallback();
        $callback = [$testCallback, "callback"];

        $route = Builder::fromArray([
            'method' => 'GET',
            'route' => '/test/:param',
            'callback' => $callback,
            'middleware' => $callback
        ]);

        $should = true;
        $is = $route->execCallback($req, $res);
        $this->assertSame($is, $should);
    }
}

class TestCallback
{
    public function callback(Request $req, Response $res, $container)
    {
        return true;
    }
}
