<?php namespace Arcanedev\LaravelLang\Tests\Providers;

use Arcanedev\LaravelLang\Providers\CommandServiceProvider;
use Arcanedev\LaravelLang\Tests\TestCase;

/**
 * Class     CommandServiceProviderTest
 *
 * @package  Arcanedev\LaravelLang\Tests\Providers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class CommandServiceProviderTest extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var CommandServiceProvider */
    private $provider;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->provider = $this->app->getProvider(CommandServiceProvider::class);
    }

    public function tearDown()
    {
        unset($this->provider);

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
            \Illuminate\Support\ServiceProvider::class,
            \Arcanedev\Support\ServiceProvider::class,
            \Arcanedev\Support\Providers\CommandServiceProvider::class,
            \Arcanedev\LaravelLang\Providers\CommandServiceProvider::class,
        ];

        foreach ($expectations as $expected) {
            $this->assertInstanceOf($expected, $this->provider);
        }
    }

    /** @test */
    public function it_can_provides()
    {
        $expected = [
            'arcanedev.laravel-lang.commands.check',
            'arcanedev.laravel-lang.commands.publish',
        ];

        $this->assertEquals($expected, $this->provider->provides());
    }
}
