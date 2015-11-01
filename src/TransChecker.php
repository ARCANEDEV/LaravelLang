<?php namespace Arcanedev\LaravelLang;

use Arcanedev\LaravelLang\Contracts\TransChecker as TransCheckerInterface;
use Arcanedev\LaravelLang\Contracts\TransManager as TransManagerInterface;
use Arcanedev\LaravelLang\Entities\Locale;
use Illuminate\Translation\Translator;

/**
 * Class     TransChecker
 *
 * @package  Arcanedev\LaravelLang
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class TransChecker implements TransCheckerInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var array */
    private $configs;

    /** @var \Arcanedev\LaravelLang\Contracts\TransManager */
    private $manager;

    /** @var array */
    private $missing     = [];

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
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

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setter
     | ------------------------------------------------------------------------------------------------
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
        return array_get($this->configs, 'check.locales', []);
    }

    /**
     * Get the ignored translation attributes.
     *
     * @return array
     */
    public function getIgnoredTranslations()
    {
        return array_get($this->configs, 'check.ignore', []);
    }

    /**
     * Get the translation paths.
     *
     * @return array
     */
    public function getPaths()
    {
        return $this->manager->getPaths();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function check()
    {
        $from    = $this->getDefaultLocale();
        $locales = $this->getLocales();
        $ignored = $this->getIgnoredTranslations();

        /**
         * @var Locale $fromAppLocale
         * @var Locale $fromVendorLocale
         */
        $fromAppLocale    = $this->manager->get('app', $from);
        $fromVendorLocale = $this->manager->get('vendor', $from);
        $fromTranslations = $fromAppLocale->mergeTranslations($fromVendorLocale, $ignored);
        $this->missing    = [];

        foreach ($locales as $to) {
            /**
             * @var Locale $toAppLocale
             * @var Locale $toVendorLocale
             */
            $toAppLocale      = $this->manager->get('app', $to);
            $toVendorLocale   = $this->manager->get('vendor', $to);
            $toTranslations   = is_null($toAppLocale)
                ? $toVendorLocale->mergeTranslations($toAppLocale, $ignored)
                : $toAppLocale->mergeTranslations($toVendorLocale, $ignored);

            $diff = array_diff_key($toTranslations, $fromTranslations);
            if (count($diff)) {
                foreach ($diff as $key => $value) {
                    $this->missing[$from][] = $key;
                }
            }

            $diff = array_diff_key($fromTranslations, $toTranslations);
            if (count($diff)) {
                foreach ($diff as $key => $value) {
                    $this->missing[$to][] = $key;
                }
            }
        }

        return $this->missing;
    }
}
