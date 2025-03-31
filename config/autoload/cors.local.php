<?php

use Bermuda\Router\Middleware\RouteMiddleware;
use Psr\Http\Message\ServerRequestInterface;

return [
    'origin' => ['*'],
    'methods' => fn() => function(ServerRequestInterface $request): array {
        return $request->getAttribute(RouteMiddleware::class)->route->methods;
    },
    'credentials' => true,
    'headers.expose' => ['x-user-id'],
    'headers.allow' => ['x-requested-with', 'authorization']
];
