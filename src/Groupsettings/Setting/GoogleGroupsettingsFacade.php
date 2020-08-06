<?php
namespace oeleco\GoogleSuite\Groupsettings\Setting;

use Illuminate\Support\Facades\Facade;

class GoogleGroupsettingsFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-google-group-settings';
    }
}
