<?php

declare(strict_types=1);

namespace Arcanedev\LaravelLang;

use Arcanedev\Support\Providers\PackageServiceProvider as ServiceProvider;

/**
 * Class     LaravelLangServiceProvider
 *
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
    public function register(): void
    {
        parent::register();

        $this->registerConfig();

        if ($this->app->runningInConsole()) {
            $this->commands([
                Commands\CheckCommand::class,
                Commands\PublishCommand::class,
            ]);
        }
    }

    /**
     * Boot the service provider.
     */
    public function boot(): void
    {
        $this->publishConfig();
    }
}
