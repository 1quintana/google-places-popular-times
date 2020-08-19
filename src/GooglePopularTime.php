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

    public function __construct(array $config)
    {
        $this->client = new Client();
        $this->config = $config;
    }

    /**
     * Get nearby places from google api.
     *
     * @param array $params
     * @return object
     */
    public function getNearbyPlaces(array $params): object
    {
        return json_decode(
            $this->client->get(
                $this->getUrl('nearbysearch').'&'.http_build_query($params)
            )->getBody()->getContents()
        );
    }

    /**
     * Get nearby places from google API and add popular times.
     *
     * @param array $params
     * @return object
     */
    public function getNearbyPlacesWithPopularTimes(array $params)
    {
        $nearbyPlaces = $this->getNearbyPlaces($params);
        if ($nearbyPlaces->status == 'OK') {
            return PopularTime::placesWithPopularTimes($nearbyPlaces->results);
        }
    }

    /**
     * Get place with details.
     *
     * @param $placeId
     * @return object
     */
    public function getPlaceDetails(string $placeId): object
    {
        return json_decode(
            $this->client->get(
                $this->getUrl('details').'&place_id='.$placeId
            )->getBody()->getContents()
        );
    }

    /**
     * Get place with details and add popular times.
     *
     * @param string $placeId
     * @return array
     */
    public function getPlaceDetailsWithPopularTimes(string $placeId)
    {
        $placeDetails = $this->getPlaceDetails($placeId);

        if ($placeDetails->status == 'OK') {
            return PopularTime::placesWithPopularTimes([$placeDetails->result]);
        }
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
