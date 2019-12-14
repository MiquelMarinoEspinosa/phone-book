<?php

namespace HostAway\Services;

use GuzzleHttp\Client;
use Phalcon\Cache\Backend\Libmemcached as BackMemCached;

class CountryService
{
    const COUNTRIES_URL = "https://api.hostaway.com/countries";
    const COUNTRY_CODES_CACHE_KEY = 'country_codes';

    /**
     * @var Client
     */
    private $client;

    /**
     * @var BackMemCached;
     */
    private $backMemCached;

    public function __construct(
        Client $client,
        BackMemCached $backMemCached
    ) {
        $this->client = $client;
        $this->backMemCached = $backMemCached;
    }

    public function getCountryCodes(): array
    {
        $countryCodes = $this->backMemCached->get(self::COUNTRY_CODES_CACHE_KEY);
        if ($countryCodes !== null) {
            return $countryCodes;
        }

        $response = $this->client->get(self::COUNTRIES_URL);
        $content = $response->getBody()->getContents();
        $countries = json_decode($content, true);
        $countryCodes = array_keys($countries['result']);
        $this->backMemCached->save(self::COUNTRY_CODES_CACHE_KEY, $countryCodes);

        return $countryCodes;
    }
}
