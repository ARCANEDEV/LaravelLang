<?php

declare(strict_types=1);

namespace Arcanedev\LaravelLang\Tests\Commands;

use Arcanedev\LaravelLang\Tests\TestCase;

/**
 * Class     PublishCommandTest
 *
 * @package  Arcanedev\LaravelLang\Tests\Commands
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class PublishCommandTest extends TestCase
{
    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_publish(): void
    {
        $locale = 'es';

        $this->artisan('trans:publish', compact('locale'))
             ->expectsOutput("The locale [{$locale}] translations were published successfully.")
             ->assertExitCode(0);

        // Delete the lang folder.
        $this->deleteLangDirectory($locale);
    }

    /** @test */
    public function it_can_publish_with_no_force(): void
    {
        $locale = 'es';

        $this->artisan('trans:publish', compact('locale'))
             ->expectsOutput("The locale [{$locale}] translations were published successfully.")
             ->assertExitCode(0);

        $this->artisan('trans:publish', compact('locale'))
             ->assertExitCode(0);

        // Delete the lang folder.
        $this->deleteLangDirectory($locale);
    }

    /** @test */
    public function it_can_publish_with_force(): void
    {
        $locale = 'es';

        $this->artisan('trans:publish', compact('locale'))
             ->expectsOutput("The locale [{$locale}] translations were published successfully.")
             ->assertExitCode(0);

        $this->artisan('trans:publish', ['locale'  => $locale, '--force' => true])
             ->expectsOutput("The locale [{$locale}] translations were published successfully.")
             ->assertExitCode(0);

        // Delete the lang folder.
        $this->deleteLangDirectory($locale);
    }

    /** @test */
    public function it_can_publish_with_inline(): void
    {
        $locale = 'es';

        $this->artisan('trans:publish', ['locale'  => $locale, '--inline' => true])
             ->expectsOutput("The locale [{$locale}] translations were published successfully.")
             ->assertExitCode(0);

        // Delete the lang folder.
        $this->deleteLangDirectory($locale);
    }

    /** @test */
    public function it_can_skip_the_default_locale(): void
    {
        $locale = 'en';

        $this->artisan('trans:publish', compact('locale'))
             ->assertExitCode(0);

        static::assertTrue(true);
    }
}
