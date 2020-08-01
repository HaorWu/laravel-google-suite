<?php
namespace oeleco\GoogleSuite\Calendar\Event;

use Google_Service_Calendar;
use oeleco\GoogleSuite\GoogleSuiteFactory;

class GoogleCalendarFactory extends GoogleSuiteFactory
{
    public static function make(string $calendarId = null): GoogleCalendar
    {
        $config = config('google-suite');

        $client = self::createAuthenticatedGoogleClient($config);

        $service = new Google_Service_Calendar($client);

        return self::createServiceClient($service, $calendarId);
    }

    public static function getSpecifiedScopes() :array
    {
        return [
            Google_Service_Calendar::CALENDAR,
            Google_Service_Calendar::CALENDAR_EVENTS
        ];
    }

    protected static function createServiceClient(Google_Service_Calendar $service, string $calendarId = null): GoogleCalendar
    {
        return new GoogleCalendar($service, $calendarId);
    }
}
