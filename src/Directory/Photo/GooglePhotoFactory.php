<?php

namespace oeleco\GoogleSuite\Directory\Photo;

use Google_Service_Directory;
use oeleco\GoogleSuite\Contracts\GoogleDirectory;
use oeleco\GoogleSuite\Directory\GoogleDirectoryFactory;
use oeleco\GoogleSuite\GoogleSuiteFactory;

class GooglePhotoFactory extends GoogleSuiteFactory
{
    public static function make(string $userKey = null) : GoogleDirectory
    {
        $config = config('google-suite');

        $client = static::createAuthenticatedGoogleClient($config);

        $service = new Google_Service_Directory($client);

        return static::createDirectoryClient($service, $userKey);
    }


    public static function getSpecifiedScopes() :array
    {
        return [
            Google_Service_Directory::ADMIN_DIRECTORY_USER
        ];
    }

    protected static function createDirectoryClient(Google_Service_Directory $service, string $userKey = null) : GoogleDirectory
    {
        return new GooglePhoto($service, $userKey);
    }
}
