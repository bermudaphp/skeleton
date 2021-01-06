<?php

use Bermuda\Templater\RendererFactory;

return [
    RendererFactory::configKey => [
        RendererFactory::configExtKey => 'phtml', 
        RendererFactory::configTemplatesFoldersKey =>
        [
            'app' => ($path = APP_ROOT . '\templates\\') . 'app',
            'errors' => $path . 'errors',
        ], 
        RendererFactory::configExtendersKey => ['urlFor' => '\Bermuda\urlFor']
   ]
];
