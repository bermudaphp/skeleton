<?php

namespace App\Factory;


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
        return new HomePageHandler;
    }
}
