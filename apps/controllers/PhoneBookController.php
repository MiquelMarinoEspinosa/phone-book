<?php

namespace HostAway\Controllers;

use HostAway\Models\PhoneBook;
use Phalcon\Mvc\Controller;
use Ramsey\Uuid\Uuid;

class PhoneBookController extends Controller
{
    public function getAction() {
        $phoneBook = PhoneBook::find();
        var_dump(count($phoneBook->toArray()));
        var_dump($phoneBook->toArray());
    }

    public function postAction()
    {
        $phoneBook = new PhoneBook();
        $data = $this->request->getJsonRawBody(true);
        $phoneBook->setId(Uuid::uuid4());
        $phoneBook->setFirstName($data['first_name'] ?? '');
        $phoneBook->setLastName($data['last_name'] ?? '');
        $phoneBook->setPhoneNumber($data['phone_number'] ?? '');
        $phoneBook->setCountryCode($data['country_code'] ?? '');
        $phoneBook->setTimeZone($data['time_zone'] ?? '');
        $now = date("Y-m-d H:i:s");
        $phoneBook->setInsertedOn($now);
        $phoneBook->setUpdatedOn($now);
        $result = $phoneBook->create();
        var_dump($result);
        var_dump($phoneBook->getMessages());
    }
}
