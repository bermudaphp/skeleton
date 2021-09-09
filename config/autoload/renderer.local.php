<?php

use function Bermuda\App\path;
use Bermuda\Templater\EngineFactory;

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
