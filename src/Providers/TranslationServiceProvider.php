<?php

declare(strict_types=1);

namespace Arcanedev\LaravelLang\Providers;

use Arcanedev\LaravelLang\FileLoader;
use Illuminate\Foundation\Application;
use Illuminate\Translation\TranslationServiceProvider as ServiceProvider;

/**
 * Class     TranslationServiceProvider
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class TranslationServiceProvider extends ServiceProvider
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Register the translation line loader.
     */
    protected function registerLoader(): void
    {
        $this->app->singleton('translation.loader', function(Application $app) {
            return new FileLoader(
                $app['files'],
                $app->langPath(),
                $app['config']->get('laravel-lang.vendor', []),
                $app['config']->get('laravel-lang.locales', [])
            );
        });
    }
}
