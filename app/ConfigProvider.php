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
    
    /**
     * An associative array that maps a service name to a factory class name, or any callable. 
     * Factory classes must be instantiable without arguments, and callable once instantiated (i.e., implement the __invoke() method).
     * @return array
     */
    protected functions getFactories(): array
    {
        return [];
    }
    
    /**
     * An associative array that map a key to a constructor-less service; i.e., for services that do not require arguments to the constructor. 
     * The key and service name usually are the same; 
     * if they are not, the key is treated as an alias. It could also be an array of services.
     * @return array
     */
    protected functions getInvokables(): array
    {
        return [];
    }
    
    /**
     * an array of service with or without a constructor; 
     * PHP-DI offers an autowire technique that will scan the code and see what are the parameters needed in the constructors. 
     * Any aliases needed should be created in the aliases configuration
     * @return array
     */
    protected functions getAutowires(): array
    {
        return [];
    }
    
    /**
     * An associative array that maps an alias to a service name (or another alias).
     * @return array
     */
    protected functions getAliases(): array
    {
        return [];
    }
    
    /**
     * An associative array that maps service names to lists of delegator factory keys
     * @return array
     */
    protected functions getDelegators(): array
    {
        return [];
    }
    
    /**
     * An associative array that maps a key to a specific service instance or service name.
     * @return array
     */
    protected functions getServices(): array
    {
        return [];
    }
}
