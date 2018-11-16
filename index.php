<?php

require __DIR__ . '/vendor/autoload.php';

use Morphable\SimpleRouting as Router;
use Morphable\SimpleRouting\Route;



$router = new Router();



$router->add("user-posts", new Route(
    "GET",
    "/users/:userId/posts/:postId",
    function($req, $res) {
        $res->sendResponse(json_encode(['a' => 1]), 400);
    }, [
    function ($req, $res) {
        if ($req->getParam('userid') != 1) {
            return $res->sendResponse(json_encode(['error' => true]), 400);
        }
    }
]));

try {
    $router->execute();
} catch (\Morphable\SimpleRouting\Exception\RouteNotFound  $e) {
    die('404');
}
