<?php namespace Arcanedev\LaravelLang\Tests\Providers;

use Arcanedev\LaravelLang\Providers\CommandServiceProvider;
use Arcanedev\LaravelLang\Tests\TestCase;
use Arcanedev\LaravelLang\Commands;

/**
 * Class     CommandServiceProviderTest
 *
 * @package  Arcanedev\LaravelLang\Tests\Providers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class CommandServiceProviderTest extends TestCase
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /** @var \Arcanedev\LaravelLang\Providers\CommandServiceProvider */
    private $provider;

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    public function setUp(): void
    {
        parent::setUp();

        $this->provider = $this->app->getProvider(CommandServiceProvider::class);
    }

    public function tearDown(): void
    {
        unset($this->provider);

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
            \Illuminate\Support\ServiceProvider::class,
            \Arcanedev\Support\ServiceProvider::class,
            \Arcanedev\Support\Providers\CommandServiceProvider::class,
            \Arcanedev\LaravelLang\Providers\CommandServiceProvider::class,
        ];

        foreach ($expectations as $expected) {
            static::assertInstanceOf($expected, $this->provider);
        }
    }

    /** @test */
    public function it_can_provides()
    {
        $expected = [
            Commands\CheckCommand::class,
            Commands\PublishCommand::class,
        ];

        static::assertEquals($expected, $this->provider->provides());
    }
}
