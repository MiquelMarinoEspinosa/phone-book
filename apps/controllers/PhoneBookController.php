<?php

namespace HostAway\Controllers;

use HostAway\Models\PhoneBook;
use Phalcon\Http\Response;
use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Model\ResultsetInterface;
use Ramsey\Uuid\Uuid;
use Phalcon\Paginator\Adapter\Model as PaginatorModel;

class PhoneBookController extends Controller
{
    const HTTP_CODE_NOT_FOUND = 404;
    const HTTP_CODE_OK = 200;
    const HTTP_CODE_INTERNAL_SERVER_ERROR = 500;
    const HTTP_CODE_BAD_REQUEST = 400;
    const DEFAULT_NUM_ITEMS_PAGE = 5;

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
                $this->failResponse(self::HTTP_CODE_INTERNAL_SERVER_ERROR, $errorMessage);
                return;
            }

            $response = new Response(null, self::HTTP_CODE_OK);
            $response
                ->setJsonContent($this->buildSuccessContent([$phoneBook->toArray()]))
                ->send();
        } catch (\InvalidArgumentException $invalidArgumentException) {
            $this->failResponse(
                self::HTTP_CODE_BAD_REQUEST,
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
        $response = new Response(null, self::HTTP_CODE_OK);
        $response
            ->setJsonContent($this->buildSuccessContent([$phoneBook->toArray()]))
            ->send();
    }

    public function findAction()
    {
        $conditions = [];
        $firstName = $this->request->get('first_name');
        if ($firstName !== null) {
            $conditions = ['conditions' => 'firstName LIKE "%' . $firstName . '%"'];
        }
        $phoneBooks = PhoneBook::find($conditions);
        $offset = $this->request->get('offset');

        if($offset !== null && (int) $offset > 0) {
            $this->paginateResults($phoneBooks, $offset);
            return;
        }

        $response = new Response(null, self::HTTP_CODE_OK);
        $response
            ->setJsonContent($this->buildSuccessContent($phoneBooks->toArray()))
            ->send();
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
                $this->failResponse(self::HTTP_CODE_INTERNAL_SERVER_ERROR, $errorMessage);
                return;
            }

            $response = new Response(null, self::HTTP_CODE_OK);
            $response
                ->setJsonContent($this->buildSuccessContent([$phoneBook->toArray()]))
                ->send();
        } catch (\InvalidArgumentException $invalidArgumentException) {
            $this->failResponse(
                self::HTTP_CODE_BAD_REQUEST,
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
            $this->failResponse(self::HTTP_CODE_INTERNAL_SERVER_ERROR, $errorMessage);
            return;
        }

        $response = new Response(null, self::HTTP_CODE_OK);
        $response
            ->setJsonContent($this->buildSuccessContent([$phoneBook->toArray()]))
            ->send();
    }

    private function findById(string $id)
    {
        $phoneBook = PhoneBook::findFirst("id = '" . $id . "'");
        if ($phoneBook === false) {
            $this->failResponse(self::HTTP_CODE_NOT_FOUND, 'Phone book not found');
            return;
        }

        return $phoneBook;
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

    private function paginateResults(ResultsetInterface $phoneBooks, $offset): void
    {
        $numItems = $this->request->get('numItems');
        if ($numItems === null) {
            $numItems = self::DEFAULT_NUM_ITEMS_PAGE;
        }

        $total = $phoneBooks->count();
        $paginator = new PaginatorModel(
            [
                'data' => $phoneBooks,
                'limit' => $numItems,
                'page' => $offset,
            ]
        );
        $page = $paginator->getPaginate();
        $phoneBooksArray = [];
        /** @var PhoneBook $phoneBook */
        foreach($page->items as $phoneBook) {
            $phoneBooksArray[] = $phoneBook->toArray();
        }

        $response = new Response(null, self::HTTP_CODE_OK);
        $content['status'] = 'success';
        $content['pagination']['total'] = $total;
        $content['pagination']['current'] = (int) $offset;
        $content['pagination']['previous'] = $page->before;
        $content['pagination']['next'] = $page->next;
        $content['pagination']['last'] = $page->last;
        $successContent = $this->buildSuccessContent($phoneBooksArray);
        $content['result'] = $successContent['result'];
        $response
            ->setJsonContent($content)
            ->send();
    }

    private function buildSuccessContent(array $phoneBooks): array
    {
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

        return [
            'status' => "success",
            'result' => $phoneBooksAsArray
        ];
    }
}
