<?php

use \PHPUnit\Framework\TestCase;
use \Morphable\SimpleRouting\Route;
use \Morphable\SimpleRouting\Request;
use \Morphable\SimpleRouting\Response;


class RouteTest extends TestCase
{
    public function getReqRes()
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
        $route = new Route("GET", "/test/:param", function () {}, []);

        $should = "/^\/test\/(\d|\w|-|_|)*?\/$/";
        $is = $route->getPattern();

        $this->assertSame($is, $should);

        $match = $route->match($req->getPath());
        $this->assertTrue($match);

        $should = ['param' => '1'];
        $is = $route->fillParams($req->getPath());

        $this->assertSame($is, $should);

    }
}

