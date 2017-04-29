<?php namespace Arcanedev\LaravelLang\Tests;

/**
 * Class     TransCheckerTest
 *
 * @package  Arcanedev\LaravelLang\Tests
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class TransCheckerTest extends TestCase
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /** @var \Arcanedev\LaravelLang\Contracts\TransChecker */
    private $checker;

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    public function setUp()
    {
        parent::setUp();

        $this->checker = $this->app[\Arcanedev\LaravelLang\Contracts\TransChecker::class];
    }

    public function tearDown()
    {
        unset($this->checker);

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
