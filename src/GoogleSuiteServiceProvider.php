<?php

namespace oeleco\GoogleSuite;

use Illuminate\Support\ServiceProvider;
use oeleco\GoogleSuite\Exceptions\InvalidConfiguration;

class GoogleSuiteServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/google-suite.php' => config_path('google-suite.php'),
        ], 'config');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/google-suite.php', 'google-suite');

        $this->registerGoogleAccount();
        $this->registerGoogleOrgunit();
        $this->registerGoogleGroup();
        $this->registerGooglePhoto();
        $this->registerGoogleCalendar();
        $this->registerGoogleGroupsettings();
    }

    protected function guardAgainstInvalidConfiguration(array $config = null)
    {
        if (empty($config['hosted_domain'])) {
            throw InvalidConfiguration::hostedDomainNotSpecified();
        }

        if (empty($config['service_account'])) {
            throw InvalidConfiguration::serviceAccountNotSpecified();
        }

        $credentials = $config['service_account_credentials_json'];

        if (! is_array($credentials) && ! is_string($credentials)) {
            throw InvalidConfiguration::credentialsTypeWrong($credentials);
        }

        if (is_string($credentials) && ! file_exists($credentials)) {
            throw InvalidConfiguration::credentialsJsonDoesNotExist($credentials);
        }
    }

    protected function registerGoogleAccount()
    {
        $this->app->bind(\oeleco\GoogleSuite\Directory\Account\GoogleAccount::class, function () {
            $config = config('google-suite');

            $this->guardAgainstInvalidConfiguration($config);

            return \oeleco\GoogleSuite\Directory\Account\GoogleAccountFactory::make();
        });

        $this->app->alias(\oeleco\GoogleSuite\Directory\Account\GoogleAccount::class, 'laravel-google-account');
    }

    protected function registerGoogleOrgunit()
    {
        $this->app->bind(\oeleco\GoogleSuite\Directory\OrgUnit\GoogleOrgunit::class, function () {
            $config = config('google-suite');

            $this->guardAgainstInvalidConfiguration($config);

            return \oeleco\GoogleSuite\Directory\OrgUnit\GoogleOrgunitFactory::make("my_customer");
        });

        $this->app->alias(\oeleco\GoogleSuite\Directory\OrgUnit\GoogleOrgunit::class, 'laravel-google-orgunit');
    }

    protected function registerGoogleGroup()
    {
        $this->app->bind(\oeleco\GoogleSuite\Directory\Group\GoogleGroup::class, function () {
            $config = config('google-suite');

            $this->guardAgainstInvalidConfiguration($config);

            return \oeleco\GoogleSuite\Directory\Group\GoogleGroupFactory::make();
        });

        $this->app->alias(\oeleco\GoogleSuite\Directory\Group\GoogleGroup::class, 'laravel-google-group');
    }

    protected function registerGooglePhoto()
    {
        $this->app->bind(\oeleco\GoogleSuite\Directory\Photo\GooglePhoto::class, function () {
            $config = config('google-suite');

            $this->guardAgainstInvalidConfiguration($config);

            return \oeleco\GoogleSuite\Directory\Photo\GooglePhotoFactory::make();
        });

        $this->app->alias(\oeleco\GoogleSuite\Directory\Photo\GooglePhoto::class, 'laravel-google-photo');
    }

    protected function registerGoogleCalendar()
    {
        $this->app->bind(\oeleco\GoogleSuite\Calendar\Event\GoogleCalendar::class, function () {
            $config = config('google-suite');

            $this->guardAgainstInvalidConfiguration($config);

            return \oeleco\GoogleSuite\Calendar\Event\GoogleCalendarFactory::make();
        });

        $this->app->alias(\oeleco\GoogleSuite\Calendar\Event\GoogleCalendar::class, 'laravel-google-calendar');
    }

    protected function registerGoogleGroupsettings()
    {
        $this->app->bind(\oeleco\GoogleSuite\Groupsettings\Setting\GoogleGroupsettings::class, function () {
            $config = config('google-suite');

            $this->guardAgainstInvalidConfiguration($config);

            return \oeleco\GoogleSuite\Groupsettings\Setting\GoogleGroupsettingsFactory::make();
        });

        $this->app->alias(\oeleco\GoogleSuite\Groupsettings\Setting\GoogleGroupsettings::class, 'laravel-google-group-settings');
    }
}
