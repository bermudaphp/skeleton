<?php

use Bermuda\HTTP\ConfigProvider;
use Bermuda\Router\Middleware\RouteMiddleware;
use Psr\Http\Message\ServerRequestInterface;
use function Bermuda\Config\callback;

return [
    'origin' => ['*'],
    'methods' => callback(function(ServerRequestInterface $request): array {
        return $request->getAttribute(RouteMiddleware::class)->route->methods;
    }),
    'credentials' => true,
    'headers.expose' => ['x-user-id'],
    'headers.allow' => [
        'x-requested-with', 
        'authorization'
    ]
];
