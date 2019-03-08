<?php namespace Arcanedev\LaravelLang\Tests;

use Arcanedev\LaravelLang\LaravelLangServiceProvider;
use Arcanedev\LaravelLang\Contracts;

/**
 * Class     LaravelLangServiceProviderTest
 *
 * @package  Arcanedev\LaravelLang\Tests
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class LaravelLangServiceProviderTest extends TestCase
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /** @var  \Arcanedev\LaravelLang\LaravelLangServiceProvider  */
    private $provider;

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    public function setUp(): void
    {
        parent::setUp();

        $this->provider = $this->app->getProvider(LaravelLangServiceProvider::class);
    }

    public function tearDown(): void
    {
        unset($this->provider);

        parent::tearDown();
    }

    /* -----------------------------------------------------------------
     |  Test Methods
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_be_instantiated()
    {
        $expectations = [
            \Illuminate\Support\ServiceProvider::class,
            \Arcanedev\Support\ServiceProvider::class,
            \Arcanedev\Support\PackageServiceProvider::class,
            \Arcanedev\LaravelLang\LaravelLangServiceProvider::class,
        ];

        foreach ($expectations as $expected) {
            static::assertInstanceOf($expected, $this->provider);
        }
    }

    /** @test */
    public function it_can_provides()
    {
        $expected = [
            Contracts\TransManager::class,
            Contracts\TransChecker::class,
            Contracts\TransPublisher::class,
        ];

        static::assertEquals($expected, $this->provider->provides());
    }
}
