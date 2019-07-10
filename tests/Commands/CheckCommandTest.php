<?php namespace Arcanedev\LaravelLang\Tests\Commands;

use Arcanedev\LaravelLang\Contracts\TransChecker;
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
        $this->mock(TransChecker::class, function ($mock) {
            $mock->shouldReceive('check')->andReturn([]);

            return $mock;
        });

        $this->artisan('trans:check')
             ->assertExitCode(0);
    }

    /** @test */
    public function it_has_an_exit_code_if_we_miss_something()
    {
        $this->mock(TransChecker::class, function ($mock) {
            $mock->shouldReceive('check')->andReturn([
                'en' => [
                    'file.message',
                ],
            ]);

            return $mock;
        });

        $this->artisan('trans:check')
             ->assertExitCode(1);
    }
}
