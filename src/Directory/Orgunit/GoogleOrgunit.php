<?php

namespace oeleco\GoogleSuite\Directory\Orgunit;

use Google_Service_Directory;
use Google_Service_Directory_OrgUnit;
use Google_Service_Directory_OrgUnits;

use oeleco\GoogleSuite\Directory\Orgunit;
use oeleco\GoogleSuite\Contracts\GoogleDirectory;

class GoogleOrgunit implements GoogleDirectory
{
    /** @var \Google_Service_Directory */
    protected $serviceDirectory;

    /** @var string */
    protected $customerId;

    public function __construct(Google_Service_Directory $serviceDirectory, string $customerId)
    {
        $this->serviceDirectory = $serviceDirectory;

        $this->customerId = $customerId;
    }

    public function getCustomerId(): string
    {
        return $this->customerId;
    }

    /*
     * @link https://developers.google.com/admin-sdk/directory/v1/reference/orgunits/list
     */
    public function listOrgunits(array $queryParameters = []): Google_Service_Directory_OrgUnits
    {
        $parameters = [
            'orgUnitPath' => '/',
        ];

        $parameters = array_merge($parameters, $queryParameters);

        return $this
            ->serviceDirectory
            ->orgunits
            ->listOrgunits($this->customerId, $parameters);
    }

    public function getOrgunit(string $orgUnitPath): Google_Service_Directory_OrgUnit
    {
        return $this->serviceDirectory->orgunits->get($this->customerId, $orgUnitPath);
    }

    /*
     * @link https://developers.google.com/admin-sdk/directory/v1/reference/orgunits/insert
     */
    public function insertOrgunit($orgunit, $optParams = []): Google_Service_Directory_OrgUnit
    {
        if ($orgunit instanceof Orgunit) {
            $orgunit = $orgunit->googleOrgunit;
        }

        return $this->serviceDirectory->orgunits->insert($this->customerId, $orgunit, $optParams);
    }


    public function updateOrgunit($orgunit): Google_Service_Directory_OrgUnit
    {
        if ($orgunit instanceof Orgunit) {
            $orgunit = $orgunit->googleOrgunit;
        }

        return $this->serviceDirectory->orgunits->update($this->customerId, $orgunit->id, $orgunit);
    }

    public function deleteOrgunit($orgunit)
    {
        if ($orgunit instanceof Orgunit) {
            $orgunit = $orgunit->id;
        }

        $this->serviceDirectory->orgunits->delete($this->customerId, $orgunit);
    }

    public function getService(): Google_Service_Directory
    {
        return $this->serviceDirectory;
    }
}
