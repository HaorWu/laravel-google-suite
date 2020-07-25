# API Documentation

This is a basic outline of the package, its architecture, etc.

## Account class

Located in `oeleco\GoogleSuite\Directory\Account`

### Static methods

```php
Account::get(); // Get collection of all account for hosted domain

Account::find(string email);

Account::create(array $properties);

Account::create(array $properties);

```

### Object

```php
$account = Account::find('john.doe@domainwithgsuite.com');

$account->update(array $properties);

$account->suspend();

$account->activate();

$account->delete();

```
