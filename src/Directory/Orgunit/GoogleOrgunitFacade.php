<?php

namespace oeleco\GoogleSuite\Directory\Orgunit;

use Illuminate\Support\Facades\Facade;

class GoogleOrgunitFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-google-orgunit';
    }
}
