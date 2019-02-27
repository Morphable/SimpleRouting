# SimpleRouting

A simple component that takes care of routing.

## Installing

```terminal
$ composer require morphable/simple-routing
```

## Usage

```php
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

// A POST route with middleware
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

```

## Builders and callbacks
Better callbacks and builders!

Callback can now be anything that works with ```call_user_func()```

```php

use \Morphable\SimpleRouting\Builder;

$route = (new Builder())
        ->setMethod('GET')
        ->setRoute('/user/:userId')
        ->setCallback($callback)
        ->setMiddleware([$callback])
        ->build();

$route = Builder::fromArray([
    'method' => 'GET',
    'route' => '/user/:userId',
    'callback' => $callback,
    'middleware' => $callback
]);

```


## Contributing
- Follow PSR-2 and the .editorconfig
- Start namespaces with \Morphable\SimpleRouting
- Make tests
