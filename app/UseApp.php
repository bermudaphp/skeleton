<?php

namespace App;

use Bermuda\App\AppInterface;
use Bermuda\App\Boot\BootstrapperInterface;

final class UseApp implements BootstrapperInterface
{
    public static AppInterface $app;
    public function boot(AppInterface $app): AppInterface
    {
        if (!function_exists('App\useApp')) {
            UseApp::$app = $app;
            function useApp(): AppInterface {
                return UseApp::$app;
            }
        }

        return $app;
    }
}
