<?php

namespace oeleco\GoogleSuite\Directory;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

use Google_Service_Directory_Orgunit;

use oeleco\GoogleSuite\Directory\Orgunit\GoogleOrgunit;
use oeleco\GoogleSuite\Directory\Orgunit\GoogleOrgunitFactory;

class Orgunit
{
    /** @var \Google_Service_Directory_Orgunit */
    public $googleOrgunit;


    /** @var string */
    protected $customerId;

    public function __construct()
    {
        $this->googleOrgunit = new Google_Service_Directory_Orgunit;
    }

    /**
     * @param \Google_Service_Directory_Orgunit $googleOrgunit
     *
     * @return static
     */
    public static function createFromGoogleOrgunit(Google_Service_Directory_Orgunit $googleOrgunit, $customerId)
    {
        $orgunit = new static;

        $orgunit->googleOrgunit = $googleOrgunit;
        $orgunit->customerId = $customerId;

        return $orgunit;
    }

    /**
     * @param array $properties
     * @param string|null $customerId
     *
     * @return mixed
     */
    public static function create(array $properties, string $customerId = null, $optParams = [])
    {
        $orgunit = new static;

        $orgunit->customerId = static::getGoogleClient($customerId)->getCustomerId();

        foreach ($properties as $name => $value) {
            $orgunit->$name = $value;
        }

        return $orgunit->save('insertOrgunit', $optParams);
    }


    public static function get(array $queryParameters = [], string $customerId = null): Collection
    {
        $googleClient = static::getGoogleClient($customerId);

        $googleOrgunits = $googleClient->listOrgunits($queryParameters);

        $googleOrgunitsList = $googleOrgunits->getOrganizationUnits();

        return collect($googleOrgunitsList)
            ->map(function (Google_Service_Directory_Orgunit $orgunit) use ($customerId) {
                return static::createFromGoogleOrgunit($orgunit, $customerId);
            })
            ->values();
    }

    public static function find($orgunitId, string $customerId = null): self
    {
        $googleClient = static::getGoogleClient($customerId);

        $googleOrgunit = $googleClient->getOrgunit($orgunitId);

        return static::createFromGoogleOrgunit($googleOrgunit, $customerId);
    }

    public function __get($name)
    {
        $name = $this->getFieldName($name);

        $value = Arr::get($this->googleOrgunit, $name);

        return $value;
    }

    public function __set($name, $value)
    {
        $name = $this->getFieldName($name);

        Arr::set($this->googleOrgunit, $name, $value);
    }

    public function exists(): bool
    {
        return $this->id != '';
    }

    public function save(string $method = null, $optParams = []): self
    {
        $method = $method ?? ($this->exists() ? 'updateOrgunit' : 'insertOrgunit');

        $googleClient = $this->getGoogleClient($this->customerId);

        $googleOrgunit = $googleClient->$method($this, $optParams);

        return static::createFromGoogleOrgunit($googleOrgunit, $googleClient->getCustomerId());
    }

    public function update(array $attributes, $optParams = []): self
    {
        foreach ($attributes as $name => $value) {
            $this->$name = $value;
        }

        return $this->save('updateOrgunit', $optParams);
    }

    public function delete(string $orgunitId = null)
    {
        $this->getGoogleClient()->deleteOrgunit($orgunitId ?? $this->id);
    }

    public function getCustomerId(): string
    {
        return $this->customerId;
    }

    public static function getGoogleClient(string $customerId = null): GoogleOrgunit
    {
        $customerId = $customerId ?? "my_customer";

        return GoogleOrgunitFactory::make($customerId);
    }

    protected function getFieldName(string $name): string
    {
        return [
            'id'          => 'orgUnitId',
            'name'        => 'name',
            'path'        => 'orgUnitPath',
            'description' => 'description',
            'parent'      => 'parentOrgUnitPath'
        ][$name] ?? $name;
    }
}
