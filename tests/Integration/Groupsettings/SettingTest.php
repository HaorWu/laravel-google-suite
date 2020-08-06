<?php

namespace oeleco\GoogleSuite\Tests\Integration;

use Mockery as m;

use oeleco\GoogleSuite\Groupsettings\Setting;
use oeleco\GoogleSuite\Tests\TestCase;

class SettingTest extends TestCase
{
    /** @var \oeleco\GoogleSuite\Groupsettings\Setting */
    protected $event;

    public function setUp(): void
    {
        parent::setUp();
        $this->setting = new Setting;
    }

    /** @test */
    public function it_cat_get_settings_for_a_google_group()
    {
        $setting = Setting::find("emailgrouo@hosteddomain.com");
        $this->assertEquals($setting->email, "emailgrouo@hosteddomain.com");
    }

    /** @test */
    public function it_can_update_a_google_group_settings()
    {
        $setting = Setting::find("emailgrouo@hosteddomain.com");
        $setting->description = "Test description for google group";
        $setting->save();

        $this->assertEquals($setting->description, "Test description for google group");
    }
}
