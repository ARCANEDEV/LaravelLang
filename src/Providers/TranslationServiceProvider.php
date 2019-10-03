<?php namespace Arcanedev\LaravelLang\Providers;

use Arcanedev\LaravelLang\FileLoader;
use Illuminate\Foundation\Application;
use Illuminate\Translation\TranslationServiceProvider as ServiceProvider;

/**
 * Class     TranslationServiceProvider
 *
 * @package  Arcanedev\LaravelLang\Providers
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
    protected function registerLoader()
    {
        $this->app->singleton('translation.loader', function(Application $app) {
            $config = $app['config'];

            return new FileLoader(
                $app['files'],
                $app->langPath(),
                $config->get('laravel-lang.vendor', ''),
                $config->get('laravel-lang.locales', [])
            );
        });
    }
}
