<?php

declare(strict_types=1);

namespace Arcanedev\LaravelLang;

use Arcanedev\LaravelLang\Contracts\TransPublisher as TransPublisherContract;
use Arcanedev\LaravelLang\Contracts\TransManager as TransManagerContract;
use Arcanedev\LaravelLang\Exceptions\LangPublishException;
use Illuminate\Filesystem\Filesystem;

/**
 * Class     TransPublisher
 *
 * @package  Arcanedev\LaravelLang
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
        $this->locales = $this->manager->getCollection('vendor');
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
        return $this->langPath.DS.$locale;
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
     * @param  bool    $force
     *
     * @return bool
     */
    public function publish(string $locale, $force = false): bool
    {
        $locale = trim($locale);

        if ($this->isDefault($locale)) {
            return true;
        }

        $this->checkLocale($locale);

        $source      = $this->getLocale($locale)->getPath();
        $destination = $this->getDestinationPath($locale);

        $this->isPublishable($locale, $destination, $force);

        return $this->filesystem->copyDirectory($source, $destination);
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

    /**
     * Check the folder exists.
     *
     * @param  string  $path
     *
     * @return bool
     */
    private function isFolderExists(string $path): bool
    {
        return $this->filesystem->exists($path)
            && $this->filesystem->isDirectory($path);
    }

    /**
     * Check if the folder is empty.
     *
     * @param  string  $path
     *
     * @return bool
     */
    private function isFolderEmpty(string $path): bool
    {
        $files = $this->filesystem->files($path);

        return empty($files);
    }

    /**
     * Check if locale is publishable.
     *
     * @param  string  $locale
     * @param  string  $path
     * @param  bool    $force
     *
     * @throws \Arcanedev\LaravelLang\Exceptions\LangPublishException
     */
    private function isPublishable(string $locale, string $path, bool $force): void
    {
        if ( ! $this->isFolderExists($path)) {
            return;
        }

        if ($this->isFolderEmpty($path)) {
            return;
        }

        if ( ! $force) {
            throw LangPublishException::unpublishable($locale);
        }
    }
}
