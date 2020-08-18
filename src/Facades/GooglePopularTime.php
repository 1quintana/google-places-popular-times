<?php

namespace LQuintana\GooglePopularTime\Facades;

use Illuminate\Support\Facades\Facade;

class GooglePopularTime extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'googlepopulartime';
    }
}
