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
    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_run_command()
    {
        $this->artisan('trans:check')
             ->assertExitCode(0);

        static::assertTrue(true);
    }
}
