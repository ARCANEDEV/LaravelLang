<?php namespace Arcanedev\LaravelLang\Entities;

use Illuminate\Support\Arr;

/**
 * Class     Locale
 *
 * @package  Arcanedev\LaravelLang\Entities
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class Locale
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /** @var string */
    private $key   = '';

    /** @var string */
    private $path  = '';

    /** @var array */
    private $files = [];

    /* -----------------------------------------------------------------
     |  Constructor
     | -----------------------------------------------------------------
     */

    /**
     * Locale constructor.
     *
     * @param  string  $key
     * @param  string  $path
     * @param  array   $files
     */
    public function __construct($key, $path, array $files)
    {
        $this->key   = $key;
        $this->path  = $path;
        $this->files = $files;
    }

    /* -----------------------------------------------------------------
     |  Getters & Setters
     | -----------------------------------------------------------------
     */

    /**
     * Get the locale key.
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Get the locale translations path.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Get locale translations.
     *
     * @return array
     */
    public function getTranslations()
    {
        $translations = array_map(function ($file) {
            return $file['content'];
        }, $this->files);

        return Arr::dot($translations);
    }

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Merge translations.
     *
     * @param  self|null  $locale
     * @param  array      $ignored
     *
     * @return array
     */
    public function mergeTranslations(Locale $locale = null, array $ignored = [])
    {
        $merged       = [];
        $translations = array_merge(
            is_null($locale) ? [] : $locale->getTranslations(),
            $this->getTranslations()
        );

        foreach ($translations as $key => $trans) {
            if ( ! starts_with($key, $ignored)) {
                $merged[$key] = $trans;
            }
        }

        return $merged;
    }
}
