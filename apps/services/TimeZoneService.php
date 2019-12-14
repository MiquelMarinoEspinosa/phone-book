<?php

namespace HostAway\Services;

use GuzzleHttp\Client;

class TimeZoneService
{
    const TIME_ZONE_URL = "https://api.hostaway.com/timezones";

    /**
     * @var Client
     */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getTimeZones(): array
    {
       $response = $this->client->get(self::TIME_ZONE_URL);
       $content = $response->getBody()->getContents();
       $timeZones = json_decode($content, true);

       return array_keys($timeZones['result']);
    }
}
