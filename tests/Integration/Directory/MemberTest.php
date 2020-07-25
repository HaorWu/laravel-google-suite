<?php

namespace oeleco\GoogleSuite\Tests\Integration\Directory;

use Mockery as m;

use oeleco\GoogleSuite\Tests\TestCase;
use oeleco\GoogleSuite\Directory\Group;
use oeleco\GoogleSuite\Directory\Member;
use oeleco\GoogleSuite\Directory\Account;

class MemberTest extends TestCase
{

    /** @var \oeleco\GoogleSuite\Directory\Member */
    protected $group;

    public function setUp(): void
    {
        parent::setUp();

        $this->member = new Member;
    }

    /** @test */
    public function it_can_add_a_member_to_google_group()
    {
        $group = Group::create([
            'name'  => "grouptest",
            'email' => "grouptest@". $this->hosted_domain
        ]);

        $account = Account::create([
            'firstName' => $this->faker->firstName(),
            'lastName'  => $this->faker->lastname,
            'password'  => "password",
            'email'     => "test@". $this->hosted_domain
        ]);

        $member = Member::create($group->id, [
                'email' => $account->email,
                'role' => 'MEMBER'
        ]);

        $this->assertTrue($member->id != null);

        $member->delete();
        $account->delete();
        $group->delete();
    }

}
