<?php

declare(strict_types=1);

namespace Arcanedev\LaravelLang\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;

/**
 * Class     TestCase
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class TestCase extends BaseTestCase
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * All the available locales.
     *
     * @var array
     */
    protected $locales = [
        'af', 'ar', 'az', 'be', 'bg', 'bn', 'bs', 'ca', 'cs', 'cy', 'da', 'de', 'de_CH', 'el', 'es', 'et', 'eu', 'fa',
        'fi', 'fil', 'fr', 'gl', 'he', 'hi', 'hr', 'hu', 'hy', 'id', 'is', 'it', 'ja', 'ka', 'kk', 'km', 'kn', 'ko',
        'lt', 'lv', 'mk', 'mn', 'mr', 'ms', 'nb', 'ne', 'nl', 'nn', 'oc', 'pl', 'ps', 'pt', 'pt_BR', 'ro', 'ru', 'sc',
        'si', 'sk', 'sl', 'sq', 'sr_Cyrl', 'sr_Latn', 'sr_Latn_ME', 'sv', 'sw', 'tg', 'th', 'tk', 'tl', 'tr', 'ug', 'uk',
        'ur', 'uz_Cyrl', 'uz_Latn', 'vi', 'zh_CN', 'zh_HK', 'zh_TW',
    ];

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getPackageProviders($app): array
    {
        return [
            \Arcanedev\LaravelLang\LaravelLangServiceProvider::class,
            \Arcanedev\LaravelLang\Providers\DeferredServicesProvider::class,
            \Arcanedev\LaravelLang\Providers\TranslationServiceProvider::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application   $app
     */
    protected function getEnvironmentSetUp($app): void
    {
        /** @var  \Illuminate\Contracts\Config\Repository  $config */
        $basePath = dirname(__DIR__);
        $config   = $app['config'];

        $config->set('laravel-lang', [
            'vendor'  => [
                realpath($basePath . '/vendor/laravel-lang/lang/locales'),
            ],

            'locales' => ['es', 'fr'],

            'check'   => [
                'ignore'  => [
                    'validation.custom',
                    'validation.attributes',
                ],
            ],
        ]);

        $this->copyLanguagesFixtures($app);
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Copy languages fixtures.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     */
    private function copyLanguagesFixtures($app)
    {
        /** @var \Illuminate\Filesystem\Filesystem $filesystem */
        $filesystem = $app['files'];

        $filesystem->copyDirectory(
            realpath(__DIR__.DIRECTORY_SEPARATOR.'fixtures'.DIRECTORY_SEPARATOR.'lang'),
            realpath(resource_path('lang'))
        );
    }

    /**
     * Get the filesystem instance.
     *
     * @return \Illuminate\Filesystem\Filesystem
     */
    protected function filesystem()
    {
        return $this->app['files'];
    }

    /**
     * Clean the lang folder.
     *
     * @param  string  $locale
     *
     * @return bool
     */
    protected function cleanLangDirectory(string $locale): bool
    {
        return $this->filesystem()
                    ->cleanDirectory($this->app->langPath().DIRECTORY_SEPARATOR.$locale);
    }

    /**
     * Delete the lang folder.
     *
     * @param  string  $locale
     *
     * @return bool
     */
    protected function deleteLangDirectory(string $locale): bool
    {
        return $this->filesystem()
                    ->deleteDirectory($this->app->langPath().DIRECTORY_SEPARATOR.$locale);
    }

    /**
     * Get available locales.
     *
     * @param  bool|true  $includeEnglish
     *
     * @return array
     */
    protected function getLocales(bool $includeEnglish = true): array
    {
        $locales = $this->locales;

        if ($includeEnglish) {
            $locales = array_merge(['en' => 'en'], array_combine($locales, $locales));
        }

        return $locales;
    }

    /**
     * Count available locales.
     *
     * @param  bool|true  $includeEnglish
     *
     * @return int
     */
    protected function getLocalesCount(bool $includeEnglish = true): int
    {
        return count($this->getLocales($includeEnglish));
    }
}
