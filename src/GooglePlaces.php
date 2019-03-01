<?php

namespace lquintana\GooglePlaces;

use GuzzleHttp\Client;
use lquintana\GooglePlaces\Exceptions\GooglePlacesException;

final class GooglePlaces
{
    const NEARBY_SEARCH_URL = 'https://maps.googleapis.com/maps/api/place/nearbysearch/json?';
    const DETAIL_URL = 'https://maps.googleapis.com/maps/api/place/details/json?';
    
    /**
     * Google exceptions
     * @var array
     */
    private $exceptions = [
        'REQUEST_DENIED' => 'API key is invalid. Request was denied!',
        'OVER_QUERY_LIMIT' => 'Query limit was exceeded for this key! Check https://developers.google.com/places/web-service/usage to upgrade your quota',
        'INVALID_REQUEST' => 'The query string is malformed, check if your formatting for lat/lng and radius is correct.',
        'NOT_FOUND' => 'The place ID was not found. Either does not exist or was retired.'
    ];

    /**
     * @var Client
     */
    private $client;

    /**
     * @var array
     */
    private $params;

    public function __construct($key=null)
    {
        $this->client = new Client();
        $this->params['key'] = !is_null($key) ? $key : env('GOOGLE_KEY');
    }
    
    /**
     * Minimum params required location=26.189774,-80.103775&radius=5000
     * Refer to google documentation for more details, https://developers.google.com/places/web-service/search to Nearby Search requests
     * @param array $params
     * @return array
     * @throws GooglePlacesException
     */
    public function nearbyPlaces($params = [])
    {
        $nearbyPlaces = json_decode(
            $this->client->get(
                self::NEARBY_SEARCH_URL.  http_build_query(array_merge($params, $this->params))
            )->getBody()->getContents()
        );
        
        if(array_key_exists($nearbyPlaces->status, $this->exceptions))
            throw new GooglePlacesException($this->exceptions[$nearbyPlaces->status]);

        return $nearbyPlaces;
    }
    
    /**
     * Minimum params required location=26.189774,-80.103775&radius=5000
     * Refer to google documentation for more details, https://developers.google.com/places/web-service/search to Nearby Search requests
     * @param array $params
     * @return array
     * @throws GooglePlacesException
     */
    public function nearbyPlacesWithPopularTimes($params = [])
    {
        $nearbyPlaces = $this->nearbyPlaces($params);
        return Helpers::placesWithPopularTimes($nearbyPlaces->results);
    }
    
    /**
     * Minimum params required placeid, ex placeid=ChIJde9xlp4B2YgRVl9hn3TriiI
     * Refer to google documentation for more details, https://developers.google.com/places/web-service/details
     * @param null $params
     * @return Object
     * @throws GooglePlacesException
     */
    public function placeDetails($params = null)
    {
        $placeDetails = json_decode(
            $this->client->get(
                self::DETAIL_URL.  http_build_query(array_merge(['place_id' => $params], $this->params))
            )->getBody()->getContents()
        );

        if(array_key_exists($placeDetails->status, $this->exceptions))
            throw new GooglePlacesException($this->exceptions[$placeDetails->status]);

        return $placeDetails;
    }
    
    /**
     * Minimun params required placeid, ex placeid=ChIJde9xlp4B2YgRVl9hn3TriiI
     * Refer to google documentation for more details, https://developers.google.com/places/web-service/details
     * @param null $params
     * @return array
     * @throws GooglePlacesException
     */
    public function placeDetailWithPopularTimes($params = null)
    {
        $placeDetails = $this->placeDetails($params);
        
        if($placeDetails->status == 'OK')
            return Helpers::placesWithPopularTimes([$placeDetails->result]);
    }
}
