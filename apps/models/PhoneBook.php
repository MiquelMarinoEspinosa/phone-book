<?php

namespace HostAway\Models;

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
