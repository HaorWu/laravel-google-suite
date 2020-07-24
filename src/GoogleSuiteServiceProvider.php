<?php

namespace oeleco\GoogleSuite;

use Illuminate\Support\ServiceProvider;
use oeleco\GoogleSuite\Directory\Account\GoogleAccountFactory;
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

        $this->registerGoogleAccountClass();
        $this->registerGoogleCalendarClass();
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

    public function registerGoogleAccountClass()
    {
        $this->app->bind(\oeleco\GoogleSuite\Directory\Account\GoogleAccount::class, function () {
            $config = config('google-suite');

            $this->guardAgainstInvalidConfiguration($config);

            return \oeleco\GoogleSuite\Directory\Account\GoogleAccountFactory::make();
        });

        $this->app->alias(\oeleco\GoogleSuite\Directory\Account\GoogleAccount::class, 'laravel-google-account');
    }

    protected function registerGoogleCalendarClass()
    {
        $this->app->bind(\oeleco\GoogleSuite\Calendar\GoogleCalendar::class, function () {
            $config = config('google-suite');

            $this->guardAgainstInvalidConfiguration($config);

            return \oeleco\GoogleSuite\Calendar\GoogleCalendarFactory::make();
        });

        $this->app->alias(\oeleco\GoogleSuite\Calendar\GoogleCalendar::class, 'laravel-google-calendar');
    }
}
