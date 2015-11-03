<?php namespace Arcanedev\LaravelLang\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;

/**
 * Class     TestCase
 *
 * @package  Arcanedev\LaravelLang\Tests
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class TestCase extends BaseTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    protected $locales = [
        'ar', 'bg', 'bs', 'ca', 'cs', 'cy', 'da', 'de', 'el', 'es', 'fa', 'fi', 'fr',
        'he', 'hr', 'hu', 'id', 'is', 'it', 'ja', 'ka', 'km', 'ko', 'lt', 'me', 'mk',
        'ms', 'nb', 'nl', 'pl', 'pt', 'pt-BR', 'ro', 'ru', 'sc', 'sk', 'sl', 'sq', 'sr',
        'sv', 'th', 'tk', 'tr', 'uk', 'vi', 'zh-CN', 'zh-HK', 'zh-TW',
    ];

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Laravel Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            \Arcanedev\LaravelLang\LaravelLangServiceProvider::class,
        ];
    }

    /**
     * Get package aliases.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getPackageAliases($app)
    {
        return [
            //
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application   $app
     */
    protected function getEnvironmentSetUp($app)
    {
        /** @var  \Illuminate\Config\Repository  $config */
        $basePath = dirname(__DIR__);
        $config   = $app['config'];

        $config->set('laravel-lang', [
            'vendor'    => realpath($basePath . '/vendor/caouecs/laravel4-lang'),

            'locales'   => ['es', 'fr'],

            'check'     => [
                'ignore'  => [
                    'validation.custom',
                    'validation.attributes',
                ],
            ],
        ]);

        $this->copyLanguagesFixtures($app);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Copy languages fixtures.
     *
     * @param  \Illuminate\Foundation\Application   $app
     */
    private function copyLanguagesFixtures($app)
    {
        /** @var \Illuminate\Filesystem\Filesystem $filesystem */
        $filesystem = $app['files'];

        $filesystem->copyDirectory(
            realpath(__DIR__ . DS . 'fixtures' . DS . 'lang'),
            realpath(base_path('resources' . DS . 'lang'))
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
    protected function cleanLangDirectory($locale)
    {
        return $this->filesystem()->cleanDirectory($this->app->langPath() . DS . $locale);
    }

    /**
     * Delete the lang folder.
     *
     * @param  string  $locale
     *
     * @return bool
     */
    protected function deleteLangDirectory($locale)
    {
        return $this->filesystem()->deleteDirectory($this->app->langPath() . DS . $locale);
    }

    /**
     * Get available locales.
     *
     * @param  bool|true  $addEnglish
     *
     * @return array
     */
    protected function getLocales($addEnglish = true)
    {
        $locales = $this->locales;

        if ($addEnglish) {
            $locales = array_merge(['en' => 'en'], $locales);
        }

        return $locales;
    }

    /**
     * Count available locales.
     *
     * @param  bool|true  $addEnglish
     *
     * @return int
     */
    protected function getLocalesCount($addEnglish = true)
    {
        return count($this->getLocales($addEnglish));
    }
}
