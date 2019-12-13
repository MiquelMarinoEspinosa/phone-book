<?php

namespace HostAway\Controllers;

use HostAway\Models\PhoneBook;
use Phalcon\Http\Response;
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
        try {
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
            if ($result === false) {
                $errorMessage = '';
                foreach ($phoneBook->getMessages() as $message) {
                    $errorMessage .= $message;
                    $errorMessage .= PHP_EOL;
                }
                $response = new Response(null, 500);
                $contents = [
                    'status' => "fail",
                    'message' => $errorMessage
                ];

                $response
                    ->setJsonContent($contents)
                    ->send();

                return;
            }

            $response = new Response(null, 200);
            $contents = [
                'status' => "success",
                'result' => [
                    'id' => $phoneBook->getId(),
                    'first_name' => $phoneBook->getFirstName(),
                    'last_name' => $phoneBook->getLastName(),
                    'phone_number' => $phoneBook->getPhoneNumber(),
                    'country_code' => $phoneBook->getCountryCode(),
                    'time_zone' => $phoneBook->getTimeZone()
                ]
            ];
            $response
                ->setJsonContent($contents)
                ->send();
        } catch (\InvalidArgumentException $invalidArgumentException) {
            $response = new Response(null, 400);
            $contents = [
                'status' => "fail",
                'message' => $invalidArgumentException->getMessage()
            ];

            $response
                ->setJsonContent($contents)
                ->send();
        } catch (\Exception $exception) {
            $response = new Response(null, 500);
            $contents = [
                'status' => "fail",
                'message' => $exception->getMessage()
            ];

            $response
                ->setJsonContent($contents)
                ->send();
        }
    }
}
