# 4. Usage

## Table of contents

1. [Artisan Commands](#artisan-commands)
  * [Translations Checker](#translations-checker)
  * [Translations Publisher](#translations-publisher)
2. API (Work in progress)

## 1. Artisan Commands

### Translations Checker

The translations checker command helps you to check if you have a missing translations.

```bash
$ php artisan trans:check
```

Before running the `trans:check` artisan command, you need to specify the locales in `config/laravel-lang.php`.

**NOTE :** No need to specify the `en` locale because it's a default/fallback locale and it's shipped by `laravel/laravel` package.

```php
// config/laravel-lang.php
return [
    // ...

    /* ------------------------------------------------------------------------------------------------
     |  Supported locales
     | ------------------------------------------------------------------------------------------------
     | If you want to limit your translations, set your supported locales list.
     */
    'locales'   => ['es', 'fr', 'it'],

    // ...
];
```

After editing your locales, run the `trans:check` command to check your missing translations.

If you want to ignore some translations, you can specify it in your config file:

```php
return [
    // ...

    /* ------------------------------------------------------------------------------------------------
     |  Check Settings
     | ------------------------------------------------------------------------------------------------
     */
    'check'     => [
        'ignore'  => [
            'validation.custom',
            'validation.attributes',
            // Add your translations attributes here to ignore
            'custom.translations.attributes',
        ],
    ],
];
```

### Translations Publisher

The translations checker command helps you to publish the translations from the [caouecs/Laravel-lang](https://github.com/caouecs/Laravel-lang) package.

This is useful if you want to update/override the translations offered by the vendor.

To publish for example the `fr` (French) translations, you simply run this command:

```bash
$ php artisan trans:publish fr
```

If you have translations already in `resources/lang` and you want to force the publishing, add the `--force` flag :

```bash
$ php artisan trans:publish fr --force
```
