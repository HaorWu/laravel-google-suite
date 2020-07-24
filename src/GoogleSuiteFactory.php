<?php

namespace oeleco\GoogleSuite;

use Google_Client;
use oeleco\GoogleSuite\Contracts\GoogleDirectoryService;
use oeleco\GoogleSuite\Directory\GoogleDirectoryFactory;

abstract class GoogleSuiteFactory
{
    abstract public static function make();

    abstract public static function getSpecifiedScopes() :array ;

    public static function createAuthenticatedGoogleClient(array $config): Google_Client
    {
        $client = new Google_Client;

        $client->setSubject($config['service_account']);
        $client->setAuthConfig($config['credentials']);
        $client->addScope(static::getSpecifiedScopes());

        return $client;
    }
}
