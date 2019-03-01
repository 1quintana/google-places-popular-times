<?php

namespace lquintana\GooglePlaces;

use GuzzleHttp\Client;
use lquintana\GooglePlaces\Exceptions\GooglePlacesException;

class GooglePlaces
{

    const NEARBY_SEARCH_URL = 'https://maps.googleapis.com/maps/api/place/nearbysearch/json?';
    const DETAIL_URL = 'https://maps.googleapis.com/maps/api/place/details/json?';
    
    /**
     * Exception by google
     * @var Array
     */
    private $exceptions = [
        'REQUEST_DENIED' => 'API key is invalid. Request was denied!',
        'OVER_QUERY_LIMIT' => 'Query limit was exceeded for this key! Check https://developers.google.com/places/web-service/usage to upgrade your quota',
        'INVALID_REQUEST' => 'The query string is malformed, check if your formatting for lat/lng and radius is correct.',
        'NOT_FOUND' => 'The place ID was not found. Either does not exist or was retired.'
    ];

    /**
     * @var GuzzleHttp\Client 
     */
    private $client;

    /**
     * @var Array
     */
    private $params;

    public function __construct($key=null)
    {
        $this->client = new Client();
        $this->params['key'] = !is_null($key) ? $key : env('GOOGLE_KEY');
    }

    /**
     * Minimun params required location=26.189774,-80.103775&radius=5000
     * Refer to google documentation for more details, https://developers.google.com/places/web-service/search to Nearby Search requests
     * @return Array 
     */
    public function nearbyPlaces($params = [])
    {
        $nearbayPlaces = json_decode(
            $this->client->get(
                self::NEARBY_SEARCH_URL.  http_build_query(array_merge($params, $this->params))
            )->getBody()->getContents()
        );
        
        if(array_key_exists($nearbayPlaces->status, $this->exceptions))
            throw new GooglePlacesException($this->exceptions[$nearbayPlaces->status]);

        return $nearbayPlaces;
    }

    /**
     * Minimun params required location=26.189774,-80.103775&radius=5000
     * Refer to google documentation for more details, https://developers.google.com/places/web-service/search to Nearby Search requests
     * @return Array 
     */
    public function nearbyPlacesWithPopularTimes($params = [])
    {
        $nearbayPlaces = $this->nearbyPlaces($params);
        return Helpers::placesWithPopularTimes($nearbayPlaces->results);
    }

    /**
     * Minimun params required placeid, ex placeid=ChIJde9xlp4B2YgRVl9hn3TriiI
     * Refer to google documentation for more details, https://developers.google.com/places/web-service/details
     * @return Object 
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
     * @return Array 
     */
    public function placeDetailWithPopularTimes($params = null)
    {
        $placeDetails = $this->placeDetails($params);
        
        if($placeDetails->status == 'OK')
            return Helpers::placesWithPopularTimes([$placeDetails->result]);
    }
}