<?php

namespace LQuintana\GooglePopularTime;

use GuzzleHttp\Client;
use LQuintana\GooglePopularTime\Helpers\PopularTime;

class GooglePopularTime
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var array
     */
    private $config;

    public function __construct(array $config = [])
    {
        $this->client = new Client();
        $this->config = $config;
    }

    /**
     * Minimum params required ['location' => '26.189774,-80.103775', 'radius' => 5000]
     * Refer to google documentation for more details, https://developers.google.com/places/web-service/search to Nearby Search requests.
     * @param array $params
     * @return object
     */
    public function getNearbyPlaces(array $params = []): object
    {
        return json_decode(
            $this->client->get(
                $this->getUrl('nearbysearch').'&'.http_build_query($params)
            )->getBody()->getContents()
        );
    }

    /**
     * Minimum params required ['location' => '26.189774,-80.103775', 'radius' => 5000]
     * Refer to google documentation for more details, https://developers.google.com/places/web-service/search to Nearby Search requests.
     * @param array $params
     * @return object
     */
    public function getNearbyPlacesWithPopularTimes(array $params = [])
    {
        return $nearbyPlaces = $this->getNearbyPlaces($params);

        return PopularTime::placesWithPopularTimes($nearbyPlaces->results);
    }

    /**
     * Return url to Google API.
     * @param string $type
     * @return string
     */
    protected function getUrl(string $type): string
    {
        return $this->config['url'].$this->config['places'][$type].http_build_query($this->config['credentials']);
    }
}
