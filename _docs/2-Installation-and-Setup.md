# 2. Installation

## Composer

You can install this package via [Composer](http://getcomposer.org/) by running this command: `composer require arcanedev/laravel-lang`.

Or by adding the package to your `composer.json`.

```json
{
    "require": {
        "arcanedev/laravel-lang": "~1.0"
    }
}
```

Then install it via `composer install` or `composer update`.

## Setup

Once the package is installed, you can register the service provider in `config/app.php` in the `providers` array:

```php
// config/app.php

'providers' => [
    ...
    Arcanedev\LaravelLang\LaravelLangServiceProvider::class,
],
```

### Artisan commands

To publish the config file, run this command:

```bash
$ php artisan vendor:publish --provider="Arcanedev\LaravelLang\LaravelLangServiceProvider"
```
