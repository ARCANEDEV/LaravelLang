<?php namespace Arcanedev\LaravelLang;

use Arcanedev\LaravelLang\Entities\Locale;
use Arcanedev\LaravelLang\Entities\LocaleCollection;
use Illuminate\Filesystem\Filesystem;

/**
 * Class     TransManager
 *
 * @package  Arcanedev\LaravelLang\Entities
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class TransManager implements Contracts\TransManager
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
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

    /** @var Filesystem */
    private $filesystem;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
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

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
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

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Load lang files.
     */
    private function load()
    {
        $exclude = ['vendor'];

        foreach ($this->getPaths() as $group => $path) {
            $this->locales[$group] = $this->loadDirectories($path, $exclude);
        }
    }

    /**
     * Load directories.
     *
     * @param  string  $dirPath
     * @param  array   $exclude
     *
     * @return LocaleCollection
     */
    private function loadDirectories($dirPath, array $exclude = [])
    {
        $locales = new LocaleCollection;

        foreach ($this->filesystem->directories($dirPath) as $path) {
            $key = basename($path);

            if (in_array($key, $exclude)) {
                continue;
            }

            $locale = new Locale($key, $path, $this->loadLocaleFiles($path));
            $locales->add($locale);
        }

        return $locales;
    }

    /**
     * Load the locale translation files.
     *
     * @param  string  $path
     *
     * @return array
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    private function loadLocaleFiles($path)
    {
        $files = [];

        foreach ($this->filesystem->allFiles($path) as $file) {
            /** @var \Symfony\Component\Finder\SplFileInfo $file */
            $key = str_replace(
                ['.php', DS], ['', '.'],
                $file->getRelativePathname()
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
     * @param  string  $group
     * @param  null    $default
     *
     * @return LocaleCollection|null
     */
    public function getCollection($group, $default = null)
    {
        return array_get($this->locales, $group, $default);
    }

    /**
     * Get locale keys.
     *
     * @return array
     */
    public function keys()
    {
        $locales = array_map(function ($locales) {
            /** @var LocaleCollection $locales */
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
}
