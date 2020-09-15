<?php

declare(strict_types=1);

namespace Arcanedev\LaravelLang\Commands;

use Arcanedev\LaravelLang\Contracts\TransPublisher;

/**
 * Class     PublishCommand
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class PublishCommand extends AbstractCommand
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
    protected $signature   = 'trans:publish
                                {locale : The language to publish the translations.}
                                {--force : Force to override the translations}
                                {--inline : Publish the inline translations}
                                {--json : Include json translations file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish the [locale] translations.';

    /**
     * The TransPublisher instance.
     *
     * @var \Arcanedev\LaravelLang\Contracts\TransPublisher
     */
    private $publisher;

    /* -----------------------------------------------------------------
     |  Constructor
     | -----------------------------------------------------------------
     */

    /**
     * Create a new console command instance.
     *
     * @param  \Arcanedev\LaravelLang\Contracts\TransPublisher  $publisher
     */
    public function __construct(TransPublisher $publisher)
    {
        $this->publisher = $publisher;
        $this->name      = 'trans:publish';

        parent::__construct();
    }

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->copyright();

        $locale = (string) $this->argument('locale');

        if ($this->publisher->isDefault($locale)) {
            $this->info("The locale [{$locale}] is a default lang and it's shipped with laravel.");
            $this->line('');
            return;
        }

        $this->publish($locale, [
            'force'  => (bool) $this->option('force'),
            'inline' => (bool) $this->option('inline'),
            'json'   => (bool) $this->option('json'),
        ]);

        $this->line('');
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Publish the translations.
     *
     * @param  string  $locale
     * @param  array   $options
     */
    private function publish(string $locale, array $options): void
    {
        $this->info("Publishing the [{$locale}] translations...");

        $results = $this->publisher->publish($locale, $options);

        $this->showResults(['Published translations'], $results['published']);
        $this->showResults(['Skipped translations'], $results['skipped']);
    }

    /**
     * Show the results.
     *
     * @param  array  $headers
     * @param  array  $results
     */
    public function showResults(array $headers, array $results): void
    {
        if (empty($results))
            return;

        $this->table($headers, array_map(function($result) {
            return [$result];
        }, $results));
    }
}
