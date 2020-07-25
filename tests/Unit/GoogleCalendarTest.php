<?php

namespace oeleco\GoogleSuite\Tests\Unit;

use Mockery;
use Google_Service_Calendar;
use PHPUnit\Framework\TestCase;
use oeleco\GoogleSuite\Calendar\GoogleCalendar;

class GoogleCalendarTest extends TestCase
{
    /** @var \Mockery\Mock|Google_Service_Calendar */
    protected $googleServiceCalendar;

    /** @var string */
    protected $calendarId;

    /** @var \Spatie\GoogleCalendar\GoogleCalendar */
    protected $googleCalendar;

    public function setUp(): void
    {
        parent::setUp();

        $this->googleServiceCalendar = Mockery::mock(Google_Service_Calendar::class);

        $this->calendarId = 'primary';

        $this->googleCalendar = new GoogleCalendar($this->googleServiceCalendar);
    }

    /** @test */
    public function it_provides_a_getter_for_calendarId()
    {
        $this->assertEquals($this->calendarId, $this->googleCalendar->getCalendarId());
    }
}
