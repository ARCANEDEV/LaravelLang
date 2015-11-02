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

    /** @var Translator */
    private $translator;

    /** @var TransManagerInterface */
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
     * @param  Translator             $translator
     * @param  TransManagerInterface  $manager
     * @param  array                  $configs
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
        return array_get($this->configs, 'locales', []);
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

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check the missing translations.
     *
     * @return array
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
        $fromAppLocale    = $this->manager->getFrom('app', $from);
        $fromVendorLocale = $this->manager->getFrom('vendor', $from);
        $fromTranslations = $fromAppLocale->mergeTranslations($fromVendorLocale, $ignored);
        $this->missing    = [];


        foreach ($locales as $to) {
            /**
             * @var Locale $toAppLocale
             * @var Locale $toVendorLocale
             */
            $toAppLocale      = $this->manager->getFrom('app', $to);
            $toVendorLocale   = $this->manager->getFrom('vendor', $to);
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
