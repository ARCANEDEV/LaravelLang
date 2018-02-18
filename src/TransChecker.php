<?php namespace Arcanedev\LaravelLang;

use Arcanedev\LaravelLang\Contracts\TransChecker as TransCheckerInterface;
use Arcanedev\LaravelLang\Contracts\TransManager as TransManagerInterface;
use Illuminate\Support\Arr;
use Illuminate\Translation\Translator;

/**
 * Class     TransChecker
 *
 * @package  Arcanedev\LaravelLang
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class TransChecker implements TransCheckerInterface
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * Config values.
     *
     * @var array
     */
    private $configs;

    /**
     * The translator instance.
     *
     * @var \Illuminate\Translation\Translator
     */
    private $translator;

    /**
     * The translator manager instance.
     *
     * @var \Arcanedev\LaravelLang\Contracts\TransManager
     */
    private $manager;

    /**
     * The missing translations.
     *
     * @var array
     */
    private $missing     = [];

    /* -----------------------------------------------------------------
     |  Constructor
     | -----------------------------------------------------------------
     */

    /**
     * Make TransChecker instance.
     *
     * @param  \Illuminate\Translation\Translator             $translator
     * @param  \Arcanedev\LaravelLang\Contracts\TransManager  $manager
     * @param  array                                          $configs
     */
    public function __construct(
        Translator $translator,
        TransManagerInterface $manager,
        array $configs
    ) {
        $this->translator = $translator;
        $this->manager    = $manager;
        $this->configs    = $configs;
    }

    /* -----------------------------------------------------------------
     |  Getters & Setter
     | -----------------------------------------------------------------
     */

    /**
     * Get the default locale being used.
     *
     * @return string
     */
    public function getDefaultLocale()
    {
        return $this->translator->getLocale();
    }

    /**
     * Get the locales to check.
     *
     * @return array
     */
    public function getLocales()
    {
        return Arr::get($this->configs, 'locales', []);
    }

    /**
     * Get the ignored translation attributes.
     *
     * @return array
     */
    public function getIgnoredTranslations()
    {
        return Arr::get($this->configs, 'check.ignore', []);
    }

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Check the missing translations.
     *
     * @return array
     */
    public function check()
    {
        $this->missing = [];
        $from          = $this->getDefaultLocale();
        $locales       = $this->getLocales();
        $ignored       = $this->getIgnoredTranslations();
        $fromTrans     = $this->getTranslations($from, $ignored);

        foreach ($locales as $to) {
            $toTrans = $this->getTranslations($to, $ignored);

            $this->diffMissing($toTrans, $fromTrans, $from);
            $this->diffMissing($fromTrans, $toTrans, $to);
        }

        return $this->missing;
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Get locale translations from multiple groups.
     *
     * @param  string  $locale
     * @param  array   $ignored
     *
     * @return array
     */
    private function getTranslations($locale, array $ignored)
    {
        $appLocale    = $this->manager->getFrom('app', $locale);
        $vendorLocale = $this->manager->getFrom('vendor', $locale);

        return is_null($appLocale)
            ? $vendorLocale->mergeTranslations($appLocale, $ignored)
            : $appLocale->mergeTranslations($vendorLocale, $ignored);
    }

    /**
     * Diff the missing translations.
     *
     * @param  array   $toTranslations
     * @param  array   $fromTranslations
     * @param  string  $locale
     */
    private function diffMissing(array $toTranslations, array $fromTranslations, $locale)
    {
        $diff = array_diff_key($toTranslations, $fromTranslations);

        if (count($diff) === 0)
            return;

        foreach ($diff as $transKey => $transValue) {
            $this->addMissing($locale, $transKey);
        }
    }

    /**
     * Adding missing translation to collection.
     *
     * @param  string  $locale
     * @param  string  $transKey
     */
    private function addMissing($locale, $transKey)
    {
        if ( ! $this->hasMissing($locale, $transKey)) {
            $this->missing[$locale][] = $transKey;
        }
    }

    /**
     * Check if a missing translation exists in collection.
     *
     * @param  string  $locale
     * @param  string  $transKey
     *
     * @return bool
     */
    private function hasMissing($locale, $transKey)
    {
        if ( ! isset($this->missing[$locale]))
            return false;

        return in_array($transKey, $this->missing[$locale]);
    }
}
