<?php namespace Arcanedev\LaravelLang\Tests\Providers;

use Arcanedev\LaravelLang\Providers\DeferredServiceProvider;
use Arcanedev\LaravelLang\Tests\TestCase;

/**
 * Class     DeferredServiceProviderTest
 *
 * @package  Arcanedev\LaravelLang\Tests\Providers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class DeferredServiceProviderTest extends TestCase
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /** @var  \Arcanedev\LaravelLang\Providers\DeferredServiceProvider */
    protected $provider;

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    protected function setUp(): void
    {
        parent::setUp();

        $this->provider = $this->app->getProvider(DeferredServiceProvider::class);
    }

    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_be_instantiated()
    {
        $expectations = [
            \Illuminate\Support\ServiceProvider::class,
            \Illuminate\Contracts\Support\DeferrableProvider::class,
            \Arcanedev\Support\Providers\ServiceProvider::class,
            \Arcanedev\LaravelLang\Providers\DeferredServiceProvider::class,
        ];

        foreach ($expectations as $expected) {
            static::assertInstanceOf($expected, $this->provider);
        }
    }

    /** @test */
    public function it_can_provides()
    {
        $expected = [
            \Arcanedev\LaravelLang\Contracts\TransManager::class,
            \Arcanedev\LaravelLang\Contracts\TransChecker::class,
            \Arcanedev\LaravelLang\Contracts\TransPublisher::class,
        ];

        static::assertEquals($expected, $this->provider->provides());
    }
}
