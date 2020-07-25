<?php

namespace oeleco\GoogleSuite\Directory\Member;

use Google_Service_Directory;
use oeleco\GoogleSuite\GoogleSuiteFactory;
use oeleco\GoogleSuite\Contracts\GoogleDirectory;

class GoogleMemberFactory extends GoogleSuiteFactory
{
    public static function make(string $groupId = null) : GoogleDirectory
    {
        $config = config('google-suite');

        $client = static::createAuthenticatedGoogleClient($config);

        $service = new Google_Service_Directory($client);

        return static::createDirectoryClient($service, $groupId);
    }


    public static function getSpecifiedScopes() :array
    {
        return [
            Google_Service_Directory::ADMIN_DIRECTORY_GROUP_MEMBER
        ];
    }

    protected static function createDirectoryClient(Google_Service_Directory $service, string $groupId = null) : GoogleDirectory
    {
        return new GoogleMember($service, $groupId);
    }
}
