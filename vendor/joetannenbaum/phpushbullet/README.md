# PHPushbullet

[![Latest Version](https://img.shields.io/github/release/joetannenbaum/phpushbullet.svg?style=flat)](https://github.com/joetannenbaum/phpushbullet/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/joetannenbaum/phpushbullet/master.svg?style=flat)](https://travis-ci.org/joetannenbaum/phpushbullet)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/joetannenbaum/phpushbullet.svg?style=flat)](https://scrutinizer-ci.com/g/joetannenbaum/phpushbullet/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/joetannenbaum/phpushbullet.svg?style=flat)](https://scrutinizer-ci.com/g/joetannenbaum/phpushbullet)
[![Total Downloads](https://img.shields.io/packagist/dt/joetannenbaum/phpushbullet.svg?style=flat)](https://packagist.org/packages/joetannenbaum/phpushbullet)

A PHP library for the [Pushbullet](https://www.pushbullet.com/) API.

## Table of Contents

+ [Installation](#installation)
+ [Listing Devices](#listing-devices)
+ [Pushing](#pushing)
  + [To Devices](#to-devices)
  + [To Users](#to-users)
+ [Types](#types)
  + [Notes](#notes)
  + [Links](#links)
  + [Addresses](#addresses)
  + [Lists](#lists)
  + [Files](#files)

## Installation

Using [composer](https://packagist.org/packages/joetannenbaum/phpushbullet):

```
{
    "require": {
        "joetannenbaum/phpushbullet": "~1.0"
    }
}
```

PHPushbullet takes one optional parameter, your [Pushbullet access token](https://www.pushbullet.com/account):

```php
require_once('vendor/autoload.php');

$pushbullet = new PHPushbullet\PHPushbullet('YOUR_ACCESS_TOKEN_HERE');
```

If you do not wish to put your access token in your code (understandable), simply set it to the environment variable `pushbullet.access_token` and PHPushbullet will automatically pick it up.

## Listing Devices

To list the available devices on your account:

```php
$pushbullet->devices();
```

This will return an array of objects with all of the device information.

## Pushing

### To Devices

When pushing a to a device, simply use the device's `nickname` or their `iden` from the list above.

To push to a single device:

```php
$pushbullet->device('Chrome')->note('Remember', 'Buy some eggs.');
```

To push to multiple devices:

```php
$pushbullet->device('Chrome')->device('Galaxy S4')->note('Remember', 'Buy some eggs.');
// or
$pushbullet->device('Chrome', 'Galaxy S4')->note('Remember', 'Buy some eggs.');
// or
$pushbullet->device(['Chrome', 'Galaxy S4'])->note('Remember', 'Buy some eggs.');
```

### To Users

When pushing a to a user, simply use the user's email address:

To push to a single user:

```php
$pushbullet->user('joe@example.com')->note('Remember', 'Buy some eggs.');
```

To push to multiple users:

```php
$pushbullet->user('joe@example.com')->user('anne@example.com')->note('Remember', 'Buy some eggs.');
// or
$pushbullet->user('joe@example.com', 'anne@example.com')->note('Remember', 'Buy some eggs.');
// or
$pushbullet->user(['joe@example.com', 'anne@example.com'])->note('Remember', 'Buy some eggs.');
```
## Types

### Notes

Arguments:

+ Title
+ Body

```php
$pushbullet->device('Chrome')->note('Musings', 'Why are fudgy brownies better than cakey brownies?');
```

### Links

Arguments:

+ Title
+ URL
+ Body (optional)

```php
$pushbullet->device('Chrome')->link('Look It Up', 'http://google.com', 'I hear this is a good site for finding things.');
```

### Addresses

Arguments:
+ Name
+ Address

```php
$pushbullet->device('Chrome')->address('The Hollywood Sign', '4059 Mt Lee Drive Hollywood, CA 90068');
```

Alternatively, you can pass in an associative array:

```php
$address = [
  'address' => '4059 Mt Lee Drive',
  'city'    => 'Hollywood',
  'state'   => 'CA',
  'zip'     => '90068',
];

$pushbullet->device('Chrome')->address('The Hollywood Sign', $address);
```

### Lists

Arguments:
+ Title
+ Items (array)

```php
$items = [
  'Socks',
  'Pants',
  'Keys',
  'Wallet',
];

$pushbullet->device('Chrome')->list('Do Not Forget', $items);
```

### Files

Arguments:
+ File Name
+ File URL (must be publicly available)
+ Body (optional)

```php
$pushbullet->device('Chrome')->file('The Big Presentation', 'http://example.com/do-not-lose-this.pptx', 'Final version of slides.');
```
