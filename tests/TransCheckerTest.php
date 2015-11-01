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
        $this->assertInstanceOf(TransChecker::class, $this->checker);
    }
}
