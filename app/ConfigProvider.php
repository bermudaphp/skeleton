<?php


namespace App;


/**
 * Class ConfigProvider
 * @package App
 */
class ConfigProvider
{
    public const dependecies = 'dependencies';
    public const factories = 'factories';
    public const invokables = 'invokables';
    public const autowires = 'autowires';
    public const aliases 'aliases';
    public const delegators = 'delegators';
    public const services = 'services';
    
    public function __invoke(): array
    {
        return [
            self::dependencies => $this->getDependencies()
        ];
    }
    
    protected function getDependencies(): array
    {
        return [
            self::factories => $this->getFactories(),
            self::invokables => $this->getInvokables(),
            self::autowires => $this->getAutowires(),
            self::aliases => $this->getAliases(),
            self::delegators => $this->getDelegators(),
            self::services => $this->getServices(),
        ];
    }
    
    protected functions getFactories(): array
    {
        return [];
    }
    
    protected functions getInvokables(): array
    {
        return [];
    }
    
    protected functions getAutowires(): array
    {
        return [];
    }
    
    protected functions getAliases(): array
    {
        return [];
    }
    
    protected functions getDelegators(): array
    {
        return [];
    }
    
    protected functions getServices(): array
    {
        return [];
    }
}
