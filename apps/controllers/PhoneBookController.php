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

    public function postAction()
    {
        //var_dump($this->request->getJsonRawBody(true));
        $phoneBook = new PhoneBook();

        $phoneBook->setId('test-124232');
        $phoneBook->setFirstName('miquel2');
        $phoneBook->setLastName('marino23');
        $phoneBook->setPhoneNumber('4334334');
        $phoneBook->setCountryCode('ES');
        $phoneBook->setTimeZone('timezone3');
        $phoneBook->setInsertedOn('2019-03-23');
        $phoneBook->setUpdatedOn('2019-03-24');
        $result = $phoneBook->create();
        var_dump($result);
        var_dump($phoneBook->getMessages());
    }
}
