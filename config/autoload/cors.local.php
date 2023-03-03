<?php

use Bermuda\Router\Middleware\RouteMiddleware;
use Psr\Http\Message\ServerRequestInterface;

return [
    'cors' => [
        'origin' => [
            '*'
        ],
        'methods' => static function(): callable {
            return function(ServerRequestInterface $request): array {
                return $request->getAttribute(RouteMiddleware::class)->methods();
            };
        },
        'credentials' => true,
        'headers.expose' => ['x-user-id'],
        'headers.allow' => ['x-requested-with', 'authorization']
    ],
];
