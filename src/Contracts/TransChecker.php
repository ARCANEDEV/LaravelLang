<?php namespace Arcanedev\LaravelLang\Contracts;

/**
 * Interface  TransChecker
 *
 * @package   Arcanedev\LaravelLang\Contracts
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface TransChecker
{
    /* -----------------------------------------------------------------
     |  Getters & Setter
     | -----------------------------------------------------------------
     */

    /**
     * Get the default locale being used.
     *
     * @return string
     */
    public function getDefaultLocale();

    /**
     * Get the locales to check.
     *
     * @return array
     */
    public function getLocales();

    /**
     * Get the ignored translation attributes.
     *
     * @return array
     */
    public function getIgnoredTranslations();

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Check the missing translations.
     *
     * @return array
     */
    public function check();
}
