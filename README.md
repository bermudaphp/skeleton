# Install
```bash
composer create-project bermudaphp/skeleton <project-path>
````
Cкрипт установки предложит выбрать реализацию psr-7 (по умолчанию Nyholm/psr7)
и установить <a href="https://github.com/bermudaphp/templater">шаблонизатор</a> (адаптер для пакета <a href="https://platesphp.com/">league/plates</a>)

После установки вы получите основанный на psr-15 диспатчере скелет приложения с <a href="https://php-di.org/">DI контрейнером</a>, HTTP маршрутизацией и поддержкой командной строки.

# Quick start

Зарегистрируйте машруты в файле 'config/routes.php'.
Описание интерфейса роутера можно найти <a href="https://github.com/bermudaphp/router">здесь.</a>
```php
$routes->get('hello.action', '/hello/{name}', static function(string $name) use ($app): ResponseInterface {
    return $app->responde(200, 'Hello, ' . $name);
});
````

Зарегистрируйте глобальные middleware в файле 'config/pipeline.php'.
Реализация диспатчера работает по принципу FIFO

```php
$app->pipe(MyFirstMiddleware::class);
$app->pipe(\Bermuda\Router\Middleware\MatchRouteMiddleware::class);
$app->pipe(MyThirdMiddleware::class);
$app->pipe(\Bermuda\Router\Middleware\DispatchRouteMiddleware::class);
````
Добавьте зависимости в файл 'config/config.php' и сконфигурируйте DI container

```php
return Config::merge(
    new Bermuda\App\ConfigProvider,
    new Bermuda\Cycle\ConfigProvider,
    new Bermuda\Flysystem\ConfigProvider,
    new Bermuda\Cycle\ConfigProvider,
    new Bermuda\PSR7ServerFactory\ConfigProvider,
    new Bermuda\Router\ConfigProvider,
    new Bermuda\Pipeline\ConfigProvider,
    new Bermuda\MiddlewareFactory\ConfigProvider,
    new Bermuda\ErrorHandler\ConfigProvider,

    new PhpFileProvider('./config/autoload/{{,*.}global,{,*.}local}.php'),
    new PhpFileProvider('./config/development.config.php'),

    // App config provider
    new class extends ConfigProvider {

        /**
         * An associative array that maps a service name to a factory class name, or any callable.
         * Factory classes must be instantiable without arguments, and callable once instantiated (i.e., implement the __invoke() method).
         * @return array
         */
        protected function getFactories(): array
        {
            return [
                BootstrapperInterface::class => static function(ContainerInterface $container): Bootstrapper {
                    return Bootstrapper::withDefaults($container)->add(new class implements BootstrapperInterface {
                        public function boot(AppInterface $app): AppInterface
                        {
                            // Бутстрап приложения выполнится после конфигурации контейнера
                            // Здесь можно зарегистрировать алиасы для функций экземпляра приложения или установить новые сущности в контейнер
                            // или все, что необходимо зделать до запуска пайплайна
                            $app->registerCallback('myCallback', static fn() => 'callback');
                            $app->myCallback() === 'callback' // true
                            $app->set('my-second-dependency', true);
                            $app->extend(MyFirstMiddleware, static function(MyFirstMiddleware $m, AppInterface $app) {
                              return new MyFirstMiddlewareDecorator($m, $app->get('my-second-dependency'));
                            });
                        }
                    });
                },

                MyService::class => static fn(ContainerInterface $container) => new MyService($container->get(MyDependency::class)),
            ];
        }
    },
);
```
