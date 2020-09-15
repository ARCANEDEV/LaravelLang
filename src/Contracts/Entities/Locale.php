<?php

declare(strict_types=1);

namespace Arcanedev\LaravelLang\Contracts\Entities;

/**
 * Interface  Locale
 *
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
    public function getKey(): string;

    /**
     * Get the locale translations path.
     *
     * @return string
     */
    public function getPath(): string;

    /**
     * Get locale translations.
     *
     * @return array
     */
    public function getTranslations(): array;
}
