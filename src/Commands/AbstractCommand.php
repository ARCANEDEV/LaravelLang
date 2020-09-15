<?php

declare(strict_types=1);

namespace Arcanedev\LaravelLang\Commands;

use Arcanedev\LaravelLang\LaravelLang;
use Arcanedev\Support\Console\Command as BaseCommand;

/**
 * Class     AbstractCommand
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class AbstractCommand extends BaseCommand
{
    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Display LogViewer Logo and Copyrights.
     */
    protected function copyright(): void
    {
        // LOGO
        $this->comment("   __                           _   __                   ");
        $this->comment("  / /  __ _ _ __ __ ___   _____| | / /  __ _ _ __   __ _ ");
        $this->comment(" / /  / _` | '__/ _` \ \ / / _ \ |/ /  / _` | '_ \ / _` |");
        $this->comment("/ /__| (_| | | | (_| |\ V /  __/ / /__| (_| | | | | (_| |");
        $this->comment("\____/\__,_|_|  \__,_| \_/ \___|_\____/\__,_|_| |_|\__, |");
        $this->comment("                                                   |___/ ");
        $this->line('');

        // Copyright
        $this->comment('Version '.LaravelLang::VERSION.' - Created by ARCANEDEV'.chr(169));
        $this->line('');
    }
}
