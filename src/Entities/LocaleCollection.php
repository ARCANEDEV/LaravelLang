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
     * @return \Arcanedev\LaravelLang\Entities\Locale|mixed
     */
    public function get($key, $default = null)
    {
        return parent::get($key, $default);
    }

    /**
     * Add a locale to collection.
     *
     * @param  \Arcanedev\LaravelLang\Entities\Locale  $local
     *
     * @return self
     */
    public function add(Locale $local)
    {
        $this->put($local->getKey(), $local);

        return $this;
    }
}
