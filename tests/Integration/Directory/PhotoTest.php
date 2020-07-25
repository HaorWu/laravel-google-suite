<?php

namespace oeleco\GoogleSuite\Tests\Integration\Directory;

use Mockery as m;

use oeleco\GoogleSuite\Tests\TestCase;
use oeleco\GoogleSuite\Directory\Photo;
use oeleco\GoogleSuite\Directory\Account;

class PhotoTest extends TestCase
{
    /** @var \oeleco\GoogleSuite\Directory\Photo */
    protected $photo;

    public function setUp(): void
    {
        parent::setUp();
        $this->photo = new Photo;
    }

    public function getImage()
    {
        $url = "https://placekitten.com/200/300";
        $img = file_get_contents($url);
        list($width, $height, $type) = getimagesizefromstring($img);

        return [
            'data'   => base64_encode($img),
            'height' => $height,
            'width'  => $width,
            'type'   => $type
        ];
    }

    /** @test */
    public function it_can_update_the_photo_for_user_account()
    {
        $account = Account::create([
            'email'     => "test@".$this->hosted_domain,
            'firstName' => 'testname',
            'lastName'  => 'testlastname',
            'password'  => "password"
        ]);

        $photo = Photo::findOrNew("test@". $this->hosted_domain);
        $photo->update($this->image);
        $this->assertEquals($this->image['data'], $photo->googlePhoto['photoData']);

        $photo->delete();
        $account->delete();
    }

}