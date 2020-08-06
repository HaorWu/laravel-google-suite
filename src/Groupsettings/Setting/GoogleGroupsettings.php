<?php
namespace oeleco\GoogleSuite\Groupsettings\Setting;

use Google_Service_Groupssettings;
use Google_Service_Groupssettings_Groups;
use oeleco\GoogleSuite\Groupsettings\Setting;

class GoogleGroupsettings
{
    /** @var \Google_Service_Groupssettings */
    protected $settingsService;

    public function __construct(Google_Service_Groupssettings $settingsService)
    {
        $this->settingsService = $settingsService;
    }

    /*
     * @link https://developers.google.com/admin-sdk/groups-settings/v1/reference/groups/get
     */
    public function getSetting(string $groupId): Google_Service_Groupssettings_Groups
    {
        return $this->settingsService->groups->get($groupId, ['alt' => 'json']);
    }

    /*
    * @link https://developers.google.com/admin-sdk/groups-settings/v1/reference/groups/update
    */
    public function updateSetting($group): Google_Service_Groupssettings_Groups
    {
        if ($group instanceof Setting) {
            $group = $group->googleSetting;
        }

        return $this->settingsService->groups->update($group->email, $group);
    }

    public function getService(): Google_Service_Groupssettings
    {
        return $this->settingsService;
    }
}
