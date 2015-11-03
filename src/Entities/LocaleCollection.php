<?php namespace Arcanedev\LaravelLang\Entities;

use Arcanedev\Support\Collection;

/**
 * Class     LocaleCollection
 *
 * @package  Arcanedev\LaravelLang\Entities
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class LocaleCollection extends Collection
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get an item from the collection by key.
     *
     * @param  string  $key
     * @param  mixed   $default
     *
     * @return Locale|mixed
     */
    public function get($key, $default = null)
    {
        return parent::get($key, $default);
    }

    /**
     * Add a locale to collection.
     *
     * @param  Locale  $local
     *
     * @return \Arcanedev\LaravelLang\Entities\LocaleCollection
     */
    public function add(Locale $local)
    {
        $this->put($local->getKey(), $local);

        return $this;
    }
}
