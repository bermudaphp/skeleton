<?php


namespace App\Handler;


use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Server\RequestHandlerInterface;


/**
 * Class AbstractHandler
 * @package App\Handler
 */
abstract class Handler implements RequestHandlerInterface
{
    protected ResponseFactoryInterface $responseFactory;

    public function __construct(ResponseFactoryInterface $factory)
    {
        $this->responseFactory = $factory;
    }
}