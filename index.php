<?php

require __DIR__ . '/vendor/autoload.php';

use Morphable\SimpleRouting as Router;
use Morphable\SimpleRouting\Route;



$router = new Router();

$router->add("user-posts", new Route("GET", "/users/:userId/posts/:postId", function($req, $res) {
    echo "test";
    die;
}));

$router->execute();
