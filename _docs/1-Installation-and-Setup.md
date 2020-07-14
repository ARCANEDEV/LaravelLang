# 1. Installation

## Table of contents

  1. [Installation and Setup](1-Installation-and-Setup.md)
  2. [Configuration](2-Configuration.md)
  3. [Usage](3-Usage.md)

## Version Compatibility

| LaravelLang                           | Laravel                                                                                                             |
|:---------------------------------------|:-------------------------------------------------------------------------------------------------------------------|
| ![LaravelLang v8.x][laravel_lang_8_x] | ![Laravel v7.x][laravel_7_x]                                                                                        |
| ![LaravelLang v7.x][laravel_lang_7_x] | ![Laravel v6.x][laravel_6_x]                                                                                        |
| ![LaravelLang v6.x][laravel_lang_6_x] | ![Laravel v5.8][laravel_5_8]                                                                                        |
| ![LaravelLang v5.x][laravel_lang_5_x] | ![Laravel v5.7][laravel_5_7]                                                                                        |
| ![LaravelLang v4.x][laravel_lang_4_x] | ![Laravel v5.6][laravel_5_6]                                                                                        |
| ![LaravelLang v3.x][laravel_lang_3_x] | ![Laravel v5.5][laravel_5_5]                                                                                        |
| ![LaravelLang v2.x][laravel_lang_2_x] | ![Laravel v5.4][laravel_5_4]                                                                                        |
| ![LaravelLang v1.x][laravel_lang_1_x] | ![Laravel v5.0][laravel_5_0] ![Laravel v5.1][laravel_5_1] ![Laravel v5.2][laravel_5_2] ![Laravel v5.3][laravel_5_3] |

[laravel_7_x]:  https://img.shields.io/badge/v7.x-supported-brightgreen.svg?style=flat-square "Laravel v7.x"
[laravel_6_x]:  https://img.shields.io/badge/v6.x-supported-brightgreen.svg?style=flat-square "Laravel v6.x"
[laravel_5_8]:  https://img.shields.io/badge/v5.8-supported-brightgreen.svg?style=flat-square "Laravel v5.8"
[laravel_5_7]:  https://img.shields.io/badge/v5.7-supported-brightgreen.svg?style=flat-square "Laravel v5.7"
[laravel_5_6]:  https://img.shields.io/badge/v5.6-supported-brightgreen.svg?style=flat-square "Laravel v5.6"
[laravel_5_5]:  https://img.shields.io/badge/v5.5-supported-brightgreen.svg?style=flat-square "Laravel v5.5"
[laravel_5_4]:  https://img.shields.io/badge/v5.4-supported-brightgreen.svg?style=flat-square "Laravel v5.4"
[laravel_5_3]:  https://img.shields.io/badge/v5.3-supported-brightgreen.svg?style=flat-square "Laravel v5.3"
[laravel_5_2]:  https://img.shields.io/badge/v5.2-supported-brightgreen.svg?style=flat-square "Laravel v5.2"
[laravel_5_1]:  https://img.shields.io/badge/v5.1-supported-brightgreen.svg?style=flat-square "Laravel v5.1"
[laravel_5_0]:  https://img.shields.io/badge/v5.0-supported-brightgreen.svg?style=flat-square "Laravel v5.0"

[laravel_lang_8_x]: https://img.shields.io/badge/version-8.x-blue.svg?style=flat-square "LaravelLang v8.x"
[laravel_lang_7_x]: https://img.shields.io/badge/version-7.x-blue.svg?style=flat-square "LaravelLang v7.x"
[laravel_lang_6_x]: https://img.shields.io/badge/version-6.x-blue.svg?style=flat-square "LaravelLang v6.x"
[laravel_lang_5_x]: https://img.shields.io/badge/version-5.x-blue.svg?style=flat-square "LaravelLang v5.x"
[laravel_lang_4_x]: https://img.shields.io/badge/version-4.x-blue.svg?style=flat-square "LaravelLang v4.x"
[laravel_lang_3_x]: https://img.shields.io/badge/version-3.x-blue.svg?style=flat-square "LaravelLang v3.x"
[laravel_lang_2_x]: https://img.shields.io/badge/version-2.x-blue.svg?style=flat-square "LaravelLang v2.x"
[laravel_lang_1_x]: https://img.shields.io/badge/version-1.x-blue.svg?style=flat-square "LaravelLang v1.x"

## Composer

You can install this package via [Composer](http://getcomposer.org/) by running this command: `composer require --dev arcanedev/laravel-lang`.

## Setup

> **NOTE :** The package will automatically register itself if you're using Laravel `>= v5.5`, so you can skip this section.

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
