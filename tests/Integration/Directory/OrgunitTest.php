<?php
namespace oeleco\GoogleSuite\Tests\Integration\Directory;

use Mockery as m;

use oeleco\GoogleSuite\Tests\TestCase;
use oeleco\GoogleSuite\Directory\Orgunit\Orgunit;

class OrgunitTest extends TestCase
{
    /** @var \oeleco\GoogleSuite\Directory\OrgUnit\Orgunit */
    protected $orgunit;

    public function setUp(): void
    {
        parent::setUp();

        $this->orgunit = new Orgunit;
    }

    /** @test */
    public function it_can_set_a_name()
    {
        $this->orgunit->name = "testname";

        $this->assertEquals("testname", $this->orgunit->googleOrgunit['name']);
    }

    /** @test */
    public function it_can_set_a_description()
    {
        $this->orgunit->description = "testdescription";

        $this->assertEquals("testdescription", $this->orgunit->googleOrgunit['description']);
    }

    /** @test */
    public function it_can_set_a_parent()
    {
        $this->orgunit->parent = "/test/parent/path";

        $this->assertEquals("/test/parent/path", $this->orgunit->googleOrgunit['parentOrgUnitPath']);
    }

    /** @test */
    public function it_can_create_a_orgunit()
    {
        $data = [
            'name' => 'testname',
            'description' => 'testdescription',
            'parent' => "/test/parent"
        ];

        $orgunit = m::mock(Orgunit::class);
        $orgunit->shouldReceive('create')->once()->with($data);

        $orgunit->create($data);
    }

    /** @test */
    public function it_cat_update_a_orgunit()
    {
        $data = [
            'name' => 'testname',
            'description' => 'testdescription'
        ];

        $orgunit = m::mock(Orgunit::class);
        $orgunit->shouldReceive('update')->once()->with($data);

        $orgunit->update($data);
    }

}
