<?php namespace Arcanedev\LaravelLang\Contracts;

/**
 * Interface  TransPublisher
 *
 * @package   Arcanedev\LaravelLang\Contracts
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface TransPublisher
{
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
     *
     * @throws \Arcanedev\LaravelLang\Exceptions\LangPublishException
     */
    public function publish($localeKey, $force = false);

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
    public function isDefault($locale);

    /**
     * Check if the locale is supported.
     *
     * @param  string  $key
     *
     * @return bool
     */
    public function isSupported($key);
}
