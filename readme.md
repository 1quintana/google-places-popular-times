# Google Places With Popular Times For Laravel 5

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Build Status][ico-travis]][link-travis]
[![StyleCI][ico-styleci]][link-styleci]

## Introduction

I am workig on this package for laravel 5.6 or higher versions to get list of places with its popular time or a place details with the popular times which is still not shared by the google API.

You have to refer to the google API to understand their requirements and use this package https://developers.google.com/places/web-service/intro

## Requirements

- PHP 7.1 or higher
- Laravel 5

## Installation

Via Composer

``` bash
$ composer require lquintana/google-places-popular-times
```
Open the file config/app.php and add the service provider to the providers array.

'providers' => [
    lquintana\GooglePlaces\GooglePlacesServiceProvider
],

On the same file config/app.php add the alias to the aliases array.

'aliases' => [
    "GooglePlaces" => lquintana\GooglePlaces\Facades\GooglePlaces
]


## Configuration
You first need to create a google key from the google console to use this package and add the following variables to the .env file in Laravel.

``` bash
GOOGLE_KEY=ADD YOUR GOOGLE KEY HERE
GOOGLE_PLACE_NEARBY_SEARCH_URL=https://maps.googleapis.com/maps/api/place/nearbysearch/json?
GOOGLE_PLACE_DETAIL_URL=https://maps.googleapis.com/maps/api/place/details/json?
```

## Usage

<br /><br />
This is en example of the url that I run in my local to get the results when trying to get a list of popular places.
http://places.test/api/places?location=26.189774,-80.103775&radius=5000&keyword=restaurants

This is an example of the url to get a place with its own popular times.
http://places.test/api/places/ChIJ5z2kxpsB2YgRjUgd_WuWdWc


<br /><br />
This is an example of my controller on laravel. You can find the method index which list places with its own popular time for each of them and the method show to show only one place with its own popular times.


``` bash
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Google\Places as GooglePlacesHelper;
use App\Http\Controllers\Controller;
use lquintana\GooglePlaces\GooglePlaces;
use lquintana\GooglePlaces\Exceptions\GooglePlacesException;

class PlaceController extends Controller
{

    /**
     * @params $request
     * Return list of places with its popular time
     */
    public function index(Request $request)
    {
        $data = $request->all();
        $googlePlaces = new GooglePlaces();

        try{
            $places = $googlePlaces->nearbyPlacesWithPopularTimes($data);
        }catch(GooglePlacesException $e){
           return $e->getMessage();
        }
        return response()->json($places, 200);
    }

    /**
     * @params $request, $place
     * Retrun a place of given id with its own popular times
     */
    public function show(Request $request, $place)
    {
        $data = $request->all();
        $googlePlaces = new GooglePlaces();

        try{
            $places = $googlePlaces->placeDetailWithPopularTimes($place);
        }catch(GooglePlacesException $e){
           return $e->getMessage();
        }
        return response()->json($places, 200);
    }
}

```

## Some screenshots with results from the method index 
![screen shot 2019-02-25 at 9 49 53 am](https://user-images.githubusercontent.com/11234646/53346291-b9a7ed80-38e4-11e9-9a51-d5d48bf0b22b.png)

<br /><br /><br />
if you open the key popular times it contains all the days of the week

![screen shot 2019-02-25 at 9 49 27 am](https://user-images.githubusercontent.com/11234646/53346504-21f6cf00-38e5-11e9-8446-e4eb648b2731.png)


<br /><br />
![screen shot 2019-02-25 at 9 51 21 am](https://user-images.githubusercontent.com/11234646/53346638-6d10e200-38e5-11e9-93b2-d612cf83905d.png)

## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Security

If you discover any security related issues, please email lquintana0707@gmail.com instead of using the issue tracker.

## Credits

- [Luciano Quintana][link-author]
- [All Contributors][link-contributors]

## License

MIT. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/lquintana/googleplaces.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/lquintana/googleplaces.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/lquintana/googleplaces/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/12345678/shield

[link-packagist]: https://packagist.org/packages/lquintana/googleplaces
[link-downloads]: https://packagist.org/packages/lquintana/googleplaces
[link-travis]: https://travis-ci.org/lquintana/googleplaces
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://github.com/lquintana
[link-contributors]: ../../contributors]
