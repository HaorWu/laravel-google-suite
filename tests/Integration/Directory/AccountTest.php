<?php
namespace oeleco\GoogleSuite\Tests\Integration\Directory;

use Mockery as m;

use oeleco\GoogleSuite\Tests\TestCase;
use oeleco\GoogleSuite\Directory\Account;

class AccountTest extends TestCase
{
    /** @var \oeleco\GoogleSuite\Directory\Account */
    protected $account;

    public function setUp(): void
    {
        parent::setUp();

        $this->account = new Account;
    }

    /** @test */
    public function it_can_set_a_firstName()
    {
        $this->account->firstName   = 'testname';

        $this->assertEquals('testname', $this->account->googleAccount['name']['givenName']);
    }

    /** @test */
    public function it_can_set_a_lastName()
    {
        $this->account->lastName   = 'testlastname';

        $this->assertEquals('testlastname', $this->account->googleAccount['name']['familyName']);
    }

    /** @test */
    public function it_can_set_a_email()
    {
        $this->account->email = "test@email.com";

        $this->assertEquals('test@email.com', $this->account->googleAccount['primaryEmail']);
    }


    /** @test */
    public function it_cant_set_a_orgunit_path()
    {
        $this->account->orgUnit = '/';

        $this->assertEquals('/', $this->account->googleAccount['orgUnitPath']);
    }

    /** @test */
    public function it_can_set_a_password()
    {
        $this->account->password = 'password';
        $this->assertEquals(hash('md5', 'password'), $this->account->googleAccount['password']);
    }

    /** @test */
    public function it_can_suspend_a_account()
    {
        $account = m::mock(Account::class);
        $account->shouldReceive('suspend')->once()->andReturns(new Account());

        $account->suspend();
    }

    /** @test */
    public function it_can_activate_account()
    {
        $account = m::mock(Account::class);
        $account->shouldReceive('activate')->once()->andReturns(new Account());

        $account->activate();
    }

    /** @test */
    public function it_can_save_a_new_account()
    {
        $data = [
            'firstName' => $this->faker->firstName(),
            'lastName'  => $this->faker->lastName,
            'email'     => "test@" . $this->hosted_domain
        ];

        $account = m::mock(Account::class);
        $account->shouldReceive('create')->once()->with($data);

        $account::create($data);
    }


    /** @test */
    public function it_can_update_a_account()
    {
        $data = [
            'firstName' => $this->faker->firstName(),
            'lastName' => $this->faker->lastName,
        ];

        $account = m::mock(Account::class);
        $account->shouldReceive('update')->once()->with($data);

        $account->update($data);
    }

    /** @test */
    public function it_can_delete_a_account()
    {
        $account = m::mock(Account::class);
        $account->shouldReceive('delete')->once()->andReturns(new Account);

        $account->delete("test@". $this->hosted_domain);
    }
}
