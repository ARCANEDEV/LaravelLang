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
            'vendor'    => realpath($basePath . '/vendor/caouecs/laravel4-lang')
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
        $dir        = 'lang/en';
        $from       = realpath(__DIR__ . DS . 'fixtures' . DS . $dir);
        $to         = realpath(base_path('resources' . DS . $dir));

        $files = array_map(function ($file) use ($from, $to) {
            /** @var \Symfony\Component\Finder\SplFileInfo $file */
            return $file->getRelativePathname();
        }, $filesystem->allFiles($from));

        foreach ($files as $file) {
            $filesystem->copy($from . DS . $file, $to . DS . $file);
        }
    }
}
