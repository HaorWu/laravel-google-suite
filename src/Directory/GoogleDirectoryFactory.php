<?php

namespace oeleco\GoogleSuite\Directory;

use Google_Service_Directory;
use oeleco\GoogleSuite\GoogleSuiteFactory;
use oeleco\GoogleSuite\Contracts\GoogleDirectory;

abstract class GoogleDirectoryFactory extends GoogleSuiteFactory
{
    public static function make() : GoogleDirectory
    {
        $config = config('google-suite');

        $client = static::createAuthenticatedGoogleClient($config);

        $service = new Google_Service_Directory($client);

        return static::createDirectoryClient($service);
    }

    abstract protected static function createDirectoryClient(Google_Service_Directory $service) : GoogleDirectory;
}
