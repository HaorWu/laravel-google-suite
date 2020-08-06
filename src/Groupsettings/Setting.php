<?php

namespace oeleco\GoogleSuite\Groupsettings;

use Illuminate\Support\Arr;

use Google_Service_Groupssettings_Groups;
use oeleco\GoogleSuite\Groupsettings\Setting\GoogleGroupsettings;
use oeleco\GoogleSuite\Groupsettings\Setting\GoogleGroupsettingsFactory;

class Setting
{
    /** @var Google_Service_Groupssettings_Groups */
    public $googleSetting;

    public function __construct()
    {
        $this->attendees = [];
        $this->googleSetting = new Google_Service_Groupssettings_Groups;
    }

    /**
     * @param Google_Service_Groupssettings_Groups $googleSetting
     * @return static
     */
    public static function createFromGoogleGroupsettings(Google_Service_Groupssettings_Groups $googleSetting)
    {
        $event = new static;
        $event->googleSetting = $googleSetting;

        return $event;
    }


    public static function find($groupId): self
    {
        $googleGroupsettings = static::getGoogleGroupsettings();

        $googleSetting = $googleGroupsettings->getSetting($groupId);

        return static::createFromGoogleGroupsettings($googleSetting);
    }

    public function __get($name)
    {
        $name = $this->getFieldName($name);

        $value = Arr::get($this->googleSetting, $name);

        return $value;
    }

    public function __set($name, $value)
    {
        $name = $this->getFieldName($name);

        Arr::set($this->googleSetting, $name, $value);
    }

    public function save($optParams = []): self
    {
        $googleGroupsettings = $this->getGoogleGroupsettings();

        $googleSetting = $googleGroupsettings->updateSetting($this, $optParams);

        return static::createFromGoogleGroupsettings($googleSetting);
    }

    public function update(array $attributes, $optParams = []): self
    {
        foreach ($attributes as $name => $value) {
            $this->$name = $value;
        }

        return $this->save($optParams);
    }

    protected static function getGoogleGroupsettings(): GoogleGroupsettings
    {
        return GoogleGroupsettingsFactory::make();
    }

    protected function getFieldName(string $name): string
    {
        return [
            'name' => 'name',
            'email' => 'email',
            'description' => 'description'
        ][$name] ?? $name;
    }
}
