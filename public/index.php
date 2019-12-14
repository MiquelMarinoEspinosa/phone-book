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
use Phalcon\Cache\Frontend\Data as FrontData;
use Phalcon\Cache\Backend\Libmemcached as BackMemCached;

const ONE_HOUR_CACHE = 60 * 60;

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

    $frontCache = new FrontData(
        [
            'lifetime' => ONE_HOUR_CACHE,
        ]
    );

    $cache = new BackMemCached(
        $frontCache,
        [
            'servers' => [
                [
                    'host'   => '127.0.0.1',
                    'port'   => '11211',
                    'weight' => '1',
                ]
            ]
        ]
    );


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
        function () use ($cache){
            return new CountryService(
                new Client(),
                $cache
            );
        }
    );

    $container->set(
        'time_zones_service',
        function () use ($cache) {
            return new TimeZoneService(
                new Client(),
                $cache
            );
        }
    );

    return $container;
}

function createRoutes(): Collection {
    $phoneBook = new Collection();
    $phoneBook->setHandler(new PhoneBookController());
    $phoneBook->post('/phone-book', 'createAction');
    $phoneBook->get('/phone-book/{id}', 'getAction');
    $phoneBook->get('/phone-book', 'findAction');
    $phoneBook->patch('/phone-book/{id}', 'updateAction');
    $phoneBook->delete('/phone-book/{id}', 'deleteAction');

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
