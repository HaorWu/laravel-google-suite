<?php

namespace oeleco\GoogleSuite\Directory;

use Illuminate\Support\Arr;

use Illuminate\Support\Collection;
use Google_Service_Directory_Group;

use oeleco\GoogleSuite\Directory\Group\GoogleGroup;
use oeleco\GoogleSuite\Directory\Group\GoogleGroupFactory;

class Group
{
    /** @var \Google_Service_Directory_Group */
    public $googleGroup;

    public function __construct()
    {
        $this->googleGroup = new Google_Service_Directory_Group;
    }

    /**
     * @param \Google_Service_Directory_Group $googleGroup
     *
     * @return static
     */
    public static function createFromGoogleGroup(Google_Service_Directory_Group $googleGroup)
    {
        $group = new static;

        $group->googleGroup = $googleGroup;

        return $group;
    }

    /**
     * @param array $properties
     *
     * @return mixed
     */
    public static function create(array $properties, $optParams = [])
    {
        $group = new static;

        foreach ($properties as $name => $value) {
            $group->$name = $value;
        }

        return $group->save('insertGroup', $optParams);
    }


    public static function get(array $queryParameters = []): Collection
    {
        $googleClient = static::getGoogleClient();

        $googleGroups = $googleClient->listGroups($queryParameters);

        $googleGroupsList = $googleGroups->getGroups();

        while ($googleGroups->getNextPageToken()) {
            $queryParameters['pageToken'] = $googleGroups->getNextPageToken();

            $googleGroups = $googleClient->listGroups($queryParameters);

            $googleGroupsList = array_merge($googleGroupsList, $googleGroups->getGroups());
        }

        $useUserOrder = isset($queryParameters['orderBy']);

        return collect($googleGroupsList)
            ->map(function (Google_Service_Directory_Group $group) {
                return static::createFromGoogleGroup($group);
            })
            ->sortBy(function (self $group, $index) use ($useUserOrder) {
                if ($useUserOrder) {
                    return $index;
                }

                return $group->email;
            })
            ->values();
    }

    public static function find($groupId): self
    {
        $googleClient = static::getGoogleClient();

        $googleGroup = $googleClient->getGroup($groupId);

        return static::createFromGoogleGroup($googleGroup);
    }

    public function __get($name)
    {
        $name = $this->getFieldName($name);

        $value = Arr::get($this->googleGroup, $name);

        return $value;
    }

    public function __set($name, $value)
    {
        $name = $this->getFieldName($name);

        Arr::set($this->googleGroup, $name, $value);
    }

    public function exists(): bool
    {
        return $this->id != '';
    }

    public function save(string $method = null, $optParams = []): self
    {
        $method = $method ?? ($this->exists() ? 'updateGroup' : 'insertGroup');

        $googleClient = $this->getGoogleClient();

        $googleGroup = $googleClient->$method($this, $optParams);

        return static::createFromGoogleGroup($googleGroup);
    }


    public function update(array $attributes, $optParams = []): self
    {
        foreach ($attributes as $name => $value) {
            $this->$name = $value;
        }

        return $this->save('updateGroup', $optParams);
    }

    public function delete(string $groupId = null)
    {
        $this->getGoogleClient()->deleteGroup($groupId ?? $this->id);
    }

    public static function getGoogleClient(): GoogleGroup
    {
        return GoogleGroupFactory::make();
    }

    protected function getFieldName(string $name): string
    {
        return [
            'name'        => 'name',
            'email'       => 'email',
            'description' => 'description'
        ][$name] ?? $name;
    }
}
