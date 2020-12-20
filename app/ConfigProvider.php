<?php

namespace App;


use Bermuda\App\AppFactory;
use Bermuda\App\AppInterface;
use Bermuda\App\Boot\Bootstrapper;
use Bermuda\App\Boot\RouterBootstrapper;
use Bermuda\App\Boot\PipelineBootstrapper;
use Bermuda\Provider\ConfigProvider as BermudaConfigProvider;


/**
 * Class ConfigProvider
 * @package App
 */
final class ConfigProvider extends BermudaConfigProvider
{  
    /**
     * An associative array that maps a service name to a factory class name, or any callable. 
     * Factory classes must be instantiable without arguments, and callable once instantiated (i.e., implement the __invoke() method).
     * @return array
     */
    protected function getFactories(): array
    {
        return [
            AppInterface::class => AppFactory::class,
            Bootstrapper::class => static function(ContainerInterface $container): Bootstrapper
            {
                return Bootstrapper::makeOf([
                    new RouterBootstrapper,
                    new PipelineBootstrapper,
                ]);
            },
       ];
    }
}
