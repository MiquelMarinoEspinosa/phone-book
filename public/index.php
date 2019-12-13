<?php

use Phalcon\Db\Adapter\Pdo\Mysql;
use Phalcon\Di\FactoryDefault;
use Phalcon\Loader;
use Phalcon\Mvc\Micro;
use Phalcon\Mvc\Micro\Collection;

$loader = new Loader();
$loader->registerDirs(
    [
        "../apps/controllers/",
        "../apps/models/",
    ]
);
$loader->register();

$container = new FactoryDefault();

$container->set(
    'db',
    function () {
        return new Mysql(
            [
                'host'     => 'localhost',
                'username' => 'hostaway',
                'password' => 'hostaway',
                'dbname'   => 'hostaway',
            ]
        );
    }
);


$app = new Micro();
$app->setDI($container);

$phoneBook = new Collection();
$phoneBook->setHandler(new PhoneBookController());
$phoneBook->get('/phone-book', 'getAction');
$app->mount($phoneBook);

try {
    $app->handle();
} catch (\Exception $exception) {
    echo $exception->getMessage();
}
