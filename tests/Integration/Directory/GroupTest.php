<?php
namespace oeleco\GoogleSuite\Tests\Integration\Directory;

use DateTime;
use Mockery as m;

use oeleco\GoogleSuite\Tests\TestCase;
use oeleco\GoogleSuite\Directory\Group\Group;

class GroupTest extends TestCase
{
    /** @var \oeleco\GoogleSuite\Directory\Group\Group */
    protected $group;

    public function setUp(): void
    {
        parent::setUp();

        $this->group = new Group;
    }

    /** @test */
    public function it_can_set_a_name()
    {
        $this->group->name   = 'testname';

        $this->assertEquals('testname', $this->group->googleGroup['name']);
    }

    /** @test */
    public function it_can_set_a_description()
    {
        $this->group->description   = 'testdescription';

        $this->assertEquals('testdescription', $this->group->googleGroup['description']);
    }

    /** @test */
    public function it_can_set_a_email()
    {
        $this->group->email = "grouptest@email.com";

        $this->assertEquals('grouptest@email.com', $this->group->googleGroup['email']);
    }


    public function it_can_save_a_new_group()
    {
        $data = [
            'name'        => $this->faker->company,
            'description' => $this->faker->catchPhrase,
            'email'       => "grouptest@" . $this->hosted_domain
        ];

        $group = m::mock(Group::class);
        $group->shouldReceive('create')->once()->with($data);

        $group::create($data);
    }


    /** @test */
    public function it_can_update_a_group()
    {
        $data = [
            'name'        => $this->faker->company,
            'description' => $this->faker->catchPhrase,
        ];

        $group = m::mock(Group::class);
        $group->shouldReceive('update')->once()->with($data);

        $group->update($data);
    }

    /** @test */
    public function it_can_delete_a_group()
    {
        $group = m::mock(Group::class);
        $group->shouldReceive('delete')->once()->andReturns(new Group);

        $group->delete("grouptest@". $this->hosted_domain);
    }

}
