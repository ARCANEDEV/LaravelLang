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
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * Package name.
     *
     * @var string
     */
    protected $package = 'laravel-lang';

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Register the service provider.
     */
    public function register()
    {
        parent::register();

        $this->registerConfig();
        $this->registerTransManager();
        $this->registerTransChecker();
        $this->registerLangPublisher();

        $this->registerProvider(Providers\TranslationServiceProvider::class);
        $this->registerConsoleServiceProvider(Providers\CommandServiceProvider::class);
    }

    /**
     * Boot the service provider.
     */
    public function boot()
    {
        parent::boot();

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
            Contracts\TransManager::class,
            Contracts\TransChecker::class,
            Contracts\TransPublisher::class,
        ];
    }

    /* -----------------------------------------------------------------
     |  Services
     | -----------------------------------------------------------------
     */

    /**
     * Register the trans manager.
     */
    private function registerTransManager()
    {
        $this->singleton(Contracts\TransManager::class, function (Application $app) {
            /**
             * @var  \Illuminate\Filesystem\Filesystem        $files
             * @var  \Illuminate\Contracts\Config\Repository  $config
             */
            $files  = $app['files'];
            $config = $app['config'];

            return new TransManager($files, array_map('realpath', [
                'app'    => $app->langPath(),
                'vendor' => $config->get('laravel-lang.vendor', ''),
            ]));
        });
    }

    /**
     * Register the trans checker.
     */
    private function registerTransChecker()
    {
        $this->singleton(Contracts\TransChecker::class, function (Application $app) {
            /**
             * @var  \Illuminate\Translation\Translator             $translator
             * @var  \Arcanedev\LaravelLang\Contracts\TransManager  $manager
             * @var  \Illuminate\Contracts\Config\Repository        $config
             */
            $translator = $app['translator'];
            $manager    = $app[Contracts\TransManager::class];
            $config     = $app['config'];

            return new TransChecker($translator, $manager, $config->get('laravel-lang', []));
        });
    }

    /**
     * Register the lang publisher.
     */
    private function registerLangPublisher()
    {
        $this->singleton(Contracts\TransPublisher::class, function (Application $app) {
            /**
             * @var  \Illuminate\Filesystem\Filesystem              $files
             * @var  \Arcanedev\LaravelLang\Contracts\TransManager  $manager
             */
            $files   = $app['files'];
            $manager = $app[Contracts\TransManager::class];

            return new TransPublisher($files, $manager, $app->langPath());
        });
    }
}
