<?php

namespace Test;

use HostAway\Models\PhoneBook;
use HostAway\Services\CountryService;

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

    public function testEmptyFirstNameShouldThrowAnException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->phoneBook->setFirstName('');
    }

    public function testShouldReturnTheFirstName()
    {
        $firstName = 'Phone Book';
        $this->phoneBook->setFirstName($firstName);
        $this->assertEquals(
            $firstName,
            $this->phoneBook->getFirstName()
        );
    }

    public function testEmptyPhoneNumberShouldThrowAnException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->phoneBook->setPhoneNumber('');
    }

    public function testBadFormatPhoneNumberShouldThrowAnException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->phoneBook->setPhoneNumber('42334234');
    }

    public function testShouldReturnThePhoneNumber()
    {
        $phoneNumber = '+12 223';
        $this->phoneBook->setPhoneNumber($phoneNumber);
        $this->assertEquals(
            $phoneNumber,
            $this->phoneBook->getPhoneNumber()
        );
    }

    public function testShouldReturnTheEmptyCountryCode()
    {
        $emptyCountryCode = '';
        $this->phoneBook->setCountryCode($emptyCountryCode);
        $this->assertEquals(
            $emptyCountryCode,
            $this->phoneBook->getCountryCode()
        );
    }

    public function testNotExistentCountryCodeShouldThrownAnException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $countryCodes = ['ES'];
        $countryService = $this->prophesize(CountryService::class);
        $countryService->getCountryCodes()->shouldBeCalled()->willReturn($countryCodes);
        $this->getDI()->set('country_service', $countryService->reveal());
        $this->phoneBook->setCountryCode('NOT_EXIST');
    }

    public function testShouldReturnTheCountryCode()
    {
        $countryCodes = ['ES'];
        $countryService = $this->prophesize(CountryService::class);
        $countryService->getCountryCodes()->shouldBeCalled()->willReturn($countryCodes);
        $this->getDI()->set('country_service', $countryService->reveal());
        $countryCode = 'ES';
        $this->phoneBook->setCountryCode($countryCode);
        $this->assertEquals(
            $countryCode,
            $this->phoneBook->getCountryCode()
        );
    }

    protected function tearDown()
    {
        parent::tearDown();
        $this->phoneBook = null;
    }


}