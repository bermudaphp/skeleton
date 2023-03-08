<?php

use Bermuda\HTTP\ConfigProvider;
use Bermuda\Router\Middleware\RouteMiddleware;
use Psr\Http\Message\ServerRequestInterface;

return [
    'origin' => ['*'],
    'methods' => static fn(ServerRequestInterface $request) => $request->getAttribute(RouteMiddleware::class)->methods(),
    'credentials' => true,
    'headers.expose' => ['x-user-id'],
    'headers.allow' => [
        'x-requested-with', 
        'authorization'
    ]
];
