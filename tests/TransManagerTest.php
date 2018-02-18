<?php namespace Arcanedev\LaravelLang\Tests;

/**
 * Class     TransManagerTest
 *
 * @package  Arcanedev\LaravelLang\Tests
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class TransManagerTest extends TestCase
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /** @var \Arcanedev\LaravelLang\Contracts\TransManager */
    private $manager;

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    public function setUp()
    {
        parent::setUp();

        $this->manager = $this->app[\Arcanedev\LaravelLang\Contracts\TransManager::class];
    }

    public function tearDown()
    {
        unset($this->manager);

        parent::tearDown();
    }

    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_be_instantiated()
    {
        $expectations = [
            \Arcanedev\LaravelLang\Contracts\TransManager::class,
            \Arcanedev\LaravelLang\TransManager::class,
        ];

        foreach ($expectations as $expected) {
            static::assertInstanceOf($expected, $this->manager);
        }

        $paths = $this->manager->getPaths();
        static::assertCount(2, $this->manager->getPaths());
        static::assertArrayHasKey('app', $paths);
        static::assertArrayHasKey('vendor', $paths);
    }

    /** @test */
    public function it_can_get_all_locales_keys()
    {
        $keys = $this->manager->keys();

        foreach ($this->getLocales() as $locale) {
            static::assertArrayHasKey($locale, $keys);
        }
    }

    /** @test */
    public function it_can_count()
    {
        static::assertEquals($this->getLocalesCount(), $this->manager->count());
    }

    /** @test */
    public function it_can_get_locales_collection()
    {
        $expectations = [
            'app'    => 2,
            'vendor' => $this->getLocalesCount(false),
        ];

        foreach ($expectations as $group => $count) {
            static::assertTrue($this->manager->hasCollection($group));

            $locales = $this->manager->getCollection($group);

            static::assertInstanceOf(
                \Arcanedev\LaravelLang\Entities\LocaleCollection::class, $locales
            );
            static::assertCount($count, $locales);
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
                'es' => 'es',
                'fr' => 'fr',
            ],
        ];

        foreach ($expectations as $group => $locales) {
            foreach ($locales as $key => $expected) {
                $locale = $this->manager->getFrom($group, $key);

                if (is_null($expected)) {
                    static::assertNull($locale);
                    continue;
                }

                static::assertEquals($key, $locale->getKey());
                static::assertTrue(file_exists($locale->getPath()));
                static::assertNotEmpty($locale->getTranslations());
            }
        }
    }

    /** @test */
    public function it_return_default_on_getting_one_from_not_existed_group()
    {
        static::assertNull($this->manager->getFrom('locales', 'en'));
    }
}
