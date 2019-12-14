<?php

namespace HostAway\Controllers;

use HostAway\Models\PhoneBook;
use Phalcon\Http\Response;
use Phalcon\Mvc\Controller;
use Ramsey\Uuid\Uuid;

class PhoneBookController extends Controller
{
    public function createAction()
    {
        try {
            $phoneBook = new PhoneBook();
            $values = $this->request->getJsonRawBody(true);
            $phoneBook->hydrate($values);
            $phoneBook->setId(Uuid::uuid4());
            $phoneBook->setInsertedOn($phoneBook->getUpdatedOn());

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
        }
    }

    public function getAction(string $id)
    {
        $phoneBook = $this->findById($id);
        if ($phoneBook === false) {
            return;
        }
        $this->successResponse([$phoneBook->toArray()]);
    }

    public function findAction()
    {
        $conditions = [];
        $firstName = $this->request->get('first_name');
        if ($firstName !== null) {
            $conditions = ['conditions' => 'firstName LIKE "%' . $firstName . '%"'];
        }
        $phoneBooks = PhoneBook::find($conditions);
        $this->successResponse($phoneBooks->toArray());
    }

    public function updateAction(string $id)
    {
        try {
            /** @var PhoneBook $phoneBook */
            $phoneBook = $this->findById($id);
            if ($phoneBook === false) {
                return;
            }
            $values = $this->request->getJsonRawBody(true);
            $phoneBook->hydrate($values);
            $result = $phoneBook->save();
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
        }
    }

    public function deleteAction(string $id)
    {
        $phoneBook = $this->findById($id);
        if ($phoneBook === false) {
            return;
        }
        $result = $phoneBook->delete();
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
    }

    private function findById(string $id)
    {
        $phoneBook = PhoneBook::findFirst("id = '" . $id . "'");
        if ($phoneBook === false) {
            $this->failResponse(404, 'Phone book not found');
            return;
        }

        return $phoneBook;
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
