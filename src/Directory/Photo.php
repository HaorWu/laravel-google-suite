<?php

namespace oeleco\GoogleSuite\Directory;

use Illuminate\Support\Arr;
use Google_Service_Directory_UserPhoto;

use oeleco\GoogleSuite\Directory\Photo\GooglePhoto;
use oeleco\GoogleSuite\Directory\Photo\GooglePhotoFactory;

class Photo
{
    /** @var \Google_Service_Directory_UserPhoto */
    public $googlePhoto;


    /** @var string */
    protected $userEmail;

    public function __construct()
    {
        $this->googlePhoto = new Google_Service_Directory_UserPhoto;
    }

    /**
     * @param \Google_Service_Directory_UserPhoto $googlePhoto
     *
     * @return static
     */
    public static function createFromGooglePhoto(Google_Service_Directory_UserPhoto $googlePhoto, string $userEmail)
    {
        $photo = new static;

        $photo->googlePhoto = $googlePhoto;
        $photo->userEmail   = $userEmail;

        return $photo;
    }

    public static function find(string $userEmail): self
    {
        $googleClient = static::getGoogleClient($userEmail);

        $googlePhoto = $googleClient->getPhoto();

        return static::createFromGooglePhoto($googlePhoto, $googleClient->getUserKey());
    }

    public static function findOrNew(string $userEmail): self
    {
        $googleClient = static::getGoogleClient($userEmail);
        try {
            $googlePhoto = $googleClient->getPhoto();
        } catch (\Google_Service_Exception $ge) {
            if($ge->getCode() == 404) {
                $googlePhoto = (new static)->googlePhoto;
            }
        }

        return static::createFromGooglePhoto($googlePhoto, $googleClient->getUserKey());
    }

    public function __get($name)
    {
        $name = $this->getFieldName($name);

        $value = Arr::get($this->googlePhoto, $name);

        return $value;
    }

    public function __set($name, $value)
    {
        $name = $this->getFieldName($name);

        Arr::set($this->googlePhoto, $name, $value);
    }

    public function save($optParams = []): self
    {
        $googleClient = $this->getGoogleClient($this->userEmail);

        $googlePhoto = $googleClient->updatePhoto($this, $optParams);

        return static::createFromGooglePhoto($googlePhoto, $googleClient->getUserKey());
    }

    public function update(array $attributes, $optParams = []): self
    {
        foreach ($attributes as $name => $value) {
            $this->$name = $value;
        }

        return $this->save($optParams);
    }

    public function delete(string $userEmail = null)
    {
        $this->getGoogleClient($userEmail ?? $this->userEmail)->deletePhoto();
    }

    public function getUserEmail(): string
    {
        return $this->userEmail;
    }

    public static function getGoogleClient(string $userEmail): GooglePhoto
    {
        return GooglePhotoFactory::make($userEmail);
    }

    protected function getFieldName(string $name): string
    {
        return [
            'data'      => 'photoData',
            'height'    => 'height',
            'width'     => 'width',
            'type'      => 'mimeType',
        ][$name] ?? $name;
    }
}
