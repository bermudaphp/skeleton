<?php

use Bermuda\HTTP\ConfigProvider;
use Bermuda\Router\Middleware\RouteMiddleware;
use Psr\Http\Message\ServerRequestInterface;

return [
    ConfigProvider::corsKey => [
        'origin' => [
            'http://localhost:5173',
            'https://shopen.io',
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
