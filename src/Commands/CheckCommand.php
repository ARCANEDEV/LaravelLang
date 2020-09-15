<?php

declare(strict_types=1);

namespace Arcanedev\LaravelLang\Commands;

use Arcanedev\LaravelLang\Contracts\TransChecker;

/**
 * Class     CheckCommand
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class CheckCommand extends AbstractCommand
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature   = 'trans:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check the missing translations.';

    /**
     * The TransChecker instance.
     *
     * @var \Arcanedev\LaravelLang\Contracts\TransChecker
     */
    private $checker;

    /**
     * Missing translations count.
     *
     * @var int
     */
    private $missingTranslations = 0;

    /* -----------------------------------------------------------------
     |  Constructor
     | -----------------------------------------------------------------
     */

    /**
     * Init the CheckCommand.
     *
     * @param  \Arcanedev\LaravelLang\Contracts\TransChecker  $checker
     */
    public function __construct(TransChecker $checker)
    {
        $this->name    = $this->signature;
        $this->checker = $checker;
        $this->missingTranslations   = 0;

        parent::__construct();
    }

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->copyright();

        $this->info('Checking the missing translations...');
        $this->line('');

        $missing = $this->checker->check();

        $this->table(['locale', 'translations'], $this->prepareRows($missing));

        $this->line('');
        $this->showMessage();
        $this->line('');

        return $this->getExitCode();
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Prepare table rows.
     *
     * @param  array  $missing
     *
     * @return array
     */
    private function prepareRows(array $missing): array
    {
        $rows = [];

        foreach ($missing as $locale => $translations) {
            foreach ($translations as $translation) {
                $rows[] = [$locale, $translation];
                $this->missingTranslations++;
            }
            $rows[] = $this->tableSeparator();
        }

        $rows[] = ['Total', "{$this->missingTranslations} translations are missing."];

        return $rows;
    }

    /**
     * Show the message.
     *
     * @codeCoverageIgnore
     */
    private function showMessage(): void
    {
        if ($this->hasMissingTranslations())
            $this->comment('Try to fix your translations and run again the `trans:check` command.');
        else
            $this->info('No missing translations, YOU ROCK !! (^_^)b');
    }

    /**
     * Get the exit code.
     *
     * @return int
     */
    protected function getExitCode(): int
    {
        return $this->hasMissingTranslations() ? 1 : 0;
    }

    /**
     * Check if has missing translations.
     *
     * @return bool
     */
    protected function hasMissingTranslations(): bool
    {
        return $this->missingTranslations > 0;
    }
}
