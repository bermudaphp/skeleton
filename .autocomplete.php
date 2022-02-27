<?php

namespace Autocomplete {

    use Bermuda\App\Config;
    use Bermuda\Router\Router;
    use Psr\Http\Message\ResponseInterface;
    use Psr\Http\Message\UriInterface;

    /**
     * @method ResponseInterface respond(int $code, mixed $content, string $contentType = null)
     * @method ResponseInterface redirect(string|UriInterface $location, int $code = 302)
     * @method string|ResponseInterface route(string $name, array $params = [], bool $redirect = false)
     * @property-read Router $router
     */
    class App
    {
    }
}
