<?php

use Phalcon\Mvc\Micro;

$app = new Micro();

$app->get(
    '/test',
    function () {
        echo "test";
    }
);




try {
    $app->handle();
} catch (\Exception $exception) {
    var_dump($_SERVER);
}
