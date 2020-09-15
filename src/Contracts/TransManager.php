<?php

declare(strict_types=1);

namespace Arcanedev\LaravelLang\Contracts;

use Arcanedev\LaravelLang\Entities\{Locale, LocaleCollection};

/**
 * Interface  TransManager
 *
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
    public function getPaths(): array;

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
    public function getCollection(string $group, $default = null): ?LocaleCollection;

    /**
     * Get a locale translations from a group.
     *
     * @param  string      $group
     * @param  string      $locale
     * @param  mixed|null  $default
     *
     * @return \Arcanedev\LaravelLang\Entities\Locale|null
     */
    public function getFrom(string $group, string $locale, $default = null): ?Locale;

    /**
     * Get locale keys.
     *
     * @return array
     */
    public function keys(): array;

    /**
     * Get locales count.
     *
     * @return int
     */
    public function count(): int;

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
    public function hasCollection(string $group): bool;
}
