<?php

use Phalcon\Mvc\Controller;

class PhoneBookController extends Controller
{
    public function getAction() {
        $db = $this->getDI()->get('db');
        var_dump($db);
        echo "PhoneBookController";
    }
}
