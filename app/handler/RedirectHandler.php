<?php


namespace App\Handler;


use Bermuda\Router\GeneratorInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;


/**
 * Class RedirectHandler
 * @package App\Handler
 */
class RedirectHandler extends Handler
{
    protected bool $onRoute = true;
    protected bool $movedPermanently = false;
    protected GeneratorInterface $generator;
    protected string $routeOrLocation = 'home';

    public function __construct(ResponseFactoryInterface $factory, GeneratorInterface $generator)
    {
        parent::__construct($factory);
        $this->generator = $generator;
    }

    /**
     * @inheritDoc
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return !$this->onRoute ? $this->redirect($this->routeOrLocation) :
            $this->redirectOnRoute($this->routeOrLocation, $this->getAttributes($request), !$this->movedPermanently);
    }

    /**
     * @param ServerRequestInterface $request
     * @return array
     */
    protected function getAttributes(ServerRequestInterface $request): array
    {
        return [];
    }

    /**
     * @param string $location
     * @param bool $code302
     * @return ResponseInterface
     */
    protected function redirect(string $location, bool $code302 = true): ResponseInterface
    {
        return $this->responseFactory->createResponse(!$code302 ? 301 : 302)
            ->withHeader('location', $location);
    }

    /**
     * @param string $name
     * @param array $attributes
     * @param bool $code302
     * @return ResponseInterface
     */
    protected function redirectOnRoute(string $name, array $attributes = [], bool $code302 = true): ResponseInterface
    {
        return $this->redirect($this->generator->generate($name, $attributes), $code302);
    }
}