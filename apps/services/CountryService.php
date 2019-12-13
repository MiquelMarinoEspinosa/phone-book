<?php

namespace HostAway\Services;

use GuzzleHttp\Client;

class CountryService
{
    const COUNTRIES_URL = "https://api.hostaway.com/countries";

    /**
     * @var Client
     */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getCountryCodes(): array
    {
       $response = $this->client->get(self::COUNTRIES_URL);
       $content = $response->getBody()->getContents();
       $countries = json_decode($content, true);

       return array_keys($countries['result']);
    }
}
