<?php

use Bermuda\Templater\EngineFactory;
use function Bermuda\App\path;

return [
    EngineFactory::configKey => [
        EngineFactory::configTemplatesDir => (string) $templates = path('templates'),
        EngineFactory::configExtKey => 'phtml',
        EngineFactory::configTemplatesFoldersKey => [
            'app' => (string) $templates->append('app'),
            'errors' =>  (string) $templates->append('errors'),
            'dashboard' =>  (string) $templates->append('dashboard')
        ],
        EngineFactory::configExtendersKey => ['route' => '\Bermuda\App\route']
    ]
];
