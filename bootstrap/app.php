<?php

require __DIR__ . '/../vendor/autoload.php';

use Slim\App;

/**
 * Initialise the application and bind the settings to the container.
 */
$app = new App(require __DIR__ . '/../app/settings.php');

/**
 * Register the application dependencies.
 */
require __DIR__ . '/../app/dependencies.php';

/**
 * Register the application routes.
 */
require __DIR__ . '/../app/routes.php';

return $app;
