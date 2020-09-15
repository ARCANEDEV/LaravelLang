<?php

declare(strict_types=1);

namespace Arcanedev\LaravelLang\Tests\Commands;

use Arcanedev\LaravelLang\Tests\TestCase;

/**
 * Class     PublishCommandTest
 *
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
             ->expectsOutput("Publishing the [{$locale}] translations...")
             ->assertExitCode(0);

        // Delete the lang folder.
        $this->deleteLangDirectory($locale);
    }

    /** @test */
    public function it_can_publish_with_no_force(): void
    {
        $locale = 'es';

        $this->artisan('trans:publish', compact('locale'))
             ->expectsOutput("Publishing the [{$locale}] translations...")
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
             ->expectsOutput("Publishing the [{$locale}] translations...")
             ->assertExitCode(0);

        $this->artisan('trans:publish', ['locale'  => $locale, '--force' => true])
             ->expectsOutput("Publishing the [{$locale}] translations...")
             ->assertExitCode(0);

        // Delete the lang folder.
        $this->deleteLangDirectory($locale);
    }

    /** @test */
    public function it_can_publish_with_inline(): void
    {
        $locale = 'es';

        $this->artisan('trans:publish', ['locale'  => $locale, '--inline' => true])
             ->expectsOutput("Publishing the [{$locale}] translations...")
             ->assertExitCode(0);

        // Delete the lang folder.
        $this->deleteLangDirectory($locale);
    }

    /** @test */
    public function it_can_skip_the_default_locale(): void
    {
        $this->artisan('trans:publish', ['locale' => 'en'])
             ->assertExitCode(0);

        static::assertTrue(true);
    }

    /** @test */
    public function it_can_publish_json(): void
    {
        $locale = 'es';

        $this->artisan('trans:publish', ['locale'  => $locale, '--json' => true])
             ->expectsOutput("Publishing the [{$locale}] translations...")
             ->assertExitCode(0);

        // Delete the lang folder.
        $this->deleteLangDirectory($locale);
        $this->deleteJsonLangFile($locale);
    }

    /**
     * Delete the json file from lang folder.
     *
     * @param  string  $locale
     *
     * @return bool
     */
    private function deleteJsonLangFile(string $locale): bool
    {
        return $this->filesystem()->delete($this->app->langPath().DIRECTORY_SEPARATOR.$locale.'.json');
    }
}
