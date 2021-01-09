<?php

namespace App\Factory;


use App\Handler\HomePageHandler;
use Psr\Container\ContainerInterface;


/**
 * Class HomePageHandlerFactory
 * @package App\Factory
 */
final class HomePageHandlerFactory
{
    /**
     * @inheritDoc
     */
    public function __invoke(ContainerInterface $container): HomePageHandler
    {
        return new HomePageHandler();
    }
}
