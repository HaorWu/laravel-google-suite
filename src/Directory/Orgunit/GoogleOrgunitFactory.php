<?php

namespace oeleco\GoogleSuite\Directory\Orgunit;

use Google_Service_Directory;
use oeleco\GoogleSuite\Contracts\GoogleDirectory;
use oeleco\GoogleSuite\Directory\GoogleDirectoryFactory;
use oeleco\GoogleSuite\GoogleSuiteFactory;

class GoogleOrgunitFactory extends GoogleSuiteFactory
{
    public static function make(string $customerId = null) : GoogleDirectory
    {
        $config = config('google-suite');

        $client = static::createAuthenticatedGoogleClient($config);

        $service = new Google_Service_Directory($client);

        return static::createDirectoryClient($service, $customerId);
    }


    public static function getSpecifiedScopes() :array
    {
        return [
            Google_Service_Directory::ADMIN_DIRECTORY_ORGUNIT
        ];
    }

    protected static function createDirectoryClient(Google_Service_Directory $service, string $customerId = null) : GoogleDirectory
    {
        return new GoogleOrgUnit($service, $customerId);
    }
}
