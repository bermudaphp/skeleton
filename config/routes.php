<?php

use Bermuda\Router\RouteMap;
use Bermuda\Router\RouteRecord;
use Bermuda\App\AppInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @var RouteMap $routes
 * @var AppInterface $app
 */
$routes->addRoute(
    RouteRecord::get('home', '/', static function() use ($app): ResponseInterface {
        return $app->respond(200, 'Hello World!');
    })
);
