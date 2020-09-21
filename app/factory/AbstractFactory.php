<?php


namespace App\Factory;


use Psr\Container\ContainerInterface;


/**
 * Class AbstractFactory
 * @package App\Factory
 */
abstract class AbstractFactory
{
    abstract public function __invoke(ContainerInterface $container): object ;
}
