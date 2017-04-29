# 2. Configuration

## Table of contents

  1. [Installation and Setup](1-Installation-and-Setup.md)
  2. [Configuration](2-Configuration.md)
  3. [Usage](3-Usage.md)

After publishing the package config file :

```php
// config/laravel-lang.php
<?php

return [

    /* -----------------------------------------------------------------
     |  The vendor path
     | -----------------------------------------------------------------
     */

    /** @link      https://github.com/caouecs/Laravel-lang */
    'vendor'    => base_path('vendor/caouecs/laravel4-lang'),

    /* -----------------------------------------------------------------
     |  Supported locales
     | -----------------------------------------------------------------
     | If you want to limit your translations, set your supported locales list.
     */

    'locales'   => [
        //
    ],

    /* -----------------------------------------------------------------
     |  Check Settings
     | -----------------------------------------------------------------
     */

    'check'     => [
        'ignore'  => [
            'validation.custom',
            'validation.attributes',
        ],
    ],

];
```
