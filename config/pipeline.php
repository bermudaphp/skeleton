<?php

/**
 * @var \Bermuda\App\AppInterface $app
 */

    /*
    $app->pipe(static function (ServerRequestInterface $request, RequestHandlerInterface $handler) use ($app)
    {
        $bootstrapper = $app->get(Bootstrapper::class);

        $bootstrapper->add(MyBootable::class);

        return $handler->handle($request);
    });
    */

$app->pipe(Bermuda\ErrorHandler\ErrorHandlerMiddleware::class);
//$app->pipe(Authenticator::class);

/**
 * Routing middleware
 */
$app->pipe(\Bermuda\Router\Middleware\MatchRouteMiddleware::class);
$app->pipe(\Bermuda\Router\Middleware\DispatchRouteMiddleware::class);
