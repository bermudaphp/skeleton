<?php

return [
    'renderer' =>
        [
            'ext' => 'phtml',
            'templates' => [
                'app' => ($path = APP_ROOT . '\templates\\') . 'app',
                'errors' => $path . 'errors',
            ],
            'functions' => []
        ]
];
