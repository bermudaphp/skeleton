<?php


namespace App;


/**
 * Class ConfigProvider
 * @package App
 */
class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies()
        ];
    }
    
    private function getDependencies(): array
    {
        return [
            'factories' => []
        ];
    }
}
