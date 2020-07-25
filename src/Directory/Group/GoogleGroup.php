<?php

namespace oeleco\GoogleSuite\Directory\Group;

use Google_Service_Directory;
use Google_Service_Directory_Group;
use Google_Service_Directory_Groups;

use oeleco\GoogleSuite\Directory\Group;
use oeleco\GoogleSuite\Contracts\GoogleDirectory;

class GoogleGroup implements GoogleDirectory
{
    /** @var \Google_Service_Directory */
    protected $serviceDirectory;

    public function __construct(Google_Service_Directory $serviceDirectory)
    {
        $this->serviceDirectory = $serviceDirectory;
    }

    /*
     * @link https://developers.google.com/admin-sdk/directory/v1/reference/groups/list
     */
    public function listGroups(array $queryParameters = []): Google_Service_Directory_Groups
    {
        $parameters = [
            'orderBy' => 'email',
        ];

        $parameters = array_merge($parameters, $queryParameters);

        return $this
            ->serviceDirectory
            ->groups
            ->listGroups($parameters);
    }

    public function getGroup(string $groupKey): Google_Service_Directory_Group
    {
        return $this->serviceDirectory->groups->get($groupKey);
    }

    /*
     https://developers.google.com/admin-sdk/directory/v1/reference/groups/insert
     */
    public function insertGroup($group, $optParams = []): Google_Service_Directory_Group
    {
        if ($group instanceof Group) {
            $group = $group->googleGroup;
        }

        return $this->serviceDirectory->groups->insert($group, $optParams);
    }


    public function updateGroup($group): Google_Service_Directory_Group
    {
        if ($group instanceof Group) {
            $group = $group->googleGroup;
        }

        return $this->serviceDirectory->groups->update($group->id, $group);
    }

    public function deleteGroup($group)
    {
        if ($group instanceof Group) {
            $group = $group->id;
        }

        $this->serviceDirectory->groups->delete($group);
    }

    public function getService(): Google_Service_Directory
    {
        return $this->serviceDirectory;
    }
}
