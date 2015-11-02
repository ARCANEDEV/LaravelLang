<?php namespace Arcanedev\LaravelLang\Tests;

use Arcanedev\LaravelLang\TransChecker;

/**
 * Class     TransCheckerTest
 *
 * @package  Arcanedev\LaravelLang\Tests
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class TransCheckerTest extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var TransChecker */
    private $checker;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->checker = $this->app['arcanedev.laravel-lang.checker'];
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->checker);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_be_instantiated()
    {
        $expectations = [
            \Arcanedev\LaravelLang\Contracts\TransChecker::class,
            \Arcanedev\LaravelLang\TransChecker::class,
        ];

        foreach ($expectations as $expected) {
            $this->assertInstanceOf($expected, $this->checker);
        }
    }

    /** @test */
    public function it_can_check()
    {
        $expected = [
            'es' => [
                'errors.404.title', 'errors.500.title', 'errors.503.title',
            ],
            'en' => [
                'main.success',
            ],
            'fr' => [
                'errors.404.title', 'errors.500.title', 'errors.503.title',
            ],
        ];

        $this->assertEquals($expected, $this->checker->check());
    }
}
