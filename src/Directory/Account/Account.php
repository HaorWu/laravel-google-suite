<?php

namespace oeleco\GoogleSuite\Directory\Account;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

use Google_Service_Directory_User;
use Illuminate\Support\Collection;
use Google_Service_Directory_UserName;
use oeleco\GoogleSuite\Directory\Account\GoogleAccountFactory;

class Account
{
    /** @var \Google_Service_Directory_User */
    public $googleAccount;

    public function __construct()
    {
        $this->googleAccount = new Google_Service_Directory_User;
    }

    /**
     * @param \Google_Service_Directory_User $googleAccount
     *
     * @return static
     */
    public static function createFromGoogleAccount(Google_Service_Directory_User $googleAccount)
    {
        $account = new static;

        $account->googleAccount = $googleAccount;

        return $account;
    }

    /**
     * @param array $properties
     *
     * @return mixed
     */
    public static function create(array $properties, $optParams = [])
    {
        $account = new static;

        foreach ($properties as $name => $value) {
            $account->$name = $value;
        }

        return $account->save('insertAccount', $optParams);
    }


    public static function get(array $queryParameters = []): Collection
    {
        $googleClient = static::getGoogleClient();

        $googleAccounts = $googleClient->listUsers($queryParameters);

        $googleAccountsList = $googleAccounts->getUsers();

        while ($googleAccounts->getNextPageToken()) {
            $queryParameters['pageToken'] = $googleAccounts->getNextPageToken();

            $googleAccounts = $googleClient->listUsers($queryParameters);

            $googleAccountsList = array_merge($googleAccountsList, $googleAccounts->getUsers());
        }

        $useUserOrder = isset($queryParameters['orderBy']);

        return collect($googleAccountsList)
            ->map(function (Google_Service_Directory_User $account) {
                return static::createFromGoogleAccount($account);
            })
            ->sortBy(function (self $account, $index) use ($useUserOrder) {
                if ($useUserOrder) {
                    return $index;
                }

                return $account->sortDate;
            })
            ->values();
    }

    public static function find($accountId): self
    {
        $googleClient = static::getGoogleClient();

        $googleAccount = $googleClient->getAccount($accountId);

        return static::createFromGoogleAccount($googleAccount);
    }

    public function __get($name)
    {
        $name = $this->getFieldName($name);

        $value = Arr::get($this->googleAccount, $name);

        return $value;
    }

    public function __set($name, $value)
    {
        $name = $this->getFieldName($name);

        if (in_array($name, ['name.givenName', 'name.familyName'])) {
            $this->setNameProperty($name, $value);

            return;
        }

        if (in_array($name, ['password'])) {
            $this->setPasswordProperty($name, $value);

            return;
        }

        Arr::set($this->googleAccount, $name, $value);
    }

    public function exists(): bool
    {
        return $this->id != '';
    }

    public function save(string $method = null, $optParams = []): self
    {
        $method = $method ?? ($this->exists() ? 'updateAccount' : 'insertAccount');

        $googleClient = $this->getGoogleClient();

        $googleAccount = $googleClient->$method($this, $optParams);

        return static::createFromGoogleAccount($googleAccount);
    }


    public function update(array $attributes, $optParams = []): self
    {
        foreach ($attributes as $name => $value) {
            $this->$name = $value;
        }

        return $this->save('updateAccount', $optParams);
    }

    public function delete(string $accountId = null)
    {
        $this->getGoogleClient()->deleteAccount($accountId ?? $this->id);
    }

    public function suspend()
    {
        return $this->update(['suspended' => true]);
    }

    public function activate()
    {
        return $this->update(['suspended' => false]);
    }


    public static function getGoogleClient(): GoogleAccount
    {
        return GoogleAccountFactory::make();
    }

    protected function setNameProperty(string $name, string $value)
    {
        $userName = $this->googleAccount->getName() ?? new Google_Service_Directory_UserName;

        if (in_array($name, ['name.givenName'])) {
            $userName->setGivenName($value);
        }

        if (in_array($name, ['name.familyName'])) {
            $userName->setFamilyName($value);
        }

        if (Str::startsWith($name, 'name')) {
            $this->googleAccount->setName($userName);
        }
    }

    protected function isValidEmail(string $value)
    {
        if (! filter_var($value, FILTER_VALIDATE_EMAIL) && Str::endsWith($value, config('google-suite.hosted_domain'))) {
            throw new \Exception("Invalid Input: primary_user_email", 1);
        }
    }

    public function setPasswordProperty($name, $value)
    {
        if (in_array($name, ['password'])) {
            $this->googleAccount->setHashFunction('MD5');
            $this->googleAccount->setPassword(hash('md5', $value));
        }
    }

    protected function getFieldName(string $name): string
    {
        return [
            'firstName'   => 'name.givenName',
            'lastName'    => 'name.familyName',
            'email'       => 'primaryEmail',
            'orgUnit'     => 'orgUnitPath',
            'password'    => 'password',
            'suspended'   => 'suspended'
        ][$name] ?? $name;
    }
}
