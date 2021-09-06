<?php

use Bermuda\Templater\EngineFactory;

return [
    EngineFactory::configKey => [
        EngineFactory::configTemplatesDir => APP_ROOT . '\templates',
        EngineFactory::configExtKey => 'phtml',
        EngineFactory::configTemplatesFoldersKey => [
            'app' => APP_ROOT . '\templates\app',
            'errors' =>  APP_ROOT . '\templates\errors',
            'dashboard' =>  APP_ROOT . '\templates\dashboard'
        ],
        EngineFactory::configExtendersKey => ['route' => '\Bermuda\route']
    ]
];
