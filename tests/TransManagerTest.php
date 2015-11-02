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
        parent::tearDown();

        unset($this->manager);
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
            'app'    => 1,
            'vendor' => $this->getLocalesCount(false),
        ];

        foreach ($expectations as $group => $count) {
            $locales = $this->manager->getCollection($group);

            $this->assertInstanceOf(
                \Arcanedev\LaravelLang\Entities\LocaleCollection::class, $locales
            );
            $this->assertCount($count, $locales);
        }
    }
}
