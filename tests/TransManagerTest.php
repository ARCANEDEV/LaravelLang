<?php namespace Arcanedev\LaravelLang\Tests;

use Arcanedev\LaravelLang\Contracts\TransManager;

/**
 * Class     TransManagerTest
 *
 * @package  Arcanedev\LaravelLang\Tests
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class TransManagerTest extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var TransManager */
    private $manager;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->manager = $this->app['arcanedev.laravel-lang.manager'];
    }

    public function tearDown()
    {
        unset($this->manager);

        parent::tearDown();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_be_instantiated()
    {
        $expectations = [
            \Arcanedev\LaravelLang\Contracts\TransManager::class,
            \Arcanedev\LaravelLang\TransManager::class,
        ];

        foreach ($expectations as $expected) {
            $this->assertInstanceOf($expected, $this->manager);
        }

        $paths = $this->manager->getPaths();
        $this->assertCount(2, $this->manager->getPaths());
        $this->assertArrayHasKey('app', $paths);
        $this->assertArrayHasKey('vendor', $paths);
    }

    /** @test */
    public function it_can_get_all_locales_keys()
    {
        $keys = $this->manager->keys();

        foreach ($this->getLocales() as $locale) {
            $this->assertArrayHasKey($locale, $keys);
        }
    }

    /** @test */
    public function it_can_count()
    {
        $this->assertEquals($this->getLocalesCount(), $this->manager->count());
    }

    /** @test */
    public function it_can_get_locales_collection()
    {
        $expectations = [
            'app'    => 2,
            'vendor' => $this->getLocalesCount(false),
        ];

        foreach ($expectations as $group => $count) {
            $this->assertTrue($this->manager->hasCollection($group));

            $locales = $this->manager->getCollection($group);

            $this->assertInstanceOf(
                \Arcanedev\LaravelLang\Entities\LocaleCollection::class, $locales
            );
            $this->assertCount($count, $locales);
        }
    }

    /** @test */
    public function it_can_get_one_from_collection()
    {
        $expectations = [
            'app'    => [
                'en' => 'en',
                'es' => null,
                'fr' => 'fr',
            ],
            'vendor' => [
                'en' => null,
                'es' => 'es',
                'fr' => 'fr',
            ],
        ];

        foreach ($expectations as $group => $locales) {
            foreach ($locales as $key => $expected) {
                $locale = $this->manager->getFrom($group, $key);

                if (is_null($expected)) {
                    $this->assertNull($locale);
                    continue;
                }

                $this->assertEquals($key, $locale->getKey());
                $this->assertTrue(file_exists($locale->getPath()));
                $this->assertNotEmpty($locale->getTranslations());
            }
        }
    }

    /** @test */
    public function it_return_default_on_getting_one_from_not_existed_group()
    {
        $this->assertNull($this->manager->getFrom('locales', 'en'));
    }
}
