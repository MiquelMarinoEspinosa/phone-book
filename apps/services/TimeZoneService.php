<?php

namespace HostAway\Services;

use GuzzleHttp\Client;
use Phalcon\Cache\Backend\Libmemcached as BackMemCached;

class TimeZoneService
{
    const TIME_ZONE_URL = "https://api.hostaway.com/timezones";
    const TIME_ZONES_CACHE_KEY = 'time_zones';

    /**
     * @var Client
     */
    private $client;

    /**
     * @var BackMemCached
     */
    private $backMemCached;

    public function __construct(
        Client $client,
        BackMemCached $backMemCached
    ) {
        $this->client = $client;
        $this->backMemCached = $backMemCached;
    }

    public function getTimeZones(): array
    {
        $timeZonesNames = $this->backMemCached->get(self::TIME_ZONES_CACHE_KEY);
        if ($timeZonesNames !== null) {
            return $timeZonesNames;
        }

        $response = $this->client->get(self::TIME_ZONE_URL);
        $content = $response->getBody()->getContents();
        $timeZones = json_decode($content, true);
        $timeZonesNames = array_keys($timeZones['result']);
        $this->backMemCached->save(self::TIME_ZONES_CACHE_KEY, $timeZonesNames);

        return $timeZonesNames;
    }
}
