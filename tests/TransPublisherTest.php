<?php

declare(strict_types=1);

namespace Arcanedev\LaravelLang\Tests;

use Arcanedev\LaravelLang\Contracts\TransPublisher;

/**
 * Class     TransPublisherTest
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class TransPublisherTest extends TestCase
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /** @var  \Arcanedev\LaravelLang\Contracts\TransPublisher */
    private $publisher;

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    public function setUp(): void
    {
        parent::setUp();

        $this->publisher = $this->app->make(TransPublisher::class);
    }

    public function tearDown(): void
    {
        unset($this->publisher);

        parent::tearDown();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */

    /** @test */
    public function it_can_be_instantiated(): void
    {
        $expectations = [
            TransPublisher::class,
            \Arcanedev\LaravelLang\TransPublisher::class,
        ];

        foreach ($expectations as $expected) {
            static::assertInstanceOf($expected, $this->publisher);
        }
    }

    /** @test */
    public function it_can_publish(): void
    {
        $locale = 'es';

        if ($this->langDirectoryExists($locale))
            $this->deleteLangDirectory($locale);

        static::assertEquals([
            'published' => [
                'es/auth.php',
                'es/es.json',
                'es/pagination.php',
                'es/passwords.php',
                'es/validation-nova.php',
                'es/validation.php',
            ],
            'skipped'   => [],
        ], $this->publisher->publish($locale));

        // Clean the lang folder content.
        $this->cleanLangDirectory($locale);

        static::assertEquals([
            'published' => [
                'es/auth.php',
                'es/es.json',
                'es/pagination.php',
                'es/passwords.php',
                'es/validation-nova.php',
                'es/validation.php',
            ],
            'skipped'   => [],
        ], $this->publisher->publish($locale));

        // Delete the lang folder.
        $this->deleteLangDirectory($locale);
    }

    /** @test */
    public function it_can_not_publish_if_is_not_forced(): void
    {
        $locale = 'es';

        if ($this->langDirectoryExists($locale))
            $this->deleteLangDirectory($locale);

        static::assertEquals([
            'published' => [
                'es/auth.php',
                'es/es.json',
                'es/pagination.php',
                'es/passwords.php',
                'es/validation-nova.php',
                'es/validation.php',
            ],
            'skipped'   => [],
        ], $this->publisher->publish($locale));

        static::assertEquals([
            'published' => [],
            'skipped'   => [
                'es/auth.php',
                'es/es.json',
                'es/pagination.php',
                'es/passwords.php',
                'es/validation-nova.php',
                'es/validation.php',
            ],
        ], $this->publisher->publish($locale));

        // Delete the lang folder.
        $this->deleteLangDirectory($locale);
    }

    /** @test */
    public function it_can_publish_on_force(): void
    {
        $locale = 'es';

        if ($this->langDirectoryExists($locale))
            $this->deleteLangDirectory($locale);

        $excepted = [
            'published' => [
                'es/auth.php',
                'es/es.json',
                'es/pagination.php',
                'es/passwords.php',
                'es/validation-nova.php',
                'es/validation.php',
            ],
            'skipped'   => [],
        ];

        static::assertEquals($excepted, $this->publisher->publish($locale));
        static::assertEquals($excepted, $this->publisher->publish($locale, ['force' => true]));

        // Delete the lang folder.
        $this->deleteLangDirectory($locale);
    }

    /** @test */
    public function it_can_skip_if_locale_is_default(): void
    {
        static::assertEquals([
            'published' => [],
            'skipped'   => ['en'],
        ], $this->publisher->publish('en'));
    }

    /** @test */
    public function it_must_throw_an_exception_on_unsupported_locale(): void
    {
        $this->expectException(\Arcanedev\LaravelLang\Exceptions\LangPublishException::class);
        $this->expectExceptionMessage('The locale [arcanedev] is not supported.');

        $this->publisher->publish('arcanedev');
    }
}
