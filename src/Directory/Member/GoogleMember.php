<?php

namespace oeleco\GoogleSuite\Directory\Member;

use Google_Service_Directory;
use Google_Service_Directory_Member;
use Google_Service_Directory_Members;

use oeleco\GoogleSuite\Contracts\GoogleDirectory;

class GoogleMember implements GoogleDirectory
{
    /** @var \Google_Service_Directory */
    protected $serviceDirectory;

    /** @var string */
    protected $groupId;

    public function __construct(Google_Service_Directory $serviceDirectory, string $groupId)
    {
        $this->serviceDirectory = $serviceDirectory;

        $this->groupId = $groupId;
    }

    public function getGroupId(): string
    {
        return $this->groupId;
    }

    /*
     * @link https://developers.google.com/admin-sdk/directory/v1/reference/members/list
     */
    public function listMembers(array $queryParameters = []): Google_Service_Directory_Members
    {
        return $this
            ->serviceDirectory
            ->members
            ->listMembers($this->groupId, $queryParameters);
    }

    public function getMember(string $memberKey): Google_Service_Directory_Member
    {
        return $this->serviceDirectory->members->get($this->groupId, $memberKey);
    }

    /*
     * @link https://developers.google.com/admin-sdk/directory/v1/reference/members/insert
     */
    public function insertMember($member, $optParams = []): Google_Service_Directory_Member
    {
        if ($member instanceof Member) {
            $member = $member->googleMember;
        }

        return $this->serviceDirectory->members->insert($this->groupId, $member, $optParams);
    }


    public function updateMember($member): Google_Service_Directory_Member
    {
        if ($member instanceof Member) {
            $member = $member->googleMember;
        }

        return $this->serviceDirectory->members->update($this->groupId, $member->id, $member);
    }

    public function deleteMember($member)
    {
        if ($member instanceof Member) {
            $member = $member->id;
        }

        $this->serviceDirectory->members->delete($this->groupId, $member);
    }

    public function getService(): Google_Service_Directory
    {
        return $this->serviceDirectory;
    }
}
