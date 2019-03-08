<?php namespace Arcanedev\LaravelLang\Tests;

use Illuminate\Translation\Translator;

/**
 * Class     Test
 *
 * @package  Arcanedev\LaravelLang\Tests
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class TranslatorTest extends TestCase
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /** @var \Illuminate\Translation\Translator */
    private $translator;

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    public function setUp(): void
    {
        parent::setUp();

        $this->translator = $this->app['translator'];
    }

    public function tearDown(): void
    {
        unset($this->translator);

        parent::tearDown();
    }

    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_be_instantiated()
    {
        static::assertInstanceOf(Translator::class, $this->translator);
    }

    /** @test */
    public function it_can_translate()
    {
        $expectations = [
            'es' => [
                'auth.failed' => 'Estas credenciales no coinciden con nuestros registros.'
            ],
            'fr' => [
                'auth.failed' => 'Ces identifiants ne correspondent pas à nos enregistrements'
            ],
        ];

        foreach ($expectations as $locale => $translations) {
            $this->translator->setLocale($locale);

            foreach ($translations as $key => $expected) {
                static::assertEquals($expected, $this->translator->get($key));
            }
        }
    }

    /** @test */
    public function it_can_translate_with_fallback()
    {
        $unsupportedLocales = ['ar', 'bg', 'bs', 'ca', 'it'];

        foreach ($unsupportedLocales as $locale) {
            $this->translator->setLocale($locale);

            static::assertEquals(
                'These credentials do not match our records.',
                $this->translator->get('auth.failed')
            );
        }
    }
}
