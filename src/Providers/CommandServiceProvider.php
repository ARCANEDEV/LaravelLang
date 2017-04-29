<?php namespace Arcanedev\LaravelLang\Providers;

use Arcanedev\LaravelLang\Commands;
use Arcanedev\Support\Providers\CommandServiceProvider as ServiceProvider;

/**
 * Class     CommandServiceProvider
 *
 * @package  Arcanedev\LaravelLang\Providers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class CommandServiceProvider extends ServiceProvider
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [
        Commands\CheckCommand::class,
        Commands\PublishCommand::class,
    ];
}
