<?php

namespace oeleco\GoogleSuite\Directory\Member;

use Illuminate\Support\Arr;

use Google_Service_Directory_Member;

use Illuminate\Support\Collection;
use oeleco\GoogleSuite\Directory\Member\GoogleMemberFactory;

class Member
{
    /** @var \Google_Service_Directory_Member */
    public $googleMember;


    /** @var string */
    protected $groupId;

    public function __construct()
    {
        $this->googleMember = new Google_Service_Directory_Member;
    }

   /**
    *
    * @param Google_Service_Directory_Member $googleMember
    * @param string $groupId
    * @return void
    */
    public static function createFromGoogleMember(Google_Service_Directory_Member $googleMember, string $groupId) :self
    {
        $member = new static;

        $member->googleMember = $googleMember;
        $member->groupId = $groupId;

        return $member;
    }

    /**
     * @param array $properties
     * @param string $groupId
     *
     * @return mixed
     */
    public static function create(string $groupId, array $properties, $optParams = [])
    {
        $member = new static;

        $member->groupId = static::getGoogleClient($groupId)->getGroupId();

        foreach ($properties as $name => $value) {
            $member->$name = $value;
        }

        return $member->save('insertMember', $optParams);
    }


    public static function get(string $groupId , array $queryParameters = []): Collection
    {
        $googleClient = static::getGoogleClient($groupId);

        $googleMembers = $googleClient->listMembers($queryParameters);

        $googleMembersList = $googleMembers->getMembers();

        while ($googleMembers->getNextPageToken()) {
            $queryParameters['pageToken'] = $googleMembers->getNextPageToken();

            $googleMembers = $googleClient->listMembers($queryParameters);

            $googleMembersList = array_merge($googleMembersList, $googleMembers->getMembers());
        }

        return collect($googleMembersList)
            ->map(function (Google_Service_Directory_Member $member) use ($groupId) {
                return static::createFromGoogleMember($member, $groupId);
            })
            ->values();
    }

    public static function find(string $groupId, $memberId): self
    {
        $googleClient = static::getGoogleClient($groupId);

        $googleMember = $googleClient->getMember($memberId);

        return static::createFromGoogleMember($googleMember, $groupId);
    }

    public function __get($name)
    {
        $name = $this->getFieldName($name);

        $value = Arr::get($this->googleMember, $name);

        return $value;
    }

    public function __set($name, $value)
    {
        $name = $this->getFieldName($name);

        Arr::set($this->googleMember, $name, $value);
    }

    public function exists(): bool
    {
        return $this->id != '';
    }

    public function save(string $method = null, $optParams = []): self
    {
        $method = $method ?? ($this->exists() ? 'updateMember' : 'insertMember');

        $googleClient = $this->getGoogleClient($this->groupId);

        $googleMember = $googleClient->$method($this, $optParams);

        return static::createFromGoogleMember($googleMember, $googleClient->getGroupId());
    }

    public function update(array $attributes, $optParams = []): self
    {
        foreach ($attributes as $name => $value) {
            $this->$name = $value;
        }

        return $this->save('updateMember', $optParams);
    }

    public function delete(string $memberId = null)
    {
        $this->getGoogleClient($this->groupId)->deleteMember($memberId ?? $this->id);
    }

    public function getCustomerId(): string
    {
        return $this->groupId;
    }

    public static function getGoogleClient(string $groupId): GoogleMember
    {
        return GoogleMemberFactory::make($groupId);
    }

    protected function getFieldName(string $name): string
    {
        return [
            'email'  => 'email',
            'type'   => 'type',
            'status' => 'status',
            'role'   => 'role'
        ][$name] ?? $name;
    }
}
