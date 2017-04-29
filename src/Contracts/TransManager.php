<?php namespace Arcanedev\LaravelLang\Contracts;

/**
 * Interface  TransManager
 *
 * @package   Arcanedev\LaravelLang\Contracts
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface TransManager
{
    /* -----------------------------------------------------------------
     |  Getters & Setters
     | -----------------------------------------------------------------
     */

    /**
     * Get the translation paths.
     *
     * @return array
     */
    public function getPaths();

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Get locale collection by group location.
     *
     * @param  string      $group
     * @param  mixed|null  $default
     *
     * @return \Arcanedev\LaravelLang\Entities\LocaleCollection|null
     */
    public function getCollection($group, $default = null);

    /**
     * Get a locale translations from a group.
     *
     * @param  string  $group
     * @param  string  $locale
     * @param  null    $default
     *
     * @return \Arcanedev\LaravelLang\Entities\Locale|null
     */
    public function getFrom($group, $locale, $default = null);

    /**
     * Get locale keys.
     *
     * @return array
     */
    public function keys();

    /**
     * Get locales count.
     *
     * @return int
     */
    public function count();

    /* -----------------------------------------------------------------
     |  Check Methods
     | -----------------------------------------------------------------
     */

    /**
     * Check if a translation group exists.
     *
     * @param  string  $group
     *
     * @return bool
     */
    public function hasCollection($group);
}
