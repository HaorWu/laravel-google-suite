<?php

namespace oeleco\GoogleSuite\Directory\Account;

use Illuminate\Support\Facades\Facade;

class GoogleAccountFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-google-account';
    }
}
