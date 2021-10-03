<?php

namespace Autocomplete {

    use Bermuda\App\Config;
    use Bermuda\Router\Router;
    use Psr\Http\Message\ResponseInterface;
    use Psr\Http\Message\UriInterface;

    /**
     * @method ResponseInterface respond(int $code, mixed $content)
     * @method ResponseInterface redirect(string|UriInterface $location, bool $permanent = false)
     * @method string|ResponseInterface route(string $name, array $params = [], bool $redirect = false)
     * @property-read Router $router
     * @property-read Config $config
     */
    class App
    {
    }
}
