<?php

namespace oeleco\GoogleSuite\Directory\Account;

use Google_Service_Directory;
use Google_Service_Directory_User;
use Google_Service_Directory_Users;

use oeleco\GoogleSuite\Contracts\GoogleDirectory;

class GoogleAccount implements GoogleDirectory
{
    /** @var \Google_Service_Directory */
    protected $serviceDirectory;

    public function __construct(Google_Service_Directory $serviceDirectory)
    {
        $this->serviceDirectory = $serviceDirectory;
    }

    /*
     * @link https://developers.google.com/admin-sdk/directory/v1/reference/users/list
     */
    public function listUsers(array $queryParameters = []): Google_Service_Directory_Users
    {
        $parameters = [
            'orderBy' => 'email',
        ];

        $parameters = array_merge($parameters, $queryParameters);

        return $this
            ->serviceDirectory
            ->users
            ->listUsers($parameters);
    }

    public function getAccount(string $userKey): Google_Service_Directory_User
    {
        return $this->serviceDirectory->users->get($userKey);
    }

    /*
     https://developers.google.com/admin-sdk/directory/v1/reference/users/insert
     */
    public function insertAccount($account, $optParams = []): Google_Service_Directory_User
    {
        if ($account instanceof Account) {
            $account = $account->googleAccount;
        }

        return $this->serviceDirectory->users->insert($account, $optParams);
    }


    public function updateAccount($account): Google_Service_Directory_User
    {
        if ($account instanceof Account) {
            $account = $account->googleAccount;
        }

        return $this->serviceDirectory->users->update($account->id, $account);
    }

    public function deleteAccount($account)
    {
        if ($account instanceof Account) {
            $account = $account->id;
        }

        $this->serviceDirectory->users->delete($account);
    }

    public function getService(): Google_Service_Directory
    {
        return $this->serviceDirectory;
    }
}
