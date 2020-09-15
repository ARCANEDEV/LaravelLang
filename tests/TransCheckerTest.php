<?php

declare(strict_types=1);

namespace Arcanedev\LaravelLang\Tests;

use Arcanedev\LaravelLang\Contracts\TransChecker;

/**
 * Class     TransCheckerTest
 *
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

    public function setUp(): void
    {
        parent::setUp();

        $this->checker = $this->app->make(TransChecker::class);
    }

    public function tearDown(): void
    {
        unset($this->checker);

        parent::tearDown();
    }

    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_be_instantiated(): void
    {
        $expectations = [
            TransChecker::class,
            \Arcanedev\LaravelLang\TransChecker::class,
        ];

        foreach ($expectations as $expected) {
            static::assertInstanceOf($expected, $this->checker);
        }
    }

    /** @test */
    public function it_can_check(): void
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

        static::assertEquals($expected, $this->checker->check());
    }
}
