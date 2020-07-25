<?php

namespace oeleco\GoogleSuite\Directory\Group;

use Google_Service_Directory;
use oeleco\GoogleSuite\Contracts\GoogleDirectory;
use oeleco\GoogleSuite\Directory\GoogleDirectoryFactory;

class GoogleGroupFactory extends GoogleDirectoryFactory
{
    public static function getSpecifiedScopes() :array
    {
        return [
            Google_Service_Directory::ADMIN_DIRECTORY_GROUP
        ];
    }

    protected static function createDirectoryClient(Google_Service_Directory $service) : GoogleDirectory
    {
        return new GoogleGroup($service);
    }
}
