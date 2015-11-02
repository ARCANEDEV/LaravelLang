<?php namespace Arcanedev\LaravelLang\Tests\Commands;

use Arcanedev\LaravelLang\Tests\TestCase;

/**
 * Class     CheckCommandTest
 *
 * @package  Arcanedev\LaravelLang\Tests\Commands
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class CheckCommandTest extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_run_command()
    {
        $this->assertEquals(0, $this->artisan('trans:check'));
    }
}
