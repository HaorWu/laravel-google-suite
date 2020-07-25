# Manage services of Google Suite

This package makes working with a Google Suite a breeze. Once it has been set up you can do these things:

```php
use oeleco\GoogleSuite\Directory\Account;

//create a new account
$account = new Account;

$account->firstName = 'john';
$account->lastName  = 'Doe';
$account->email     = 'john.doe@domainwithgsuite.com';

$account->save();

// get all account for hosted domain
$accounts = Account::get();

// update existing account
$firstAccount = $accounts->first();
$firstAccount->firstName = 'updated name';
$firstAccount->save();

// set password account
$firstAccount->update(['password' => 'password']);

// create a new account
Account::create([
   'firstName' => 'Pepito',
   'lastName'  => 'Perez',
   'email'     => 'pepito.perez@domainwithgsuite.com',
   'password'  => 'password'
]);

// delete an account
$account->delete();
```
Please see [API](API.md) for more information about what you can do with this package.

## Disclaimer

**_This package is under active development, use at your own risk. A version will be tagged when ready._**

## Installation

You can install the package via composer:

```bash
composer require oeleco/laravel-google-suite
```

You must publish the configuration with this command:

```bash
php artisan vendor:publish --provider="oeleco\GoogleSuite\GoogleSuiteServiceProvider"
```

This will publish a file called `google-suite.php` in your config-directory with these contents:
```
return [
   /*
     *  The hosted domain on gsuite configuratarion.
     */
    'hosted_domain' => env('GOOGLE_HOSTED_DOMAIN'),

    /*
     *  User account to impersonate.
     */
    'service_account' => env('GOOGLE_SERVICE_ACCOUNT'),

    /*
     * Path to the json file containing the credentials.
     */
    'credentials' => env('GOOGLE_SERVICE_ACCOUNT_CREDENTIALS'),
];

```

## Testing

``` bash
composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security-related issues, please tweet me at [@oele_co](https://twitter.com/oele_co) instead of using the issue tracker.

## Credits

- [Osmell Caicedo](https://github.com/oeleco)
- [All Contributors](../../contributors)

This project is based on [Laravel Google Calendar](https://github.com/spatie/laravel-google-calendar) package made by great team of [Spatie](https://spatie.be)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
