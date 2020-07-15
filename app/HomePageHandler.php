<?php


namespace App;


use function Bermuda\view;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;


/**
 * Class HomePageHandler
 * @package App
 */
class HomePageHandler implements RequestHandlerInterface
{
    /**
     * @inheritDoc
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return view('app::home');
    }
}
