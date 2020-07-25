<?php

namespace oeleco\GoogleSuite\Directory\Member;

use Illuminate\Support\Facades\Facade;

class GoogleMemberFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-google-member';
    }
}
