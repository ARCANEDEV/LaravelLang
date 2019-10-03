<?php namespace Arcanedev\LaravelLang\Providers;

use Arcanedev\LaravelLang\{Contracts, TransChecker, TransManager, TransPublisher};
use Arcanedev\Support\Providers\ServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Foundation\Application;

/**
 * Class     DeferredServiceProvider
 *
 * @package  Arcanedev\LaravelLang\Providers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class DeferredServicesProvider extends ServiceProvider implements DeferrableProvider
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->registerTransManager();
        $this->registerTransChecker();
        $this->registerLangPublisher();
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
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
            $paths = array_map('realpath', [
                'app'    => $app->langPath(),
                'vendor' => $app['config']->get('laravel-lang.vendor', ''),
            ]);

            return new TransManager($app['files'], $paths);
        });
    }

    /**
     * Register the trans checker.
     */
    private function registerTransChecker()
    {
        $this->singleton(Contracts\TransChecker::class, function (Application $app) {
            return new TransChecker(
                $app['translator'],
                $app[Contracts\TransManager::class],
                $app['config']->get('laravel-lang', [])
            );
        });
    }

    /**
     * Register the lang publisher.
     */
    private function registerLangPublisher()
    {
        $this->singleton(Contracts\TransPublisher::class, function (Application $app) {
            return new TransPublisher(
                $app['files'],
                $app[Contracts\TransManager::class],
                $app->langPath()
            );
        });
    }
}
