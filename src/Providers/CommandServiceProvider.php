<?php namespace Arcanedev\LaravelLang\Providers;

use Arcanedev\Support\Providers\CommandServiceProvider as ServiceProvider;

/**
 * Class     CommandServiceProvider
 *
 * @package  Arcanedev\LaravelLang\Providers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class CommandServiceProvider extends ServiceProvider
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
    protected $vendor   = 'arcanedev';

    /**
     * Package name.
     *
     * @var string
     */
    protected $package  = 'laravel-lang';

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->registerCommand(
            'check', \Arcanedev\LaravelLang\Commands\CheckCommand::class
        );

        $this->registerCommand(
            'publish', \Arcanedev\LaravelLang\Commands\PublishCommand::class
        );

        $this->commands($this->commands);
    }

    /**
     * Get the provided commands.
     *
     * @return array
     */
    public function provides()
    {
        return $this->commands;
    }
}
