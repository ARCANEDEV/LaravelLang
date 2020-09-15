<?php

declare(strict_types=1);

namespace Arcanedev\LaravelLang\Contracts;

/**
 * Interface  TransPublisher
 *
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
     * @param  array   $options
     *
     * @return array
     *
     * @throws \Arcanedev\LaravelLang\Exceptions\LangPublishException
     */
    public function publish(string $localeKey, array $options = []): array;

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
    public function isDefault(string $locale): bool;

    /**
     * Check if the locale is supported.
     *
     * @param  string  $key
     *
     * @return bool
     */
    public function isSupported(string $key): bool;
}
