# API Documentation

This is a basic outline of the package, its architecture, etc.

## Account class

Located in `oeleco\GoogleSuite\Directory\Account`

### Static methods

```php
Account::get(); // Get collection of all account for hosted domain

Account::find(string $email);

Account::create(array $properties);

```

### Object

```php
$account = Account::find('john.doe@domainwithgsuite.com');

$account->save();

$account->update(array $properties);

$account->suspend();

$account->activate();

$account->delete();

```

## Orgunit class

### Static methods

```php
Orgunit::get();

Orgunit::find(string $email);

Orgunit::create(array $properties);

```

### Object

```php
$orgunit = Orgunit::find('/orgunit/path');

$orgunit->save();

$orgunit->update(array $properties);

$orgunit->delete();

```

## Group class

### Static methods

```php
Group::get();

Group::find(string $email);

Group::create(array $properties);

```

### Object

```php
$group = Group::find('group@domainwithgsuite.com');

$group->save();

$group->update(array $properties);

$group->delete();

```

## Member class

### Static methods

```php
Member::get(string $groupId);

Member::find(string $groupId, string $memberId);

Member::create(string $groupId, array $properties);

```

### Object

```php
$orgunit = Member::find('group@domainwithgsuite.com', 'john.doe@domainwithgsuite.com');

$orgunit->save();

$orgunit->update(array $properties);

$orgunit->delete();

```
## Photo class

### Static methods

```php
Photo::find(string $userEmail);

Photo::findOrNew(string $userEmail);

```

### Object

```php
$photo = Photo::findOrNew('john.doe@domainwithgsuite.com');

$photo->update(array $properties);

$photo->delete();

```