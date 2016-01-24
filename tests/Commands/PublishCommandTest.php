<?php namespace Arcanedev\LaravelLang\Tests\Commands;

use Arcanedev\LaravelLang\Tests\TestCase;

/**
 * Class     PublishCommandTest
 *
 * @package  Arcanedev\LaravelLang\Tests\Commands
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class PublishCommandTest extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_publish()
    {
        $locale = 'es';

        $this->assertEquals(0, $this->artisan('trans:publish', compact('locale')));

        // Delete the lang folder.
        $this->deleteLangDirectory($locale);
    }

    /** @test */
    public function it_can_publish_with_no_force()
    {
        $locale = 'es';

        $this->assertEquals(0, $this->artisan('trans:publish', compact('locale')));
        $this->assertEquals(0, $this->artisan('trans:publish', compact('locale')));

        // Delete the lang folder.
        $this->deleteLangDirectory($locale);
    }

    /** @test */
    public function it_can_publish_with_force()
    {
        $locale = 'es';

        $this->assertEquals(0, $this->artisan('trans:publish', compact('locale')));
        $this->assertEquals(0, $this->artisan('trans:publish', [
            'locale'  => $locale,
            '--force' => true,
        ]));

        // Delete the lang folder.
        $this->deleteLangDirectory($locale);
    }

    /** @test */
    public function it_can_skip_the_default_locale()
    {
        $locale = 'en';

        $this->assertEquals(0, $this->artisan('trans:publish', compact('locale')));
    }
}
