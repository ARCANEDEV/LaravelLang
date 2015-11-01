<?php namespace Arcanedev\LaravelLang\Contracts;

/**
 * Interface  TransManager
 *
 * @package   Arcanedev\LaravelLang\Contracts
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface TransManager
{
    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get the translation paths.
     *
     * @return array
     */
    public function getPaths();

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get locale from a group.
     *
     * @param  string  $group
     * @param  string  $locale
     * @param  mixed   $default
     *
     * @return \Arcanedev\LaravelLang\Entities\Locale|mixed
     */
    public function get($group, $locale, $default = null);
}
