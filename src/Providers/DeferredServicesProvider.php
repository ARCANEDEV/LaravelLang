<?php

declare(strict_types=1);

namespace Arcanedev\LaravelLang\Providers;

use Arcanedev\LaravelLang\{TransChecker, TransManager, TransPublisher};
use Arcanedev\LaravelLang\Contracts\{
    TransChecker as TransCheckerContract,
    TransManager as TransManagerContract,
    TransPublisher as TransPublisherContract
};
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
            TransManagerContract::class,
            TransCheckerContract::class,
            TransPublisherContract::class,
        ];
    }

    /* -----------------------------------------------------------------
     |  Services
     | -----------------------------------------------------------------
     */

    /**
     * Register the trans manager.
     */
    private function registerTransManager(): void
    {
        $this->singleton(TransManagerContract::class, function (Application $app) {
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
    private function registerTransChecker(): void
    {
        $this->singleton(TransCheckerContract::class, function (Application $app) {
            return new TransChecker(
                $app['translator'],
                $app[TransManagerContract::class],
                $app['config']->get('laravel-lang', [])
            );
        });
    }

    /**
     * Register the lang publisher.
     */
    private function registerLangPublisher(): void
    {
        $this->singleton(TransPublisherContract::class, function (Application $app) {
            return new TransPublisher(
                $app['files'],
                $app[TransManagerContract::class],
                $app->langPath()
            );
        });
    }
}
