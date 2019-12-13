<?php

namespace HostAway\Controllers;

use HostAway\Models\PhoneBook;
use Phalcon\Mvc\Controller;

class PhoneBookController extends Controller
{
    public function getAction() {
        $db = $this->getDI()->get('db');
        $phoneBook = PhoneBook::find();
        var_dump(count($phoneBook->toArray()));
        var_dump($phoneBook->toArray());
    }
}
