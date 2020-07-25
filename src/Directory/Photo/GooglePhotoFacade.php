<?php

namespace oeleco\GoogleSuite\Directory\Photo;

use Illuminate\Support\Facades\Facade;

class GooglePhotoFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-google-photo';
    }
}
