<?php

require __DIR__ . '/vendor/autoload.php';

use \Morphable\SimpleRouting;
use \Morphable\SimpleRouting\Route;

/*
$req = \Morphable\SimpleRouting\Request
$res = \Morphable\SimpleRouting\Response
*/

// A simple route with response
$route = new Route('GET', '/user/:user_id/', function ($req, $res) {
    return $res->sendResponse('UserId = ' . $req->getParam('user_id'), 200);
});

// A route with middleware
$route2 = new Route('POST', '/user/:user_id/update', function ($req, $res) {
    return $res->sendResponse('Welcome user 2!');
}, [
    // middlewares
    function ($req, $res) {
        if ($req->getParam('user_id') != 2) {
            return $res->sendResponse('Forbidden', 403);
        }
    }
]);

SimpleRouting::add('user_detail', $route);
SimpleRouting::add('user_update', $route2);

try {
    SimpleRouting::execute();
} catch (\Morphable\SimpleRouting\Exception\RouteNotFound  $e) {
    // catch 404
    die('404');
}
