<?php

declare(strict_types=1);

namespace Arcanedev\LaravelLang\Entities;

use Arcanedev\LaravelLang\Contracts\Entities\Locale as LocaleContract;
use Illuminate\Support\{Arr, Str};

/**
 * Class     Locale
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class Locale implements LocaleContract
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /** @var string */
    private $key   = '';

    /** @var string */
    private $path  = '';

    /** @var array */
    private $files = [];

    /* -----------------------------------------------------------------
     |  Constructor
     | -----------------------------------------------------------------
     */

    /**
     * Locale constructor.
     *
     * @param  string  $key
     * @param  string  $path
     * @param  array   $files
     */
    public function __construct(string $key, string $path, array $files = [])
    {
        $this->key   = $key;
        $this->path  = $path;
        $this->files = $files;
    }

    /* -----------------------------------------------------------------
     |  Getters & Setters
     | -----------------------------------------------------------------
     */

    /**
     * Get the locale key.
     *
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * Get the locale translations path.
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Get locale translations.
     *
     * @return array
     */
    public function getTranslations(): array
    {
        $translations = array_map(function ($file) {
            return $file['content'];
        }, $this->files);

        return Arr::dot($translations);
    }

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Merge translations.
     *
     * @param  \Arcanedev\LaravelLang\Contracts\Entities\Locale|null  $locale
     * @param  array      $ignored
     *
     * @return array
     */
    public function mergeTranslations(LocaleContract $locale = null, array $ignored = []): array
    {
        $merged       = [];
        $translations = array_merge(
            is_null($locale) ? [] : $locale->getTranslations(),
            $this->getTranslations()
        );

        foreach ($translations as $key => $trans) {
            if ( ! Str::startsWith($key, $ignored)) {
                $merged[$key] = $trans;
            }
        }

        return $merged;
    }
}
