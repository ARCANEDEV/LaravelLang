<?php

declare(strict_types=1);

namespace Arcanedev\LaravelLang\Exceptions;

/**
 * Class     LangPublishException
 *
 * @package  Arcanedev\LaravelLang\Exceptions
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class LangPublishException extends LaravelLangException
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * @param  string  $locale
     *
     * @return static
     */
    public static function unpublishable(string $locale): self
    {
        return new static(
            "You can't publish the translations because the [$locale] folder is not empty. ".
            "To override the translations, try to clean/delete the [$locale] folder or force the publication."
        );
    }
}
