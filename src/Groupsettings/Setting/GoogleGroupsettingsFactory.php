<?php
namespace oeleco\GoogleSuite\Groupsettings\Setting;

use Google_Service_Groupssettings;
use oeleco\GoogleSuite\GoogleSuiteFactory;

class GoogleGroupsettingsFactory extends GoogleSuiteFactory
{
    public static function make(): GoogleGroupsettings
    {
        $config = config('google-suite');

        $client = self::createAuthenticatedGoogleClient($config);

        $service = new Google_Service_Groupssettings($client);

        return self::createServiceClient($service);
    }

    public static function getSpecifiedScopes() :array
    {
        return [
            Google_Service_Groupssettings::APPS_GROUPS_SETTINGS,
        ];
    }

    protected static function createServiceClient(Google_Service_Groupssettings $service): GoogleGroupsettings
    {
        return new GoogleGroupsettings($service);
    }
}
