<?php namespace Arcanedev\LaravelLang\Providers;

use Arcanedev\LaravelLang\FileLoader;
use Illuminate\Translation\TranslationServiceProvider as ServiceProvider;

/**
 * Class     TranslationServiceProvider
 *
 * @package  Arcanedev\LaravelLang\Providers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class TranslationServiceProvider extends ServiceProvider
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Register the translation line loader.
     *
     * @return void
     */
    protected function registerLoader()
    {
        $this->app->singleton('translation.loader', function($app) {
            /**
             * @var  \Illuminate\Config\Repository      $config
             * @var  \Illuminate\Filesystem\Filesystem  $files
             */
            $config   = $app['config'];
            $files    = $app['files'];
            $path     = $app['path.lang'];

            return new FileLoader(
                $files, $path, $config->get('laravel-lang.vendor')
            );
        });
    }
}
