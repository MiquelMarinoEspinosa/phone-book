<?php

namespace HostAway\Controllers;

use HostAway\Models\PhoneBook;
use Phalcon\Http\Response;
use Phalcon\Mvc\Controller;
use Ramsey\Uuid\Uuid;

class PhoneBookController extends Controller
{
    public function getAction(string $id)
    {
        $phoneBook = PhoneBook::findFirst("id = '" . $id . "'");
        if ($phoneBook === false) {
            $this->failResponse(404, 'Phone book not found');
            return;
        }
        $this->successResponse([$phoneBook->toArray()]);
    }

    public function findAction()
    {
        $phoneBooks = PhoneBook::find();
        $this->successResponse($phoneBooks->toArray());
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
                $this->failResponse(500, $errorMessage);
                return;
            }

            $this->successResponse([$phoneBook->toArray()]);
        } catch (\InvalidArgumentException $invalidArgumentException) {
            $this->failResponse(
                400,
                $invalidArgumentException->getMessage()
            );
        } catch (\Exception $exception) {
            $this->failResponse(
                500,
                $exception->getMessage()
            );
        }
    }

    private function successResponse(array $phoneBooks)
    {
        $response = new Response(null, 200);
        $phoneBooksAsArray = [];
        foreach ($phoneBooks as $phoneBook) {
            $phoneBooksAsArray[] = [
                'id' => $phoneBook['id'],
                'first_name' => $phoneBook['firstName'],
                'last_name' => $phoneBook['lastName'],
                'phone_number' => $phoneBook['phoneNumber'],
                'country_code' => $phoneBook['countryCode'],
                'time_zone' => $phoneBook['timeZone']
            ];
        }

        $contents = [
            'status' => "success",
            'result' => $phoneBooksAsArray
        ];
        $response
            ->setJsonContent($contents)
            ->send();
    }

    private function failResponse(int $code, string $message)
    {
        $response = new Response(null, $code);
        $contents = [
            'status' => "fail",
            'message' => $message
        ];

        $response
            ->setJsonContent($contents)
            ->send();
    }
}
