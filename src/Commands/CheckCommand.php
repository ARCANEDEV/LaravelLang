<?php namespace Arcanedev\LaravelLang\Commands;

use Arcanedev\LaravelLang\Bases\Command;
use Arcanedev\LaravelLang\Contracts\TransChecker;

/**
 * Class     CheckCommand
 *
 * @package  Arcanedev\LaravelLang\Commands
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class CheckCommand extends Command
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
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

    /** @var \Arcanedev\LaravelLang\Contracts\TransChecker */
    private $checker;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Init the CheckCommand.
     *
     * @param  \Arcanedev\LaravelLang\Contracts\TransChecker  $checker
     */
    public function __construct(TransChecker $checker)
    {
        parent::__construct();

        $this->checker = $checker;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->copyright();

        $this->info('Checking the missing translation...');

        $missing = $this->checker->check();

        $this->table(
            ['locale', 'translations'],
            $this->prepareRows($missing)
        );
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Prepare table rows.
     *
     * @param  array  $missing
     *
     * @return array
     */
    private function prepareRows(array $missing)
    {
        $rows  = [];
        $count = 0;

        foreach ($missing as $locale => $translations) {
            foreach ($translations as $translation) {
                $rows[] = [$locale, $translation];
                $count++;
            }
            $rows[] = $this->tableSeparator();
        }

        /** @var \Symfony\Component\Console\Helper\FormatterHelper $formatter */
        $formatter = $this->getHelper('formatter');

        $rows[] = [
            $formatter->formatSection('Total', '', 'info'),
            $formatter->formatSection($count . ' translations are missing.', '', $count == 0 ? 'info' : 'error'),
        ];

        return $rows;
    }
}
