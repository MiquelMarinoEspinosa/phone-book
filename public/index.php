<?php

use GuzzleHttp\Client;
use HostAway\Controllers\PhoneBookController;
use HostAway\Services\CountryService;
use HostAway\Services\TimeZoneService;
use Phalcon\Db\Adapter\Pdo\Mysql;
use Phalcon\Di\FactoryDefault;
use Phalcon\Http\Response;
use Phalcon\Loader;
use Phalcon\Mvc\Micro;
use Phalcon\Mvc\Micro\Collection;

function loadResources() {
    $loader = new Loader();
    $loader->registerNamespaces(
        [
            'HostAway\Controllers' => "../apps/controllers/",
            'HostAway\Models'   => "../apps/models/",
            'HostAway\Services' => "../apps/services/"
        ]
    );
    $loader->registerFiles(['../vendor/autoload.php']);
    $loader->register();
}

function buildContainer(): FactoryDefault {
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

    $container->set(
        'country_service',
        function () {
            return new CountryService(
                new Client()
            );
        }
    );

    $container->set(
        'time_zones_service',
        function () {
            return new TimeZoneService(
                new Client()
            );
        }
    );

    return $container;
}

function createRoutes(): Collection {
    $phoneBook = new Collection();
    $phoneBook->setHandler(new PhoneBookController());
    $phoneBook->get('/phone-book', 'getAction');
    $phoneBook->post('/phone-book', 'postAction');

    return $phoneBook;
}

function notFoundHandler() {
    $response = new Response(null, 404);
    $contents = [
        'status' => "fail",
        'message' => "The route was not found"
    ];

    $response
        ->setJsonContent($contents)
        ->send();
}

function errorResponse(\Exception $exception) {
    $response = new Response(null, 500);
    $contents = [
        'status' => "fail",
        'message' => $exception->getMessage()
    ];

    $response
        ->setJsonContent($contents)
        ->send();
}

loadResources();
$app = new Micro();
$app->setDI(buildContainer());
$app->mount(createRoutes());
$app->notFound('notFoundHandler');

try {
    $app->handle();
} catch (\Exception $exception) {
    errorResponse($exception);
}
