<?php namespace Arcanedev\LaravelLang;

use Arcanedev\LaravelLang\Exceptions\LangPublishException;
use Illuminate\Filesystem\Filesystem;

/**
 * Class     TransPublisher
 *
 * @package  Arcanedev\LaravelLang\Services
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class TransPublisher implements Contracts\TransPublisher
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
    public function __construct(Filesystem $filesystem, Contracts\TransManager $manager, $langPath)
    {
        $this->filesystem = $filesystem;
        $this->manager    = $manager;
        $this->langPath   = realpath($langPath);

        $this->init();
    }

    /**
     * Start the engine.
     */
    private function init()
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
    private function getDestinationPath($locale)
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
    private function getLocale($key, $default = null)
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
     * @param  string  $localeKey
     * @param  bool    $force
     *
     * @return bool
     */
    public function publish($localeKey, $force = false)
    {
        $localeKey = trim($localeKey);

        if ($this->isDefault($localeKey))
            return true;

        $this->checkLocale($localeKey);

        $source      = $this->getLocale($localeKey)->getPath();
        $destination = $this->getDestinationPath($localeKey);

        $this->isPublishable($localeKey, $destination, $force);

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
    public function isDefault($locale)
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
    public function isSupported($key)
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
    private function checkLocale($locale)
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
    private function isFolderExists($path)
    {
        return $this->filesystem->exists($path) && $this->filesystem->isDirectory($path);
    }

    /**
     * Check if the folder is empty.
     *
     * @param  string  $path
     *
     * @return bool
     */
    private function isFolderEmpty($path)
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
    private function isPublishable($locale, $path, $force)
    {
        if ( ! $this->isFolderExists($path) || $this->isFolderEmpty($path))
            return;

        if ( ! $force) {
            throw new LangPublishException(
                "You can't publish the translations because the [$locale] folder is not empty. ".
                "To override the translations, try to clean/delete the [$locale] folder or force the publication."
            );
        }
    }
}
