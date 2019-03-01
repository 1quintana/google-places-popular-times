<?php

namespace lquintana\GooglePlaces;

use GuzzleHttp\Client;
use GuzzleHttp\Promise;

class Helpers
{
    const GOOGLE_SEARCH_URL = 'https://www.google.com/search?';

    /**
    * Basic params required by google search
    * @var array
    */
    const PARAMS = [
        'tbm' => 'map',
        'hl' => 'en',
        'pb' => '!4m12!1m3!1d4005.9771522653964!2d-122.42072974863942!3d37.8077459796541!2m3!1f0!2f0!3f0!3m2!1i1125!2i976!4f13.1!7i20!10b1!12m6!2m3!5m1!6e2!20e3!10b1!16b1!19m3!2m2!1i392!2i106!20m61!2m2!1i203!2i100!3m2!2i4!5b1!6m6!1m2!1i86!2i86!1m2!1i408!2i200!7m46!1m3!1e1!2b0!3e3!1m3!1e2!2b1!3e2!1m3!1e2!2b0!3e3!1m3!1e3!2b0!3e3!1m3!1e4!2b0!3e3!1m3!1e8!2b0!3e3!1m3!1e3!2b1!3e2!1m3!1e9!2b1!3e2!1m3!1e10!2b0!3e3!1m3!1e10!2b1!3e2!1m3!1e10!2b0!3e4!2b1!4b1!9b0!22m6!1sa9fVWea_MsX8adX8j8AE%3A1!2zMWk6Mix0OjExODg3LGU6MSxwOmE5ZlZXZWFfTXNYOGFkWDhqOEFFOjE!7e81!12e3!17sa9fVWea_MsX8adX8j8AE%3A564!18e15!24m15!2b1!5m4!2b1!3b1!5b1!6b1!10m1!8e3!17b1!24b1!25b1!26b1!30m1!2b1!36b1!26m3!2m2!1i80!2i92!30m28!1m6!1m2!1i0!2i0!2m2!1i458!2i976!1m6!1m2!1i1075!2i0!2m2!1i1125!2i976!1m6!1m2!1i0!2i0!2m2!1i1125!2i20!1m6!1m2!1i0!2i956!2m2!1i1125!2i976!37m1!1e81!42b1!47m0!49m1!3b1',
    ];

    /**
    * Use to map popular times.
    * @var array
    */
    const MAP_DAYS = [
        1 => 'monday',
        2 => 'tuesday',
        3 => 'wednesday',
        4 => 'thursday',
        5 => 'friday',
        6 => 'saturday',
        7 => 'sunday',
    ];
    
    /**
     * Return Place with popular times
     * @param array $places
     * @return array
     */
    public static function placesWithPopularTimes($places = [])
    {
        $results = self::getPopularTimes($places);

        $placesWithTimes = [];
        foreach($results as $placeId => $result)
        {
            $googleResponse = $result['value']->getBody()->getContents();
            $googleResponse =  json_decode(str_replace(")]}'", "",$googleResponse));
            
            foreach($places as $place)
            {
                if(isset($googleResponse[0][1][0][14]) && $placeId == $place->place_id)
                {
                    $popularTime = $googleResponse[0][1][0][14];
                    $placesWithTimes[] = self::addPopularTimeToPlace($place, $popularTime);
                }elseif($placeId == $place->place_id){
                    $placesWithTimes[] = $place;
                }
            }
        }
        
        return $placesWithTimes;
    }
    
    /**
     * GetAsync all the times for all given places
     * @param array $places
     * @return array
     */
    public static function getPopularTimes($places = [])
    {
        $client = new Client();

        $requestPromises = [];
        foreach($places as $place)
        {
            //Find address and place name as unique id
            $address = isset($place->formatted_address) && $place->formatted_address != '' ? $place->formatted_address : $place->vicinity;
            $placeName = $place->name;

            $requestPromises[$place->place_id] = $client->getAsync(self::GOOGLE_SEARCH_URL.http_build_query(self::PARAMS)."&q=$address $placeName");
        }

        return Promise\settle($requestPromises)->wait();
    }
    
    /**
     * If place has position with time need to merge both place and times
     * @param $place
     * @param $popularTime
     * @return array
     */
    private static function addPopularTimeToPlace($place, $popularTime)
    {
        $time = [];
        if(isset($popularTime[84][0]))
            $time['popular_time'] = self::mapDaysOnPopularTime($popularTime[84][0]);
        
        if(isset($popularTime[84][6]))
            $time['now'] = $popularTime[84][6];

        if(isset($popularTime[117][0]))
            $time['time_spent'] = $popularTime[117][0];
        
        return array_merge((array) $place, $time);
    }
    
    /**
     * Convert popular times indexes to human read
     * @param $popularTimes
     * @return array
     */
    private static function mapDaysOnPopularTime($popularTimes)
    {
        $popularTimeDays = [];
        foreach($popularTimes as $popularTime)
        {
            $popularTimeDays[self::MAP_DAYS[$popularTime[0]]] = $popularTime;
        }

        return $popularTimeDays;
    }
}
