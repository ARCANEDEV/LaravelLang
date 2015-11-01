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
    private $paths   = [];
    private $locales = [];

    /** @var Filesystem */
    private $filesystem;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
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
    public function load()
    {
        $exclude = ['vendor'];

        foreach ($this->getPaths() as $group => $langPath) {
            $directories = new LocaleCollection;

            foreach ($this->filesystem->directories($langPath) as $path) {
                $locale = basename($path);

                if (in_array($locale, $exclude)) {
                    continue;
                }

                $directories->add($this->loadDirectory($locale, $path));
            }

            $this->locales[$group] = $directories;

        }
    }

    /**
     * Load the lang directory.
     *
     * @param  string  $locale
     * @param  string  $path
     *
     * @return Locale
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    private function loadDirectory($locale, $path)
    {
        $files = [];

        foreach ($this->filesystem->allFiles($path) as $file) {
            /** @var \Symfony\Component\Finder\SplFileInfo $file */
            $key = str_replace(
                ['.php', DS],
                ['', '.'],
                $file->getRelativePathname()
            );

            $files[$key] = [
                'path'    => $file->getRealPath(),
                'content' => $this->filesystem->getRequire($file),
            ];
        }

        return new Locale($locale, $path, $files);
    }

    /**
     * Get locale from a group.
     *
     * @param  string  $group
     * @param  string  $locale
     * @param  mixed   $default
     *
     * @return Locale|mixed
     */
    public function get($group, $locale, $default = null)
    {
        $locales = $this->getCollection($group);

        return $locales->get($locale, $default);
    }

    /**
     * @param  string  $group
     *
     * @return LocaleCollection
     */
    public function getCollection($group)
    {
        return $this->locales[$group];
    }
}
