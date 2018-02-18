<?php namespace Arcanedev\LaravelLang;

use Arcanedev\LaravelLang\Entities\Locale;
use Arcanedev\LaravelLang\Entities\LocaleCollection;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;

/**
 * Class     TransManager
 *
 * @package  Arcanedev\LaravelLang
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class TransManager implements Contracts\TransManager
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
    public function getPaths()
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
    private function load()
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
    private function loadDirectories($dirPath)
    {
        $locales = new LocaleCollection;

        foreach ($this->filesystem->directories($dirPath) as $path) {
            $excluded = in_array($key = basename($path), $this->excludedFolders);

            if ( ! $excluded) {
                $locales->add(
                    new Locale($key, $path, $this->loadLocaleFiles($path))
                );
            }
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
    private function loadLocaleFiles($path)
    {
        $files = [];

        foreach ($this->filesystem->allFiles($path) as $file) {
            /** @var \Symfony\Component\Finder\SplFileInfo $file */
            $key = str_replace(
                ['.php', DS], ['', '.'], $file->getRelativePathname()
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
    public function getCollection($group, $default = null)
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
    public function getFrom($group, $locale, $default = null)
    {
        if ( ! $this->hasCollection($group))
            return $default;

        $locales = $this->getCollection($group);

        return $locales->get($locale, $default);
    }

    /**
     * Get locale keys.
     *
     * @return array
     */
    public function keys()
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
    public function count()
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
    public function hasCollection($group)
    {
        return Arr::has($this->locales, $group);
    }
}
