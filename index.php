<?php

require __DIR__ . '/vendor/autoload.php';

use Morphable\SimpleRouting as Router;
use Morphable\SimpleRouting\Route;



$router = new Router();

$router->add("user-posts", new Route("GET", "/users/:userId/posts/:postId", function($req, $res) {
    $res->sendResponse(json_encode(['a' => 1]), 400);
}));

$router->execute();
