<?php

namespace HostAway\Models;

use HostAway\Services\CountryService;
use Phalcon\Mvc\Model;

class PhoneBook extends Model
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $firstName;

    /**
     * @var string
     */
    protected $lastName;

    /**
     * @var string
     */
    protected $phoneNumber;

    /**
     * @var string
     */
    protected $countryCode;

    /**
     * @var string
     */
    protected $timeZone;

    /**
     * @var string
     */
    protected $insertedOn;

    /**
     * @var string
     */
    protected $updatedOn;

    public function getId(): string
    {
        return $this->id;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    public function getTimeZone(): string
    {
        return $this->timeZone;
    }

    public function getInsertedOn(): string
    {
        return $this->insertedOn;
    }

    public function getUpdatedOn(): string
    {
        return $this->updatedOn;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function setPhoneNumber(string $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    public function setCountryCode(string $countryCode): void
    {
        /** @var CountryService $countryService */
        $countryService = $this->getDI()->get('country_service');
        $countyCodes = $countryService->getCountryCodes();
        var_dump($countyCodes);
        if (!in_array($countryCode, $countyCodes)) {
            throw new \InvalidArgumentException(
                'The country_code ' . $countryCode . ' does not exist.'
                . ' Please check the ' . CountryService::COUNTRIES_URL . ' to see which countries are valid'
            );
        }
        $this->countryCode = $countryCode;
    }

    public function setTimeZone(string $timeZone): void
    {
        $this->timeZone = $timeZone;
    }

    public function setInsertedOn(string $insertedOn): void
    {
        $this->insertedOn = $insertedOn;
    }

    public function setUpdatedOn(string $updatedOn): void
    {
        $this->updatedOn = $updatedOn;
    }
}
