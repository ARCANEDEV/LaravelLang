<?php namespace Arcanedev\LaravelLang\Contracts\Entities;

/**
 * Interface  Locale
 *
 * @package   Arcanedev\LaravelLang\Contracts\Entities
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface Locale
{
    /* -----------------------------------------------------------------
     |  Getters & Setters
     | -----------------------------------------------------------------
     */

    /**
     * Get the locale key.
     *
     * @return string
     */
    public function getKey();

    /**
     * Get the locale translations path.
     *
     * @return string
     */
    public function getPath();

    /**
     * Get locale translations.
     *
     * @return array
     */
    public function getTranslations();
}
