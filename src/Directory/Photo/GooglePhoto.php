<?php

namespace oeleco\GoogleSuite\Directory\Photo;

use Google_Service_Directory;
use Google_Service_Directory_UserPhoto;

use oeleco\GoogleSuite\Contracts\GoogleDirectory;

class GooglePhoto implements GoogleDirectory
{
    /** @var \Google_Service_Directory */
    protected $serviceDirectory;

    /** @var string */
    protected $userKey;

    public function __construct(Google_Service_Directory $serviceDirectory, string $userKey)
    {
        $this->serviceDirectory = $serviceDirectory;

        $this->userKey = $userKey;
    }

    public function getUserKey(): string
    {
        return $this->userKey;
    }

    public function getPhoto(): Google_Service_Directory_UserPhoto
    {
        return $this->serviceDirectory->users_photos->get($this->userKey);
    }

    /*
     * @link https://developers.google.com/admin-sdk/directory/v1/reference/users/photos/update
     */
    public function updatePhoto($photo): Google_Service_Directory_UserPhoto
    {
        if ($photo instanceof Photo) {
            $photo = $photo->googlePhoto;
        }

        return $this->serviceDirectory->users_photos->update($this->userKey, $photo);
    }

    public function deletePhoto()
    {
        $this->serviceDirectory->users_photos->delete($this->userKey);
    }

    public function getService(): Google_Service_Directory
    {
        return $this->serviceDirectory;
    }
}
