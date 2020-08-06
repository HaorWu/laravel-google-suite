<?php

namespace oeleco\GoogleSuite\Tests;

use Illuminate\Foundation\Testing\WithFaker;
use oeleco\GoogleSuite\GoogleSuiteServiceProvider;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    use MockeryPHPUnitIntegration;
    use WithFaker;

    /** @var string */
    protected $hosted_domain;

    protected function getPackageProviders($app): array
    {
        return [
            GoogleSuiteServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('google-suite.hosted_domain', $this->hosted_domain = 'hosteddomain.com');
        $app['config']->set('google-suite.service_account', 'domaindelegationemail@hosteddomain.com');
        $app['config']->set('google-suite.credentials', './credentials.json');
    }
}
