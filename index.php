<?php

use Phalcon\Mvc\Micro;

$app = new Micro();

$app->get(
    '/',
    function () {
        echo "hola miquel que passa";
    }
);

$app->handle();