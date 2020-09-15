<?php

declare(strict_types=1);

namespace Arcanedev\LaravelLang\Contracts;

/**
 * Interface  TransChecker
 *
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
    public function getDefaultLocale(): string;

    /**
     * Get the locales to check.
     *
     * @return array
     */
    public function getLocales(): array;

    /**
     * Get the ignored translation attributes.
     *
     * @return array
     */
    public function getIgnoredTranslations(): array;

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Check the missing translations.
     *
     * @return array
     */
    public function check(): array;
}
