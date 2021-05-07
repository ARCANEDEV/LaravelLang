<?php

declare(strict_types=1);

namespace Arcanedev\LaravelLang\Providers;

use Arcanedev\LaravelLang\{TransChecker, TransManager, TransPublisher};
use Arcanedev\LaravelLang\Contracts\TransChecker as TransCheckerContract;
use Arcanedev\LaravelLang\Contracts\TransManager as TransManagerContract;
use Arcanedev\LaravelLang\Contracts\TransPublisher as TransPublisherContract;
use Arcanedev\Support\Providers\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\DeferrableProvider;

/**
 * Class     DeferredServicesProvider
 *
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
            return new TransManager($app['files'], $this->getPaths($app));
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

    /**
     * Get the localization paths.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     *
     * @return array
     */
    private function getPaths(Application $app): array
    {
        return [
            'app'     => $app->langPath(),
            'vendors' => $app['config']->get('laravel-lang.vendor', []),
        ];
    }
}
