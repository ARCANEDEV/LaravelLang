<?php

declare(strict_types=1);

namespace Arcanedev\LaravelLang;

use Arcanedev\LaravelLang\Contracts\TransPublisher as TransPublisherContract;
use Arcanedev\LaravelLang\Contracts\TransManager as TransManagerContract;
use Arcanedev\LaravelLang\Exceptions\LangPublishException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use SplFileInfo;

/**
 * Class     TransPublisher
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class TransPublisher implements TransPublisherContract
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    private $filesystem;

    /**
     * The TransManager instance.
     *
     * @var \Arcanedev\LaravelLang\Contracts\TransManager
     */
    private $manager;

    /**
     * Available locales.
     *
     * @var \Arcanedev\LaravelLang\Entities\LocaleCollection
     */
    private $locales;

    /**
     * The application lang path.
     *
     * @var string
     */
    private $langPath;

    /**
     * Publish's results.
     *
     * @var array
     */
    private $results = [];

    /* -----------------------------------------------------------------
     |  Constructor
     | -----------------------------------------------------------------
     */

    /**
     * Make TransPublisher instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem              $filesystem
     * @param  \Arcanedev\LaravelLang\Contracts\TransManager  $manager
     * @param  string                                         $langPath
     */
    public function __construct(Filesystem $filesystem, TransManagerContract $manager, string $langPath)
    {
        $this->filesystem = $filesystem;
        $this->manager    = $manager;
        $this->langPath   = realpath($langPath);

        $this->init();
    }

    /**
     * Start the engine.
     */
    private function init(): void
    {
        $this->locales = $this->manager->getCollection('vendor-php');
    }

    /* -----------------------------------------------------------------
     |  Getters & Setters
     | -----------------------------------------------------------------
     */

    /**
     * Get the locale destination path.
     *
     * @param  string  $locale
     *
     * @return string
     */
    private function getDestinationPath(string $locale): string
    {
        return $this->langPath.DIRECTORY_SEPARATOR.$locale;
    }

    /**
     * Get a locale from the collection.
     *
     * @param  string  $key
     * @param  mixed   $default
     *
     * @return \Arcanedev\LaravelLang\Entities\Locale|mixed
     */
    private function getLocale(string $key, $default = null)
    {
        return $this->locales->get($key, $default);
    }

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Publish a lang.
     *
     * @param  string  $locale
     * @param  array   $options
     *
     * @return array
     */
    public function publish(string $locale, array $options = []): array
    {
        $this->resetResults();

        $locale = trim($locale);

        if ($this->isDefault($locale)) {
            $this->results['skipped'][] = $locale;

            return $this->results;
        }

        $this->checkLocale($locale);

        $source      = $this->getLocale($locale)->getPath();
        $destination = $this->getDestinationPath($locale);

        $this->filesystem->ensureDirectoryExists($destination);

        // Publish the PHP Translations
        foreach ($this->filesystem->files($source) as $file) {
            $this->publishFile($file, $locale, $destination, $options);
        }

        // Publish the JSON Translation
        if ($options['json'] ?? false) {
            $this->publishJson($locale, $destination);
        }

        return $this->results;
    }

    /**
     * Publish the json file.
     *
     * @param  string  $locale
     * @param  string  $destination
     * @param  array   $options
     *
     * @return bool
     */
    private function publishJson(string $locale, string $destination, array $options = []): bool
    {
        $file = $this->manager->getCollection('vendor-json')->get($locale);

        if (is_null($file)) {
            $this->results['skipped'][] = "{$locale}.json";
            return false;
        }

        if ($this->filesystem->exists($destFile = $destination.'.json') && ($options['force'] ?? false) === false) {
            $this->results['skipped'][] = "{$locale}.json";
            return false;
        }

        return tap($this->filesystem->copy($file->getPath(), $destFile), function (bool $published) use ($locale) {
            if ($published)
                $this->results['published'][] = "{$locale}.json";
            else
                $this->results['skipped'][] = "{$locale}.json";
        });
    }

    /* -----------------------------------------------------------------
     |  Check Methods
     | -----------------------------------------------------------------
     */

    /**
     * Check if the locale is a default one (English is shipped with laravel).
     *
     * @param  string  $locale
     *
     * @return bool
     */
    public function isDefault(string $locale): bool
    {
        return $locale === 'en';
    }

    /**
     * Check if the locale is supported.
     *
     * @param  string  $key
     *
     * @return bool
     */
    public function isSupported(string $key): bool
    {
        return $this->locales->has($key);
    }

    /**
     * Check if the locale is supported or fail.
     *
     * @param  string  $locale
     *
     * @throws \Arcanedev\LaravelLang\Exceptions\LangPublishException
     */
    private function checkLocale(string $locale): void
    {
        if ( ! $this->isSupported($locale)) {
            throw new LangPublishException("The locale [$locale] is not supported.");
        }
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Publish the translation file.
     *
     * @param  SplFileInfo  $file
     * @param  string       $locale
     * @param  string       $destination
     * @param  array        $options
     */
    private function publishFile(SplFileInfo $file, string $locale, string $destination, array $options): void
    {
        $isInlineFile = Str::endsWith($file->getFilename(), '-inline.php');
        $destFile = $isInlineFile
            ? Str::replaceLast('-inline.php', '.php', $file->getFilename())
            : $file->getFilename();

        if ($this->isInResults($key = "{$locale}/{$destFile}"))
            return;

        // Ignore if inline option is not enabled
        if ($isInlineFile && (($options['inline'] ?? false) === false))
            return;

        // Ignore if force option is not enabled
        if ($this->filesystem->exists($destination.DIRECTORY_SEPARATOR.$destFile) && ($options['force'] ?? false) === false) {
            $this->results['skipped'][] = $key;
            return;
        }

        $this->filesystem->copy($file->getRealPath(), $destination.DIRECTORY_SEPARATOR.$destFile);
        $this->results['published'][] = $key;
    }

    /**
     * Reset the publish results.
     */
    private function resetResults(): void
    {
        $this->results = [
            'published' => [],
            'skipped'   => [],
        ];
    }

    /**
     * Check if the given key exists in results.
     *
     * @param  string  $key
     *
     * @return bool
     */
    private function isInResults(string $key): bool
    {
        return in_array($key, $this->results['published'])
            || in_array($key, $this->results['skipped']);
    }
}
