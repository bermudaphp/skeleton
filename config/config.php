<?php


declare(strict_types=1);


use Laminas\ConfigAggregator\ArrayProvider;
use Laminas\ConfigAggregator\ConfigAggregator;
use Laminas\ConfigAggregator\PhpFileProvider;


// To enable or disable caching, set the `ConfigAggregator::ENABLE_CACHE` boolean in
// `config/autoload/local.php`.
$cacheConfig = [
    'config_cache_path' => 'data/cache/config-cache.php',
];

$aggregate = static function(callable ... $providers) use ($cacheConfig): array
{
    return (new ConfigAggregator($providers, $cacheConfig['config_cache_path']))->getMergedConfig();
};

return $aggregate(new ArrayProvider($cacheConfig),
    new \Bermuda\RequestHandlerRunner\ConfigProvider(),
    new \Bermuda\Router\ConfigProvider(),
    new \Bermuda\Pipeline\ConfigProvider(),
    new \Bermuda\Templater\ConfigProvider(),
    new \Bermuda\MiddlewareFactory\ConfigProvider(),
    new \Bermuda\ErrorHandler\ConfigProvider(),
    new PhpFileProvider(APP_ROOT  . '/config/autoload/{{,*.}global,{,*.}local}.php'),
    new PhpFileProvider(APP_ROOT  . '/config/development.config.php')
);




