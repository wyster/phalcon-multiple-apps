<?php declare(strict_types=1);

/**
 * Registering an autoloader
 */
$loader = new \Phalcon\Loader();

$loader->registerNamespaces(
    [
        'app' =>  APP_PATH
    ]
);

$loader->registerDirs(
    [
        APP_PATH,
        $config->application->controllersDir,
        $config->application->modelsDir
    ]
)->register();
