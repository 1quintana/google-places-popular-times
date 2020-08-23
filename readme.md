# GooglePopularTime

[![The MIT License](https://img.shields.io/badge/license-MIT-orange.svg?style=flat-square)](http://opensource.org/licenses/MIT)
[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Build Status][ico-travis]][link-travis]

After over 2000 download I had to close the other package since it was getting hard to maintain it was only working up to Laravel 5.6. I have no idea why Google is not sharing popular times yet in their API, so I developed this solution and I would like to share with the community of developers building this package. With this package you can get a list of places with its popular times or also a place details with its popular times.

After different testing I found this way which looks like the fastest one. I am not scraping google to extract the popular time, but I will simulate a serching and extract the results from there.

## Introduction

Everyone is welcome to became part of this project, but keep in mind that if you open a PR it will need to pass the github actions so I can review it.

You have to refer to the google API to understand their requirements and use this package https://developers.google.com/places/web-service/intro


## Requirements

- &gt;= PHP 7.1
- &gt;= Laravel 5.8

## Installation

Via Composer

``` bash
$ composer require lquintana/google-popular-times
```

## Configuration
You need to create a google key from the google console. The key need permisions to use places API. Add the following variables to the .env file in Laravel and replace YOUR GOOGLE KEY HERE for the google key.

``` bash
GOOGLE_KEY=YOUR GOOGLE KEY HERE
```

## Usage
Here there is an example of url when trying to get a list of popular places.
`http://places.test/api/places?location=26.189774,-80.103775&radius=5000&keyword=restaurants`

You should add the route places to route/api.php or create the route in any convinient location for your project


 @ This is an example of my controller in Laravel. You can find the method index which will list places with its own popular time for each of them 

``` php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use LQuintana\GooglePopularTime\Facades\GooglePopularTime;

class PlaceController extends Controller
{
    /**
     * @params $request
     * Return list of places with its popular time.
     */
    public function index(Request $request)
    {
        $data = $request->all();
        $places = GooglePopularTime::getNearbyPlacesWithPopularTimes($data);
        return response()->json($places, 200);
    }
}

```

## Here Some screenshots with the result from the previous method.
![screen shot 2019-02-25 at 9 49 53 am](https://user-images.githubusercontent.com/11234646/53346291-b9a7ed80-38e4-11e9-9a51-d5d48bf0b22b.png)

<br /><br /><br />
if you open the key popular times it contains all the days of the week

![screen shot 2019-02-25 at 9 49 27 am](https://user-images.githubusercontent.com/11234646/53346504-21f6cf00-38e5-11e9-8446-e4eb648b2731.png)

<br /><br />
![screen shot 2019-02-25 at 9 51 21 am](https://user-images.githubusercontent.com/11234646/53346638-6d10e200-38e5-11e9-93b2-d612cf83905d.png)


* Keep in mind that Google Places API require location=26.189774,-80.103775&radius=5000 as minimum parameters.
* Check the Google documentation in the following link to know more about the allowed parameters.
`https://developers.google.com/places/web-service/search`

This is an example of the url to get a place with its own popular times.
`http://places.test/api/places/ChIJ5z2kxpsB2YgRjUgd_WuWdWc`

Google Places API Minimum params required placeid, ex placeid=ChIJde9xlp4B2YgRVl9hn3TriiI
* Refer to google documentation for more details. `https://developers.google.com/places/web-service/details`

@ The next code is an example of my controller in Laravel. You can find the method index which list places with its own popular time for each of them and the method show to show only one place with its own popular times.

``` php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use LQuintana\GooglePopularTime\Facades\GooglePopularTime;

class PlaceController extends Controller
{
    /**
     * @params $request
     * Return place with its popular time.
     */
    public function show(Request $request, $place)
    {
        $placeDetails = GooglePopularTime::getPlaceDetailsWithPopularTimes($place);
        return response()->json($placeDetails, 200);
    }
}

```

This is an example of the url to get a place with its own popular times.
`http://places.test/api/places/ChIJ5z2kxpsB2YgRjUgd_WuWdWc`

* Do not forget to create the route.

If you just want a list of places without popular time this is the method 
``` php
<?php

GooglePopularTime::getNearbyPlaces($data);

```

If you just want a place details without popular time this is the method 
``` php
<?php

GooglePopularTime::getPlaceDetails($placeId);

```

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Security

If you discover any security related issues, please email author email instead of using the issue tracker.

## Credits

- [Luciano Quintana][link-author] (Venmo a coffee if you like this work @kinpal80)
- [All Contributors][link-contributors]

## License

license. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/lquintana/google-popular-times.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/lquintana/google-popular-times.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/lquintana/google-popular-times/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/12345678/shield

[link-packagist]: https://packagist.org/packages/lquintana/google-popular-times
[link-downloads]: https://packagist.org/packages/lquintana/google-popular-times
[link-travis]: https://travis-ci.org/1quintana/google-popular-times
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://github.com/lquintana
[link-contributors]: ../../contributors
