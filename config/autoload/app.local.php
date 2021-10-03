<?php

use Bermuda\ErrorHandler\LogErrorListener;

return [
    'app' => [
        'name' => 'skeleton',
        'version' => '1.0',
    ],

    'errors' => [
        'listeners' => static function () {
            return [LogErrorListener::class];
        }
    ]
];
