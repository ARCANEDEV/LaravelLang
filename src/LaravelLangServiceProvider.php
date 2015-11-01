<?php namespace Arcanedev\LaravelLang;

use Arcanedev\Support\PackageServiceProvider as ServiceProvider;

/**
 * Class     LaravelLangServiceProvider
 *
 * @package  Arcanedev\LaravelLang
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class LaravelLangServiceProvider extends ServiceProvider
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Vendor name.
     *
     * @var string
     */
    protected $vendor  = 'arcanedev';

    /**
     * Package name.
     *
     * @var string
     */
    protected $package = 'laravel-lang';

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get the base path of the package.
     *
     * @return string
     */
    public function getBasePath()
    {
        return dirname(__DIR__);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConfig();

        $this->app->register(Providers\TranslationServiceProvider::class);

        $this->registerTransManager();
        $this->registerTransChecker();
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'arcanedev.laravel-lang.manager',
            'arcanedev.laravel-lang.checker',
        ];
    }

    /**
     * Register the trans manager.
     */
    private function registerTransManager()
    {
        $this->singleton('arcanedev.laravel-lang.manager', function ($app) {
            /**
             * @var  \Illuminate\Foundation\Application  $app
             * @var  \Illuminate\Filesystem\Filesystem   $files
             * @var  \Illuminate\Config\Repository       $config
             */
            $files  = $app['files'];
            $config = $app['config'];
            $paths  = [
                'app'    => $app->langPath(),
                'vendor' => $config->get('laravel-lang.vendor', ''),
            ];

            return new TransManager($files, $paths);
        });
    }

    /**
     * Register the trans checker.
     */
    private function registerTransChecker()
    {
        $this->singleton('arcanedev.laravel-lang.checker', function ($app) {
            /**
             * @var  \Illuminate\Translation\Translator             $translator
             * @var  \Arcanedev\LaravelLang\Contracts\TransManager  $manager
             * @var  \Illuminate\Config\Repository                  $config
             */
            $translator = $app['translator'];
            $manager    = $app['arcanedev.laravel-lang.manager'];
            $config     = $app['config'];

            return new TransChecker($translator, $manager, $config->get('laravel-lang'));
        });
    }
}
