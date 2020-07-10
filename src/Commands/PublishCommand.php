<?php

declare(strict_types=1);

namespace Arcanedev\LaravelLang\Commands;

use Arcanedev\LaravelLang\Contracts\TransPublisher;
use Arcanedev\LaravelLang\Exceptions\LangPublishException;

/**
 * Class     PublishCommand
 *
 * @package  Arcanedev\LaravelLang\Commands
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
                                {--inline : Publish the inline translations}';

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
        $this->publisher->publish($locale, $options);

        $this->info("The locale [{$locale}] translations were published successfully.");
    }
}
