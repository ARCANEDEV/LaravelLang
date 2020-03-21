<?php

declare(strict_types=1);

namespace Arcanedev\LaravelLang;

use Arcanedev\LaravelLang\Contracts\TransManager as TransManagerContract;
use Arcanedev\LaravelLang\Entities\{Locale, LocaleCollection};
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;

/**
 * Class     TransManager
 *
 * @package  Arcanedev\LaravelLang
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class TransManager implements TransManagerContract
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * Lang directories paths.
     *
     * @var array
     */
    private $paths   = [];

    /**
     * Translations collection.
     *
     * @var array
     */
    private $locales = [];

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    private $filesystem;

    /**
     * Excluded folders.
     *
     * @var array
     */
    private $excludedFolders = ['script', 'vendor'];

    /* -----------------------------------------------------------------
     |  Constructor
     | -----------------------------------------------------------------
     */

    /**
     * Make TransManager instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $filesystem
     * @param  array                              $paths
     */
    public function __construct(Filesystem $filesystem, array $paths)
    {
        $this->filesystem = $filesystem;
        $this->paths      = $paths;

        $this->load();
    }

    /* -----------------------------------------------------------------
     |  Getters & Setters
     | -----------------------------------------------------------------
     */

    /**
     * Get the translation paths.
     *
     * @return array
     */
    public function getPaths(): array
    {
        return $this->paths;
    }

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Load lang files.
     */
    private function load(): void
    {
        foreach ($this->getPaths() as $group => $path) {
            $this->locales[$group] = $this->loadDirectories($path);
        }
    }

    /**
     * Load directories.
     *
     * @param  string  $dirPath
     *
     * @return \Arcanedev\LaravelLang\Entities\LocaleCollection
     */
    private function loadDirectories($dirPath): LocaleCollection
    {
        $locales = new LocaleCollection;

        foreach ($this->filesystem->directories($dirPath) as $path) {
            if ($this->isExcluded($path)) {
                continue;
            }

            $locales->addOne(
                new Locale(basename($path), $path, $this->loadLocaleFiles($path))
            );
        }

        return $locales;
    }

    /**
     * Load the locale translation files.
     *
     * @param  string  $path
     *
     * @return array
     */
    private function loadLocaleFiles(string $path): array
    {
        $files = [];

        foreach ($this->filesystem->allFiles($path) as $file) {
            /** @var \Symfony\Component\Finder\SplFileInfo $file */
            $key = str_replace(
                ['.php', DIRECTORY_SEPARATOR], ['', '.'], $file->getRelativePathname()
            );

            $files[$key] = [
                'path'    => $file->getRealPath(),
                'content' => $this->filesystem->getRequire($file),
            ];
        }

        return $files;
    }

    /**
     * Get locale collection by group location.
     *
     * @param  string      $group
     * @param  mixed|null  $default
     *
     * @return \Arcanedev\LaravelLang\Entities\LocaleCollection|null
     */
    public function getCollection(string $group, $default = null): ?LocaleCollection
    {
        return Arr::get($this->locales, $group, $default);
    }

    /**
     * Get a locale translations from a group.
     *
     * @param  string  $group
     * @param  string  $locale
     * @param  null    $default
     *
     * @return \Arcanedev\LaravelLang\Entities\Locale|null
     */
    public function getFrom(string $group, string $locale, $default = null): ?Locale
    {
        if ( ! $this->hasCollection($group)) {
            return $default;
        }

        $locales = $this->getCollection($group);

        return $locales->get($locale, $default);
    }

    /**
     * Get locale keys.
     *
     * @return array
     */
    public function keys(): array
    {
        $locales = array_map(function (LocaleCollection $locales) {
            $keys = $locales->keys()->toArray();
            return array_combine($keys, $keys);
        }, $this->locales);

        $all = [];

        foreach ($locales as $keys) {
            $all = array_merge($all, $keys);
        }

        return $all;
    }

    /**
     * Get locales count.
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->keys());
    }

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
    public function hasCollection(string $group): bool
    {
        return Arr::has($this->locales, $group);
    }

    /**
     * Check if the given path is excluded.
     *
     * @param  string  $path
     *
     * @return bool
     */
    private function isExcluded(string $path): bool
    {
        return in_array($key = basename($path), $this->excludedFolders);
    }
}
