<?php

use Phalcon\Loader;
use Phalcon\Mvc\Micro;
use Phalcon\Mvc\Router;

$app = new Micro();

$router = new Router();

$loader = new Loader();
$loader->registerDirs(
    [
        "../apps/controllers/",
        "../apps/models/",
    ]
);
$loader->register();

//$app->setService('router', $router, true);

$app->get(
    '/test',
    'PhoneBookController::get'
);

try {
    $app->handle();
} catch (\Exception $exception) {
    echo $exception->getMessage();
}
