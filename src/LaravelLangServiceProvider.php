<?php namespace Arcanedev\LaravelLang;

use Arcanedev\Support\PackageServiceProvider as ServiceProvider;
use Illuminate\Foundation\Application;

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
     */
    public function register()
    {
        $this->registerConfig();
        $this->registerTransManager();
        $this->registerTransChecker();
        $this->registerLangPublisher();

        $this->app->register(Providers\TranslationServiceProvider::class);

        if ($this->app->runningInConsole()) {
            $this->app->register(Providers\CommandServiceProvider::class);
        }
    }

    /**
     * Boot the service provider.
     */
    public function boot()
    {
        $this->publishConfig();
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
            Contracts\TransManager::class,
            'arcanedev.laravel-lang.checker',
            Contracts\TransChecker::class,
            'arcanedev.laravel-lang.publisher',
            Contracts\TransPublisher::class,
        ];
    }

    /* ------------------------------------------------------------------------------------------------
     |  Services Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Register the trans manager.
     */
    private function registerTransManager()
    {
        $this->singleton('arcanedev.laravel-lang.manager', function (Application $app) {
            /**
             * @var  \Illuminate\Filesystem\Filesystem        $files
             * @var  \Illuminate\Contracts\Config\Repository  $config
             */
            $files  = $app['files'];
            $config = $app['config'];
            $paths  = array_map('realpath', [
                'app'    => $app->langPath(),
                'vendor' => $config->get('laravel-lang.vendor', ''),
            ]);

            return new TransManager($files, $paths);
        });

        $this->bind(Contracts\TransManager::class, 'arcanedev.laravel-lang.manager');
    }

    /**
     * Register the trans checker.
     */
    private function registerTransChecker()
    {
        $this->singleton('arcanedev.laravel-lang.checker', function (Application $app) {
            /**
             * @var  \Illuminate\Translation\Translator             $translator
             * @var  \Arcanedev\LaravelLang\Contracts\TransManager  $manager
             * @var  \Illuminate\Contracts\Config\Repository        $config
             */
            $translator = $app['translator'];
            $manager    = $app['arcanedev.laravel-lang.manager'];
            $config     = $app['config'];

            return new TransChecker($translator, $manager, $config->get('laravel-lang', []));
        });

        $this->bind(Contracts\TransChecker::class, 'arcanedev.laravel-lang.checker');
    }

    /**
     * Register the lang publisher.
     */
    private function registerLangPublisher()
    {
        $this->singleton('arcanedev.laravel-lang.publisher', function (Application $app) {
            /**
             * @var  \Illuminate\Filesystem\Filesystem              $files
             * @var  \Arcanedev\LaravelLang\Contracts\TransManager  $manager
             */
            $files   = $app['files'];
            $manager = $app['arcanedev.laravel-lang.manager'];

            return new TransPublisher($files, $manager, $app->langPath());
        });

        $this->bind(Contracts\TransPublisher::class, 'arcanedev.laravel-lang.publisher');
    }
}
