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
            /**
             * @var  \Illuminate\Config\Repository      $config
             * @var  \Illuminate\Filesystem\Filesystem  $files
             */
            $config  = $app['config'];
            $files   = $app['files'];
            $vendor  = $config->get('laravel-lang.vendor', '');
            $locales = $config->get('laravel-lang.locales', []);

            return new FileLoader(
                $files, $app->langPath(), $vendor, $locales
            );
        });
    }
}
