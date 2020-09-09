<?php


use Bermuda\App\AppInterface;
use Bermuda\Router\RouterInterface;


/**
 * @var AppInterface $app
 */
$routes = $app->get(RouterInterface::class)->getRoutes();

$routes->get('home', '/', \App\Handler\HomePageHandler::class);
