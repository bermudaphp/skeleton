<?php

declare(strict_types = 1);

use Elie\PHPDI\Config\Config;
use Elie\PHPDI\Config\ContainerFactory;


return (new ContainerFactory)(new Config(require APP_ROOT . '/config/config.php'));

