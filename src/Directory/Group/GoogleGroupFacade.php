<?php

namespace oeleco\GoogleSuite\Directory\Group;

use Illuminate\Support\Facades\Facade;

class GoogleGroupFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-google-group';
    }
}
