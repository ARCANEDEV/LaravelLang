<?php namespace Arcanedev\LaravelLang\Tests;

use Arcanedev\LaravelLang\LaravelLangServiceProvider;

/**
 * Class     LaravelLangServiceProviderTest
 *
 * @package  Arcanedev\LaravelLang\Tests
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class LaravelLangServiceProviderTest extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var  LaravelLangServiceProvider  */
    private $provider;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->provider = $this->app->getProvider(LaravelLangServiceProvider::class);
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
            \Arcanedev\Support\PackageServiceProvider::class,
        ];

        foreach ($expectations as $expected) {
            $this->assertInstanceOf($expected, $this->provider);
        }
    }

    /** @test */
    public function it_can_provides()
    {
        $expected = [
            'arcanedev.laravel-lang.manager',
            \Arcanedev\LaravelLang\Contracts\TransManager::class,
            'arcanedev.laravel-lang.checker',
            \Arcanedev\LaravelLang\Contracts\TransChecker::class,
            'arcanedev.laravel-lang.publisher',
            \Arcanedev\LaravelLang\Contracts\TransPublisher::class,
        ];

        $this->assertEquals($expected, $this->provider->provides());
    }
}
