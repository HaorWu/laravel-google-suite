<?php

namespace oeleco\GoogleSuite\Directory\Account;

use Google_Service_Directory;
use oeleco\GoogleSuite\Contracts\GoogleDirectory;
use oeleco\GoogleSuite\Directory\GoogleDirectoryFactory;

class GoogleAccountFactory extends GoogleDirectoryFactory
{
    public static function getSpecifiedScopes() :array
    {
        return [
            Google_Service_Directory::ADMIN_DIRECTORY_USER
        ];
    }

    protected static function createDirectoryClient(Google_Service_Directory $service) : GoogleDirectory
    {
        return new GoogleAccount($service);
    }
}
