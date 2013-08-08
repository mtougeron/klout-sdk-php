# Klout

A PHP library for the [Klout API](http://klout.com/s/developers/v2)

[![Build Status](https://travis-ci.org/mtougeron/klout-sdk-php.png)](https://travis-ci.org/mtougeron/klout-sdk-php)

=========

## Installation

Use [Composer](http://getcomposer.org/) to download and install this package as well as its dependencies.

To add this package as dependency for your project, add `mtougeron/klout-sdk-php` to your project's `composer.json` file.

    {
        "require": {
            "mtougeron/klout-sdk-php": "1.0.*"
        }
    }

### Usage

```php
use Klout\Klout;
$klout = new Klout('<your Klout API license key');

$user = $klout->getUserByTwitterUsername('mtougeron');

echo $user->getNickname() . '\'s Klout score is ' . round($user->getScore()->getScore());
// outputs: mtougeron's Klout score is 49

```

### Other

Use of the Klout API is bound by Klout's developer [Terms of Service](http://klout.com/s/developers/tos)
