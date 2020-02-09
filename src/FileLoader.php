<?php

declare(strict_types=1);

namespace Arcanedev\LaravelLang;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Translation\FileLoader as IlluminateFileLoader;

/**
 * Class     FileLoader
 *
 * @package  Arcanedev\LaravelLang
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class FileLoader extends IlluminateFileLoader
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * Vendor directory path.
     *
     * @var string
     */
    protected $vendorPath;

    /**
     * Supported locales.
     *
     * @var array
     */
    protected $locales;

    /* -----------------------------------------------------------------
     |  Constructor
     | -----------------------------------------------------------------
     */

    /**
     * Create a new file loader instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files
     * @param  string                             $path
     * @param  string                             $vendorPath
     * @param  array                              $locales
     */
    public function __construct(Filesystem $files, string $path, string $vendorPath, array $locales = [])
    {
        parent::__construct($files, $path);

        $this->setVendorPath($vendorPath);
        $this->setSupportedLocales($locales);
    }

    /* -----------------------------------------------------------------
     |  Getters & Setters
     | -----------------------------------------------------------------
     */

    /**
     * Get the vendor path.
     *
     * @return string
     */
    private function getVendorPath(): string
    {
        return $this->vendorPath;
    }

    /**
     * Set the vendor path.
     *
     * @param  string  $vendorPath
     *
     * @return $this
     */
    private function setVendorPath(string $vendorPath): self
    {
        $this->vendorPath = $vendorPath;

        return $this;
    }

    /**
     * Set the supported locales.
     *
     * @param  array  $locales
     *
     * @return $this
     */
    private function setSupportedLocales(array $locales): self
    {
        $this->locales = $locales;

        return $this;
    }

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Load the messages for the given locale.
     *
     * @param  string  $locale
     * @param  string  $group
     * @param  string  $namespace
     *
     * @return array
     */
    public function load($locale, $group, $namespace = null): array
    {
        $defaults = [];

        if (empty($this->locales) || $this->isSupported($locale))
            $defaults = $this->loadPath($this->getVendorPath(), $locale, $group);

        return array_replace_recursive($defaults, parent::load($locale, $group, $namespace));
    }

    /* -----------------------------------------------------------------
     |  Check Methods
     | -----------------------------------------------------------------
     */

    /**
     * Check if the locale is supported or use the fallback.
     *
     * @param  string  $locale
     *
     * @return bool
     */
    private function isSupported($locale): bool
    {
        return in_array($locale, $this->locales);
    }
}
