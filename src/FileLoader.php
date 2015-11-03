<?php namespace Arcanedev\LaravelLang;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Translation\FileLoader as IlluminateFileLoader;

/**
 * Class     FileLoader
 *
 * @package  Arcanedev\LaravelLang
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class FileLoader extends IlluminateFileLoader
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Languages dir.
     *
     * @var string
     */
    protected $languagesPath;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Create a new file loader instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files
     * @param  string                             $path
     * @param  string                             $languagesPath
     */
    public function __construct(Filesystem $files, $path, $languagesPath)
    {
        parent::__construct($files, $path);

        $this->setLanguagesPath($languagesPath);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get the languages path.
     *
     * @return string
     */
    private function getLanguagesPath()
    {
        return $this->languagesPath;
    }

    /**
     * Set the languages path.
     *
     * @param  string  $languagesPath
     *
     * @return self
     */
    private function setLanguagesPath($languagesPath)
    {
        $this->languagesPath = $languagesPath;

        return $this;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Load the messages for the given locale.
     *
     * @param  string  $locale
     * @param  string  $group
     * @param  string  $namespace
     *
     * @return array
     */
    public function load($locale, $group, $namespace = null)
    {
        $defaults = $this->loadPath($this->getLanguagesPath(), $locale, $group);

        return array_replace_recursive($defaults, parent::load($locale, $group, $namespace));
    }
}
