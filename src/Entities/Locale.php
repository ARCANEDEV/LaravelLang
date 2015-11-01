<?php namespace Arcanedev\LaravelLang\Entities;

/**
 * Class     Locale
 *
 * @package  Arcanedev\LaravelLang\Entities
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class Locale
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    private $key   = '';

    private $path  = '';

    private $files = [];

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    public function __construct($key, $path, array $files)
    {
        $this->key   = $key;
        $this->path  = $path;
        $this->files = $files;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
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

    public function getTranslations()
    {
        $translations = array_map(function ($file) {
            return $file['content'];
        }, $this->files);

        return array_dot($translations);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function mergeTranslations(Locale $locale = null, array $ignored = [])
    {
        $merged       = [];
        $translations = array_merge(
            is_null($locale) ? [] : $locale->getTranslations(),
            $this->getTranslations()
        );

        foreach ($translations as $key => $trans) {
            if (starts_with($key, $ignored)) {
                continue;
            }

            $merged[$key] = $trans;
        }

        return $merged;
    }
}
