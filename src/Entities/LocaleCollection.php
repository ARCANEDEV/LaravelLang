<?php

declare(strict_types=1);

namespace Arcanedev\LaravelLang\Entities;

use Arcanedev\LaravelLang\Contracts\Entities\Locale as LocaleContract;
use Illuminate\Support\Collection;

/**
 * Class     LocaleCollection
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class LocaleCollection extends Collection
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Get an item from the collection by key.
     *
     * @param  string  $key
     * @param  mixed   $default
     *
     * @return \Arcanedev\LaravelLang\Contracts\Entities\Locale|mixed
     */
    public function get($key, $default = null)
    {
        return parent::get($key, $default);
    }

    /**
     * Add a locale to collection.
     *
     * @param  \Arcanedev\LaravelLang\Contracts\Entities\Locale  $local
     *
     * @return $this
     */
    public function addOne(LocaleContract $local)
    {
        return $this->put($local->getKey(), $local);
    }
}
