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
    public function add(Locale $local)
    {
        $this->put($local->getKey(), $local);

        return $this;
    }
}
