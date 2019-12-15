<?php

namespace Test;

use HostAway\Models\PhoneBook;

/**
 * Class UnitTest
 */
class PhoneBookTest extends \UnitTestCase
{
    /**
     * @var PhoneBook
     */
    private $phoneBook;

    public function setUp()
    {
        parent::setUp();
        $this->phoneBook = new PhoneBook();

    }

    public function testFirstNameEmptyShouldThrowAnException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->phoneBook->setFirstName('');
    }
}